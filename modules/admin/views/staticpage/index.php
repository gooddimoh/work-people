<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\TagCategory;
use yii\grid\ActionColumn;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статические страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staticpage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить статическую страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'file_name',
            // 'body',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                                Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]),
                                ['/admin/staticpage/update', 'file_name' => $model->file_name],
                                [
                                    'title' => "Изменить статическую страницу"
                                ]
                            );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                                Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]),
                                ['/admin/staticpage/delete', 'file_name' => $model->file_name],
                                [
                                    'title' => "Удалить статическую страницу",
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить этот файл?',
                                        'method' => 'post',
                                    ]
                                ]
                            );
                    }
                ],
            ],
        ],
    ]); ?>
</div>
