<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CompanyReview;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */

$this->title = '#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company-review', 'Company Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-review-view">

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
            // 'status',
            [
                'label' => Yii::t('company-review', 'Status'),
                'value'=> function($model) {
                    $status_list = CompanyReview::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            // 'company_id',
            [
                'label' => Yii::t('company', 'User ID'),
                'value' => $model->company->company_name,
            ],
            'position',
            // 'worker_status',
            [
                'label' => Yii::t('company-review', 'Worker Status'),
                'value'=> function($model) {
                    $status_list = CompanyReview::getWorkerStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'department',
            'date_end:date',
            'rating_total',
            'rating_salary',
            'rating_opportunities',
            'rating_bosses',
            'general_impression',
            'pluses_impression',
            'minuses_impression',
            'tips_for_bosses',
            // 'worker_recommendation',
            [
                'label' => Yii::t('company-review', 'Worker Recommendation'),
                'value'=> function($model) {
                    $item_list = CompanyReview::getWorkerRecommendationList();
                    return $item_list[$model->worker_recommendation] ;
                },
            ],
        ],
    ]) ?>

</div>
