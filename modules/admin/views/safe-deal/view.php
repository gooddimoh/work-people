<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Safe Deals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="safe-deal-view">

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
            'creator_id',
            'status',
            'deal_type',
            'has_prepaid',
            'title',
            'amount_total',
            'amount_total_src',
            'amount_currency_code',
            'amount_prepaid',
            'amount_prepaid_currency_code',
            'condition_prepaid:ntext',
            'condition_deal:ntext',
            'execution_period',
            'execution_range',
            'possible_delay_days',
            'comission',
            'started_at',
            'finished_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
