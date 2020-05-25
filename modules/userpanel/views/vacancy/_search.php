<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Vacancy;
use app\models\CategoryJob;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\VacancySearch */
/* @var $form yii\widgets\ActiveForm */

$job_list_grouped = CategoryJob::getUserMultiSelectList();
$job_list = [];
foreach($job_list_grouped as $group) {
	$jobs = [];
    foreach($group['jobs'] as $job_item) {
        $jobs[$job_item['id']] = Yii::t('category-job', $job_item['name']);
	}
	$job_list[Yii::t('category-job', $group['group_name'])] = $jobs;
}
?>

<div class="vacancy-review__top-sort">
    <div class="vacancy-review__sort">
        <div class="dropdown j-zvon-wrapper">
            <a href="#" class="j-zvon dropdown__btn">
                <?php if ($model->status == Vacancy::STATUS_SHOW): ?>
                    <?= Yii::t('vacancy', 'Posted only') ?>
                <?php else: ?>
                    <?= Yii::t('vacancy', 'All') ?>
                <?php endif; ?>
            </a>
            <div class="j-zvon-menu dropdown__menu">
                <a href="<?= Url::to(['index', 'VacancySearch[status]' => Vacancy::STATUS_SHOW]) ?>" class="dropdown__item">
                    <?= Yii::t('vacancy', 'Posted only') ?> (<span class="blue"><?= $status_show_count ?></span>)
                </a>
                <a href="<?= Url::to(['index']) ?>" class="dropdown__item">
                    <?= Yii::t('vacancy', 'All') ?> (<span class="blue"><?= $total_count_my ?></span>)
                </a>
            </div>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
        <?php /* $form->field($model, 'job_title')->label(false)->textInput([
            'template' => '{input}<input type="submit" value="">',
            // 'placeholder' => Yii::t('main', 'Search vacancy')
        ]) */ ?>
        <?php
            echo $form->field($model, 'category_job_list')->widget(Select2::classname(), [
                'data' => $job_list,
                'size' => Select2::LARGE,
                'options' => [
                    'multiple' => true,
                    'placeholder' => Yii::t('main', 'Enter your request ...'),
                ],
                'showToggleAll' => false,
                'pluginOptions' => [
                    // 'tags' => true,
                    // 'maximumSelectionLength' => 1
                ],
            ])->label(false);
        ?>
        <?php // Html::submitButton('', ['class' => false]) ?>
        <?php // Html::resetButton(Yii::t('vacancy', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    <?php ActiveForm::end(); ?>
</div>
