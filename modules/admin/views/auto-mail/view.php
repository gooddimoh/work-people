<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AutoMail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('automail', 'Auto Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="auto-mail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('main', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('main', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            // 'user_id',
            [
                'label' => Yii::t('user', 'User'),
                'value' => $model->user->login . ' (' . $model->user->email . ')',
            ],
            // 'category_id',
            [
                'label' => Yii::t('automail', 'Category ID'),
                'value' => $model->category->name,
            ],
            'status',
            'use_messenger',
            'request',
            'country_codes',
            'location',
            'created_at:datetime',
        ],
    ]) ?>

</div>
