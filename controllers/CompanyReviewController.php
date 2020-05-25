<?php

namespace app\controllers;

use Yii;
use app\models\CompanyReview;
use app\models\CompanyReviewSearch;
use app\models\Company;
use app\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyReviewController implements the CRUD actions for CompanyReview model.
 */
class CompanyReviewController extends Controller
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
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CompanyReview models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanyReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModelCompany = new CompanySearch();
        $dataProviderCompany = $searchModelCompany->search([]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderCompany' => $dataProviderCompany,
        ]);
    }

    /**
     * Displays a single CompanyReview model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewcompany($id)
    {
        $searchModel = new CompanyReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['company_id' => $id]);
        
        return $this->render('view-company', [
            'model' => $this->findModelCompany($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays add CompanyReview from.
     * @param integer $id - id company
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAdd($id)
    {
        $model = new CompanyReview();

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['CompanyReview']['company_id'] = $id;

            if ($model->load($post_data) && $model->save()) {
                // return $this->redirect(['view', 'id' => $model->id]);
                return $this->redirect(['success']);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }

        return $this->render('add', [
            'model' => $model,
            'model_company' => $this->findModelCompany($id),
        ]);
    }

    public function actionSuccess() {
        return $this->render('success');
    }
    
    /**
     * Displays a single CompanyReview model.
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
     * Finds the CompanyReview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompanyReview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompanyReview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelCompany($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }
}
