<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('company', 'Create Company'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'filter'=> Company::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = Company::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'company_name',
            // 'logo',
            // 'type',
            [
                'attribute' => 'type',
                'filter'=> Company::getTypeList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $type_list = Company::getTypeList();
                    return $type_list[$model->type] ;
                },
            ],
            'type_industry',
            //'number_of_employees',
            //'worker_country_codes',
            //'site',
            //'contact_name',
            //'contact_phone',
            //'contact_email:email',
            //'description:ntext',
            //'document_code',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
