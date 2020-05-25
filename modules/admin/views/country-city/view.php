<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CountryCity;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CountryCity */

$this->title = '#' . $model->id . ' ' . $model->city_name;
$this->params['breadcrumbs'][] = ['label' => 'Города', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="country-city-view">

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
            'id',
            'priority',
            // 'country_char_code',
            [
                'label' => 'Страна',
                'value'=> function($model) {
                    $item_list = ArrayHelper::map(CountryCity::getCountryList(), 'char_code', 'name');
                    return $item_list[$model->country_char_code] ;
                },
            ],
            'city_name',
        ],
    ]) ?>

</div>
