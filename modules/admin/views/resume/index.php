<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Resume;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('resume', 'Resumes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resume-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('resume', 'Create Resume'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('vacancy', 'Export current search to Excel (*.xls)'), ['export', 'VacancySearch' => empty(Yii::$app->request->queryParams['VacancySearch']) ? null : Yii::$app->request->queryParams['VacancySearch']], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('vacancy', 'Import Excel (*.xls)'), ['import'], ['class' => 'btn btn-info']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            // 'user_id',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return $model->user->login . ' (' . $model->user->email . ')';
                }
            ],
            // 'status',
            [
                'attribute' => 'status',
                'filter'=> Resume::getStatusList(),
                'format' => 'raw',
                'value'=> function($model) {
                    $status_list = Resume::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'first_name',
            'last_name',
            // 'middle_name',
            //'birth_day',
            //'location',
            //'type_of_employment',
            //'desired_salary',
            //'desired_country_of_work',
            //'photo_path',
            //'phone',
            //'resume_country_codes',
            //'custom_country',
            //'skills:ntext',
            //'had_foreign_job',
            //'creation_time:datetime',
            //'update_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
