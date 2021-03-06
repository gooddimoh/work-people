<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
?>

<a href="<?= Url::to(['viewcompany', 'id' => $model->id]) ?>" class="home-reviews__inner">
    <div class="home-reviews__row">
        <div class="home-reviews__col1">
            <div class="home-reviews__img">
                <img src="/img/reviews-company/logo.jpg" alt="">
            </div>
            <div class="home-reviews__title">
                <?= $model->company_name ?>
            </div>
        </div>
        <div class="home-reviews__col2">
            <div class="home-reviews__count">
                <div><?= Yii::t('company', 'Reviews') ?></div>
                <div><b><?= $model->getCompanyReviews()->count() ?></b> <?= Yii::t('company', 'about company') ?></div>
                <div><b>0</b> <?= Yii::t('company', 'about salary') ?></div>
            </div>
            <div class="home-reviews__star">
                <div class="star"></div>
                <?= $model->getCompanyReviewsTotalRating() ?>
            </div>
        </div>
    </div>
</a>