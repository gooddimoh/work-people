<?php
use yii\helpers\Url;
?>

<footer class="footer">
	<div class="container">
		<div class="footer__row">
			<div class="footer__logo footer__col">
				<img src="/img/global/work_people_logo_003_white.png" alt="">
			</div>
			<div class="footer__col">
				<div class="footer__title">
					<?= Yii::t('site', 'About us') ?>
				</div>
				<ul class="footer__list">
					<li><a href="<?= Url::to(['/vacancy/index']) ?>"><?= Yii::t('site', 'All jobs') ?></a></li>
					<li><a href="<?= Url::to(['/company-review/index']) ?>"><?= Yii::t('site', 'Company reviews') ?></a></li>
					<?php /* <li><a href="<?= Url::to(['/site/safe-deal']) ?>"><?= Yii::t('site', 'Safe deal') ?></a></li> */ ?>
				</ul>
			</div>
			<div class="footer__col">
				<div class="footer__title">
					<?= Yii::t('site', 'How it works?') ?>
				</div>
				<ul class="footer__list">
					<li><a href="<?= Url::to(['/userpanel/vacancy/create']) ?>"><?= Yii::t('site', 'Post a resume') ?></a></li>
					<li><a href="#"><?= Yii::t('site', 'For employers') ?></a></li>
				</ul>
			</div>
			<div class="footer__col">
				<div class="footer__title">
					<?= Yii::t('site', 'Help') ?>
				</div>
				<ul class="footer__list">
					<li><a href="<?= Url::to(['/site/about']); ?>"><?= Yii::t('site', 'About the project') ?></a></li>
					<li><a href="<?= Url::to(['/site/contacts']); ?>"><?= Yii::t('site', 'Contacts') ?></a></li>
					<li><a href="<?= Url::to(['/site/faq']); ?>"><?= Yii::t('site', 'FAQ') ?></a></li>
					<?php /* <li><a href="<?= Url::to(['/site/public-offer']); ?>"><?= Yii::t('site', 'Public offer') ?></a></li> */ ?>
					<li><a href="<?= Url::to(['/site/terms-of-use']); ?>"><?= Yii::t('site', 'Terms of use') ?></a></li>
					<li><a href="<?= Url::to(['/site/paid-services']); ?>"><?= Yii::t('site', 'Paid services') ?></a></li>
					<li><a href="<?= Url::to(['/site/map']); ?>"><?= Yii::t('site', 'Site map') ?></a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>
<?php
$this->registerJsFile('/libs/jquery/dist/jquery.min.js');
$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/libs/jquery-nice-select/js/jquery.nice-select.min.js');
$this->registerJsFile('/libs/slick-carousel/slick/slick.min.js');
$this->registerJsFile('/libs/multi-select/jquery.multiselect.js');
$this->registerJsFile('/libs/fancybox/dist/jquery.fancybox.min.js');
$this->registerJsFile('/libs/scrollmagic/scrollmagic/minified/ScrollMagic.min.js');
$this->registerJsFile('/libs/jquery.maskedinput/dist/jquery.maskedinput.min.js');
$this->registerJsFile('/libs/air-datepicker/dist/js/datepicker.min.js');
$this->registerJsFile('/libs/img-preview/jquery.uploadPreview.min.js');
$this->registerJsFile('/libs/scrollbar/jquery.scrollbar.min.js');
// $this->registerJsFile('/libs/ckeditor5/ckeditor.js');
$this->registerJsFile('/libs/ckeditor5/translations/ru.js');
$this->registerJsFile('/libs/jquery-bar-rating/dist/jquery.barrating.min.js');
$this->registerJsFile('/js/common.js');
?>
<?php $this->endBody() ?>
</body>
</html>

