<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('user', 'Update Profile') . ': #' . $model->id . ' ' . Html::encode($model->getFullName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id . ' ' . Html::encode($model->getFullName()), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Update');
?>
<div class="profile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
