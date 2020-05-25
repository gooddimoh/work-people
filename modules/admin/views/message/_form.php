<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Message;

/* @var $this yii\web\View */
/* @var $model app\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'message_room_id')->textInput() ?>

    <?= $form->field($model, 'owner_id')->textInput() ?>

    <?= $form->field($model, 'for_user_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        Message::getStatusList()
    ) ?>

    <?= $form->field($model, 'device_type')->dropDownList(
        Message::getDeviceTypeList()
    ) ?>

    <?= $form->field($model, 'message_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
