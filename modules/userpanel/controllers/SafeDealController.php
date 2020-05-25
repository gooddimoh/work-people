<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\SafeDeal;
use app\models\SafeDealUser;
use app\models\SafeDealSearch;
use app\models\Notification;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\CurrencyConverterHelper;
use yii\helpers\Url;

/**
 * SafeDealController implements the CRUD actions for SafeDeal model.
 */
class SafeDealController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'actions' => [
                            'index', 'view', 'create'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'changestatus' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all SafeDeal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SafeDealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $dataProvider->query->join('safe_deal_user') //! need join user's deals

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SafeDeal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if(!$model->hasAccess()) {
            throw new NotFoundHttpException(Yii::t('main', 'The requested page does not exist.'));
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SafeDeal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SafeDeal();
        $modelDealUser = new SafeDealUser();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['SafeDeal']['creator_id'] = Yii::$app->user->id;
            
            $post_data['SafeDeal']['amount_total_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['SafeDeal']['amount_total'],
                $post_data['SafeDeal']['amount_currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );

            // realtions:
            $acceptor_user_id = $post_data['SafeDeal']['safeDealUsers'][0]['user_id'];
            $post_data['SafeDeal']['safeDealUsers'] = [
                ['user_id' => $acceptor_user_id],
                ['user_id' => Yii::$app->user->id]
            ];
            
            $model->created_at = time();

            if ($model->loadAll($post_data) && $model->saveAll()) {
                // send notification to user
                $notification_model = new Notification;
                $notification_model->user_id = $acceptor_user_id;
                $user = Yii::$app->user->identity;
                $notification_model->title = $user->profile->getFullName()
                            . ' ' . Yii::t('deal', 'create safe deal with you')
                            ;
                
                $img_path = empty($user->profile->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($user->profile->getImageWebPath());
                $notification_model->title_html = 
                              '<img src="' . $img_path .'" alt="">'
                            . '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->creator_id]) . '" target="_blank">' . $user->profile->getFullName() .'</a>'
                            . '&nbsp;' . Yii::t('deal', 'create safe deal with you')
                            . '&nbsp;<a href="' . Url::to(['/userpanel/safe-deal/view', 'id' => $model->id]) . '" target="_blank">#' . $model->id .'&nbsp;' . $model->title .'</a>'
                            ;
                
                $current_date = Yii::$app->formatter->format(time(), 'datetime');
                $text = '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->creator_id]) . '" target="_blank">' . $user->profile->getFullName() .'</a>'
                        . '&nbsp;' . Yii::t('deal', 'create safe deal with you')
                        . '&nbsp;<a href="' . Url::to(['/userpanel/safe-deal/view', 'id' => $model->id]) . '" target="_blank">#' . $model->id .'&nbsp;' . $model->title .'</a>'
                        ;

                $notification_model->text = <<< HTML
                <div class="message-box__img">
                        <img src="{$img_path}" alt="">
                    </div>
                    <div class="message-box__text">
                        <div class="message-box__top">
                            <div class="message-box__name">
                                
                            </div>
                            <div class="message-box__date">
                                {$current_date}
                            </div>
                        </div>
                        <div class="message-box__desc">
                            {$text}
                        </div>
                    </div>
HTML;
                
                $notification_model->save();

                return $this->redirect(['view', 'id' => $model->id]);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }
        
        return $this->render('create', [
            'model' => $model,
            'modelDealUser' => $modelDealUser,
        ]);
    }

    /**
     * Finds the SafeDeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SafeDeal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SafeDeal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('main', 'The requested page does not exist.'));
    }
}
