<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */
?>

<div class="review__img">
    <img src="/img/reviews-company/logo.jpg" alt="">
</div>
<div class="review__content">
    <div class="review__date">
        <?= $model->date_end ?>
    </div>
    <div class="review__desc">
        <?= $model->general_impression ?>
    </div>
    <div class="review__nav">
        <div class="review__star">
            <div class="star"></div>
            <span><?= $model->rating_total ?></span>
        </div>
        <ul class="review__list">
            <li>
                <?php if($model->rating_salary < 4): ?>
                    <div class="minus"></div>
                <?php else: ?>
                    <div class="plus"></div>
                <?php endif; ?>
                <?= Yii::t('company', 'Salary') ?>
            </li>
            <li>
                <?php if($model->rating_bosses < 4): ?>
                    <div class="minus"></div>
                <?php else: ?>
                    <div class="plus"></div>
                <?php endif; ?>
                <?= Yii::t('company', 'Bosses') ?>
            </li>
            <li>
                <?php if($model->rating_opportunities < 4): ?>
                    <div class="minus"></div>
                <?php else: ?>
                    <div class="plus"></div>
                <?php endif; ?>
                <?= Yii::t('company', 'Career') ?>
            </li>
        </ul>
    </div>
    <ul class="review__preim">
        <li>
            +<?= Yii::t('company', 'Team') ?>
        </li>
        <li>
            +Оплата ужинов при задержке на работе
        </li>
    </ul>
</div>

