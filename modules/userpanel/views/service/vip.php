<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tarif;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $tarifList app\models\Tarif */

$this->title = Yii::t('service', 'Job posting');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and prices'), 'url' => ['price']];
$this->params['breadcrumbs'][] = $this->title;

$tarifListGrouped = ArrayHelper::index($tarifList, null, 'tarif_type');
$tarif_type_list = Tarif::getTarifTypeList();
$tarif_type_description_list = Tarif::getTarifTypeDescriptionList();
$default_tarif_index = 1;
?>
<div class="container">
	<div class="title-sec">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="title-desc">
		<?= Yii::t('service', 'Your vacancy will be placed on the first page of the site.') ?>
	</div>
</div>
<div id="appTarif" class="buy-services buy-services--publication">
	<div class="cat-nav">
		<div class="container">
			<ul class="cat-nav__cat">
				<li>
					<a href="#" class="active"><?= Yii::t('service', 'VIP postings') ?></a>
				</li>
				<li>
					<a href="<?= Url::to(['/userpanel/service/top']) ?>"><?= Yii::t('service', 'TOP postings') ?></a>
				</li>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="row info-tarif_row">
			<?php foreach($tarifListGrouped as $tarif_type => $tarifs) { ?>
				<div class="col">
					<?php $form = ActiveForm::begin(['method' => 'post']); ?>
					<div class="trud-if__bl <?= $tarif_type == Tarif::TARIF_TYPE_VIP ? 'trud-if__bl--yellow' : '' ?>">
						<div class="trud-if__select">
							<select class="select" name="Tarif[desired_country_of_work]" id="select_group_<?= $tarif_type ?>">
								<?php foreach($tarifs as $index => $tarif) { ?>
									<option value="<?= $tarif->id ?>" <?= $index == $default_tarif_index ? 'selected': '' ?>>
										<?= Yii::t('service', '{publication_count, plural, =0{} =1{1 publication} one{# publication} few{# publications} many{# publications} other{# publications}}', [
											'publication_count' => $tarif->publication_count
										]) ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<div class="trud-if__title">
							<?= Yii::t('service', $tarif_type_list[$tarif_type]) ?>
							<div class="trud-if__title-description">
								<?= Yii::t('service', $tarif_type_description_list[$tarif_type]['sub_title']) ?>
							</div>
						</div>
						<hr>
						<?php foreach($tarifs as $index => $tarif) { ?>
							<div class="trud-if__list" id="tarif_<?= $tarif_type ?>_<?=$tarif->id?>" <?= $index != $default_tarif_index ? 'style="display:none;"' : '' ?>>
								<div>
									<?= Yii::t('service', 'Top announcement for {top_days} days + publication in the general list (published at the top of the list and highlighted)', [
										'top_days' => $tarif->top_days
									]) ?>
								</div>
								<hr>
								<div>
									<?= Yii::t('service', '{upvote_count, plural, =0{upvote in list} =1{one upvote in list} one{# upvote in list} few{# upvotes in list} many{# upvotes in list} other{# upvotes in list}}', [
										'upvote_count' => $tarif->upvote_count
									]) ?>
									<br>
									<?php if ($tarif->upvote_count > 0) { ?>
										(<?= Yii::t('service', '{upvote_period, plural, =0{} =1{upvote every day} one{upvote every # day} few{upvote every # days} many{upvote every # days} other{upvote every # days}}', [
											'upvote_period' => $tarif->upvote_period
										]) ?>)
									<?php } ?>
								</div>
								<hr>
								<div>
									<?php if ($tarif->vip_count == 0) { ?>
										<?= Yii::t('service', 'VIP ad') ?>
									<?php } else { ?>
										<?= Yii::t('service', '{vip_period, plural, =0{VIP ad on home page} =1{VIP ad on home page on one day} one{VIP ad on home page on # day} few{VIP ad on home page on # days} many{VIP ad on home page on # days} other{VIP ad on home page on # days}}', [
											'vip_period' => $tarif->vip_period
										]) ?>
									<?php } ?>
									
									<br>
									(<?= Yii::t('service', 'Home ad') ?>)
								</div>
								<hr>
								<div class="tarif-block-price">
									<div>
										<?php if($tarif->discount_size == 0)  { ?>
											<div class="tarif-final-price"><?= $tarif->getPaidPrice(0) ?></div>
										<?php } else { ?>
											<div class="tarif-discount-price"><?= $tarif->price ?></div>
											<div class="tarif-discount-size">
												<div class="tarif-discount-marker">-<?= $tarif->discount_size ?>%</div>
											</div>
											<div class="tarif-final-price"><?= $tarif->getPaidPrice(0) ?></div>
										<?php } ?>
									</div>
									<div class="tarif-block-price__currency">
										<?= Yii::t('curr', 'UAH') ?>
									</div>
								</div>
								<div>
									<?= Html::submitButton(Yii::t('service', 'Select'), ['class' => 'btn btn--green']) ?>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php ActiveForm::end(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="container">
		<div class="title-sec">
			<?= Yii::t('service', 'You will be seen first on the home page') ?>
		</div>
		<div class="vip-publication">
			<div class="vip-publication__col">
				<div class="vip-publication__screen">
					Скрин сайта
				</div>
			</div>
			<div class="vip-publication__col">
				<div class="vip-publication__bl">
					<div class="vip-publication__zag">
						VIP - объявления
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

<?php $this->beginJs(); ?>
<script>
$(document).ready(function() {
	let tarif_type_list = [
		<?php foreach($tarifListGrouped as $tarif_type => $tarifs) { ?>
			<?=$tarif_type ?>,
		<?php } ?>
	];

	for (let i = 0; i < tarif_type_list.length; i++) {
		let select_item = $('#select_group_' + tarif_type_list[i]);
		select_item.niceSelect();
		select_item.on('change', function(e) {
			// hide all
			let el = $(this).find('option').each(function() {
				$('#tarif_'+ tarif_type_list[i] +'_'+ $(this).val()).hide();
			});

			// show selected
			$('#tarif_'+ tarif_type_list[i] +'_'+ $(this).val()).show();
		});
	}

});
</script>
<?php $this->endJs(); ?>
