<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Message;

/* @var $this yii\web\View */
/* @var $model app\models\Message */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('message', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="message-view">

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
            'message_room_id',
            // 'owner_id',
            [
                'label' => Yii::t('message', 'Owner ID'),
                'value' => $model->owner->login . ' (' . $model->owner->email . ')',
            ],
            // 'for_user_id',
            [
                'label' => Yii::t('message', 'For User ID'),
                'value' => $model->forUser->login . ' (' . $model->forUser->email . ')',
            ],
            // 'status',
            [
                'label' => Yii::t('vacancy', 'Status'),
                'value'=> function($model) {
                    $item_list = Message::getStatusList();
                    return $item_list[$model->status] ;
                },
            ],
            // 'device_type',
            [
                'label' => Yii::t('vacancy', 'Device Type'),
                'value'=> function($model) {
                    $item_list = Message::getDeviceTypeList();
                    return $item_list[$model->device_type] ;
                },
            ],
            'message_text:ntext',
            'created_at:datetime',
        ],
    ]) ?>

</div>
