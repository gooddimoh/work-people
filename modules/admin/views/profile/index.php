<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('main', 'Profiles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('main', 'Create Profile'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return $model->user->login . ' (' . $model->user->email . ')';
                }
            ],
            'status',
            'first_name',
            'last_name',
            //'middle_name',
            'email:email',
            //'gender_list',
            //'birth_day',
            'country_name',
            //'country_city_id',
            //'photo_path',
            //'phone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
