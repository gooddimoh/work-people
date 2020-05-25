<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $this->beginJs(); ?>
<script>
// fix crash html code on load wysiwyg editor
$(function() {
	'use strict';
	CKEDITOR.config.allowedContent = true
});
</script>
<? $this->endJs(); ?>

<div class="tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if(!empty($model->file_name)): ?>
        <?= $form->field($model, 'file_name')->hiddenInput(['maxlength' => true])->label(false) ?>
    <?php else: ?>
        <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'body')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?php /* $form->field($model, 'body')->textarea(['rows' => '12']) */ ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
