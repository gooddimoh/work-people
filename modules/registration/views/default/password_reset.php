<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="container">
  <h1>Восстановление паролья</h1>
  <div class="alert alert-info alert-dismissable">
    Для восстановления пароля заполнение форму ниже
  </div>

  <div class="user-form">
      <?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
      
      <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

      <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
          <?= Html::submitButton('Восстановить Пароль', ['class' => 'btn btn-success']) ?>
        </div>
      </div>

      <?php ActiveForm::end(); ?>

  </div>
</div>