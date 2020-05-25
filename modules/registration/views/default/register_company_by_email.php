<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="registration">
  <div class="container">
    <h1>Активация компании</h1>
    <div class="alert alert-info alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
      Для завершения активации компании, придумайте пароль и введите в форму ниже
    </div>

    <div class="user-form">
      <div class="container">
          <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
          
          <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
          
          <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

          <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
              <?= Html::submitButton('Изменить пароль', ['class' => 'btn btn-success']) ?>
            </div>
          </div>

          <?php ActiveForm::end(); ?>

      </div>
    </div>

  </div>
</div>