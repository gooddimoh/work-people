<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', 'Companies');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-grey-searchworkers">
    <div class="container">
    	<div class="search-workers">
    		<h1 class="title-sec" style="margin: 10px 0 30px;">
                <?= Html::encode($this->title) ?>
	    	</h1>
    	</div>
        <form action="" class="search search--workers">
            <div class="search__row">
                <div class="search__col1 search__col">
                    <input type="text" placeholder="<?= Yii::t('company', 'Enter your request...') ?>">
                </div>
                <div class="search__submit search__col">
                    <input type="submit" value="<?= Yii::t('company', 'Find') ?>">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="info-company">
	<div class="container">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php
            echo ListView::widget([
                'layout' => '
                    <!-- {sorter} -->
                    <div class="home-reviews__wrapper">
                        {items}
                    </div>
                    {pager}
                ',
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
                'itemOptions' => [
                    'class' => 'home-reviews__item'
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
                    // 'firstPageLabel' => '<< ',
                    'prevPageLabel'  => '« ' . Yii::t('main', 'previous'),
                    'nextPageLabel'  => Yii::t('main', 'next') . ' »',
                    // 'lastPageLabel'  => ' >>',
                    'options' => [
                        'class' => 'pagination',
                        // 'class' => 'pagination pagination--mobile'
                    ],
                    'linkOptions' => [
                        'class' => 'pagination__number'
                    ]
                ]
            ]);
        ?>


    </div>
</div>

