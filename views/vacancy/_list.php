<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\components\CurrencyConverterHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */
/* @var $list_currency string */

// $categories = $model->getCategories()->asArray()->all();
// $category_names = implode(', ', ArrayHelper::getColumn($categories, 'name'));

$genders = $model->getGenders();
foreach($genders as $key => $gender) {
    $genders[$key] = mb_substr($gender, 0, 1);
}

$gender_names = implode(', ', $genders);
$vacancy_country = $model->getVacancyCountry();
$main_image = $model->getImageWebPath();

// convert currency
$salary_per_hour_min = CurrencyConverterHelper::currencyToCurrency(
    $model->salary_per_hour_min,
    $model->currency_code,
    $list_currency
);
$salary_per_hour_max = CurrencyConverterHelper::currencyToCurrency(
    $model->salary_per_hour_min,
    $model->currency_code,
    $list_currency
);
$prepaid_expense_min = CurrencyConverterHelper::currencyToCurrency(
    $model->prepaid_expense_min,
    $model->currency_code,
    $list_currency
);
$prepaid_expense_max = CurrencyConverterHelper::currencyToCurrency(
    $model->prepaid_expense_max,
    $model->currency_code,
    $list_currency
);

$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = ', ' .  Yii::t('city', $model->countryCity->city_name);
}
?>

<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__title">
        <?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>
    </a>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__img">
        <?php if(!empty($main_image)): ?>
            <img src="<?= $main_image ?>" alt="<?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>">
        <?php else: ?>
            <img src="/img/vacancy/1.jpg" alt="<?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>">
        <?php endif; ?>
    </a>
    <div class="vacancy__content">
        <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__title vacancy__title--big">
            <?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>
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
                    </div><?= Yii::t('vacancy', 'City') ?>: <div class="vacancy__city"><?= Yii::t('country', $vacancy_country['name']) ?><?= Html::encode($country_city_name) ?></div>
                </li>
            </ul>
            <div class="vacancy__prices">
                <div class="vacancy__price">
                    <div><b><?= $salary_per_hour_min ?> - <?= $salary_per_hour_max ?></b> <?= $list_currency ?>. / час</div> 
                    <div><?= $prepaid_expense_min ?> - <?= $prepaid_expense_max ?> <?= $list_currency ?>. / мес.</div>
                </div>
            </div>
            <ul class="vacancy__age">
                <li>
                    <?= Yii::t('vacancy', 'Gender') ?>:&nbsp;<b><?= $gender_names ?></b>
                </li>
                <li>
                    <?= Yii::t('vacancy', 'Age') ?>:&nbsp;<b><?= Yii::t('main', 'from') ?> <?= $model->age_min ?> <?= Yii::t('vacancy', 'to') ?> <?= $model->age_max ?></b>
                </li>
            </ul>
            <div class="vacancy__like-wrap">
                <a  id="vacancy_remove_like_<?= $model->id ?>"
                    class="pointer vacancy__like in_favorite jqh-vacancy__like"
                    data-id="<?= $model->id ?>"
                    data-rtype="remove-like"
                    <?= in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('vacancy', 'Remove from favorite') ?>
                </a>

                <a  id="vacancy_add_like_<?= $model->id ?>"
                    class="pointer vacancy__like jqh-vacancy__like"
                    data-id="<?= $model->id ?>"
                    data-rtype="add-like"
                    <?= !in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('vacancy', 'Add to favorite') ?>
                </a>
                <div class="vacancy__like like-loading" id="vacancy_like_loading_<?= $model->id ?>" style="display:none;"><?= Yii::t('vacancy', 'Loading...') ?></div>
            </div>
        </div>
    </div>
</div>
