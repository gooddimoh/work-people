<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AutoMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('automail', 'Auto Mails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auto-mail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('automail', 'Create Auto Mail'), ['create'], ['class' => 'btn btn-success']) ?>
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
            // 'category_id',
            [
                'attribute' => 'category_id',
                'value' => function($model) {
                    return $model->category->name;
                }
            ],
            'status',
            // 'use_messenger',
            'request',
            //'country_codes',
            //'location',
            'created_at:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
