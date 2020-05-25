<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\color\ColorInput;
use app\models\CategoryJob;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(
        CategoryJob::getStatusList()
    ) ?>

    <?= $form->field($model, 'show_on_main_page')->dropDownList(
        CategoryJob::getShowOnMainPageList()
    ) ?>

    <?= $form->field($model, 'main_page_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'background_color')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Выберите цвет ...'],
    ]) ?>

    <?= $form->field($model, 'background_color')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Выберите цвет ...'],
    ]) ?>

    <label>Изображение:</label>
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <?php if(!empty($model->image_path)): ?>
                    <img src="/upload/category/<?=$model->image_path ?>" class="img-thumbnail" style="max-width:600px;">
                <?php else: ?>
                    <p>изображение не указано</p>
                <?php endif; ?>
            </div>
        </div>
        <?= $form->field($model, 'file')->fileInput()->label('Изменить изображение') ?>
    </div>
    

    <?= $form->field($model, 'meta_keywords')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
