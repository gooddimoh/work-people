<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$category_jobs = $model->getCategoryJobs()->select('name')->asArray()->column();
foreach($category_jobs as &$job) {
	$job = Yii::t('category-job', $job);
}
$category_job_names = implode(', ', $category_jobs);

$education = $model->getResumeEducations()->one();
// get better education
$education_label = '-';
if(!empty($education)) {
    $education_label = $education->description;
}

/* $resume_countries = ArrayHelper::getColumn($model->getResumeCountries(), 'name');
foreach($resume_countries as &$country) {
    $country = Yii::t('country', $country);
}
$resume_country_names = implode(', ', $resume_countries);
*/

$desired_countries_of_work = ArrayHelper::getColumn($model->getDesiredCountryOfWork(), 'name');
foreach($desired_countries_of_work as $key => $val) {
	$desired_countries_of_work[$key] = Yii::t('country', $val);
}

$desired_country_of_work_names = implode(', ', $desired_countries_of_work);

$category_jobs = $model->getCategoryJobs()->asArray()->all();
$category_jobs = ArrayHelper::getColumn($category_jobs, 'name');
foreach($category_jobs as &$category_last_job) {
    $category_last_job = Yii::t('category-job', $category_last_job);
}
$category_job_names = implode(', ', $category_jobs);
?>
<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/resume/view', 'id' => $model->id]) ?>" class="vacancy__title">
        <?= $category_job_names ?>
    </a>
    <div class="vacancy__date">
        <?= Yii::t('resume', 'Resume date') ?> <b><?= Yii::$app->formatter->asDate($model->creation_time); ?></b>
    </div>
    <a href="<?= Url::to(['/resume/view', 'id' => $model->id]) ?>" class="vacancy__img">
        <img src="<?= $model->getImageWebPath() ? $model->getImageWebPath() : '/img/icons-svg/user.svg'  ?>" alt="">
    </a>
    <div class="vacancy__content">
        <a href="<?= Url::to(['/resume/view', 'id' => $model->id]) ?>" class="vacancy__title vacancy__title--big">
            <?= $category_job_names ?>
        </a>
        <div class="vacancy__bottom">
            <ul class="vacancy__list resume_list">
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/person.png" alt="">
                    </div>
                        <a href="<?= Url::to(['/resume/view', 'id' => $model->id]) ?>" class="resume__list_full_name">
                            <b><?= Html::encode($model->getFullName()) ?></b>
                        </a>
                    <div class="vacancy__list-ml2">
                        (<?= $model->getAge() ?> <?= Yii::t('user', 'Years') ?>)
                    </div>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list1.png" alt="">
                    </div><?= Yii::t('resume', 'Desired Country') ?>: <div class="vacancy__city"><?= $desired_country_of_work_names ?></div>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list2.png" alt="">
                    </div><?= Yii::t('resume', 'Work experience') ?>: <div class="vacancy__city"><?= $category_job_names ?></div>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list3.png" alt="">
                    </div><?= Yii::t('resume', 'Сitizenship') ?>: <div class="vacancy__city"><?php /* echo $resume_country_names */ ?></div>
                </li>
                <li>
                    <a href="<?= Url::to(['/userpanel/resume-respond/create', 'id' => $model->id]) ?>" class="btn single-top__nav-rezume" target="_blank">
                        <?= Yii::t('resume', 'Offer a job') ?>
                    </a>
                <li>
                <?php /*
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list3.png" alt="">
                    </div><?= Yii::t('resume', 'City') ?>: <div class="vacancy__city"><?= Html::encode($model->location) ?></div>
                </li>
                */ ?>
            </ul>
            <?php /*
            <ul class="vacancy__age">
                <li>
                    <?= Yii::t('resume', 'Education') ?>: 
                    <div class="vacancy__list-ml2">
                        <b><?= $education_label ?></b>
                    </div>	
                </li>
                <li>
                    <?= Yii::t('resume', 'Employment') ?>:
                    <div class="vacancy__list-ml2">
                        <b><?= Html::encode($model->type_of_employment) ?></b>
                    </div>
                </li>
                <li>
                    <?= Yii::t('resume', 'Salary') ?>: <div class="vacancy__city"><?= Html::encode($model->desired_salary) ?></div>
                </li>
            </ul>
            */ ?>
            <div class="vacancy__candidate-right">
                <div class="vacancy__date">
                    <?= Yii::t('resume', 'Resume date') ?> <b><?= Yii::$app->formatter->asDate($model->creation_time); ?></b>
                </div>
                <a href="<?= Url::to(['/resume/view', 'id' => $model->id]) ?>" class="btn btn--transparent">
                    <?= Yii::t('resume', 'More') ?>
                </a>

                <a  class="pointer vacancy__like in_favorite jqh-resume__like resume_remove_like_<?= $model->id ?>"
                    data-id="<?= $model->id ?>"
                    data-rtype="remove-like"
                    <?= in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('resume', 'Remove from favorite') ?>
                </a>
                
                <a  class="pointer vacancy__like jqh-resume__like resume_add_like_<?= $model->id ?>"
                    data-id="<?= $model->id ?>"
                    data-rtype="add-like"
                    <?= !in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('resume', 'Add to favorite') ?>
                </a>
            </div>
            <div class="vacancy__like-wrap">
                <a  class="pointer vacancy__like in_favorite jqh-resume__like resume_remove_like_<?= $model->id ?>"
                    data-id="<?= $model->id ?>"
                    data-rtype="remove-like"
                    <?= in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('resume', 'Remove from favorite') ?>
                </a>

                <a  class="pointer vacancy__like jqh-resume__like resume_add_like_<?= $model->id ?>"
                    data-id="<?= $model->id ?>"
                    data-rtype="add-like"
                    <?= !in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
                >
                    <?= Yii::t('resume', 'Add to favorite') ?>
                </a>
                <div class="resume__like like-loading" id="resume_like_loading_<?= $model->id ?>" style="display:none;"><?= Yii::t('resume', 'Loading...') ?></div>
            </div>
        </div>
    </div>
</div>
