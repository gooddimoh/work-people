<?php

use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use app\models\Vacancy;
use app\models\VacancySearch;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;

// export data to excel file
$searchModel = new VacancySearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams); //! BUG of optimization, need use JOIN fo relations

// --
$country_list = Vacancy::getCountryList();

$exporter = new Spreadsheet([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id'
        ],
        [
            'attribute' => 'status',
            'value' => function ($model, $key, $index, $column) {
                if($model->status == Vacancy::STATUS_SHOW) {
                    return Yii::t('main', 'Show');
                }

                return Yii::t('main', 'Hide');
            }
        ],
        [
            'attribute' => 'title',
            // 'contentOptions' => [
            //     'alignment' => [
            //         'horizontal' => 'center',
            //         'vertical' => 'center',
            //     ],
            // ],
        ],
        [
            'attribute' => 'company_name',
            'value' => function ($model, $key, $index, $column) {
                return $model->company_name . ' it work!';
            }
        ],
        [
            'label' => Yii::t('category', 'Name'),
            'value' => function($model, $key, $index, $column) {
                return $model->categoryJob->name;
            }
        ],
        [
            'label' => Yii::t('vacancy', 'Job posting categories'),
            'value' => function($model, $key, $index, $column) {
                $categories = $model->getCategories()->asArray()->all();
                $category_list = [];
                foreach($categories as $category) {
                    $category_list[] = Yii::t('category', $category['name']);
                }

                $category_names = implode(', ', $category_list);
                return $category_names;
            }
        ],
        [
            'attribute' => 'country_name',
            'value' => function($model, $key, $index, $column) use ($country_list) {
                $country_name = $model->country_name;
                foreach($country_list as $country) {
                    if($country['char_code'] == $country_name) {
                        $country_name = Yii::t('country', $country['name']);
                        break;
                    }
                }

                return $country_name;
            }
        ],
        [
            'attribute' => 'country_city_id',
            'value' => function($model, $key, $index, $column) {
                $country_city_name = '';
                if(!empty($model->countryCity)) {
                    $country_city_name = Yii::t('city', $model->countryCity->city_name);
                }

                return $country_city_name;
            }
        ],
        [
            'label' => Yii::t('vacancy', 'For citizens of next countries'),
            'value' => function($model, $key, $index, $column) {
                $worker_countries = $model->getWorkerCountries();
                foreach($worker_countries as &$country) {
                    $country['name'] = Yii::t('country', $country['name']); // translate
                }
                $worker_country_names = implode(', ', ArrayHelper::getColumn($worker_countries, 'name'));

                return $worker_country_names;
            }
        ],
        [
            'attribute' => 'gender_list',
            'value' => function($model, $key, $index, $column) {
                $genders = $model->getGenders();
                $gender_names = implode(', ', $genders);

                return $gender_names;
            }
        ],
        [
            'attribute' => 'salary_per_hour_min'
        ],
        [
            'attribute' => 'salary_per_hour_max'
        ],
        [
            'attribute' => 'currency_code'
        ],
        [
            'label' => Yii::t('vacancy', 'Salary per month from-to'),
            'attribute' => 'prepaid_expense_min'
        ],
        [
            'attribute' => 'prepaid_expense_max'
        ],
        [
            'attribute' => 'hours_per_day_min'
        ],
        [
            'attribute' => 'hours_per_day_max'
        ],
        [
            'attribute' => 'days_per_week_min'
        ],
        [
            'attribute' => 'days_per_week_max'
        ],
        [
            'attribute' => 'free_places',
        ],
        [
            'attribute' => 'date_start',
        ],
        [
            'attribute' => 'date_end',
        ],
        [
            'attribute' => 'date_free',
            'value' => function($model, $key, $index, $column) {
                if($model->date_free == Vacancy::DATE_FREE_YES) {
                    return Yii::t('main', 'Yes');
                }
                return Yii::t('main', 'No');
            }
        ],
        [
            'label' => Yii::t('vacancy', 'Shifts'),
            'value' => function($model, $key, $index, $column) {
                $working_shifts = $model->getWorkingShifts();
                $working_shift_names = implode(', ', $working_shifts);

                return $working_shift_names;
            }
        ],
        [
            'attribute' => 'residence_provided',
            'value' => function($model, $key, $index, $column) {
                if($model->residence_provided == Vacancy::RESIDENCE_PROVIDED_YES) {
                    return Yii::t('main', 'Yes');
                }

                return Yii::t('main', 'No');
            }
        ],
        [
            'attribute' => 'residence_amount'
        ],
        [
            'attribute' => 'residence_amount_currency_code'
        ],
        [
            'attribute' => 'residence_people_per_room'
        ],
        [
            'attribute' => 'documents_required',
            'value' => function($model, $key, $index, $column) {
                $documents_required = $model->getDocumentsRequired();
                // translate
                foreach($documents_required as $index => $val) {
                    $documents_required[$index] = Yii::t('vacancy', $val);
                }
                $documents_required_names = implode(', ', $documents_required);

                return $documents_required_names;
            }
        ],
        [
            'attribute' => 'documents_provided',
            'value' => function($model, $key, $index, $column) {
                $documents_provided = $model->getDocumentsProvided();
                // translate
                foreach($documents_provided as $index => $val) {
                    $documents_provided[$index] = Yii::t('vacancy', $val);
                }
                $documents_provided_names = implode(', ', $documents_provided);

                return $documents_provided_names;
            }
        ],
        [
            'attribute' => 'job_description',
        ],
        [
            'attribute' => 'contact_name',
        ],
        [
            'attribute' => 'contact_phone',
        ],
        [
            'attribute' => 'job_description_bonus',
        ],
        [
            'attribute' => 'creation_time',
            'value' => function($model, $key, $index, $column) {
                return date('Y-m-d', $model->creation_time);
            }
        ],
        [
            'attribute' => 'upvote_time',
            'value' => function($model, $key, $index, $column) {
                return date('Y-m-d', $model->upvote_time);
            }
        ],
        [
            'attribute' => 'update_time',
            'value' => function($model, $key, $index, $column) {
                return date('Y-m-d', $model->update_time);
            }
        ],
    ],
]);

$file_name = 'vacancy_'. rand(1000, 9999) .'_'. date('Y-m-d') .'.xls';
// $exporter->send($file_name); //! not working on server

$saveDirectory = Yii::getAlias('@runtime/export_xls');
if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
    var_dump('Не могу создать каталог на сервере: ' . $saveDirectory);
    die();
}

$exporter->save( $saveDirectory . DIRECTORY_SEPARATOR . $file_name);

Yii::$app->response->sendFile($saveDirectory .DIRECTORY_SEPARATOR . $file_name, $file_name);
