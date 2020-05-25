<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$number_of_employees_list = Company::getNumberOfEmployeesList();
?>

<?php echo $this->render('_view_header', [
    'model' => $model
]) ?>

<div class="info-company">
	<div class="container review-single">
		<div class="row info-company__row j-trigger">
			<div class="col">
				<hr style="margin-top: 0;">

                <?php if($model->isOwner()): ?>
                    <a href="<?= Url::to(['/userpanel/company/view']) ?>" class="btn btn--trans-yellow" style="width: 260px;"><?= Yii::t('main', 'Edit') ?></a>
                    <hr>
                <?php endif; ?>

				<div class="profile-top">
					<div class="profile-top__col">
						<div class="profile-top__logo">
							<div class="profile-top__img fix-company-logo">
                                <img src="<?= empty($model->logo) ? '/img/profile/link4.svg' : Html::encode($model->getLogoWebPath()); ?>" alt="<?= Html::encode($this->title) ?>">
							</div>
						</div>
						<div class="profile-top__text">
							<div class="profile-top__title">
                                <?= Html::encode($this->title) ?>
							</div>
							<div class="profile-top__desc">
                                <?php if($model->status == Company::STATUS_VERIFIED): ?>
                                    <div class="authentication authentication--yes">
                                        <?= Yii::t('company', 'Verified') ?>
                                    </div>
                                    <div class="edit-info__auth-title edit-info__auth-title--yes">
                                        <?= Yii::t('company', 'Company verified') ?>
                                    </div>
                                <?php else: ?>
                                    <div class="authentication authentication--no">
                                        <?= Yii::t('company', 'Not Verified') ?>
                                    </div>
                                    <div class="edit-info__auth-title edit-info__auth-title--no">
                                        <?= Yii::t('company', 'Company not verified') ?>
                                    </div>
                                <?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="table">
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('type_industry') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($model->type_industry) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('number_of_employees') ?>
						</div>
						<div class="table__td">
							<b><?= $number_of_employees_list[$model->number_of_employees] ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('site') ?>
						</div>
						<div class="table__td">
                            <?= Html::encode($model->site) ?>
						</div>
					</div>
					<?php /*
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_name') ?>
						</div>
						<div class="table__td">
                            <?= Html::encode($model->contact_name) ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_phone') ?>
						</div>
						<div class="table__td">
                            <?= Html::encode($model->contact_phone) ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_email') ?>
						</div>
						<div class="table__td">
                            <?= Html::encode($model->contact_email) ?>
						</div>
					</div>
					*/ ?>
				</div>
				<p>
                    <?= nl2br(Html::encode($model->description)) ?>
				</p>
			</div>
			<div class="col info-company__col j-height-sticky-column">
				<div class="sidebar j-sticky">
					<div class="sidebar__title">
                        <?= Yii::t('company', 'Address of your company’s website') ?>
					</div>
					<div class="sidebar__content">
                        <a href="<?= Url::to(['/company/' . $model->id]) ?>"><?= Url::base(true) . Url::to(['/company/' . $model->id]) ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
