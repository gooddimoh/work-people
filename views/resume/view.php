<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\ResumeLanguage;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$category_jobs = $model->getCategoryJobs()->select('name')->asArray()->column();
foreach($category_jobs as &$job) {
	$job = Yii::t('category-job', $job);
}
$category_job_names = implode(', ', $category_jobs);

$this->title = Html::encode($category_job_names) . ' - ' . Html::encode($model->getFullName());
// $this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Search resume'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Resumes'), 'url' => ['search']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$desired_countries_of_work = ArrayHelper::getColumn($model->getDesiredCountryOfWork(), 'name');
foreach($desired_countries_of_work as $key => $val) {
	$desired_countries_of_work[$key] = Yii::t('country', $val);
}

$desired_country_of_work_names = implode(', ', $desired_countries_of_work);

$genders = $model->getGenders();
$gender_names = implode(', ', $genders);

$country_list = Resume::getCountryList();
// get resume country name label
$country_name = $model->country_name;
foreach($country_list as $country) {
	if($country['char_code'] == $country_name) {
		$country_name = Yii::t('country', $country['name']);
		break;
	}
}

// get resume city name
$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}

// categories
$categories = $model->getCategories()->asArray()->all();
$categories = ArrayHelper::getColumn($categories, 'name');
foreach($categories as &$category) {
    $category = Yii::t('category', $category);
}
$category_names = implode(', ', $categories);

$level_list = ResumeLanguage::getLevelList();
?>

