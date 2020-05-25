<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Message;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('message', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('message', 'Create Message'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'message_room_id',
            // 'owner_id',
            [
                'attribute' => 'owner_id',
                'value' => function($model) {
                    return $model->owner->login . ' (' . $model->owner->email . ')';
                }
            ],
            // 'for_user_id',
            [
                'attribute' => 'for_user_id',
                'value' => function($model) {
                    return $model->forUser->login . ' (' . $model->forUser->email . ')';
                }
            ],
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> Message::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $item_list = Message::getStatusList();
                    return $item_list[$model->status] ;
                },
            ],
            //'device_type',
            //'message_text:ntext',
            'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
