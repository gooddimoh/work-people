<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Tarif;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ServiceController extends \yii\web\Controller
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
                            'index', 'purchase', 'top', 'individual',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
					[
                        'actions' => [
                            'price', 'vip',
                        ],
                        'allow' => true,
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'changestatus' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPurchase()
    {
        return $this->render('purchase');
    }

    public function actionPrice()
    {
        $tarifList = Tarif::find()->all();
        $min_price_vip = 10000000;
        $publication_count = 1;

        foreach ($tarifList as $tarif) {
            if (floatval($tarif->price) < $min_price_vip) {
                $min_price_vip = floatval($tarif->price);
                $publication_count = $tarif->publication_count;
            }
        }

        return $this->render('price', [
            'min_price_vip' => $min_price_vip,
            'publication_count' => $publication_count
        ]);
    }

    public function actionVip()
    {
        $tarifList = Tarif::find()->all();
        
        return $this->render('vip', [
            'tarifList' => $tarifList,
        ]);
    }

    public function actionTop()
    {
        return $this->render('top');
    }
    
    public function actionIndividual()
    {
        return $this->render('individual');
    }
}
