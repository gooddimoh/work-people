<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CompanyReview;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company-review', 'Company Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-review-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('company-review', 'Create Company Review'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> CompanyReview::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = CompanyReview::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            // 'company_id',
            [
                'attribute' => 'company_id',
                'value' => function($model) {
                    return $model->company->company_name;
                }
            ],
            'position',
            // 'worker_status',
            [
                'attribute' => 'worker_status',
                'filter'=> CompanyReview::getWorkerStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = CompanyReview::getWorkerStatusList();
                    return $status_list[$model->worker_status] ;
                },
            ],
            // 'department',
            'date_end:date',
            'rating_total',
            //'rating_salary',
            //'rating_opportunities',
            //'rating_bosses',
            //'general_impression',
            //'pluses_impression',
            //'minuses_impression',
            //'tips_for_bosses',
            // 'worker_recommendation',
            [
                'attribute' => 'worker_recommendation',
                'filter'=> CompanyReview::getWorkerRecommendationList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $item_list = CompanyReview::getWorkerRecommendationList();
                    return $item_list[$model->worker_recommendation] ;
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
