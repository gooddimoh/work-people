<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Vacancy;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = '#' . $model->id . ' ' . Html::encode($model->categoryJob->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vacancy-view">

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
            // 'id',
            // 'company_id',
            [
                'label' => Yii::t('company', 'Company'),
                'value' => $model->company->company_name,
            ],
            [
                'label' => Yii::t('vacancy', 'Vacancy posting categories'),
                'value'=> function($model) {
                    $item_list = ArrayHelper::getColumn($model->categories, 'name');
                    return implode(', ', $item_list);
                },
            ],
            // 'status',
            [
                'label' => Yii::t('vacancy', 'Status'),
                'value'=> function($model) {
                    $status_list = Vacancy::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'pin_position',
            'special_status',
            // 'show_on_main_page',
            [
                'label' => Yii::t('vacancy', 'Show On Main Page'),
                'value'=> function($model) {
                    $status_list = Vacancy::getShowOnMainPageStatusList();
                    return $status_list[$model->show_on_main_page] ;
                },
            ],
            'main_page_priority',
            'title',
            'company_name',
            // 'category_job_id',
            [
                'label' => Yii::t('vacancy', 'Category Job ID'),
                'value'=> function($model) {
                    return $model->categoryJob->name;
                },
            ],
            // 'gender_list',
            [
                'label' => Yii::t('vacancy', 'Gender List'),
                'value'=> function($model) {
                    return implode(', ', $model->getGenders());
                },
            ],
            'age_min',
            'age_max',
            // 'worker_country_codes',
            [
                'label' => Yii::t('vacancy', 'Worker Country Codes'),
                'value'=> function($model) {
                    $worker_countries = ArrayHelper::getColumn($model->getWorkerCountries(), 'name');
                    foreach($worker_countries as &$country) { $country = Yii::t('country', $country); } // translate
                    return implode(', ', $worker_countries);
                },
            ],
            'free_places',
            // 'regular_places',
            [
                'label' => Yii::t('vacancy', 'Regular Places'),
                'value'=> function($model) {
                    $status_list = Vacancy::getRegularPlacesList();
                    return $status_list[$model->regular_places] ;
                },
            ],
            'date_start:date',
            'date_end:date',
            // 'date_free',
            [
                'label' => Yii::t('vacancy', 'Date Free'),
                'value'=> function($model) {
                    $status_list = Vacancy::getDateFreeList();
                    return $status_list[$model->date_free] ;
                },
            ],
            // 'country_name',
            [
                'label' => Yii::t('vacancy', 'Country Name'),
                'value'=> function($model) {
                    $countries = ArrayHelper::map($model->getCountryList(), 'char_code', 'name');
                    return Yii::t('vacancy', $countries[$model->country_name]);
                },
            ],
            // 'country_city_id',
            [
                'label' => Yii::t('vacancy', 'Country City ID'),
                'value'=> function($model) {
                    return Yii::t('city', $model->countryCity->city_name);
                },
            ],
            'salary_per_hour_min',
            'salary_per_hour_max',
            'salary_per_hour_min_src',
            'salary_per_hour_max_src',
            'currency_code',
            'hours_per_day_min',
            'hours_per_day_max',
            'days_per_week_min',
            'days_per_week_max',
            'prepaid_expense_min',
            'prepaid_expense_max',
            // 'type_of_working_shift',
            [
                'label' => Yii::t('vacancy', 'Type Of Working Shift'),
                'value'=> function($model) {
                    $item_list = $model->getWorkingShifts();
                    return implode(', ', $item_list);
                },
            ],
            // 'residence_provided',
            [
                'label' => Yii::t('vacancy', 'Residence Provided'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::RESIDENCE_PROVIDED_YES => 'Да',
                        Vacancy::RESIDENCE_PROVIDED_NO => 'Нет',
                    ];
                    return $status_list[$model->residence_provided] ;
                },
            ],
            'residence_amount',
            'residence_amount_currency_code',
            'residence_people_per_room',
            // 'documents_provided',
            [
                'label' => Yii::t('vacancy', 'Documents Provided'),
                'value'=> function($model) {
                    $item_list = $model->getDocumentsProvided();
                    // translate
                    foreach($item_list as $index => $val) {
                        $item_list[$index] = Yii::t('vacancy', $val);
                    }
                    return implode(', ', $item_list);
                },
            ],
            'documents_required',
            [
                'label' => Yii::t('vacancy', 'Documents Required'),
                'value'=> function($model) {
                    $item_list = $model->getDocumentsRequired();
                    // translate
                    foreach($item_list as $index => $val) {
                        $item_list[$index] = Yii::t('vacancy', $val);
                    }
                    return implode(', ', $item_list);
                },
            ],
            'full_import_description:ntext',
            'full_import_description_cleaned:ntext',
            'use_full_import_description_cleaned',
            'job_description:ntext',
            'job_description_bonus:ntext',
            'contact_name',
            'contact_phone',
            'contact_email_list:email',
            // 'main_image',
            [
                'format' => 'raw',
                'label' => Yii::t('vacancy', 'Main Image'),
                'value'=> function($model) {
                    return '<img src="' . $model->getImageWebPath() .'" style="max-height: 160px; max-width: 160px;">';
                },
            ],
            // 'agency_accept',
            [
                'label' => Yii::t('vacancy', 'Agency Accept'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::AGENCY_ACCEPT_YES => 'Да',
                        Vacancy::AGENCY_ACCEPT_NO => 'Нет',
                    ];
                    return $status_list[$model->agency_accept] ;
                },
            ],
            // 'agency_paid_document',
            [
                'label' => Yii::t('vacancy', 'Agency Paid Document'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::AGENCY_PAID_DOCUMENT_YES => 'Да',
                        Vacancy::AGENCY_PAID_DOCUMENT_NO => 'Нет',
                    ];
                    return $status_list[$model->agency_paid_document] ;
                },
            ],
            'agency_paid_document_price',
            'agency_paid_document_currency_code',
            // 'agency_free_document',
            [
                'label' => Yii::t('vacancy', 'Agency Free Document'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::AGENCY_FREE_DOCUMENT_YES => 'Да',
                        Vacancy::AGENCY_FREE_DOCUMENT_NO => 'Нет',
                    ];
                    return $status_list[$model->agency_free_document] ;
                },
            ],
            // 'agency_pay_commission',
            [
                'label' => Yii::t('vacancy', 'Agency Pay Commission'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::AGENCY_PAY_COMMISSION_YES => 'Да',
                        Vacancy::AGENCY_PAY_COMMISSION_NO => 'Нет',
                    ];
                    return $status_list[$model->agency_pay_commission] ;
                },
            ],
            'agency_pay_commission_amount',
            'agency_pay_commission_currency_code',
            // 'secure_deal',
            [
                'label' => Yii::t('vacancy', 'Secure Deal'),
                'value'=> function($model) {
                    $status_list = [
                        Vacancy::SECURE_DEAL_YES => 'Да',
                        Vacancy::SECURE_DEAL_NO => 'Нет',
                    ];
                    return $status_list[$model->secure_deal] ;
                },
            ],
            'meta_keywords:ntext',
            'meta_description:ntext',
            'creation_time:datetime',
            'upvote_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
