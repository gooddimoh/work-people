<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AccountController implements the CRUD actions for User model.
 */
class AccountController extends Controller
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
                            'view', 'edit'
                        ],
                        'allow' => false, // disabled
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
        return $this->render('view', [
            'model' => Yii::$app->user->identity,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->scenario = User::SCENARIO_EDIT_PROFILE;
        
        // filter data
        $post = Yii::$app->request->post();
        
        if(!empty($post)) {
            // filter data
            $model->username = trim($post['User']['username']);
            $model->password = trim($post['User']['password']);
            $model->password_new = trim($post['User']['password_new']);
            $model->password_repeat = trim($post['User']['password_repeat']);
            $model->updated_at = time();
            
            $post['User'] = [];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            if(!empty($model->password_new)) { // user change password
                $model->setPassword($model->password_new); // generate password
                $model->save(false);
            }
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
