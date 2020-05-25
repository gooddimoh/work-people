<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Invoice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Withdraw */
/* @var $paymentSystem app\components\payment\PaymentSystem */


// $paymentSystem->getStatus();

$this->title = Yii::t('invoice', 'Deposit') . ' #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice', 'Invoices'), 'url' => ['/userpanel/invoice/index']];
$this->params['breadcrumbs'][] = $this->title;

$pay_system_name = '';
$pay_system_img = '';

$pay_status_ico = '';
$profit_action = '';

switch($model->pay_system) {
    case Invoice::PAYMENT_QIWI:
        $pay_system_name = 'Qiwi';
        $pay_system_img = '/img/payment_systems/qiwi.png';
        break;
    case Invoice::PAYMENT_PAYEER:
        $pay_system_name = 'Payeer';
        $pay_system_img = '/img/payment_systems/payeer.png';
        break;
    case Invoice::PAYMENT_MY:
        $pay_system_name = 'Pay Pay';
        $pay_system_img = '/img/logo_footer.png';
        break;
    case Invoice::PAYMENT_FREE_KASSA:
        $pay_system_name = 'Free Kassa';
        $pay_system_img = '/img/payment_systems/free-kassa.png';
        break;
    case Invoice::PAYMENT_PLATON_ONLINE:
        $pay_system_name = 'Platon online';
        $pay_system_img = '/img/payment_systems/platon.png';
        break;
    // case Invoice::PAYMENT_VISA_MASTERCARD:
        // $pay_system_img = '/img/payment_systems/visa.png';
        // break;
    // case Invoice::PAYMENT_YANDEX_MONEY:
        // $pay_system_img = '/img/payment_systems/ya2.png';
        // break;
    
}

$pay_system_img = Html::img(Url::toRoute($pay_system_img), [
    'alt'=>'payment systems',
    'style' => 'max-height:48px; background-color: #fff; float:left;margin-right:28px;'
]); 

// --

switch($model->status) {
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
// --
?>

<div class="registration registration--2">
  <div class="container withdraw-view">
    <div class="row">
      <div class="registration__main col">
        <div class="registration__inner">
          <div class="registration__title">
              <span><?= Html::encode($this->title) ?></span>
              <!-- <div>Deposit</div> -->
          </div>
          <div class="registration__form create-resume-form">
              <div class="row" style="margin-bottom:45px;">
                  <div class="col">
                      <?= $pay_system_name; ?> <?= $pay_system_img; ?>
                  </div>
              </div>
              
              <div class="row">
                  <label class="col"><?= Yii::t('invoice', 'Status') ?>:</label>
                  <div class="col"><?= $pay_status_ico; ?></div>
              </div>
              
              <div class="row" style="margin-bottom:45px;">
                  <label class="col">Сумма пополнения:</label>
                  <div class="col"><?= $model->price ?> <?= $model->currency_code ?></i></div>
              </div>
              <?php if($model->status != Invoice::STATUS_WAITING): ?>
                  <div class="row">
                      <label class="col"><?= Yii::t('invoice', 'Amount') ?></label>
                      <div class="col"><?= $model->price ?> <?= $model->currency_code ?></div>
                  </div>
                  
              <?php else: ?>
                  <?php echo $paymentSystem->renderWidget(); ?>
              <?php endif?>
          </div>
            
        </div>
      </div>
    </div>
  </div>
</div>

<div style="margin-top:45px;">&nbsp;</div>
