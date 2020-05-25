<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Company;
use app\models\CompanySearch;
use app\models\UserPhone;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    public $defaultAction = 'view'; 
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'actions' => [
                            'view', 'create', 'update'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->render('company_not_exists');    
        }

        $model = $this->findModel(Yii::$app->user->identity->company->id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!empty(Yii::$app->user->identity->profile) && Yii::$app->user->id != 1) { // block register candidate as company
            return $this->redirect(['/userpanel']);
        }

        if(!empty(Yii::$app->user->identity->company)) {
            return $this->redirect(['view']);
        }

        $model = new Company();
        $modelUserPhone = Yii::$app->user->identity->getPhoneIdentity();
        if ($modelUserPhone->verified == UserPhone::VERIFIED_REGISTRED_ACCOUNT_BY_SMS) {
            $modelUserPhone->scenario = UserPhone::SCENARION_CREATE_COMPANY;
        }

        if (Yii::$app->request->isPost) {
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['Company']['user_id'] = Yii::$app->user->id;
            $post_data['Company']['status'] = Company::STATUS_NOT_VERIFIED;

            // upgrade company administrator information and access
            $modelUserPhone->contact_phone_for_admin = $post_data['UserPhone']['contact_phone_for_admin'];
            $modelUserPhone->company_role = UserPhone::ROLE_COMPANY_OWNER;
            $modelUserPhone->company_worker_name = $post_data['UserPhone']['company_worker_name'];
            $modelUserPhone->company_worker_email = $post_data['UserPhone']['company_worker_email'];

            if ($modelUserPhone->save()) {
                if ($model->load($post_data) && $model->save()) {
                    return $this->redirect(['/userpanel/vacancy/create', 'type' => 'registration']);
                } else if(Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $model->getErrors()
                    ];
                }
            } else if (Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $modelUserPhone->getErrors()
                ];
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelUserPhone' => $modelUserPhone,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (empty(Yii::$app->user->identity->company)) {
            return $this->redirect('view');    
        }

        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = $this->findModel(Yii::$app->user->identity->company->id);

        //! BUG, can be vulnerability need filter post data (relation keys)
        $post_data = Yii::$app->request->post();

        $post_data['Company']['user_id'] = Yii::$app->user->id;
        $post_data['Company']['status'] = Company::STATUS_NOT_VERIFIED; // information changed then unverified
        // $post_data['Company']['vacancies'][] = []; //! fix remove relations on update

        if ($model->load($post_data) && $model->save()) {
            $result_data = $model->getAttributes();
            return [
                'success' => true,
                'data' => $result_data,
            ];
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        return [
            'success' => false,
            'errors' => $model->getErrors()
        ];
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('company', 'The requested page does not exist.'));
    }
}
