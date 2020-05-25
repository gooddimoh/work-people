<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\VacancyRespond;
/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('user', 'User Panel');
$this->params['breadcrumbs'][] = $this->title;

$session = Yii::$app->session;
$user_type = $session->get('interface_for');
?>
<div class="profile">
	<div class="container">
		<div class="profile-top">
			<div class="profile-top__col">
				<div class="profile-top__logo">
					<div class="profile-top__img">
						<img src="/img/profile/logo.png" alt="">
					</div>
				</div>
				<div class="profile-top__text">
					<div class="profile-top__title">
						<?= Yii::t('user', 'Welcome') ?>, <?= Yii::$app->user->identity->username ?>!
					</div>
					<div class="profile-top__desc">
                        <?php if(!empty(Yii::$app->user->identity->profile) && !empty(Yii::$app->user->identity->company)): ?>
						    <?= Yii::t('user', 'Company') ?> «<b><?= Yii::$app->user->identity->company->company_name ?></b>»
                        <?php endif; ?>
					</div>
				</div>
			</div>
            <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'logout-form']) ?>
            <?= Html::submitButton(
                    Yii::t('main', 'Logout'),
                    ['class' => 'btn btn--white userpanel-logout-btn', 'title' => Yii::$app->user->identity->username]
                ) ?>
            <?= Html::endForm() ?>
			<?php /* // disabled
			<a href="<?= Url::to(['/userpanel/account/edit'])?>" class="btn btn--white"><?= Yii::t('user', 'Account Management') ?></a>
			*/ ?>
		</div>
		<?php if($user_type != 'employer'): ?>
        <?php if(!empty(Yii::$app->user->identity->profile)): ?>
            <div class="profile-links">
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/resume']) ?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link1.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'My<br> resume') ?>
                        </span>
                    </a>
                </div>
				<?php /*
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/auto-mail']) ?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link-mes.png" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Automatic distribution of suitable vacancies') ?>
                        </span>
                    </a>
				</div>
				*/ ?>
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_NEW]) ?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link-history.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Response<br> history') ?>
                        </span>
                    </a>
                </div>
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/profile'])?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link5.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Personal<br> data') ?>
                        </span>
                    </a>
                </div>
            </div>
		<?php else: ?>
			<?php if (empty(Yii::$app->user->identity->company) || Yii::$app->user->id == 1): ?>
				<p><?= Yii::t('user', 'Profile is empty') ?>:</p>
				<a href="<?= Url::to(['/userpanel/profile/create'])?>" class="btn" style="margin-bottom: 10px;"><?= Yii::t('user', 'Create Profile') ?></a>
			<?php else: ?>
				<p><?= Yii::t('user', 'Register profile unavailable for your account.') ?></p>
				<div style="display: inline-block;">
					<a href="<?= Url::to(['/site/for-employer']) ?>" class="header__login">
						<?= Yii::t('main', 'For employers') ?>
					</a>
				</div>
			<?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
		<?php if($user_type == 'employer'): ?>
        <?php if(!empty(Yii::$app->user->identity->company)): ?>
            <div class="profile-links">
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/vacancy'])?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link1.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'My vacancies<br> and responses') ?>
                        </span>
                    </a>
                </div>
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/service']) ?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link2.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Services<br> and accounts') ?>
                        </span>
                    </a>
                </div>
				<?php /*
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/safe-deal']) ?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link3.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Revenues and expenses<br> through a safe deal') ?>
                        </span>
                    </a>
				</div>
				*/ ?>
                <div class="profile-links__item">
                    <a href="<?= Url::to(['/userpanel/company/view'])?>" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link4.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Company<br> information') ?>
                        </span>
                    </a>
                </div>
                <div class="profile-links__item">
                    <a href="#" class="profile-links__inner">
                        <span class="profile-links__img">
                            <img src="/img/profile/link6.svg" alt="">
                        </span>
                        <span class="profile-links__title">
                            <?= Yii::t('user', 'Users') ?>
                        </span>
                    </a>
                </div>
            </div>
		<?php else: ?>
			<?php if (empty(Yii::$app->user->identity->profile) || Yii::$app->user->id == 1): ?>
				<p><?= Yii::t('user', 'Company information not filled') ?>:</p>
				<a href="<?= Url::to(['/userpanel/company/create'])?>" class="btn"><?= Yii::t('user', 'Add company') ?></a>
			<?php else: ?>
				<p><?= Yii::t('user', 'Register company unavailable for your account.') ?></p>
				<div style="display: inline-block;">
					<a href="<?= Url::to(['/site/for-candidate']) ?>" class="header__login">
						<?= Yii::t('main', 'For candidates') ?>
					</a>
				<div>
			<?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>
	</div>
	<div class="profile-manager">
		<div class="container">
			<div class="profile-manager__title">
				<?= Yii::t('user', 'Always ready to help') ?>
			</div>
			<div class="profile-manager__desc">
				<?= Yii::t('user', 'manager__desc') ?>
			</div>
			<div class="social">
				<a href="<?= Yii::$app->params['messangers']['telegram'] ?>" class="social__telegram social__link">
					<svg version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<circle class="st0" cx="256" cy="256" r="256"></circle>
					<path class="st1" d="M34.1,256C34.1,120.4,139.6,9.4,273.1,0.6C267.4,0.2,261.7,0,256,0C114.6,0,0,114.6,0,256s114.6,256,256,256
						c5.7,0,11.4-0.2,17.1-0.6C139.6,502.6,34.1,391.6,34.1,256z"></path>
					<path class="st2" d="M380.3,109.1c-2.5-1.7-5.7-1.9-8.4-0.7L96.8,236.4c-4.8,2.3-7.9,7.2-7.8,12.5c0.1,5.2,3.3,9.9,8.1,12
						l253.6,110.5c8.5,3.8,18.4-2.2,19-11.5L384,116.6C384.2,113.6,382.7,110.7,380.3,109.1z"></path>
					<polygon class="st3" points="171.6,293.4 188.8,408 379.2,108.4 "></polygon>
					<path class="st4" d="M371.9,108.4L96.8,236.4c-4.7,2.2-7.8,7.1-7.8,12.3c0.1,5.2,3.3,10.1,8.1,12.2l74.6,32.5l207.5-185
						C376.9,107.3,374.2,107.3,371.9,108.4z"></path>
					<polygon class="st2" points="211.4,310.7 188.8,408 379.2,108.4 "></polygon>
					<path class="st4" d="M380.3,109.1c-0.4-0.2-0.7-0.4-1.1-0.6L211.4,310.7l139.3,60.7c8.5,3.8,18.4-2.2,19-11.5L384,116.6
						C384.2,113.6,382.7,110.7,380.3,109.1z"></path>
					</svg>
					telegram
				</a>
				<a href="<?= Yii::$app->params['messangers']['messenger'] ?>" class="social__facebook social__link">
					<svg version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<path class="st0" d="M256,0C117.6-2.7,3.2,107,0,245.4C0.4,315.8,31.5,382.5,85.3,428v73.4c0,5.9,4.8,10.7,10.7,10.7
						c2,0,4-0.6,5.7-1.6l59.5-37.1c30.3,11.6,62.5,17.5,94.9,17.4c138.4,2.7,252.8-107,256-245.3C508.8,107,394.4-2.7,256,0z"></path>
					<path class="st1" d="M424.5,175c-3.2-4.3-9-5.5-13.7-3l-110.9,60.5l-69-59.2c-4.2-3.6-10.5-3.4-14.5,0.6l-128,128
						c-4.2,4.2-4.1,10.9,0,15.1c3.3,3.3,8.5,4.1,12.6,1.8L212,258.3l69.1,59.2c4.2,3.6,10.5,3.4,14.5-0.6l128-128
						C427.3,185.1,427.7,179.2,424.5,175z"></path>
					</svg>
					messenger
				</a>
				<a href="<?= Yii::$app->params['messangers']['viber'] ?>" class="social__viber social__link">
					<svg version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
					<circle class="st0" cx="256" cy="256" r="256"></circle>
					<path class="st1" d="M367.1,140.4c-62.3-15.1-124.7-32.7-188.6-10.3c-41.4,15.5-41.4,60.3-39.6,98.3c0,10.3-12.1,24.1-6.9,36.2
						c10.3,34.5,19,69,55.2,86.2c5.2,3.4,0,10.3,3.4,15.5c-1.7,0-5.2,1.7-5.2,3.4c0,8.3,3.7,20.9,1.2,29l110,110
						c113.1-18,201.5-110.1,213.9-224.9L367.1,140.4z"></path>
					<g>
						<path class="st2" d="M391.4,179.9l-0.1-0.3c-6.8-27.7-37.7-57.3-66-63.5L325,116c-45.8-8.7-92.3-8.7-138,0l-0.3,0.1
							c-28.3,6.2-59.1,35.8-66,63.5l-0.1,0.3c-8.5,38.6-8.5,77.8,0,116.4l0.1,0.3c6.6,26.5,35.1,54.8,62.4,62.6v30.9
							c0,11.2,13.6,16.7,21.4,8.6l31.3-32.5c6.8,0.4,13.6,0.6,20.4,0.6c23.1,0,46.1-2.2,69-6.5l0.3-0.1c28.3-6.2,59.2-35.8,66-63.5
							l0.1-0.3C399.9,257.7,399.9,218.5,391.4,179.9z M366.7,290.7c-4.6,18-28,40.5-46.6,44.6c-24.4,4.6-48.9,6.6-73.4,5.9
							c-0.5,0-1,0.2-1.3,0.5c-3.5,3.6-22.8,23.4-22.8,23.4l-24.3,24.9c-1.8,1.9-4.9,0.6-4.9-2v-51.1c0-0.8-0.6-1.6-1.4-1.7c0,0,0,0,0,0
							c-18.6-4.1-42-26.6-46.6-44.6c-7.6-34.9-7.6-70.3,0-105.2c4.6-18,28-40.5,46.6-44.6c42.5-8.1,85.7-8.1,128.2,0
							c18.6,4.1,42,26.6,46.6,44.6C374.3,220.4,374.3,255.8,366.7,290.7z"></path>
						<path class="st2" d="M296.5,314.3c-2.9-0.9-5.6-1.5-8.1-2.5c-26.2-10.9-50.4-24.9-69.5-46.4c-10.9-12.2-19.4-26.1-26.6-40.7
							c-3.4-6.9-6.3-14.1-9.2-21.3c-2.7-6.5,1.3-13.3,5.4-18.2c3.9-4.6,8.9-8.1,14.3-10.8c4.2-2,8.4-0.9,11.5,2.7
							c6.7,7.8,12.8,15.9,17.8,24.9c3.1,5.5,2.2,12.3-3.3,16c-1.3,0.9-2.6,2-3.8,3c-1.1,0.9-2.1,1.8-2.9,3c-1.4,2.2-1.4,4.9-0.6,7.3
							c6.8,18.8,18.3,33.4,37.2,41.2c3,1.3,6.1,2.7,9.5,2.3c5.8-0.7,7.7-7.1,11.8-10.4c4-3.3,9.1-3.3,13.4-0.6c4.3,2.7,8.5,5.6,12.6,8.6
							c4.1,2.9,8.1,5.7,11.9,9c3.6,3.2,4.9,7.3,2.8,11.7c-3.7,7.9-9.1,14.5-16.9,18.7C301.6,313.2,299,313.6,296.5,314.3
							C293.6,313.5,299,313.6,296.5,314.3z"></path>
						<path class="st2" d="M256.1,165.4c34.3,1,62.5,23.7,68.5,57.7c1,5.8,1.4,11.7,1.9,17.6c0.2,2.5-1.2,4.8-3.9,4.8
							c-2.8,0-4-2.3-4.2-4.7c-0.4-4.9-0.6-9.8-1.3-14.6c-3.6-25.6-23.9-46.7-49.4-51.2c-3.8-0.7-7.7-0.9-11.6-1.3
							c-2.5-0.3-5.7-0.4-6.2-3.5c-0.5-2.6,1.7-4.6,4.1-4.7C254.7,165.4,255.4,165.4,256.1,165.4C290.4,166.4,255.4,165.4,256.1,165.4z"></path>
						<path class="st2" d="M308.2,233c-0.1,0.4-0.1,1.4-0.3,2.4c-0.9,3.4-6.1,3.9-7.3,0.4c-0.4-1-0.4-2.2-0.4-3.3
							c0-7.3-1.6-14.5-5.3-20.8c-3.8-6.5-9.5-12-16.3-15.3c-4.1-2-8.5-3.2-13-4c-2-0.3-3.9-0.5-5.9-0.8c-2.4-0.3-3.7-1.8-3.5-4.2
							c0.1-2.2,1.7-3.8,4.1-3.6c7.9,0.4,15.5,2.1,22.5,5.9c14.2,7.5,22.4,19.4,24.7,35.3c0.1,0.7,0.3,1.4,0.3,2.2
							C308,228.9,308.1,230.7,308.2,233C308.2,233.4,308.1,230.7,308.2,233z"></path>
						<path class="st2" d="M286.9,232.2c-2.9,0.1-4.4-1.5-4.7-4.2c-0.2-1.8-0.4-3.7-0.8-5.5c-0.9-3.5-2.7-6.8-5.7-8.9
							c-1.4-1-3-1.8-4.6-2.2c-2.1-0.6-4.3-0.4-6.4-1c-2.3-0.6-3.5-2.4-3.2-4.5c0.3-1.9,2.2-3.5,4.3-3.3c13.3,1,22.8,7.8,24.2,23.5
							c0.1,1.1,0.2,2.3,0,3.3C289.5,231.2,288.2,232.1,286.9,232.2C284,232.2,288.2,232.1,286.9,232.2z"></path>
					</g>
					<path class="st3" d="M391.4,179.9l-0.1-0.3c-3.8-15.5-15.2-31.6-29.5-43.9l-19.3,17.1c11.5,9.1,21.3,21.8,24.1,32.8
						c7.6,34.9,7.6,70.3,0,105.2c-4.6,18-28,40.5-46.6,44.6c-24.4,4.6-48.9,6.6-73.4,5.9c-0.5,0-1,0.2-1.3,0.5
						c-3.5,3.6-22.8,23.4-22.8,23.4l-24.3,24.9c-1.8,1.9-4.9,0.6-4.9-2v-51.1c0-0.8-0.6-1.6-1.4-1.7c0,0,0,0,0,0
						c-10.6-2.4-22.7-10.6-32-20.6l-19,16.9c11.9,12.9,27.2,23.4,42.2,27.7v30.9c0,11.2,13.6,16.7,21.4,8.6l31.3-32.5
						c6.8,0.4,13.6,0.6,20.4,0.6c23.1,0,46.1-2.2,69-6.5l0.3-0.1c28.3-6.2,59.2-35.8,66-63.5l0.1-0.3
						C399.9,257.7,399.9,218.5,391.4,179.9z"></path>
					<path class="st2" d="M296.5,314.3C299,313.6,293.6,313.5,296.5,314.3L296.5,314.3z"></path>
					<path class="st3" d="M317.9,281.7c-3.8-3.3-7.8-6.1-11.9-9c-4.1-3-8.3-5.9-12.6-8.6c-4.3-2.7-9.4-2.7-13.4,0.6
						c-4.1,3.3-6,9.7-11.8,10.4c-3.5,0.4-6.5-1.1-9.5-2.3c-11.6-4.8-20.4-12.2-27.1-21.6l-14.2,12.6c0.5,0.6,0.9,1.1,1.4,1.7
						c19.1,21.5,43.3,35.6,69.5,46.4c2.5,1,5.3,1.6,8.1,2.5c-2.9-0.9,2.5-0.8,0,0c2.5-0.8,5.1-1.1,7.3-2.3c7.8-4.2,13.2-10.8,16.9-18.7
						C322.8,289,321.5,284.8,317.9,281.7z"></path>
					<g>
						<path class="st2" d="M256.2,165.4C256.1,165.4,256.1,165.4,256.2,165.4C256,165.4,256.1,165.4,256.2,165.4z"></path>
						<path class="st2" d="M256.1,165.4C256.1,165.4,256.1,165.4,256.1,165.4C258.1,165.5,289,166.4,256.1,165.4z"></path>
					</g>
					<g>
						<path class="st3" d="M305.3,185.8l-6,5.4c9.5,9.2,16,21.5,17.9,34.9c0.7,4.8,0.9,9.7,1.3,14.6c0.2,2.5,1.4,4.8,4.2,4.7
							c2.7,0,4.1-2.4,3.9-4.8c-0.5-5.9-0.8-11.8-1.9-17.6C322,208.2,315.1,195.5,305.3,185.8z"></path>
						<path class="st3" d="M307.5,224.9c-1.7-11.6-6.5-21-14.5-28.2l-6,5.3c3.1,2.8,5.8,6,7.9,9.6c3.7,6.3,5.2,13.6,5.3,20.8
							c0,1.1,0.1,2.3,0.4,3.3c1.2,3.5,6.4,3,7.3-0.4c0.3-0.9,0.3-2,0.3-2.4c-0.1,0.4-0.1-2.4,0,0c-0.1-2.4-0.2-4.1-0.4-5.9
							C307.8,226.4,307.6,225.7,307.5,224.9z"></path>
					</g>
					<g>
						<path class="st2" d="M308.2,233C308.1,230.7,308.2,233.4,308.2,233L308.2,233z"></path>
						<path class="st2" d="M286.9,232.2c0,0,0.1,0,0.1,0c-0.1,0-0.3,0-0.5,0C286.6,232.2,286.7,232.2,286.9,232.2z"></path>
						<path class="st2" d="M286.9,232.2c-0.1,0-0.2,0-0.4,0C285.9,232.2,285.2,232.2,286.9,232.2z"></path>
						<path class="st2" d="M287,232.2c0,0-0.1,0-0.1,0C287.2,232.2,287.2,232.2,287,232.2z"></path>
					</g>
					<path class="st3" d="M280.8,207.5l-6.1,5.4c0.3,0.2,0.7,0.4,1,0.7c3,2.2,4.8,5.4,5.7,8.9c0.4,1.8,0.6,3.6,0.8,5.5
						c0.3,2.5,1.7,4.1,4.3,4.1c0.2,0,0.4,0,0.5,0c1.3-0.1,2.5-1,2.9-2.8c0.2-1.1,0.1-2.2,0-3.3C289.2,217.3,285.9,211.3,280.8,207.5z"></path>
					</svg>
					viber
				</a>
			</div>
		</div>
	</div>
</div>
