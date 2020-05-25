<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Category;
use app\models\CategoryJob;
use app\models\Vacancy;
use app\components\ReferenceHelper;

class RefController extends \yii\web\Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $ref_list = [
            [
                'name' => Yii::t('ref', 'Localization list'),
                'url' => Url::to(['localization-list']),
            ],
            [
                'name' => Yii::t('ref', 'Category main page list'),
                'url' => Url::to(['category-main-page']),
            ], [
                'name' => Yii::t('ref', 'Category job'),
                'url' => Url::to(['category-job']),
            ], [
                'name' => Yii::t('ref', 'Country list'),
                'url' => Url::to(['сountry-list']),
            ], [
                'name' => Yii::t('ref', 'Currency list'),
                'url' => Url::to(['currency-list']),
            ], [
                'name' => Yii::t('ref', 'Language list'),
                'url' => Url::to(['language-list']),
            ], [
                'name' => Yii::t('ref', 'Country city list'),
                'url' => Url::to(['/api/country']),
                'description' => 'Search params example: /api/country?country_char_code=RU&page=2&limit=500',
            ], [
                'name' => Yii::t('ref', 'Employment list'),
                'url' => Url::to(['employment']),
            ], [
                'name' => Yii::t('ref', 'Type of working shift'),
                'url' => Url::to(['type-of-working-shift']),
            ], [
                'name' => Yii::t('ref', 'Documents required'),
                'url' => Url::to(['documents-required']),
            ], [
                'name' => Yii::t('ref', 'Vacancy'),
                'url' => Url::to(['/api/vacancy']),
                'description' => 'Search params example: /api/vacancy?filter=[{"property":"worker_country_codes","value":"UA;","operator":"like"},{"property":"gender_list","value":"20;","operator":"like"}]&page=1&limit=10 , supported operators: "in", "not in", "like", "=", "!=", ">", ">=", "<", "<="',
            ]
        ];

        return [
            'success' => true,
            'data' => $ref_list
        ];
    }

    public function actionLocalizationList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = [
            [
                'name' => Yii::t('lang', 'Ukrainian'),
                'prefix' => 'ua',
            ], [
                'name' => Yii::t('lang', 'Russian'),
                'prefix' => 'ru',
            ], [
                'name' => Yii::t('lang', 'English'),
                'prefix' => 'en',
            ],
        ];

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    public function actionCategoryMainPage()
    {
        $category_main_page = Category::getMainPageList();
        // translate
        foreach ($category_main_page as &$category) {
            $category['name'] = Yii::t('category', $category['name']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $category_main_page
        ];
    }

    public function actionCategoryJob()
    {
        $job_list_grouped = CategoryJob::getUserMultiSelectList();
        $job_list = [];
        foreach ($job_list_grouped as &$group) {
            $group['group_name'] = Yii::t('category-job', $group['group_name']);

            foreach ($group['jobs'] as &$job_item) {
                $job_item['name'] = Yii::t('category-job', $job_item['name']);
            }
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $job_list_grouped
        ];
    }

    public function actionCountryList()
    {
        $data = ReferenceHelper::getCountryList(true);
        foreach ($data as &$item) {
            $item['name'] = Yii::t('country', $item['name']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function actionCurrencyList()
    {
        $data = ReferenceHelper::getCurrencyList(true);
        foreach ($data as &$item) {
            $item['name_full'] = Yii::t('curr', $item['name_full']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }
    
    public function actionLanguageList()
    {
        $data = ReferenceHelper::getLanguageList(true);
        foreach ($data as &$item) {
            $item['name'] = Yii::t('lang', $item['name']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function actionEmployment()
    {
        $data = Vacancy::getEmploymentTypeList();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function actionTypeOfWorkingShift()
    {
        $data = Vacancy::getTypeOfWorkingShiftList();

        foreach($data as $key => $value) {
            $data[$key] = Yii::t('vacancy', $value);
        } 

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function actionDocumentsRequired()
    {
        $data = Vacancy::getDocumentsRequiredList();

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
