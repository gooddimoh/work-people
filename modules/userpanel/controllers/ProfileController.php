<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Profile;
use app\models\ProfileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
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
                            'view', 'create', 'update',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Displays a single Profile model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        if (empty(Yii::$app->user->identity->profile)) {
            return $this->render('profile_not_exists');    
        }

        $model = $this->findModel(Yii::$app->user->identity->profile->id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Profile model.
     * if profile already exists redirect to 'view' page
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!empty(Yii::$app->user->identity->company) && Yii::$app->user->id != 1) { // block register company as candidate
            return $this->redirect(['/userpanel']);
        }

        if(!empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['view']);
        }

        $model = new Profile();
        
        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            $post_data['Profile']['user_id'] = Yii::$app->user->id;
            if(Yii::$app->user->identity->getPhoneIdentity() !== false) {
                // insert phone that user logged in
                $post_data['Profile']['phone'] = Yii::$app->user->identity->getPhoneIdentity()->phone;
            }

            if ($model->load($post_data) && $model->save()) {
                return $this->redirect(['/userpanel/resume/create']);
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
     * Updates an existing Profile model through AJAX.
     * If update is successful, return updated data.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = $this->findModel(Yii::$app->user->identity->profile->id);

        $post_data = Yii::$app->request->post();
        $post_data['Profile']['user_id'] = Yii::$app->user->id;

        if ($model->load($post_data) && $model->save()) {
            $result_data = $model->getAttributes();
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

    /**
     * Deletes an existing Profile model.
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
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Profile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }
}
