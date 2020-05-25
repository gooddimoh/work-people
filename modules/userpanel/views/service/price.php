<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = Yii::t('service', 'Services and prices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="info-company">
	<div class="container">
		<div class="title-sec">
			<?= Html::encode($this->title) ?>
		</div>
		<div class="row info-company__row j-trigger">
			<div class="col">
				<hr style="margin: 0">
				<div class="services-prices">
					<div class="services-prices__item j-edit" id="bl1">
						<div class="services-prices__img">
							<div class="services-prices__img-inner">
								<img src="/img/services-prices/1.png" alt="">
							</div>
						</div>
						<div class="services-prices__main">
							<div class="services-prices__title">
								<?= Yii::t('service', 'VIP job postings') ?>
							</div>
							<p>
								<?= Yii::t('service', 'You will be placed in the TOP vacancies in your category when searching.') ?>
							</p>
							<a href="<?= Url::to(['/userpanel/service/vip']) ?>" class="btn btn--trans-yellow"><?= Yii::t('main', 'More') ?></a>
						</div>
						<div class="services-prices__price">
							<div>
								<?= Yii::t('main', 'from') ?> <b><?= $min_price_vip ?></b> <?= Yii::t('curr', 'UAH') ?>
							</div>
							<div>
								<?= Yii::t('main', 'for') ?> <b><?= $publication_count ?></b> <?= Yii::t('main', 'pc') ?>
							</div>
						</div>
					</div>
					<div class="services-prices__item j-edit" id="bl2">
						<div class="services-prices__img">
							<div class="services-prices__img-inner">
								<img src="/img/services-prices/2.png" alt="">
							</div>
						</div>
						<div class="services-prices__main">
							<div class="services-prices__title">
								TOP публикации вакансий
							</div>
							<p>
								Вы будете размещены в ТОП-е вакансий в своей категории при поиске. Число вакансий возрастет в 5-7 раз.
							</p>
							<a href="<?= Url::to(['/userpanel/service/top']) ?>" class="btn btn--trans-yellow">Подробнее</a>
						</div>
						<div class="services-prices__price">
							<div>
								<?= Yii::t('main', 'from') ?> <b>173</b> <?= Yii::t('curr', 'UAH') ?>
							</div>
							<div>
								<?= Yii::t('main', 'for') ?> <b>1</b> <?= Yii::t('main', 'pc') ?>
							</div>
						</div>
					</div>
					<div class="services-prices__item j-edit" id="bl3">
						<div class="services-prices__img">
							<div class="services-prices__img-inner">
								<img src="/img/services-prices/3.png" alt="">
							</div>
						</div>
						<div class="services-prices__main">
							<div class="services-prices__title">
								Индивидуальный найм персонала
							</div>
							<p>
								
							</p>
							<a href="<?= Url::to(['/userpanel/service/individual']) ?>" class="btn btn--trans-yellow">Подробнее</a>
						</div>
						<div class="services-prices__price">
							<div>
								<?= Yii::t('main', 'from') ?> <b> 500</b> <?= Yii::t('curr', 'UAH') ?>
							</div>
							<div>
								<b>1</b> работник
							</div>
						</div>
					</div>
					<div class="services-prices__item j-edit" id="bl4">
						<div class="services-prices__img">
							<div class="services-prices__img-inner">
								<img src="/img/services-prices/4.png" alt="">
							</div>
						</div>
						<div class="services-prices__main">
							<div class="services-prices__title">
								Доступ к базе анкет
							</div>
							<p>
								
							</p>
							<a href="#" class="btn btn--trans-yellow">Подробнее</a>
						</div>
						<div class="services-prices__price">
							<div>
								<b>Бесплатно</b>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col info-company__col j-height-sticky-column">
				<div class="sidebar j-sticky">
					<div class="sidebar__title">
						Адрес страницы вашей компании на сайте
					</div>
					<div class="sidebar__content">
						<ul class="sidebar__list">
							<li><a href="#bl1" class="j-scroll"><b>VIP</b> публикации вакансий</a></li>
							<li><a href="#bl2" class="j-scroll"><b>TOP</b> публикации вакансий</a></li>
							<li><a href="#bl3" class="j-scroll">Индивидуальный набор</a></li>
							<li><a href="#bl4" class="j-scroll">Доступ к базе анкет</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
