<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = 'Редактировать вакансию: ' . Html::encode($model->categoryJob->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Html::encode($model->categoryJob->name), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Update');
?>
<div class="vacancy-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
