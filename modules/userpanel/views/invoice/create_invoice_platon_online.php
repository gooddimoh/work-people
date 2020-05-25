<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('invoice', 'Deposit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('invoice', 'Invoices'), 'url' => ['/userpanel/invoice/index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="registration registration--2">
  <div class="container withdraw-view">
    <div class="row">
      <div class="registration__main col">
        <div class="registration__inner">
          <div class="registration__title">
              <span><?= Html::encode($this->title) ?></span>
              <!-- <div>Deposit</div> -->
          </div>
          <?php $form = ActiveForm::begin([
              'options' => [
                  'class' => 'registration__form create-resume-form',
              ]
          ]); ?>
    
            <div class="row">
              <div class="col">

                <?php
                    $field = $form->field($model, 'price', [
                        'options' => ['class' => 'form-group'],
                        'inputOptions' => [
                            'placeholder' => 100,
                        ],
                    ]);

                    $field->template = <<< HTML
                      <label>{label}</label>
                      <div class="input-group">
                        <!-- <div class="input-group-addon">{$model->currency_code}</div> -->
                        {input}
                        <!-- <div class="input-group-addon">.00</div> -->
                      </div>
                      {error}
HTML;
                    echo $field->textInput(['maxlength' => true])->label(Yii::t('invoice', 'Amount') . ' (' . $model->currency_code . ')');
                ?>
                
                <?= Html::submitButton(Yii::t('invoice', 'Make Deposit'), ['class' => 'btn btn-primary']) ?>
              </div>
            </div>
            
          <?php ActiveForm::end(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<div style="margin-top:45px;">&nbsp;</div>