<div class="single-top">
    <div class="container">
        <div class="row">
            <div class="single-top__col col">
                <div class="row single-top__row">
                    <div class="single-top__main single-top__main-resume col">
                        <ul>
                            <li>
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false"><?= $model->phone ?></div>
                            </li>
                            <li>
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                            <li class="j-more-hide" style="display: none;">
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                            <li class="j-more-hide" style="display: none;">
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                        </ul>
                        <span class="single-top__more j-more"><?= Yii::t('resume', 'More...') ?></span> <br>
						<div class="row resume-btn-double">
							<div class="col">
								<a href="<?= Url::to(['/userpanel/message/view', 'id' => $model->user_id]) ?>" class="btn">
									<?= Yii::t('resume', 'Write to candidate') ?>
								</a>
								<div class="single-top__message">
									<?= Yii::t('resume', 'Write to the candidate directly') ?>
								</div>
							</div>
							<div class="col">
								<a href="<?= Url::to(['/userpanel/vacancy-respond/create-resume', 'id' => $model->id]) ?>" class="btn single-top__nav-rezume" target="_blank">
									<?= Yii::t('resume', 'Offer a job') ?>
								</a>
							</div>
						</div>
                    </div>
                </div>
                <div class="single-top__nav">
					<a  id="resume_remove_like_<?= $model->id ?>"
						class="btn btn--transparent single-top__nav-like in_favorite jqh-resume__like"
						data-id="<?= $model->id ?>"
						data-rtype="remove-like"
						<?= in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
					>
						<?= Yii::t('resume', 'Remove from favorite') ?>
					</a>

					<a  id="resume_add_like_<?= $model->id ?>"
						class="btn btn--transparent single-top__nav-like jqh-resume__like"
						data-id="<?= $model->id ?>"
						data-rtype="add-like"
						<?= !in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
					>
						<?= Yii::t('resume', 'Save to favorites') ?>
					</a>

					<div class="btn btn--transparent single-top__nav-like like-loading" id="resume_like_loading_<?= $model->id ?>" style="display:none;"><?= Yii::t('resume', 'Loading...') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="info-company edit-info resume">
	<div class="container">
		<div class="single__top">
			<div class="single__top-img">
				<img src="<?= empty($model->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($model->getImageWebPath()); ?>" alt="<?= $category_job_names ?>">
			</div>
			<div class="single__top-text">
				<?php /*
				<div class="single__top-date">
					<?= Yii::t('resume', 'Resume date') ?> <span><?= Yii::$app->formatter->format($model->update_time, 'date') ?></span>
				</div>
				*/ ?>
				<h1>
					<?= Html::encode($category_job_names) ?>
				</h1>
			</div>
		</div>
		<div class="row info-company__row">
			<div class="col">
				<hr style="margin-top: 0;margin-bottom: 30px;">
				<div class="edit-info__bl resume__bl">
					<div class="row edit-info__row">
						<div class="col">
							<div class="info-company__title">
								<?= Yii::t('user', 'Personal data') ?>
							</div>
							<div class="table">
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= Yii::t('user', 'Full Name') ?>
									</div>
									<div class="table__td table__td--second-view">
										<?= Html::encode($model->getFullName()) ?>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= Yii::t('resume', 'Age') ?>
									</div>
									<div class="table__td table__td--second-view">
										<b><?= $model->getAge() ?> <?= Yii::t('resume', 'years') ?></b>
									</div>
								</div>
								<div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= $gender_names ?>
                                    </div>
                                </div>
								<div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($country_name) ?>
                                    </div>
                                </div>
								<div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_city_id') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($country_city_name) ?>
                                    </div>
                                </div>
								<?php /*
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= Yii::t('user', 'Phone') ?>
									</div>
									<div class="table__td table__td--second-view">
										<?= Html::encode($model->phone); ?>
									</div>
								</div>
								*/ ?>
							</div>
							<?php if($model->isOwner()): ?>
								<a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="btn btn--small btn--trans-yellow" ><?= Yii::t('main', 'Edit') ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="edit-info__bl">
                    <div class="info-company__title">
                        <?= Yii::t('resume', 'General information') ?>
                    </div>
                    <!-- resume info 2 view mode -->
                    <div class="table" v-if="!resume_info2_is_edit_mode">
						<div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                <?= Html::encode($model->desired_salary); ?> <?= Html::encode(Yii::t('curr', $model->desired_salary_currency_code)); ?>
                            </div>
                        </div>
						<div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary_per_hour') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                <?= Html::encode($model->desired_salary_per_hour); ?> <?= Html::encode(Yii::t('curr', $model->desired_salary_currency_code)); ?>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_country_of_work') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                <?= Html::encode($desired_country_of_work_names); ?>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Preferred job') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                <?= $category_job_names ?>
                            </div>
						</div>
						<div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Categories in which you would like to work') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                <?= $category_names ?>
                            </div>
                        </div>
						<?php if($model->isOwner()): ?>
							<a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="btn btn--small btn--trans-yellow" ><?= Yii::t('main', 'Edit') ?></a>
						<?php endif; ?>
                    </div>
                    <!--/ resume info 2 view mode -->
                </div>

				<div class="edit-info__bl" id="bl3">
                    <div class="info-company__title" style="margin-bottom: 0;">
                        <?= Yii::t('resume', 'Experience') ?>
                    </div>

                    <!-- resume job view mode -->
                    <div class="edit-info__work" style="margin: 10px 0;" v-if="!resume_job_is_edit_mode">
                        <?php foreach($model->resumeJobs as $index => $resumeJobModel): ?>
                            <div class="table">
                                <div class="table__tr">
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($resumeJobModel->company_name); ?>,&nbsp;
                                        <?= Html::encode($resumeJobModel->categoryJob->name); ?>,&nbsp;
                                        <?= $resumeJobModel->getAttributeLabel('month') ?>: <?= Html::encode($resumeJobModel->month); ?>,&nbsp;
                                        <?= $resumeJobModel->getAttributeLabel('years') ?>: <?= Html::encode($resumeJobModel->years); ?>&nbsp;
                                        <?php if($resumeJobModel->for_now == ResumeJob::STATUS_FOR_NOW_YES): ?>
                                            <span class="reg-lang__title-desc">(<?= $resumeJobModel->getAttributeLabel('for_now') ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($model->isOwner()): ?>
								<a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="btn btn--small btn--trans-yellow" ><?= Yii::t('main', 'Edit') ?></a>
							<?php endif; ?>
                        <?php endforeach; ?>
                    </div>
				</div>
				<div class="edit-info__bl" id="bl4">
                    <div class="info-company__title">
                        <?= Yii::t('resume', 'Education') ?>
                    </div>
                    <!-- resume education view mode -->
                    <div class="edit-info__work" style="margin: 10px 0;" v-if="!resume_education_is_edit_mode">
                        <?php foreach($model->resumeEducations as $index => $resumeEducationModel): ?>
                            <div class="table">
                                <div class="table__tr">
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($resumeEducationModel->description) ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($model->isOwner()): ?>
								<a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="btn btn--small btn--trans-yellow" ><?= Yii::t('main', 'Edit') ?></a>
							<?php endif; ?>
                        <?php endforeach; ?>
                    </div>
				</div>
				<div class="edit-info__bl" id="bl6">
                    <div class="info-company__title">
                        <?= Yii::t('user', 'Language proficiency') ?>
                    </div>
                    <div class="registration__input-bl">
                        <!-- resume language view mode -->
                        <div class="reg-lang" style="margin-top: 10px;" v-if="!resume_language_is_edit_mode">
                            <?php foreach($model->resumeLanguages as $index => $resumeLanguageModel): ?>
                                <div class="j-prop-lang-wrapper">
                                    <div class="edit-info__lang-top">
                                        <b><span class="j-prop-title-lang"><?= Yii::t('lang', $resumeLanguageModel->getLanguageName()) ?></span> </b>
                                        <span class="reg-lang__title-desc">
                                            (<span class="j-prop-title-level"><?= $level_list[$resumeLanguageModel->level] ?></span>)
                                        </span>
                                    </div>
									<?php if($model->isOwner()): ?>
										<a href="<?= Url::to(['/userpanel/resume/view', 'id' => $model->id]) ?>" class="btn btn--small btn--trans-yellow" ><?= Yii::t('main', 'Edit') ?></a>
									<?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
					</div>
				</div>
			</div>
			<div class="col info-company__col j-height-sticky-column">
				<div class="j-sticky">
					<?= Html::a(Yii::t('vacancy', 'Edit'), ['/userpanel/resume/view', 'id' => $model->id], [
						'class' => 'btn btn--transparent',
						'style' => 'width: 100%; margin-top:0;margin-bottom: 20px;',
					]) ?>

					<div class="sidebar">
						<div class="sidebar__title">
							<?= Yii::t('resume', 'The summary is posted at') ?>
						</div>
						<div class="sidebar__content">
							<a href="<?= Url::to(['/resume/' . $model->id]) ?>"><?= Url::base(true) . Url::to(['/resume/' . $model->id]) ?></a>
						</div>
					</div>
					<?php if($model->status == Resume::STATUS_SHOW): ?>
						<?= Html::beginForm(['/userpanel/resume/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Hide'),
								['class' => 'btn btn-danger']
							)
							. Html::endForm()
						?>
					<?php else: ?>
						<?= Html::beginForm(['/userpanel/resume/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Show'),
								['class' => 'btn']
							)
							. Html::endForm()
						?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="single-edit-buttons">
	<div class="container">
		<div class="row">
			<div class="col">
				<a href="<?= Url::to(['/userpanel/vacancy-respond/create-resume', 'id' => $model->id]) ?>" class="btn single-top__nav-rezume" target="_blank">
					<?= Yii::t('resume', 'Offer a job') ?>
				</a>
			</div>
			<div class="col">
				<a href="<?= Url::to(['/userpanel/message/view', 'id' => $model->user_id]) ?>" class="btn btn--transparent">
					<?= Yii::t('resume', 'Write to candidate') ?>
				</a>
			</div>
		</div>
	</div>
</div>
						
<?php if($model->isOwner()): ?>
	<div class="single-edit-buttons">
		<div class="container">
			<div class="row">
				<div class="col">
					<?php if($model->status == Resume::STATUS_SHOW): ?>
						<?= Html::beginForm(['/userpanel/resume/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Hide'),
								['class' => 'btn btn-danger']
							)
							. Html::endForm()
						?>
					<?php else: ?>
						<?= Html::beginForm(['/userpanel/resume/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Show'),
								['class' => 'btn']
							)
							. Html::endForm()
						?>
					<?php endif; ?>
				</div>
				<div class="col">
					<?= Html::a(Yii::t('vacancy', 'Edit'), ['/userpanel/resume/view', 'id' => $model->id], [
						'class' => 'btn btn--transparent',
					]) ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>