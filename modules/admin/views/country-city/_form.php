<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CountryCity;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CountryCity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-city-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'priority')->textInput() ?>
    
    <?= $form->field($model, 'country_char_code')->dropDownList(
        ArrayHelper::map(CountryCity::getCountryList(), 'char_code', 'name')
    ) ?>

    <?= $form->field($model, 'city_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
