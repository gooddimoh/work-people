<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="safe-deal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'creator_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'deal_type')->textInput() ?>

    <?= $form->field($model, 'has_prepaid')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_total')->textInput() ?>

    <?= $form->field($model, 'amount_total_src')->textInput() ?>

    <?= $form->field($model, 'amount_currency_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_prepaid')->textInput() ?>

    <?= $form->field($model, 'amount_prepaid_currency_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'condition_prepaid')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'condition_deal')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'execution_period')->textInput() ?>

    <?= $form->field($model, 'execution_range')->textInput() ?>

    <?= $form->field($model, 'possible_delay_days')->textInput() ?>

    <?= $form->field($model, 'comission')->textInput() ?>

    <?= $form->field($model, 'started_at')->textInput() ?>

    <?= $form->field($model, 'finished_at')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
