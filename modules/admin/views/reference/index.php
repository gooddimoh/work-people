<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = 'Справочник: ' . $name;
$this->params['breadcrumbs'][] = 'Справочники';
$this->params['breadcrumbs'][] = $name;
?>
<div class="vacancy-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'data_list' => $data_list,
        'data_field_list' => $data_field_list,
    ]) ?>

</div>
