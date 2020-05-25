<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Resume;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$this->title = Html::encode($model->getFullName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="resume-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('main', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('main', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'user_id',
            [
                'label' => Yii::t('user', 'User'),
                'value' => $model->user->login . ' (' . $model->user->email . ')',
            ],
            [
                'label' => Yii::t('resume', 'Categories'),
                'value'=> function($model) {
                    $item_list = ArrayHelper::getColumn($model->categories, 'name');
                    return implode(', ', $item_list);
                },
            ],
            // 'status',
            [
                'label' => Yii::t('vacancy', 'Status'),
                'value'=> function($model) {
                    $status_list = Resume::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'title',
            'use_title',
            'job_experience:ntext',
            'use_job_experience',
            'language',
            'use_language',
            'relocation_possible',
            'full_import_description:ntext',
            'full_import_description_cleaned:ntext',
            'use_full_import_description_cleaned',
            'source_url:url',
            'first_name',
            'last_name',
            'middle_name',
            'email:email',
            'gender_list',
            'birth_day:date',
            'country_name',
            'country_city_id',
            'desired_salary',
            'desired_salary_per_hour',
            'desired_salary_currency_code',
            'desired_country_of_work',
            'photo_path',
            'phone',
            'custom_country',
            'description:ntext',
            'creation_time:datetime',
            'upvote_time:datetime',
            'update_time:datetime',
        ],
    ]) ?>

</div>
