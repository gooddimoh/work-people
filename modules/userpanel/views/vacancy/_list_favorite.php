<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Vacancy;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */

// $categories = $model->getCategories()->asArray()->all();
// $category_names = implode(', ', ArrayHelper::getColumn($categories, 'name'));

$genders = $model->getGenders();
foreach($genders as $key => $gender) {
    $genders[$key] = mb_substr($gender, 0, 1);
}

$gender_names = implode(', ', $genders);
$vacancy_country = $model->getVacancyCountry();

$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}
?>

<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__title">
        <?= Html::encode($model->job_title) ?>
    </a>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__img">
        <img src="/img/vacancy/1.jpg" alt="">
    </a>
    <div class="vacancy__content">
        <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__title vacancy__title--big">
            <?= Html::encode($model->job_title) ?>
        </a>
        <div class="vacancy__bottom">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list1.png" alt="">
                    </div><?= Yii::t('vacancy', 'Employer') ?>: <a href="<?= Url::to(['company/view', 'id' => $model->company_id ]) ?>"><?= $model->company->company_name ?></a>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list2.png" alt="">
                    </div><?= Yii::t('vacancy', 'Сompany') ?>: <a href="#"><?= Html::encode($model->company_name) ?></a>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list3.png" alt="">
                    </div><?= Yii::t('vacancy', 'City') ?>: <div class="vacancy__city"><?= Yii::t('country', $vacancy_country['name']) ?>, <?= Html::encode($country_city_name) ?></div>
                </li>
            </ul>
            <div class="vacancy__prices">
                <div class="vacancy__price">
                    <div><b><?= $model->salary_per_hour_min ?> - <?= $model->salary_per_hour_max ?></b> <?= $model->currency_code ?>. / <?= Yii::t('vacancy', 'hour') ?></div> 
                    <div><?= $model->prepaid_expense_min ?> - <?= $model->prepaid_expense_max ?> <?= $model->currency_code ?>. / <?= Yii::t('vacancy', 'month') ?>.</div>
                </div>
            </div>
            <ul class="vacancy__age">
                <li>
                    <?= Yii::t('vacancy', 'Gender') ?>:&nbsp;<b><?= $gender_names ?></b>
                </li>
                <li>
                    <?= Yii::t('vacancy', 'Age') ?>:&nbsp;<b><?= Yii::t('main', 'from') ?> <?= $model->age_min ?> <?= Yii::t('main', 'to') ?> <?= $model->age_max ?></b>
                </li>
            </ul>
        </div>
    </div>
</div>
