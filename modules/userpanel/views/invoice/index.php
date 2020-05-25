<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Invoice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('invoice', 'Invoices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="title-desc">
        <h2>Ваш текущий баланс: <b class="text-success"><?= Yii::$app->user->identity->getBalance(); ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?>.</h2>
	</div>
</div>

<div class="container">
    <p>
        <!-- <?= Html::a(Yii::t('invoice', 'Deposit'), ['choice-payment-system'], ['class' => 'btn']) ?> -->
        <?= Html::a(Yii::t('invoice', 'Deposit'), ['create-invoice-platon-online'], ['class' => 'btn']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'user_id',
            // 'pay_system',
            [
                'attribute' => 'pay_system',
                'headerOptions' => ['width' => '100'],
                // 'label' => 'pay_system',
                'format' => 'raw',
                'value' => function($data) {
                    $pay_system_img = '';
                    
                    switch($data->pay_system) {
                        case Invoice::PAYMENT_QIWI:
                            $pay_system_img = '/img/payment_systems/qiwi.png';
                            break;
                        case Invoice::PAYMENT_PAYEER:
                            $pay_system_img = '/img/payment_systems/payeer.png';
                            break;
                        case Invoice::PAYMENT_MY:
                            $pay_system_img = '/img/logo_footer.png';
                            break;
                        case Invoice::PAYMENT_FREE_KASSA:
                            $pay_system_img = '/img/payment_systems/free-kassa.png';
                            break;
                        case Invoice::PAYMENT_PLATON_ONLINE:
                            $pay_system_img = '/img/payment_systems/platon.png';
                            break;
                        // case Invoice::PAYMENT_VISA_MASTERCARD:
                            // $pay_system_img = '/img/payment_systems/visa.png';
                            // break;
                        // case Invoice::PAYMENT_YANDEX_MONEY:
                            // $pay_system_img = '/img/payment_systems/ya2.png';
                            // break;
                        
                    }
                    
                    return Html::img(Url::toRoute($pay_system_img), [
                        'alt'=>'payment systems',
                        'style' => 'max-height:30px;'
                    ]);
                },
            ],
            // 'status',
            [
                'attribute' => 'status',
                'headerOptions' => ['width' => '150'],
                'format' => 'raw',
                'value' => function($data) {
                    $pay_status_ico = '';
                    
                    switch($data->status) {
                        case Invoice::STATUS_WAITING:
                            $pay_status_ico = 'ожидает оплату';
                            break;
                        case Invoice::STATUS_PAYED:
                            $pay_status_ico = 'оплачено';
                            break;
                        case Invoice::STATUS_REJECTED:
                            $pay_status_ico = 'ошибка';
                            break;
                        default:
                            $pay_status_ico = 'BUG!';
                            break;
                    }
                    
                    return $pay_status_ico;
                },
            ],
            'price',
            //'price_source',
            'currency_code',
            //'phone',
            //'pay_system_response',
            //'pay_system_i',
            //'pay_date',
            //'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=> Yii::t('invoice', 'Actions'),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if($model->status == Invoice::STATUS_WAITING) {
                            return Html::a(
                                Yii::t('invoice', 'Pay'), 
                                Url::to(['/userpanel/invoice/invoice-view?id=' . $model->id]), ['class'=>'btn']);
                        }
                        
                        return Html::a(
                            Yii::t('invoice', 'View'), 
                            Url::to(['/userpanel/invoice/invoice-view?id=' . $model->id]));
                    },
                ],
            ],
        ],
    ]); ?>


</div>

<div style="margin-top:45px;">&nbsp;</div>
