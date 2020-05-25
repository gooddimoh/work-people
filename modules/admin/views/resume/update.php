<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$this->title = 'Редактировать анкету: ' . Html::encode($model->getFullName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->getFullName()), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Update');
?>
<div class="resume-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelJob' => $modelJob,
        'modelLang' => $modelLang,
        'modelEducation' => $modelEducation,
    ]) ?>

</div>
