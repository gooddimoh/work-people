<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = Yii::t('service', 'Services and accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title) ?>
	</div>
</div>
<div class="profile" style="padding-bottom: 90px;">
	<div class="container">
		<div class="profile-links">
			<div class="profile-links__item">
				<a href="<?= Url::to(['/userpanel/service/purchase']) ?>" class="profile-links__inner">
					<span class="profile-links__img">
						<img src="/img/audit/link1.svg" alt="">
					</span>
					<span class="profile-links__title">
						Приобретенные<br> услуги
					</span>
				</a>
			</div>
			<div class="profile-links__item">
				<a href="<?= Url::to(['/userpanel/service/price']) ?>" class="profile-links__inner">
					<span class="profile-links__img">
						<img src="/img/audit/link2.svg" alt="">
					</span>
					<span class="profile-links__title">
						Услуги <br> и цены
					</span>
				</a>
			</div>
			<div class="profile-links__item">
				<a href="<?= Url::to(['/userpanel/invoice/index']) ?>" class="profile-links__inner">
					<span class="profile-links__img">
						<img src="/img/profile/link2.svg" alt="">
					</span>
					<span class="profile-links__title">
						Счета
					</span>
				</a>
			</div>
		</div>
	</div>
</div>
