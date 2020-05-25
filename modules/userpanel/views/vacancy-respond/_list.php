<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Vacancy;

$categories = $model->vacancy->getCategories()->all();
$country = $model->vacancy->getVacancyCountry();
$vacancy_country = $model->vacancy->getVacancyCountry();

$genders = $model->vacancy->getGenders();
foreach($genders as $key => $gender) {
    $genders[$key] = mb_substr($gender, 0, 1);
}

$gender_names = implode(', ', $genders);

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vacancy__inner">
    <div class="vacancy__overlay"></div>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) ?>" class="vacancy__title">
        <?= Html::encode($model->vacancy->categoryJob->name) ?>
    </a>
    <a href="<?= Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) ?>" class="vacancy__img">
        <?php if(!empty($main_image)): ?>
            <img src="<?= $main_image ?>" alt="<?= Html::encode($model->vacancy->categoryJob->name) ?>">
        <?php else: ?>
            <img src="/img/vacancy/1.jpg" alt="<?= Html::encode($model->vacancy->categoryJob->name) ?>">
        <?php endif; ?>
    </a>
    <div class="vacancy__content">
        <a href="<?= Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) ?>" class="vacancy__title vacancy__title--big">
            <?= Html::encode($model->vacancy->categoryJob->name) ?>
        </a>
        <div class="vacancy__bottom">
            <ul class="vacancy__list">
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list1.png" alt="">
                    </div><?= Yii::t('vacancy', 'Employer') ?>: <a href="<?= Url::to(['company/view', 'id' => $model->vacancy->company_id ]) ?>"><?= $model->vacancy->company->company_name ?></a>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list2.png" alt="">
                    </div><?= Yii::t('vacancy', 'Сompany') ?>: <a href="#"><?= Html::encode($model->vacancy->company_name) ?></a>
                </li>
                <li>
                    <div class="vacancy__list-img">
                        <img src="/img/vacancy/list3.png" alt="">
                    </div><?= Yii::t('vacancy', 'City') ?>: <div class="vacancy__city"><?= Yii::t('country',  $vacancy_country['name']) ?></div>
                </li>
            </ul>
            <div class="vacancy__prices">
                <div class="vacancy__price">
                    <div><b><?= $model->vacancy->salary_per_hour_min ?> - <?= $model->vacancy->salary_per_hour_max ?></b> <?= $model->vacancy->currency_code ?>. / час</div> 
                    <div><?= $model->vacancy->prepaid_expense_min ?> - <?= $model->vacancy->prepaid_expense_max ?> <?= $model->vacancy->currency_code ?>. / мес.</div>
                </div>
            </div>
            <ul class="vacancy__age">
                <li>
                    <?= Yii::t('vacancy', 'Gender') ?>:&nbsp;<b><?= $gender_names ?></b>
                </li>
                <li>

                    <?= Yii::t('vacancy', 'Age') ?>: <b><?= Yii::t('main', 'from') ?> <?= $model->vacancy->age_min ?> <?= Yii::t('main', 'to') ?> <?= $model->vacancy->age_max ?></b>
                </li>
            </ul>
            <div class="vacancy__like-wrap">
                <a href="<?= Url::to(['/userpanel/vacancy/add-favorite', 'id' => $model->vacancy->id]) ?>" class="vacancy__like">
                    <?= Yii::t('vacancy', 'Add to favorite') ?>
                </a>
            </div>
        </div>
    </div>
</div>
