<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\User;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [ // add access control by `role`
					'class' => 'app\components\AccessRule'
				],
                'rules' => [
					// [
                        // 'actions' => ['index'],
                        // 'allow' => false,
                        // 'roles' => ['?', '@'],
                    // ],
					[
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMINISTRATOR],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return Yii::$app->runAction('admin/vacancy/index');
        // return $this->render('index');
    }
}
