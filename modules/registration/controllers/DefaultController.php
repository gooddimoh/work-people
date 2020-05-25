<?php

namespace app\modules\registration\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserSearch;
use app\models\ResetPasswordForm;
use app\components\SendMailHelper;

/**
 * Default controller for the `registration` module
 */
class DefaultController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new User();
        $model->scenario = User::SCENARION_REGISTRATION;
        
        // filter data
        $post = Yii::$app->request->post();
        
        if(!empty($post)) {
            // filter data
            $model->login = trim($post['User']['login']);
            $model->phone = '' . intval(trim($post['User']['phone']));
            $model->username = trim($post['User']['login']);
            $model->email = trim($post['User']['email']);
            $model->password = trim($post['User']['password']);
            $model->password_repeat = trim($post['User']['password_repeat']);
            $model->reCaptcha = $post['User']['reCaptcha'];
            $model->setPassword($post['User']['password']); // generate password
            // $model->created_at = date('Y-m-d h:i:s'); // model behaviors() used
            // $model->updated_at = date('Y-m-d h:i:s');
            $model->generateEmailConfirmToken();
            
            $post['User'] = [];
        }

        if ($model->load($post) && $model->save()) {
            // send mail
            $sendMail = new SendMailHelper(
                $model->email,
                'Подтверждение регистрации пользователя',
                $this->render('registartion_mail_confirm', [
                    'user' => $model
                    ])
            );
            
            $sendMail->send();
            
            Yii::$app->session->setFlash('success', Yii::t('main', 'To complete registration, please confirm your email.'));
            
            return $this->redirect(['success']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionSuccess()
    {
        return $this->render('success');
    }
    
    public function actionConfirmMail()
    {
        $token = Yii::$app->request->get('token', null);
        
        if(empty($token))
            throw new NotFoundHttpException('Invalid token.');
        
        if (($model = User::findOne(['email_confirm_token' => $token])) !== null) {
            
            $model->email_confirm_token = null;
            // $this->date_confirm = time(); //! huh, fake confirm :)
            $model->save();
            
            // autologin in user
            Yii::$app->user->login($model, 3600*24*30);
            
            return $this->render('confirm-mail', [
                'sucess' => true // or false
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionResetPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->isUserExists()) {
            
            $user = $model->getUser();
            $user->generatePasswordResetToken();
            $user->save();
            
            $sendMail = new SendMailHelper(
                $user->email,
                'Восстановление пароля',
                $this->render('reset_password_mail', [
                    'user' => $user
                ])
            );
            
            $sendMail->send();
            
            Yii::$app->session->setFlash('success', 'На ваш Email отправленно сообщение с инструкциями по восстановлению пароля.');
            return $this->redirect(['success']);
        }
        
        return $this->render('password_reset', [
            'model' => $model,
        ]);
    }
    
    public function actionPasswordResetToken()
    {
        $token = Yii::$app->request->get('token', null);
        
        if(empty($token))
            throw new NotFoundHttpException('Invalid token.');
        
        $model = User::findByPasswordResetToken($token);
        
        if($model === null)
            throw new NotFoundHttpException('The requested page does not exist.');
        
        $model->scenario = User::SCENARION_RESET_PASSWORD;
        $model->removePasswordResetToken();
        
        // filter data
        $post = Yii::$app->request->post();
        
        if(!empty($post)) {
            // filter data
            $model->password = trim($post['User']['password']);;
            $model->password_repeat = trim($post['User']['password_repeat']);;
            $model->setPassword($post['User']['password']); // generate password
            $model->updated_at = time();
            
            $post['User'] = [];
        }

        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', "Пароль успешно изменён.");
            
            return $this->redirect(['success']);
        } else {
            return $this->render('password_reset_token', [
                'model' => $model,
            ]);
        }
    }

    public function actionRegisterCompanyByEmail()
    {
        $token = Yii::$app->request->get('token', null);
        
        if(empty($token))
            throw new NotFoundHttpException('Invalid token.');
        
        $model = User::findByPasswordResetToken($token);
        
        if($model === null)
            throw new NotFoundHttpException('The requested page does not exist.');
        
        $model->scenario = User::SCENARION_RESET_PASSWORD;
        $model->removePasswordResetToken();
        
        // filter data
        $post = Yii::$app->request->post();
        
        if(!empty($post)) {
            // filter data
            $model->verified_email = User::EMAIL_VERIFIED; // account email verified
            $model->password = trim($post['User']['password']);
            $model->password_repeat = trim($post['User']['password_repeat']);
            $model->setPassword($post['User']['password']); // generate password
            $model->updated_at = time();
            
            $post['User'] = [];
        }

        if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', "Пароль успешно изменён.");
            
            return $this->redirect(['success']);
        } else {
            return $this->render('register_company_by_email', [
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
