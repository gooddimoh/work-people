<?php

use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use app\models\Resume;
use app\models\ResumeLanguage;
use app\models\ResumeJob;
use app\models\ResumeSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;

// export data to excel file
$searchModel = new ResumeSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams); //! BUG of optimization, need use JOIN fo relations

// --
$country_list = Resume::getCountryList();
$level_list = ResumeLanguage::getLevelList();

$exporter = new Spreadsheet([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'id'
        ],
        [
            'attribute' => 'status',
            'value' => function($model, $key, $index, $column) {
                if($model->status == Resume::STATUS_SHOW) {
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
            'attribute' => 'first_name'
        ],
        [
            'attribute' => 'last_name'
        ],
        [
            'attribute' => 'middle_name'
        ],
        [
            'attribute' => 'email'
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
            'attribute' => 'birth_day'
        ],
        [
            'attribute' => 'country_name',
            'value' => function($model, $key, $index, $column) use ($country_list) {
                // get resume country name label
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
                // get resume city name
                $country_city_name = '';
                if(!empty($model->countryCity)) {
                    $country_city_name = Yii::t('city', $model->countryCity->city_name);
                }

                return $country_city_name;
            }
        ],
        [
            'attribute' => 'desired_salary'
        ],
        [
            'attribute' => 'desired_salary_per_hour'
        ],
        [
            'attribute' => 'desired_salary_currency_code'
        ],
        [
            'attribute' => 'desired_country_of_work',
            'value' => function($model, $key, $index, $column) {
                $desired_countries_of_work = ArrayHelper::getColumn($model->getDesiredCountryOfWork(), 'name');
                foreach($desired_countries_of_work as $key => $val) {
                    $desired_countries_of_work[$key] = Yii::t('country', $val);
                }

                $desired_country_of_work_names = implode(', ', $desired_countries_of_work);

                return $desired_country_of_work_names;
            }
        ],
        [
            'attribute' => 'phone',
        ],
        [
            'attribute' => 'description',
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
        [
            'label' => Yii::t('resume', 'Preferred job'),
            'value' => function($model, $key, $index, $column) {
                $category_jobs = $model->getCategoryJobs()->select('name')->asArray()->column();
                foreach($category_jobs as &$job) {
                    $job = Yii::t('category-job', $job);
                }
                $category_job_names = implode(', ', $category_jobs);

                return $category_job_names;
            }
        ],
        [
            'label' => Yii::t('resume', 'Categories in which you would like to work'),
            'value' => function($model, $key, $index, $column) {
                // categories
                $categories = $model->getCategories()->asArray()->all();
                $categories = ArrayHelper::getColumn($categories, 'name');
                foreach($categories as &$category) {
                    $category = Yii::t('category', $category);
                }
                $category_names = implode(', ', $categories);

                return $category_names;
            }
        ],
        [
            'label' => Yii::t('resume', 'Experience'),
            'value' => function($model, $key, $index, $column) {
                $result = '';
                foreach($model->resumeJobs as $index => $resumeJobModel) {
                    $result .= '#' . ($index + 1) . ', ';
                    $result .= $resumeJobModel->company_name .  ', ';
                    $result .= $resumeJobModel->categoryJob->name . ', ';
                    $result .= $resumeJobModel->getAttributeLabel('years') . ': ' . $resumeJobModel->years . ', ';
                    $result .= ($resumeJobModel->for_now == ResumeJob::STATUS_FOR_NOW_YES ? $resumeJobModel->getAttributeLabel('for_now') : '-') . ';';
                }

                return $result;
            }
        ],
        [
            'label' => Yii::t('resume', 'Education'),
            'value' => function($model, $key, $index, $column) {
                $result = '';
                foreach($model->resumeEducations as $index => $resumeEducationModel) {
                    $result .= '#' . ($index + 1) . ', ';
                    $result .= $resumeEducationModel->description . ';';
                }

                return $result;
            }
        ],
        [
            'label' => Yii::t('user', 'Language proficiency'),
            'value' => function($model, $key, $index, $column) use ($level_list) {
                $result = '';
                foreach($model->resumeLanguages as $index => $resumeLanguageModel) {
                    $result .= '#' . ($index + 1) . ', ';
                    $result .= Yii::t('lang', $resumeLanguageModel->getLanguageName()) .  ', ';
                    $result .= $level_list[$resumeLanguageModel->level] . ', ';
                }

                return $result;
            }
        ],
    ],
]);

$file_name = 'resume_'. rand(1000, 9999) .'_'. date('Y-m-d') .'.xls';
// $exporter->send($file_name); //! not working on server

$saveDirectory = Yii::getAlias('@runtime/export_xls');
if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
    var_dump('Не могу создать каталог на сервере: ' . $saveDirectory);
    die();
}

$exporter->save( $saveDirectory . DIRECTORY_SEPARATOR . $file_name);

Yii::$app->response->sendFile($saveDirectory . DIRECTORY_SEPARATOR . $file_name, $file_name);
