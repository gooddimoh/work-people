<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VacancySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('vacancy', 'Favorite');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
	<div class="title-sec" style="margin-bottom: 10px;">
		<?= $this->title ?>
	</div>
	<div class="row cat-nav-main" style="margin-bottom: 0;">
		<div class="cat-nav-main__col">
		</div>
		<div class="cat-nav-main__col">
			<ul class="view-list j-view-list col">
				<li><?=Yii::t('main', 'List view') ?>:</li>
				<li>
					<a href="list"><?= Yii::t('main', 'list') ?></a>
				</li>
				<li>
					<a class="active" href="tile"><?= Yii::t('main', 'tile') ?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr style="margin-top: 0;">
	<div class="cat-main">
		<div>
			<?php Pjax::begin(); ?>
			<?php
				echo ListView::widget([
					'layout' => '
						<!-- {sorter} -->
						<div class="vacancy vacancy--4">
							{items}
						</div>
						{pager}
					',
					'dataProvider' => $dataProvider,
					'itemView' => '_list_favorite',
					'itemOptions' => [
						'class' => 'vacancy__item'
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
							'class' => 'pagination',
						],
						'linkOptions' => [
							'class' => 'pagination__number'
						]
					]
				]);
			?>
			<?php Pjax::end(); ?>
				<!--
						<div class="subscribe-cat">
							<div class="subscribe-cat__wrapper">
								<svg><use x="0" y="0" xlink:href="img/icons-svg/zvon.svg#bell" /></svg>
								<div class="subscribe-cat__title col">
									Подпишитесь на бесплатную рассылку горячих вакансий
								</div>
								<div class="social col">
									<a href="#" class="social__telegram social__link">
										<img src="/img/icons-svg/telegram.svg" alt="">
										telegram
									</a>
									<a href="#" class="social__facebook social__link">
										<img src="/img/icons-svg/messenger.svg" alt="">
										messenger
									</a>
								</div>
							</div>
						</div>
				-->
			<?php /*
				<ul class="pagination pagination--mobile">
					<li class="prev"><a href="#" class="pagination__number">« назад</a></li>
					<li><a href="#" class="pagination__number">1</a></li>
					<li class="active"><div class="pagination__number">2</div></li>
					<li><a href="#" class="pagination__number">...</a></li>
					<li><a href="#" class="pagination__number">58</a></li>
					<li><a href="#" class="pagination__number">59</a></li>
					<li class="next"><a href="#" class="pagination__number">вперед »</a></li>
				</ul>
			*/ ?>
		</div>
	</div>
</div>

<div class="history-views">
	<div class="container">
		<div class="title-sec-wrap j-arrows-wrap">
			<div class="title-sec">
		        <?= Yii::t('main', 'Browsing history') ?>
		    </div>
		    <div class="j-arrows carousel-arrows"></div>
		</div>
		<div class="vacancy" slick-slider data-tablet="4" data-tablet-v="3" data-mobile="2" data-mobile-v="1">
	    	<?php for ($i = 1; $i <= 5; $i++) { ?>
		    	<div class="vacancy__item">
		    		<div class="vacancy__inner">
		    			<div class="vacancy__overlay"></div>
		    			<a href="" class="vacancy__title">
		    				Металлургический завод
		    			</a>
		    			<ul class="vacancy__place">
		    				<li>
		    					<a href="#" class="vacancy__country">
			    					Германия
			    				</a>
		    				</li>
		    				<li><a href="#">Фёльклинген</a></li>
		    			</ul>
		    			<a href="#" class="vacancy__img">
		    				<img src="/img/vacancy/1.jpg" alt="">
		    			</a>
		    			<div class="vacancy__price">
		    				от <b>12 000</b> до <b>25 000</b> грн.
		    			</div>
		    			<div class="vacancy__like-wrap">
		    				<a href="#" class="vacancy__like">
			    				Добавить в избранное
			    			</a>
		    			</div>
		    		</div>
		    	</div>
	    	<?php } ?>
	    </div>
	</div>
</div>