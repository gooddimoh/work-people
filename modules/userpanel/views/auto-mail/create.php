<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AutoMail */

$this->title = Yii::t('automail', 'Create Auto Mail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('automail', 'Auto Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-mail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
