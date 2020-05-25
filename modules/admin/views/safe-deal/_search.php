<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDealSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="safe-deal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'creator_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'deal_type') ?>

    <?= $form->field($model, 'has_prepaid') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'amount_total') ?>

    <?php // echo $form->field($model, 'amount_total_src') ?>

    <?php // echo $form->field($model, 'amount_currency_code') ?>

    <?php // echo $form->field($model, 'amount_prepaid') ?>

    <?php // echo $form->field($model, 'amount_prepaid_currency_code') ?>

    <?php // echo $form->field($model, 'condition_prepaid') ?>

    <?php // echo $form->field($model, 'condition_deal') ?>

    <?php // echo $form->field($model, 'execution_period') ?>

    <?php // echo $form->field($model, 'execution_range') ?>

    <?php // echo $form->field($model, 'possible_delay_days') ?>

    <?php // echo $form->field($model, 'comission') ?>

    <?php // echo $form->field($model, 'started_at') ?>

    <?php // echo $form->field($model, 'finished_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
