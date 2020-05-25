<?php

namespace app\controllers;

use Yii;
use app\models\Vacancy;
use app\models\VacancySearch;
use app\models\VacancyCounter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VacancyController implements the CRUD actions for Vacancy model.
 */
class VacancyController extends Controller
{
    /**
     * Lists all Vacancy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query_params = Yii::$app->request->queryParams;
        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search($query_params);

        // upgrade counters
        $models = $dataProvider->getModels();
        foreach($models as $model) {
            if(!$model->isOwner()) { // skip owner
                $model_counter = VacancyCounter::find()->where(['vacancy_id' => $model->id])->one();
                if ($model_counter === null) {
                    $model_counter = new VacancyCounter;
                    $model_counter->vacancy_id = $model->id;
                    $model_counter->view_count = 1;
                } else {
                    $model_counter->view_count = (int)$model_counter->view_count + 1;
                }

                $model_counter->save();
            }
        }

        $currency_list = Vacancy::getCurrencyList();
        $list_currency = Yii::$app->params['defaultCurrencyCharCode'];

        // check exists currency char_code
        foreach($currency_list as $item) {
            if(!empty($query_params['list_currency']) && $query_params['list_currency'] === $item['char_code']) {
                $list_currency = $item['char_code'];
                break; // exit
            }
        }

        // current favorite resume id's
        $favorite_ids = [];
        if(!Yii::$app->user->isGuest) {
            $favorite_ids = Yii::$app->user->identity->getUserFavoriteVacancies()->limit(100)->select('vacancy_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list_currency' => $list_currency,
            'currency_list' => $currency_list,
            'favorite_ids' => $favorite_ids,
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
        // upgrade counter
        $model = $this->findModel($id);

        if(!$model->isOwner()) { // skip owner
            $model_counter = VacancyCounter::find()->where(['vacancy_id' => $model->id])->one();
            if($model_counter === null) {
                $model_counter = new VacancyCounter;
                $model_counter->vacancy_id = $model->id;
                $model_counter->view_count = 0;
                $model_counter->open_count = 1;
            } else {
                $model_counter->open_count = (int)$model_counter->open_count + 1;
            }

            $model_counter->save();
        }

        // current favorite resume id's
        $favorite_ids = [];
        if(!Yii::$app->user->isGuest) {
            $favorite_ids = Yii::$app->user->identity->getUserFavoriteVacancies()->limit(100)->select('vacancy_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
        }

        return $this->render('view', [
            'model' => $model,
            'favorite_ids' => $favorite_ids,
        ]);
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
