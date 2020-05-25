<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CountryCity */

$this->title = 'Create Country City';
$this->params['breadcrumbs'][] = ['label' => 'Country Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
