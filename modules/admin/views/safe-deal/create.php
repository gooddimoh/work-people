<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */

$this->title = 'Create Safe Deal';
$this->params['breadcrumbs'][] = ['label' => 'Safe Deals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="safe-deal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
