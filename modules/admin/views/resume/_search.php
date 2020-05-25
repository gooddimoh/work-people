<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ResumeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resume-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?php // echo $form->field($model, 'middle_name') ?>

    <?php // echo $form->field($model, 'birth_day') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'type_of_employment') ?>

    <?php // echo $form->field($model, 'desired_salary') ?>

    <?php // echo $form->field($model, 'desired_country_of_work') ?>

    <?php // echo $form->field($model, 'photo_path') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'resume_country_codes') ?>

    <?php // echo $form->field($model, 'custom_country') ?>

    <?php // echo $form->field($model, 'skills') ?>

    <?php // echo $form->field($model, 'had_foreign_job') ?>

    <?php // echo $form->field($model, 'creation_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
