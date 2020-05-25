<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$categories = $model->getCategories()->asArray()->all();
$category_names = implode(', ', ArrayHelper::getColumn($categories, 'name'));

$education = $model->profile->getProfileEducations()->one();
// get better education
$education_label = '-';
if(!empty($education)) {
    $education_label = $education->description;
}

$resume_countries = $model->getResumeCountries();
$resume_country_names = implode(', ', ArrayHelper::getColumn($resume_countries, 'name'));

?>
<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/resume/' . $model->id]) ?>" class="vacancy__title">
        <?= $category_names ?>
    </a>
    <div class="vacancy__date">
        <?= Yii::t('resume', 'Resume date') ?> <b><?= Yii::$app->formatter->asDate($model->creation_time); ?></b>
    </div>
    <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="vacancy__img">
        <img src="<?= $model->profile->getImageWebPath() ?>" alt="">
    </a>
    <div class="vacancy__content">
        <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="vacancy__title vacancy__title--big">
            <?= Html::encode($model->profile->position) ?>
        </a>
        <div class="vacancy__bottom">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                        <img src="img/vacancy/person.png" alt="">
                    </div>
                    <b><?= Html::encode($model->profile->getFullName()) ?></b> 
                    <div class="vacancy__list-ml2">
                        (<?= $model->profile->getAge() ?> <?= Yii::t('resume', 'years') ?>)
                    </div>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="img/vacancy/list3.png" alt="">
                    </div><?= Yii::t('resume', 'City') ?>: <div class="vacancy__city"><?= Html::encode($model->profile->location) ?></div>
                </li>
            </ul>
            <ul class="vacancy__age">
                <li>
                    <?= Yii::t('resume', 'Education') ?>: 
                    <div class="vacancy__list-ml2">
                        <b><?= Html::encode($education_label) ?></b>
                    </div>	
                </li>
                <li>
                    <?= Yii::t('resume', 'Employment') ?>:
                    <div class="vacancy__list-ml2">
                        <b><?= Html::encode($model->profile->type_of_employment) ?></b>
                    </div>
                </li>
                <li>
                    <?= Yii::t('resume', 'Salary') ?>: <div class="vacancy__city"><?= Html::encode($model->profile->desired_salary) ?></div>
                </li>
                <li>
                    <?= Yii::t('resume', 'Country of work') ?>: <div class="vacancy__city"><?= $resume_country_names ?> <?= empty($model->custom_country) ? '' : ', ' . Html::encode($model->custom_country) ?></div>
                </li>
            </ul>
            <div class="vacancy__candidate-right">
                <div class="vacancy__date">
                    <?= Yii::t('resume', 'Resume date') ?> <b><?= Yii::$app->formatter->asDate($model->creation_time); ?></b>
                </div>
                <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="btn btn--transparent">
                    <?= Yii::t('resume', 'More') ?>
                </a>
                <a href="<?= Url::to(['/userpanel/resume/add-favorite', 'id' => $model->id]) ?>" class="vacancy__like" target="_blank">
                    <?= Yii::t('resume', 'Add to favorite') ?>
                </a>
            </div>
        </div>
    </div>
</div>
