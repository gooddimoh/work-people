<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = Yii::t('service', 'Job posting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and prices'), 'url' => ['price']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="title-desc">
		Ваша вакансия будет размещена на первой странице сайта. 
		И в Подарок, вы будете размещены в ТОП-е вакансий в своей категории при поиске.
		Число просмотров вакансии возрастет в 10-12 раз.
		Ее увидят тысячи соискателей, которые ищут работу.
	</div>
</div>
<div class="buy-services buy-services--publication">
	<div class="cat-nav">
		<div class="container">
			<ul class="cat-nav__cat">
				<li>
					<a href="<?= Url::to(['/userpanel/service/vip']) ?>" >VIP публикация</a>
				</li>
				<li>
					<a href="#" class="active">Top публикация</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="buy-services__item">
			<div class="buy-services__main">
				<div class="buy-services__title">
					1 публикация
				</div>
				<div class="buy-services__price-by-one">
					270грн / шт
				</div>
			</div>
			<div class="buy-services__price">
				<div>270 грн</div>
			</div>
			<div class="buy-services__btn">
				<a href="#" class="btn">Оплатить</a>
			</div>
		</div>
		<div class="buy-services__item">
			<div class="buy-services__main">
				<div class="buy-services__title">
					5 публикаций
				</div>
				<div class="buy-services__price-by-one">
					198 грн / шт
				</div>
			</div>
			<div class="buy-services__price">
				<div class="buy-services__old-price">1350 грн</div>
				<div class="buy-services__new-price">990 грн</div>
			</div>
			<div class="buy-services__btn">
				<a href="#" class="btn">Оплатить</a>
			</div>
		</div>
		<div class="buy-services__item">
			<div class="buy-services__main">
				<div class="buy-services__title">
					10 публикаций
				</div>
				<div class="buy-services__price-by-one">
					173грн / шт
				</div>
			</div>
			<div class="buy-services__price">
				<div class="buy-services__old-price">2700 грн</div>
				<div class="buy-services__new-price">1730 грн</div>
			</div>
			<div class="buy-services__btn">
				<a href="#" class="btn">Оплатить</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="vip-publication">
			<div class="vip-publication__col" style="width: 100%;">
				<div class="vip-publication__bl">
					<div class="vip-publication__zag">
						TOP - объявления
					</div>
					<?php for($i=0;$i<3;$i++) {?>
						<div class="vip-publication__item">
							<div class="vip-publication__img">
								<a href="single.php">							
									<img src="/img/vacancy/1.jpg" alt="">
								</a>
							</div>
							<div class="vip-publication__main">
								<a href="single.php" class="vip-publication__title">
									Разнорабочие на автомобильный завод
								</a>
								<div class="vip-publication__info">
									<ul class="vacancy__list">
										<li>
					    					<div class="vacancy__list-img">
					    						<img src="/img/vacancy/list3.png" alt="">
					    					</div>Город: <div class="vacancy__city">Чехия, Прага</div>
					    				</li>
					    				<li>
					    					<div class="vacancy__list-img">
					    						<img src="/img/vacancy/list1.png" alt="">
					    					</div>Работодатель: <a href="#">dinelgroup</a>
					    				</li>
					    			</ul>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>