<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\VacancyRespond;
use app\models\VacancyRespondSearch;
use app\models\Resume;
use app\models\Vacancy;
use app\models\Notification;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * VacancyRespondController implements the CRUD actions for VacancyRespond model.
 */
class VacancyRespondController extends Controller
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
                            'index', 'view', 'create', 'create-resume', 'view-resume'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VacancyRespond models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VacancyRespondSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VacancyRespond model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single VacancyRespond model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewResume($id)
    {
        return $this->render('view_resume', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VacancyRespond model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if (empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['/userpanel/profile/view']);
        }

        $model_vacancy = $this->findModelVacancy($id);

        if ($model_vacancy->company->user_id == Yii::$app->user->id) {
            return $this->render('this_is_your_vacancy');
        }

        $model = new VacancyRespond();
        $my_resume_list = Resume::find()->where([
            'user_id' => Yii::$app->user->id
        ])->all();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['VacancyRespond']['vacancy_id'] = $id;
            $post_data['VacancyRespond']['user_id'] = Yii::$app->user->id;
            $post_data['VacancyRespond']['for_user_id'] = $model_vacancy->company->user_id;
            
            // check is user's `resume_id`
            $is_user_resume = false;
            $resume_id = (int)$post_data['VacancyRespond']['resume_id'];
            unset($post_data['VacancyRespond']['resume_id']);
            foreach($my_resume_list as $resume) {
                if($resume->id == $resume_id) {
                    $is_user_resume = true;
                    break;
                }
            }

            if($is_user_resume) {
                $post_data['VacancyRespond']['resume_id'] = $resume_id;
            }

            $post_data['VacancyRespond']['status'] = VacancyRespond::STATUS_NEW;
            // $post_data['VacancyRespond']['message'] = $post_data['VacancyRespond']['message'];

            if ($model->load($post_data) && $model->save()) {
                // send notification to user
                $notification_model = new Notification;
                $notification_model->user_id = $model->for_user_id;
                $notification_model->title = $model->user->profile->getFullName()
                            . ' ' . Yii::t('message', 'applied for a job')
                            . ' #' . $model_vacancy->id .' '. $model_vacancy->categoryJob->name
                            ;
                
                $img_path = empty($model->user->profile->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($model->user->profile->getImageWebPath());
                $notification_model->title_html = 
                              '<img src="' . $img_path .'" alt="">'
                            . '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->user_id]) . '" target="_blank">' . $model->user->profile->getFullName() .'</a>'
                            . '&nbsp;' . Yii::t('message', 'applied for a job')
                            . '&nbsp;<a href="' . Url::to(['/vacancy/view', 'id' => $model_vacancy->id]) . '" target="_blank">#' . $model_vacancy->id .'&nbsp;' . $model_vacancy->categoryJob->name .'</a>'
                            . '&nbsp;' . $model_vacancy->categoryJob->name
                            ;
                
                $current_date = Yii::$app->formatter->format(time(), 'datetime');
                $text = '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->user_id]) . '" target="_blank">' . $model->user->profile->getFullName() .'</a>'
                        . '&nbsp;' . Yii::t('message', 'applied for a job')
                        . '&nbsp;<a href="' . Url::to(['/vacancy/view', 'id' => $model_vacancy->id]) . '" target="_blank">#' . $model_vacancy->id .'&nbsp;' . $model_vacancy->categoryJob->name .'</a>'
                        . '&nbsp;' . $model_vacancy->categoryJob->name;

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
                
                // --
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
            'model_vacancy' => $model_vacancy,
            'my_resume_list' => $my_resume_list,
        ]);
    }

    /**
     * Creates a new VacancyRespond model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateResume($id)
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->redirect('/userpanel/company/view');    
        }

        $model_resume = $this->findModelResume($id);

        if ($model_resume->user_id == Yii::$app->user->id) {
            return $this->render('this_is_your_resume');
        }

        $model = new VacancyRespond();
        $my_vacancy_list = Vacancy::find()->where([
            'company_id' => Yii::$app->user->identity->company->id
        ])->orderBy('id DESC')->limit(100)->all();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['VacancyRespond']['resume_id'] = $id;
            $post_data['VacancyRespond']['user_id'] = Yii::$app->user->id;
            $post_data['VacancyRespond']['for_user_id'] = $model_resume->user_id;

            // check is user's `vacancy_id`
            $is_user_vacancy = false;
            $vacancy_id = (int)$post_data['VacancyRespond']['vacancy_id'];
            unset($post_data['VacancyRespond']['vacancy_id']);
            foreach($my_vacancy_list as $vacancy) {
                if($vacancy->id == $vacancy_id) {
                    $is_user_vacancy = true;
                    break;
                }
            }

            if($is_user_vacancy) {
                $post_data['VacancyRespond']['vacancy_id'] = $vacancy_id;
            }

            $post_data['VacancyRespond']['status'] = VacancyRespond::STATUS_INVITED;
            // $post_data['VacancyRespond']['message'] = $post_data['VacancyRespond']['message'];

            if ($model->load($post_data) && $model->save()) {
                // send notification to user
                $notification_model = new Notification;
                $notification_model->user_id = $model->for_user_id;
                $notification_model->title = $model->user->profile->getFullName()
                            . ' ' . Yii::t('message', 'offered a job')
                            . ' #' . $model_resume->id . '&nbsp;' . $model_resume->getFullName()
                            ;
                
                $img_path = empty($model->user->profile->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($model->user->profile->getImageWebPath());
                $notification_model->title_html = 
                              '<img src="' . $img_path .'" alt="">'
                            . '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->user_id]) . '" target="_blank">' . $model->user->profile->getFullName() .'</a>'
                            . '&nbsp;' . Yii::t('message', 'offered a job')
                            . '&nbsp;<a href="' . Url::to(['/resume/view', 'id' => $model_resume->id]) . '" target="_blank">#' . $model_resume->id .'&nbsp;' . $model_resume->getFullName() .'</a>'
                            . '&nbsp;-&nbsp;<a href="' . Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) . '" target="_blank">#' . $model->vacancy->id .'&nbsp;' . $model->vacancy->categoryJob->name .'</a>'
                            ;
                
                $current_date = Yii::$app->formatter->format(time(), 'datetime');
                $text = '&nbsp;<a href="' . Url::to(['/userpanel/message/view', 'id' => $model->user_id]) . '" target="_blank">' . $model->user->profile->getFullName() .'</a>'
                        . '&nbsp;' . Yii::t('message', 'offered a job')
                        . '&nbsp;<a href="' . Url::to(['/resume/view', 'id' => $model_resume->id]) . '" target="_blank">#' . $model_resume->id .'&nbsp;' . $model_resume->getFullName() .'</a>'
                        . '&nbsp;-&nbsp;<a href="' . Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) . '" target="_blank">#' . $model->vacancy->id .'&nbsp;' . $model->vacancy->categoryJob->name .'</a>'
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
                
                // --
                return $this->redirect(['view-resume', 'id' => $model->id]);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }

        return $this->render('create_resume', [
            'model' => $model,
            'model_resume' => $model_resume,
            'my_vacancy_list' => $my_vacancy_list,
        ]);
    }

    /**
     * Finds the VacancyRespond model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VacancyRespond the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VacancyRespond::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }

    protected function findModelVacancy($id)
    {
        if (($model = Vacancy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }

    protected function findModelResume($id)
    {
        if (($model = Resume::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }
}
