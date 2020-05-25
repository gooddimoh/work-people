<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Польователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> [
                    '0' => 'блокировка',
                    '10' => 'активен'
                ],
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = [
                        '0' => 'блокировка',
                        '10' => 'активен'
                    ];
                    return $status_list[$model->status] ;
                },
            ],
            'login',
            'username',
            'email:email',
            // 'phone',
            // 'role',
            [
                'attribute' => 'role',
                'filter'=> [
                    'administrator' => 'administrator',
                    'user' => 'user'
                ],
            ],
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email_confirm_token:email',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
