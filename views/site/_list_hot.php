<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$vacancy_country = $model->getVacancyCountry();
$main_image = $model->getImageWebPath();
$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}
?>

<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__title">
        <?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>
    </a>
    <ul class="vacancy__place">
        <li>
            <!-- link country -->
            <a href="" class="vacancy__country">
                <?= Yii::t('country', $vacancy_country['name']) ?>
            </a>
        </li>
        <!-- link city_name -->
        <li><a href=""><?= Html::encode($country_city_name) ?></a></li>
    </ul>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="vacancy__img">
        <?php if(!empty($main_image)): ?>
            <img src="<?= $main_image ?>" alt="<?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>">
        <?php else: ?>
            <img src="/img/vacancy/1.jpg" alt="<?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>">
        <?php endif; ?>
    </a>
    <div class="vacancy__price">
        от <b><?= $model->salary_per_hour_min ?> </b> до <b><?= $model->salary_per_hour_max ?></b> <?= $model->currency_code ?>.
    </div>
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