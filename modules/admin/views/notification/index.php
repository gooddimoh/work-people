<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Notification;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('message', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('message', 'Create Notification'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'user_id',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return $model->user->login . ' (' . $model->user->email . ')';
                }
            ],
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> Notification::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = Notification::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'title',
            // 'title_html',
            //'text:ntext',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
