<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\CountryCity;
use yii\helpers\ArrayHelper;
use app\components\ReferenceHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CountryCitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Города';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-city-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить Город', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'priority',
            // 'country_char_code',
            [
                'attribute' => 'country_char_code',
                'filter'=> ArrayHelper::map(ReferenceHelper::getCountryList(true), 'char_code', 'name'),
                'value'=> function($model) {
                    $item_list = ArrayHelper::map(ReferenceHelper::getCountryList(true), 'char_code', 'name');
                    return $item_list[$model->country_char_code] ;
                },
            ],
            'city_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
