<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = Yii::t('service', 'Purchased services');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title);?>
	</div>
</div>
<div class="buy-services">
	<div class="cat-nav">
		<div class="container">
			<ul class="cat-nav__cat">
				<li><a href="#">В наличии</a></li>
				<li>
					<a href="#" class="active">Включенные</a>
				</li>
				<li>
					<a href="#">Недавно отключились</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="buy-services__item">
			<div class="buy-services__main">
				<div class="buy-services__title">
					Доступ к базе анкет, Вся Украина
				</div>
				<div class="buy-services__date">
					до 04.05.2019
				</div>
				<div class="buy-services__contacts">
					0 контактов
				</div>
			</div>
			<div class="buy-services__btn">
				<a href="#" class="btn btn--trans-yellow">Продлить</a>
			</div>
		</div>
		<div class="buy-services__item">
			<div class="buy-services__main">
				<div class="buy-services__title">
					Доступ к базе анкет, Вся Украина
				</div>
				<div class="buy-services__date">
					до 04.05.2019
				</div>
				<div class="buy-services__contacts">
					0 контактов
				</div>
			</div>
			<div class="buy-services__btn">
				<a href="#" class="btn btn--trans-yellow">Продлить</a>
			</div>
		</div>
		<a href="<?= Url::to(['/userpanel/service/price']) ?>" class="btn" style="margin-top: 30px;">Заказать услуги</a>
	</div>
</div>
