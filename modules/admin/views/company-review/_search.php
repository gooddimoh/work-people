<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReviewSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-review-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'company_id') ?>

    <?= $form->field($model, 'position') ?>

    <?= $form->field($model, 'worker_status') ?>

    <?php // echo $form->field($model, 'department') ?>

    <?php // echo $form->field($model, 'date_end') ?>

    <?php // echo $form->field($model, 'rating_total') ?>

    <?php // echo $form->field($model, 'rating_salary') ?>

    <?php // echo $form->field($model, 'rating_opportunities') ?>

    <?php // echo $form->field($model, 'rating_bosses') ?>

    <?php // echo $form->field($model, 'general_impression') ?>

    <?php // echo $form->field($model, 'pluses_impression') ?>

    <?php // echo $form->field($model, 'minuses_impression') ?>

    <?php // echo $form->field($model, 'tips_for_bosses') ?>

    <?php // echo $form->field($model, 'worker_recommendation') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
