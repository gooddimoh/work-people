<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$this->title = Yii::t('resume', 'Create Resume');
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resume-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelJob' => $modelJob,
        'modelLang' => $modelLang,
        'modelEducation' => $modelEducation,
    ]) ?>

</div>
