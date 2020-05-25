<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration registration--2">
  <div class="container">
    <div class="registration__inner">
        <div class="registration__title"><?= Html::encode($this->title) ?></div>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

            <div class="registration__form alert alert-success">
                Ваше сообщение получено. Если это необходимо, мы свяжемся с вами как можно быстрее.
            </div>

        <?php else: ?>

            <div class="registration__form">
                Если у вас возникли какие-то проблемы при пользовании нашим сервисом, пожалуйста воспользуйтесь формой обратной связи расположенной ниже.
                Спасибо.
            </div>
            <div class="registration__or">
                <span></span>
            </div>
            
            <?php $form = ActiveForm::begin([
                'id' => 'contact-form',
                'options' => ['class' => 'registration__form']
            ]); ?>

                <?= $form->field($model, 'name')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('name'), 'autofocus' => true]) ?>

                <?= $form->field($model, 'email')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('email')]) ?>

                <?= $form->field($model, 'subject')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('subject')]) ?>

                <?= $form->field($model, 'body')->label(false)->textarea(['rows' => 6, 'placeholder' => $model->getAttributeLabel('body')]) ?>

                <?php /* $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) */ ?>
                
                <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('main', 'Send'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>

        <?php endif; ?>
    </div>
  </div>
</div>
