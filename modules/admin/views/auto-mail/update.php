<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AutoMail */

$this->title = Yii::t('automail', 'Update Auto Mail') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('automail', 'Auto Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Update');
?>
<div class="auto-mail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
