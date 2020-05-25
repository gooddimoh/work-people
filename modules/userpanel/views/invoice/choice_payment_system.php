<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Invoice */

$this->title = 'Выберите способ оплаты';
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="title-desc">
    выберите предпочитаемого провайдера для оплаты:
	</div>
</div>

<div class="container">

  <h2>Platononline</h2>
  <?= Html::a('Пополнить', ['/userpanel/create-invoice-platon-online'], ['class' => 'btn']) ?>

</div>

<div style="margin-top:45px;">&nbsp;</div>
