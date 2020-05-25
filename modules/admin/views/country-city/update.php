<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CountryCity */

$this->title = 'Изменить название города: ' . '#' . $model->id . ' ' . $model->city_name;
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id . ' ' . $model->city_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Update');
?>
<div class="country-city-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
