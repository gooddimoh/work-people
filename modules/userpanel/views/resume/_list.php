<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Resume;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */

$category_jobs = $model->getCategoryJobs()->select('name')->asArray()->column();
foreach($category_jobs as &$job) {
	$job = Yii::t('category-job', $job);
}
$category_job_names = implode(', ', $category_jobs);

?>

<div class="vacancy-review__item resumes__item">
    <div class="vacancy-review__main">
        <a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="vacancy-review__title">
            <?= $category_job_names ?> - <?= Html::encode($model->getFullName()) ?>
        </a>
        <div class="resumes__status">
            <?= Yii::t('resume', 'Standard resume') ?>
        </div>
        <div class="resumes__date">
            <?= Yii::t('resume', 'Resume posted on the site') ?>: <?= Yii::$app->formatter->asDate($model->creation_time); ?>
        </div>
        <ul class="vacancy-review__nav resumes__nav">
            <li>
                <?= Yii::t('resume', 'You can update the date through') ?> 7 <?= Yii::t('resume', 'days') ?>
            </li>
            <li><a href="#"><?= Yii::t('resume', 'Find jobs') ?></a></li>
            <li>
                <div class="dropdown j-zvon-wrapper">
                    <a href="#" class="dropdown__btn j-zvon"><?= Yii::t('vacancy', 'More') ?></a>
                    <div class="dropdown__menu j-zvon-menu">
                        <a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="dropdown__item"><?= Yii::t('main', 'Edit') ?></a>
                        <!-- <a href="#" class="dropdown__item"><?= Yii::t('main', 'Copy') ?></a> -->
                        <?= Html::a(Yii::t('vacancy', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'dropdown__item',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?= Html::a(
                            ($model->status == Resume::STATUS_SHOW) ? Yii::t('vacancy', 'Hide') : Yii::t('vacancy', 'Show'),
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
        <div class="vacancy-review__number resumes__number">
            <div>
                <b><?= empty($model->resumeCounter) ? '0' : $model->resumeCounter->view_count ?></b>
                <?= Yii::t('resume', 'showing') ?>
            </div>
            <div class="view">
                <b><?= empty($model->resumeCounter) ? '0' : $model->resumeCounter->open_count ?></b>
                <?= Yii::t('resume', 'views') ?>
            </div>
        </div>
        <div class="dropdown j-zvon-wrapper">
            <a href="#" class="vacancy-review__share j-zvon dropdown__btn btn btn--trans-yellow">Поделиться</a>
            <div class="dropdown__menu j-zvon-menu">
                <a href="#" class="dropdown__item">
                    Скопировать ссылку
                </a>
                <a href="#" class="dropdown__item">
                    Поделиться на Facebook
                    <svg class="icon"> 
                        <use xlink:href="/img/icons-svg/facebook.svg#icon" x="0" y="0"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
