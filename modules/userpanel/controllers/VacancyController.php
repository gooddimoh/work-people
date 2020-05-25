<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Vacancy;
use app\models\VacancySearch;
use app\models\UserFavoriteVacancy;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\CurrencyConverterHelper;

/**
 * VacancyController implements the CRUD actions for Vacancy model.
 */
class VacancyController extends Controller
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
                            'index', 'view', 'create', 'update', 'changestatus', 'delete', 'favorite', 'addfavorite', 'removefavorite',
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
                    'addfavorite' => ['POST'],
                    'removefavorite' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Vacancy models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->redirect('/userpanel/company/view');    
        }

        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere([
            'company_id' => Yii::$app->user->identity->company->id
        ]);

        $status_show_count = Vacancy::find()->andFilterWhere([
            'status' => Vacancy::STATUS_SHOW,
            'company_id' => Yii::$app->user->identity->company->id
        ])->count();
        
        $total_count_my = Vacancy::find()->andFilterWhere([
            'company_id' => Yii::$app->user->identity->company->id
        ])->count();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'status_show_count' => $status_show_count,
            'total_count_my' => $total_count_my,
        ]);
    }

    /**
     * Displays a single Vacancy model.
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
     * Creates a new Vacancy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->redirect('/userpanel/company/view');    
        }
        
        $model = new Vacancy();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data for relations
            $post_data = Yii::$app->request->post();
            $post_data['Vacancy']['company_id'] = Yii::$app->user->identity->company->id;
            $post_data['Vacancy']['user_phone_id'] = Yii::$app->user->identity->getPhoneIdentity()->id;
            $model->creation_time = time();
            $model->update_time = time();
            
            $post_data['Vacancy']['salary_per_hour_min_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_min'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );
            $post_data['Vacancy']['salary_per_hour_max_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_max'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );

            if ($model->loadAll($post_data) && $model->saveAll()) {
                // set main_image, just get first
                if(!empty($model->vacancyImages)) {
                    $model->main_image = $model->vacancyImages[0]->path_name;
                    $model->save(false);
                }
                
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
        ]);
    }

    /**
     * Updates an existing Vacancy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->redirect('view');    
        }
        
        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        $model = $this->findModel($id);

        // check access for this user
        if(!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }
        
        //! BUG, can be vulnerability need filter post data (relation keys)
        $post_data = Yii::$app->request->post();
        $post_data['Vacancy']['company_id'] = Yii::$app->user->identity->company->id;
        $post_data['Vacancy']['user_phone_id'] = Yii::$app->user->identity->getPhoneIdentity()->id;
        $model->update_time = time();
            
        $post_data['Vacancy']['salary_per_hour_min_src'] = CurrencyConverterHelper::currencyToCurrency(
            $post_data['Vacancy']['salary_per_hour_min'],
            $post_data['Vacancy']['currency_code'],
            Yii::$app->params['sourceCurrencyCharCode']
        );
        $post_data['Vacancy']['salary_per_hour_max_src'] = CurrencyConverterHelper::currencyToCurrency(
            $post_data['Vacancy']['salary_per_hour_max'],
            $post_data['Vacancy']['currency_code'],
            Yii::$app->params['sourceCurrencyCharCode']
        );

        // fix insert null into DB
        if (empty($post_data['Vacancy']['worker_country_codes'])) {
            $post_data['Vacancy']['worker_country_codes'] = null;
        }

        // unlink deleted relations
        if(!empty($post_data['relations'])) {
            foreach($post_data['relations'] as $relation_name) {
                //! BUG of optimization need save `id` for unmodifed relations
                $model->unlinkAll($relation_name, true);
            }
        }

        if ($model->loadAll($post_data) && $model->saveAll()) {
            // set main_image, just get first
            if(!empty($model->vacancyImages)) {
                $model->main_image = $model->vacancyImages[0]->path_name;
                $model->save(false);
            }

            $result_data = $model->getAttributes();

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true,
                'data' => $result_data,
            ];
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'success' => false,
            'errors' => $model->getErrors()
        ];
    }

    public function actionChangestatus($id)
    {
        $model = $this->findModel($id);
        
        // check access for this user
        if(!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        if($model->status == Vacancy::STATUS_SHOW) {
            $model->status = Vacancy::STATUS_HIDE;
        } else {
            $model->status = Vacancy::STATUS_SHOW;
        }

        $model->save(false); //

        if(!empty(Yii::$app->request->referrer)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Vacancy model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // check access for this user
        if(!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Lists all user's favorite Vacancy models.
     * @return mixed
     */
    public function actionFavorite()
    {
        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->join(
            'INNER JOIN',
            'user_favorite_vacancy',
            'user_favorite_vacancy.vacancy_id = vacancy.id AND user_favorite_vacancy.user_id = :user_id'
        )->addParams([':user_id' => Yii::$app->user->id]);

        return $this->render('favorite', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Add Vacancy to user's favorite list
     * If update is successful, the browser will be redirected to the 'favorite' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddfavorite($id) {
        $model = $this->findModel($id);
        $model_favorite = new UserFavoriteVacancy();
        $model_favorite->user_id = Yii::$app->user->id;
        $model_favorite->vacancy_id = $model->id;

        $model_favorite->save();

        //? need flash message

        if(Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true
            ];
        }
        
        return $this->redirect(['favorite']);
    }

    /**
     * Remove Vacancy from user's favorite list
     * If update is successful, the browser will be redirected to the 'favorite' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemovefavorite($id) {
        $model = $this->findModel($id);
        $model_favorite = UserFavoriteVacancy::find()->where([
            'user_id' => Yii::$app->user->id,
            'vacancy_id' => $model->id,
        ])->one();

        $model_favorite->delete();

        //? need flash message

        if(Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true
            ];
        }
        
        return $this->redirect(['favorite']);
    }

    /**
     * Finds the Vacancy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vacancy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vacancy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }
}
