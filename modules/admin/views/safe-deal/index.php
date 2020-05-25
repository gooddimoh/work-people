<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\SafeDeal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SafeDealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('deal', 'Safe Deals');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="safe-deal-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('deal', 'Create Safe Deal'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'creator_id',
            'status',
            'deal_type',
            // 'has_prepaid',
            [
                'attribute' => 'has_prepaid',
                'filter'=> SafeDeal::getHasPrepaidList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $item_list = SafeDeal::getHasPrepaidList();
                    return $item_list[$model->has_prepaid] ;
                },
            ],
            'title',
            //'amount_total',
            //'amount_total_src',
            //'amount_currency_code',
            //'amount_prepaid',
            //'amount_prepaid_currency_code',
            //'condition_prepaid:ntext',
            //'condition_deal:ntext',
            //'execution_period',
            //'execution_range',
            //'possible_delay_days',
            //'comission',
            //'started_at',
            //'finished_at',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
