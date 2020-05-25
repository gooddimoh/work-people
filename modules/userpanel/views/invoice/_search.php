<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'pay_system') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'price_source') ?>

    <?php // echo $form->field($model, 'currency_code') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'pay_system_response') ?>

    <?php // echo $form->field($model, 'pay_system_i') ?>

    <?php // echo $form->field($model, 'pay_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('invoice', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('invoice', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
