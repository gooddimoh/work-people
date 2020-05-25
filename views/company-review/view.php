<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Company Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-review-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'status',
            'company_id',
            'position',
            'worker_status',
            'department',
            'date_end',
            'rating_total',
            'rating_salary',
            'rating_opportunities',
            'rating_bosses',
            'general_impression',
            'pluses_impression',
            'minuses_impression',
            'tips_for_bosses',
            'worker_recommendation',
        ],
    ]) ?>

</div>
