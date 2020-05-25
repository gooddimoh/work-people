<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\Vacancy;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VacancySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('vacancy', 'Vacancies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vacancy-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('vacancy', 'Create Vacancy'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('vacancy', 'Export current search to Excel (*.xls)'), ['export', 'VacancySearch' => empty(Yii::$app->request->queryParams['VacancySearch']) ? null : Yii::$app->request->queryParams['VacancySearch']], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('vacancy', 'Import Excel (*.xls)'), ['import'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'company_id',
            [
                'attribute' => 'company_id',
                'value' => function($model) {
                    return $model->company->company_name;
                }
            ],
            // 'category_job_id',
            [
                'attribute' => 'category_job_id',
                'value' => function($model) {
                    return $model->categoryJob->name;
                }
            ],
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> Vacancy::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = Vacancy::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            // 'pin_position',
            // 'special_status',
            //'show_on_main_page',
            [
                'attribute' => 'show_on_main_page',
                'filter'=> Vacancy::getShowOnMainPageStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = Vacancy::getShowOnMainPageStatusList();
                    return $status_list[$model->show_on_main_page] ;
                },
            ],
            'main_page_priority',
            'company_name',
            //'gender_list',
            //'age_min',
            //'age_max',
            //'employment_type',
            //'worker_country_codes',
            //'free_places',
            //'regular_places',
            //'date_start',
            //'date_end',
            //'date_free',
            //'country_name',
            //'salary_per_hour_min',
            //'salary_per_hour_max',
            //'salary_per_hour_min_src',
            //'salary_per_hour_max_src',
            //'currency_code',
            //'hours_per_day_min',
            //'hours_per_day_max',
            //'days_per_week_min',
            //'days_per_week_max',
            //'prepaid_expense_min',
            //'prepaid_expense_max',
            //'type_of_working_shift',
            //'residence_provided',
            //'residence_amount',
            //'residence_amount_currency_code',
            //'residence_people_per_room',
            //'documents_provided',
            //'documents_required',
            //'job_description:ntext',
            //'job_description_bonus:ntext',
            //'contact_email_list:email',
            //'main_image',
            //'agency_accept',
            //'agency_paid_document',
            //'agency_paid_document_price',
            //'agency_paid_document_currency_code',
            //'agency_free_document',
            //'agency_pay_commission',
            //'agency_pay_commission_amount',
            //'agency_pay_commission_currency_code',
            //'secure_deal',
            //'meta_keywords:ntext',
            //'meta_description:ntext',
            //'creation_time:datetime',
            //'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
