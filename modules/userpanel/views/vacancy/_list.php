<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Vacancy;

$categories = $model->getCategories()->all();
$country = $model->getVacancyCountry();

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */

$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}
?>

<div class="vacancy-review__item">
    <div class="vacancy-review__main">
        <a href="<?= Url::to(['/userpanel/vacancy/view', 'id' => $model->id]) ?>" class="vacancy-review__title">
            <?= Html::encode($model->title) ?>&nbsp;-&nbsp;
            <?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>
        </a>
        <div class="vacancy-review__city">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                    </div><?= Yii::t('vacancy', 'Category')?>: 
                    <?php foreach($categories as $category): ?>
                        <div class="vacancy__city"><?= Yii::t('category', $category->name) ?></div>
                    <?php endforeach; ?>
                </li>
            </ul>
        </div>
        <div class="vacancy-review__city">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                    <img src="/img/vacancy/list3.png" alt="">
                    </div><?= $model->getAttributeLabel('country_name') ?>: <div class="vacancy__city"><?= Yii::t('country', $country['name']) ?></div>
                </li>
            </ul>
        </div>
        <div class="vacancy-review__city">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                    <img src="/img/vacancy/list3.png" alt="">
                    </div><?= $model->getAttributeLabel('city_name') ?>: <div class="vacancy__city"><?= Html::encode($country_city_name) ?></div>
                </li>
            </ul>
        </div>
        <ul class="vacancy-review__nav">
            <li>
                <!-- <a href="#">Разместить</a> -->
                <?= Html::a(
                    ($model->status == Vacancy::STATUS_SHOW) ? Yii::t('vacancy', 'Hide') : Yii::t('vacancy', 'Show'),
                    ['changestatus', 'id' => $model->id], [
                    'class' => '',
                    'data' => [
                        'method' => 'post',
                    ],
                ]) ?>
            </li>
            <li><a href="<?= Url::to(['/resume/index']) ?>"><?= Yii::t('vacancy', 'Find resume') ?></a></li>
            <li>
                <div class="dropdown j-zvon-wrapper">
                    <a href="#" class="dropdown__btn j-zvon"><?= Yii::t('vacancy', 'More') ?></a>
                    <div class="dropdown__menu j-zvon-menu">
                        <!-- <a href="#" class="dropdown__item">Скопировать</a> -->
                        <a href="<?= Url::to(['/userpanel/vacancy/view', 'id' => $model->id]) ?>" class="dropdown__item"><?= Yii::t('main', 'Edit') ?></a>
                        <?= Html::a(Yii::t('vacancy', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'dropdown__item',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a(
                            ($model->status == Vacancy::STATUS_SHOW) ? Yii::t('vacancy', 'Hide') : Yii::t('vacancy', 'Show'),
                            ['changestatus', 'id' => $model->id], [
                            'class' => 'dropdown__item',
                            'data' => [
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="vacancy-review__count">
        <div class="vacancy-review__number">
            <?= empty($model->vacancyCounter) ? '0' : $model->vacancyCounter->view_count ?>
            <span><?= empty($model->vacancyCounter) ? '0' : $model->vacancyCounter->open_count ?>+</span>
        </div>
        <div class="dropdown j-zvon-wrapper">
            <a href="#" class="vacancy-review__share j-zvon dropdown__btn btn btn--trans-yellow"><?= Yii::t('vacancy', 'Share') ?></a>
            <div class="dropdown__menu j-zvon-menu">
                <a href="<?= Url::to(['/vacancy/view', 'id' => $model->id]) ?>" class="dropdown__item">
                    <?= Yii::t('vacancy', 'Copy link') ?>
                </a>
                <a href="#" class="dropdown__item">
                    <?= Yii::t('vacancy', 'Share on') ?> Facebook
                    <svg class="icon"> 
                        <use xlink:href="/img/icons-svg/facebook.svg#icon" x="0" y="0"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
