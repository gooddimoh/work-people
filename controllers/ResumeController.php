<?php

namespace app\controllers;

use Yii;
use app\models\Resume;
use app\models\ResumeSearch;
use app\models\ResumeCounter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * search landing page.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // upgrade counters
        $models = $dataProvider->getModels();
        foreach($models as $model) {
            if(!$model->isOwner()) { // skip owner
                $model_counter = ResumeCounter::find()->where(['resume_id' => $model->id])->one();
                if ($model_counter === null) {
                    $model_counter = new ResumeCounter;
                    $model_counter->resume_id = $model->id;
                    $model_counter->view_count = 1;
                } else {
                    $model_counter->view_count = (int)$model_counter->view_count + 1;
                }

                $model_counter->save();
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Resume models.
     * @return mixed
     */
    public function actionSearch()
    {
        $searchModel = new ResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // upgrade counters
        $models = $dataProvider->getModels();
        foreach($models as $model) {
            if(!$model->isOwner()) { // skip owner
                $model_counter = ResumeCounter::find()->where(['resume_id' => $model->id])->one();
                if ($model_counter === null) {
                    $model_counter = new ResumeCounter;
                    $model_counter->resume_id = $model->id;
                    $model_counter->view_count = 1;
                } else {
                    $model_counter->view_count = (int)$model_counter->view_count + 1;
                }

                $model_counter->save();
            }
        }

        // current favorite resume id's
        $favorite_ids = [];
        if(!Yii::$app->user->isGuest) {
            $favorite_ids = Yii::$app->user->identity->getUserFavoriteResumes()->limit(100)->select('resume_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
        }

        return $this->render('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'favorite_ids' => $favorite_ids,
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
        // current favorite resume id's
        $favorite_ids = [];
        if(!Yii::$app->user->isGuest) {
            $favorite_ids = Yii::$app->user->identity->getUserFavoriteResumes()->limit(100)->select('resume_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
        }

        $model = $this->findModel($id);
        // upgrade counter
        if(!$model->isOwner()) { // skip owner
            $model_counter = ResumeCounter::find()->where(['resume_id' => $model->id])->one();
            if($model_counter === null) {
                $model_counter = new ResumeCounter;
                $model_counter->resume_id = $model->id;
                $model_counter->view_count = 0;
                $model_counter->open_count = 1;
            } else {
                $model_counter->open_count = (int)$model_counter->open_count + 1;
            }

            $model_counter->save();
        }
    

        return $this->render('view', [
            'model' => $model,
            'favorite_ids' => $favorite_ids,
        ]);
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

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }
}
