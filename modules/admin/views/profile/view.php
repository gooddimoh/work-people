<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = '#' . $model->id . ' ' . Html::encode($model->getFullName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="profile-view">

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
                'label' => Yii::t('user', 'User ID'),
                'value' => $model->user->login . ' (' . $model->user->email . ')',
            ],
            'status',
            'first_name',
            'last_name',
            'middle_name',
            'email:email',
            'gender_list',
            'birth_day:date',
            'country_name',
            'country_city_id',
            'photo_path',
            'phone',
        ],
    ]) ?>

</div>
