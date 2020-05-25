<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\ResumeLanguage;
use app\models\ResumeEducation;
use app\models\Category;
use app\models\ResumeSearch;
use app\models\UserFavoriteResume;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ResumeController implements the CRUD actions for Resume model.
 */
class ResumeController extends Controller
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
     * Lists all Resume models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere([
            'user_id' => Yii::$app->user->id
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Resume model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // check access for this user
        if(!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        $modelJob = new ResumeJob();
        $modelLang = new ResumeLanguage();
        $modelEducation = new ResumeEducation();

        return $this->render('view', [
            'model' => $model,
            'modelJob' => $modelJob,
            'modelLang' => $modelLang,
            'modelEducation' => $modelEducation,
        ]);
    }

    /**
     * Creates a new Resume model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['/userpanel/profile/view']);
        }

        $model = new Resume();
        $modelJob = new ResumeJob();
        $modelLang = new ResumeLanguage();
        $modelEducation = new ResumeEducation();

        // if user has no resume then registration not complete
        $is_registration = !Yii::$app->user->identity->isUserHasResume();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['Resume']['user_id'] = Yii::$app->user->id;
            $model->creation_time = time();
            $model->update_time = time();

            if($is_registration) {
                $model->phone = Yii::$app->user->identity->profile->phone;
            }

            if ($model->loadAll($post_data) && $model->saveAll()) {
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
            'modelJob' => $modelJob,
            'modelLang' => $modelLang,
            'modelEducation' => $modelEducation,
            'category_list' => Category::getUserSelectList(),
            'is_registration' => $is_registration,
        ]);
    }

    /**
     * Updates an existing Resume model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = $this->findModelWithRelations($id);

        // check access for this user
        if(!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        //! BUG, can be vulnerability need filter post data
        $post_data = Yii::$app->request->post();
        
        //! BUG, need transaction before remove relations
        // unlink deleted relations
        if(!empty($post_data['relations'])) {
            foreach($post_data['relations'] as $relation_name) {
                //! BUG of optimization need save `id` for unmodifed relations
                $model->unlinkAll($relation_name, true);
            }
        }

        $post_data['Resume']['user_id'] = Yii::$app->user->id;

        if ( $model->loadAll($post_data) && $model->saveAll()) {
            $result_data = $model->getAttributesWithRelatedAsPost();
            unset($result_data['user']); // remove unsafe data
            return [
                'success' => true,
                'data' => $result_data,
            ];
        }

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

        if($model->status == Resume::STATUS_SHOW) {
            $model->status = Resume::STATUS_HIDE;
        } else {
            $model->status = Resume::STATUS_SHOW;
        }

        $model->save(false); //

        if(!empty(Yii::$app->request->referrer)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Lists all user's favorite Resume models.
     * @return mixed
     */
    public function actionFavorite()
    {
        $searchModel = new ResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->join(
            'INNER JOIN',
            'user_favorite_resume',
            'user_favorite_resume.resume_id = resume.id AND user_favorite_resume.user_id = :user_id'
        )->addParams([':user_id' => Yii::$app->user->id]);

        return $this->render('favorite', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Add Resume to user's favorite list
     * If update is successful, the browser will be redirected to the 'favorite' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddfavorite($id) {
        $model = $this->findModel($id);
        $model_favorite = new UserFavoriteResume();
        $model_favorite->user_id = Yii::$app->user->id;
        $model_favorite->resume_id = $model->id;

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
     * Remove Resume from user's favorite list
     * If update is successful, the browser will be redirected to the 'favorite' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemovefavorite($id) {
        $model = $this->findModel($id);
        $model_favorite = UserFavoriteResume::find()->where([
            'user_id' => Yii::$app->user->id,
            'resume_id' => $model->id,
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
     * Deletes an existing Resume model.
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
     * Finds the Resume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Resume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resume::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('resume', 'The requested page does not exist.'));
    }

    protected function findModelWithRelations($id)
    {
        $model = Resume::find()
                ->where(['id' => $id])
                ->with([
                    'categoryResumes',
                    'resumeCategoryJobs',
                    'resumeCountryCities',
                    'resumeEducations',
                    'resumeJobs',
                    'resumeLanguages'
                ])->one();
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }
}
