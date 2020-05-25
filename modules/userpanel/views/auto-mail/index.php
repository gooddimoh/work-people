<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AutoMailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('automail', 'Auto Mails');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="vacancy-review resumes">
		<div class="title-sec">
			Автоматическая рассылка подходящих вакансий
		</div>
		<div class="success-msg">
			Рассылка успешно отредактирована
		</div>
		<div class="info-company__title">
			По поисковому запросу
		</div>
		<hr style="margin-bottom: 0;">
        <?php
            echo ListView::widget([
                'layout' => '
                    <!-- {sorter} -->
                    {items}
                    <div class="pagination">{pager}</div>
                ',
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
                'itemOptions' => [
                    'class' => ''
                ],
                'sorter' => [
                    'options' => [
                        'class' => 'list-view-sorter'
                    ],
                    // 'linkOptions' => [
                    //     'class' => ''
                    // ]
                ],
                'pager' => [
                    'options' => [
                        'class' => ''
                    ],
                ]
            ]);
        ?>
	</div>

</div>
