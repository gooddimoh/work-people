<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\ReferenceHelper;
use app\components\CurrencyConverterHelper;

class ReferenceController extends Controller
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
            // ---
            'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [ // add access control by `role`
					'class' => 'app\components\AccessRule'
				],
                'rules' => [
					[
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMINISTRATOR],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $reference_name = $params['file'];
        if (empty($reference_name)) {
            throw new NotFoundHttpException('Param `file` required.');
        }

        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            $data = [];
            foreach ($post_data['Reference'] as $val) {
                $data[] = $val;
            }
            
            if($this->setReferenceInfo($reference_name, $data)) {
                if(Yii::$app->request->isAjax) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => true
                    ];
                }
            }
        }
        
        return $this->render('index', $this->getReferenceInfo($reference_name));
    }

    protected function getReferenceInfo($reference_name)
    {
        switch($reference_name) {
            case 'country_list':
                $data_list = ReferenceHelper::getCountryList(true);
                return [
                    'name' => 'Страны',
                    'data_list' => $data_list,
                    'data_field_list' => array_keys($data_list[0]),
                ];
            case 'currency_list':
                $data_list = ReferenceHelper::getCurrencyList();
                return [
                    'name' => 'Валюты',
                    'data_list' => $data_list,
                    'data_field_list' => array_keys($data_list[0]),
                ];
            case 'language_list':
                $data_list = ReferenceHelper::getLanguageList();
                return [
                    'name' => 'Языки',
                    'data_list' => $data_list,
                    'data_field_list' => array_keys($data_list[0]),
                ];
            default: 
                throw new NotFoundHttpException('The reference name does not exist.');
        }
    }

    protected function setReferenceInfo($reference_name, $data)
    {
        switch($reference_name) {
            case 'country_list':
                return ReferenceHelper::setCountryList($data);
            case 'currency_list':
                // check is currency code supported
                $courses_list = CurrencyConverterHelper::getCoursesList();
                foreach ($data as $data_item) {
                    $founded = false;
                    foreach ($courses_list as $currency_item) {
                        if ($data_item['char_code'] == $currency_item['CharCode']) {
                            $founded = true;
                            break;
                        }
                    }

                    if (!$founded) {
                        if ($data_item['char_code'] == 'RUR') continue; // skip RUR

                        throw new BadRequestHttpException('Unsupported currency code: ' . $data_item['char_code']);
                    }
                }

                return ReferenceHelper::setCurrencyList($data);
            case 'language_list':
                return ReferenceHelper::setLanguageList($data);
            default: 
                throw new NotFoundHttpException('The reference name does not exist.');
        }
    }

}
