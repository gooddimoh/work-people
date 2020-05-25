<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Vacancy;
use app\components\CurrencyConverterHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = Html::encode(Yii::t('category-job', $model->categoryJob->name));
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$categories = $model->getCategories()->asArray()->all();
$category_list = [];
foreach($categories as $category) {
    $category_list[] = Yii::t('category', $category['name']);
}

$category_names = implode(', ', $category_list);

$worker_country_names = Yii::t('vacancy', 'All countries');
$worker_countries = ArrayHelper::getColumn($model->getWorkerCountries(), 'name');
if(!empty($worker_countries)) {
	foreach($worker_countries as $key => $val) {
		$worker_countries[$key] = Yii::t('country', $val);
	}
	
	$worker_country_names = implode(', ', $worker_countries);
}

$genders = $model->getGenders();
$gender_names = implode(', ', $genders);

$working_shifts = $model->getWorkingShifts();
$working_shift_names = implode(', ', $working_shifts);

$documents_provided = $model->getDocumentsProvided();
// translate
foreach($documents_provided as $index => $val) {
	$documents_provided[$index] = Yii::t('vacancy', $val);
}
$documents_provided_names = implode(', ', $documents_provided);

$documents_required = $model->getDocumentsRequired();
// translate
foreach($documents_required as $index => $val) {
	$documents_required[$index] = Yii::t('vacancy', $val);
}
$documents_required_names = implode(', ', $documents_required);

// get vacancy country name label
$country_list = Vacancy::getCountryList();
$country_name = $model->country_name;
foreach($country_list as $country) {
	if($country['char_code'] == $country_name) {
		$country_name = Yii::t('country', $country['name']);
		break;
	}
}

$vacancy_images = $model->vacancyImages;
$main_image = $model->getImageWebPath();

$country_city_name = '-';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}

// Arrival date
$arrival_date_arr = [];
if (!empty($model->date_start)) {
	$arrival_date_arr[] = Yii::$app->formatter->format($model->date_start, 'date');
}
if (!empty($model->date_end)) {
	$arrival_date_arr[] = Yii::$app->formatter->format($model->date_end, 'date');
}

$arrival_date = implode(' - ', $arrival_date_arr);
?>
<div class="single-top">
    <div class="container">
        <div class="row">
            <div class="single-top__col col">
                <div class="row single-top__row">
                    <div class="single-top__main col">
                        <div class="title-sec">
                            <?= Html::encode($model->company->company_name) ?>
                        </div>
                        <ul class="single-top__list">
                            <li>
                                <?= Yii::t('vacancy', 'Safe deals') ?>: <span class="green">0</span>
                            </li>
                            <li>
                                <?= Yii::t('vacancy', 'Reviews') ?>: <span class="green">+<?= $model->company->getCompanyReviewsPositive()->count() ?></span> / <span class="red">-<?= $model->company->getCompanyReviewsNegative()->count() ?></span>
                            </li>
                            <li>
                                <?= Yii::t('vacancy', 'Favored by') ?> <span class="blue"><?= $model->getUserFavoriteVacancies()->count() ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="single-top__numbers col">
                        <ul>
                            <li>
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
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
                        <span class="single-top__more j-more"><?= Yii::t('vacancy', 'More...') ?></span> <br>
                        <a href="<?= Url::to(['/userpanel/message/view', 'id' => $model->company->user_id]) ?>" class="btn">
                        	<?= Yii::t('vacancy', 'Write to employer') ?>
                        </a>
                        <div class="single-top__message">
                        	<?= Yii::t('vacancy', 'Write to the employer directly') ?>
                        </div>
                    </div>
                </div>
                <div class="single-top__nav">
					<a href="<?= Url::to(['/userpanel/vacancy-respond/create', 'id' => $model->id]) ?>" class="btn single-top__nav-rezume" target="_blank">
						<?= Yii::t('vacancy', 'Send resume') ?>
					</a>
					
					<a  id="vacancy_remove_like_<?= $model->id ?>"
						class="btn btn--transparent single-top__nav-like in_favorite jqh-vacancy__like"
						data-id="<?= $model->id ?>"
						data-rtype="remove-like"
						<?= in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
					>
						<?= Yii::t('vacancy', 'Remove from favorite') ?>
					</a>

					<a  id="vacancy_add_like_<?= $model->id ?>"
						class="btn btn--transparent single-top__nav-like jqh-vacancy__like"
						data-id="<?= $model->id ?>"
						data-rtype="add-like"
						<?= !in_array($model->id, $favorite_ids) ? '' : 'style="display:none;"' ?>
					>
						<?= Yii::t('vacancy', 'Save to favorites') ?>
					</a>

					<div class="btn btn--transparent single-top__nav-like like-loading" id="vacancy_like_loading_<?= $model->id ?>" style="display:none;"><?= Yii::t('vacancy', 'Loading...') ?></div>

                    <a href="<?= Url::to(['company-review/viewcompany', 'id' => $model->company->id]) ?>" class="btn btn--transparent single-top__nav-reviews" target="_blank">
                    	<?= Yii::t('vacancy', 'All reviews') ?>
                    </a>
                </div>
            </div>
            <div class="single-top__col2 col">
            	<div class="single-top__subscribe">
            		<div class="single-top__subscribe-title">
            			<?= Yii::t('vacancy', 'Be the first to receive an offer') ?>
            		</div>
            		<a href="#" class="btn">
            			<?= Yii::t('vacancy', 'Follow company news') ?>
            		</a>
            		<div class="single-top__subscribe-desc">
            			<?= Yii::t('vacancy', 'Subscribe to the newsletter and follow the vacancies in your category') ?>
            		</div>
            	</div>
            </div>
        </div>
    </div>
</div>

<div class="single">
	<div class="container">
		<div class="single__top">
			<div class="single__top-img">
				<?php if(!empty($main_image)): ?>
					<img src="<?= $main_image ?>" alt="">
				<?php endif; ?>
			</div>
			<div class="single__top-text">
				<div class="single__top-date">
					<?= Yii::t('vacancy', 'Vacancy date') ?> <span><?= Yii::$app->formatter->format($model->update_time, 'date') ?></span>
				</div>
				<h1>
					<?= Html::encode($model->title) ?>
					&nbsp;-&nbsp;
					<?= Html::encode(Yii::t('category-job', $model->categoryJob->name)) ?>
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="single__table col">
				<div class="table">
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= $model->getAttributeLabel('company_name') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($model->company_name) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('vacancy', 'Job posting categories') ?>
						</div>
						<div class="table__td">
							<b><?= $category_names ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('country_name') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($country_name) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('country_city_id') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($country_city_name) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'For citizens of next countries') ?>
						</div>
						<div class="table__td">
                            <b><?= $worker_country_names ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('gender_list') ?>
						</div>
						<div class="table__td">
							<b><?= $gender_names ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Salary per hour') ?>
						</div>
						<div class="table__td">
                            <b><?= $model->salary_per_hour_min ?></b> <?= Yii::t('curr', $model->currency_code) ?> - <b><?= $model->salary_per_hour_max ?></b> <?= Yii::t('curr', $model->currency_code) ?> (<b><?= $model->salary_per_hour_min_src ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?> - <b><?= $model->salary_per_hour_max_src ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?>)
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Salary per month from-to') ?>
						</div>
						<div class="table__td">
							<b><?= $model->prepaid_expense_min ?></b> <?= Yii::t('curr', $model->currency_code) ?> - <b><?= $model->prepaid_expense_min ?></b> <?= Yii::t('curr', $model->currency_code) ?> (<b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_max, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?> - <b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_max, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?>)
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Number of hours from-to') ?>
						</div>
						<div class="table__td">
							<b><?= $model->hours_per_day_min ?> - <?= $model->hours_per_day_max ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Number of days from-to (per week)') ?>
						</div>
						<div class="table__td">
							<b><?= $model->days_per_week_min ?> - <?= $model->days_per_week_max ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Arrival date') ?>
						</div>
						<div class="table__td">
							<b><?= $arrival_date ?></b>
                            &nbsp;
                            <?php if($model->date_free == Vacancy::DATE_FREE_YES): ?>
                                (<?= Yii::t('vacancy', 'free') ?>)
                            <?php endif; ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Shifts') ?>
						</div>
						<div class="table__td">
							<b><?= $working_shift_names ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Residence') ?>
						</div>
						<div class="table__td table__td--multiple">
                            <?php if($model->residence_provided == Vacancy::RESIDENCE_PROVIDED_YES): ?>
                                <div>
                                    <b><?= Yii::t('main', 'Yes') ?></b>
                                </div>
                                <div>
									<b><?= $model->residence_amount ?></b> <?= Yii::t('curr', $model->residence_amount_currency_code) ?> (<b><?= CurrencyConverterHelper::currencyToCurrency($model->residence_amount, $model->residence_amount_currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?> )
                                </div>
                                <div>
                                    <b><?= $model->residence_people_per_room ?></b> <?= Yii::t('vacancy', 'peoples per the room') ?>
                                </div>
                            <?php else: ?>
                                <div>
                                    <b><?= Yii::t('main', 'No') ?></b>
                                </div>
                            <?php endif; ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Documents from the employee') ?>
                            <!-- <?= $model->getAttributeLabel('documents_required') ?> -->
						</div>
						<div class="table__td">
							<?= $documents_required_names ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('documents_provided') ?>
						</div>
						<div class="table__td">
							<?= $documents_provided_names ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= $model->getAttributeLabel('job_description') ?>
						</div>
						<div class="table__td table__td--multiple">
							<textarea wrap="hard" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="edit-info__textarea"><?= Html::encode($model->job_description) ?></textarea>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_name') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($model->contact_name) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_phone') ?>
						</div>
						<div class="table__td">
							<b><?= Html::encode($model->contact_phone) ?></b>
						</div>
					</div>
				</div>
				<div class="single__table-title">
					<?= Yii::t('vacancy', 'Bonuses') ?>
				</div>
				<div class="table">
					<div class="table__tr">
						<div class="table__td">
							<textarea wrap="hard" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="edit-info__textarea"><?= Html::encode($model->job_description_bonus) ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="single__info col">
				<ul>
					<li>
						<span><?= Yii::t('vacancy', 'Published') ?>: </span>
						<b><?= Yii::$app->formatter->format($model->creation_time, 'date') ?></b>
					</li>
					<li>
						<span><?= Yii::t('vacancy', 'Viewed') ?> (<?= Yii::t('vacancy', 'count') ?>):</span>
						<b><?= empty($model->vacancyCounter) ? '0' : $model->vacancyCounter->view_count ?></b>
					</li>
					<li>
						<span><?= Yii::t('vacancy', 'Opened number') ?> (<?= Yii::t('vacancy', 'count') ?>):</span>
						<b><?= empty($model->vacancyCounter) ? '0' : $model->vacancyCounter->open_count ?></b>
					</li>
					<li>
						<span><?= Yii::t('vacancy', 'Ad Number') ?>:</span>
						<b><?= $model->id ?></b>
					</li>
				</ul>
			</div>
		</div>
		<div class="single__title-wrap j-arrows-wrap">
			<div class="single__table-title">
				<?= Yii::t('vacancy', 'Photo/Video') ?>
			</div>
			 <div class="j-arrows carousel-arrows"></div>
		</div>
		<div class="single__gallery row" slick-slider data-tablet="4" data-tablet-v="3" data-mobile="2" data-mobile-v="1">
			<?php foreach($vacancy_images as $image): ?>
				<div class="single__gallery-item col">
					<a href="<?= Html::encode($image->getImageWebPath()); ?>" class="single__gallery-img" alt="<?= Html::encode($image->name) ?>" data-fancybox>
						<img src="<?= Html::encode($image->getImageWebPath()); ?>" alt="">
					</a>
				</div>
			<?php endforeach; ?>
			<?php /*
			<div class="single__gallery-item col">
				<a href="https://www.youtube.com/watch?v=z0mnx8OA5D0" class="single__gallery-img single__gallery-img--video" data-fancybox>
					<img src="/img/single/6.jpg" alt="">
				</a>
			</div>
			*/ ?>
		</div>
	</div>
</div>
<?php if($model->isOwner()): ?>
	<div class="single-edit-buttons">
		<div class="container">
			<div class="row">
				<div class="col">
					<?php if($model->status == Vacancy::STATUS_SHOW): ?>
						<?= Html::beginForm(['/userpanel/vacancy/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Hide'),
								['class' => 'btn btn-danger']
							)
							. Html::endForm()
						?>
					<?php else: ?>
						<?= Html::beginForm(['/userpanel/vacancy/changestatus', 'id' => $model->id], 'post')
							. Html::submitButton(
								Yii::t('vacancy', 'Show'),
								['class' => 'btn']
							)
							. Html::endForm()
						?>
					<?php endif; ?>
				</div>
				<div class="col">
					<?= Html::a(Yii::t('vacancy', 'Edit'), ['/userpanel/vacancy/view', 'id' => $model->id], [
						'class' => 'btn btn--transparent',
					]) ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php $this->beginJs(); ?>
<script>
    $(document).ready(function() {
		// add remove favorite vacancy
        $( ".jqh-vacancy__like" ).click(function(e) {
			e.stopPropagation();
			let request_url_like = "<?= Url::to(['/userpanel/vacancy/add-favorite']) ?>";
			let request_url_unlike = "<?= Url::to(['/userpanel/vacancy/remove-favorite']) ?>";

			let vacancy_id = $(this).data('id');
			let request_type = $(this).data('rtype');
			let request_url = '';
			let el_to_show = {};

			if(request_type == 'add-like') {
				request_url = request_url_like;
				el_to_show = $('#vacancy_remove_like_' + vacancy_id);
			} else { // request_type == 'remove-like'
				request_url = request_url_unlike;
				el_to_show = $('#vacancy_add_like_' + vacancy_id);
			}

			$(this).hide();
			let loader_wait = $('#vacancy_like_loading_' + vacancy_id);
			loader_wait.show();
			$.post( request_url + '?id=' + vacancy_id, [])
				.always(function ( response ) {
					loader_wait.hide();
					if(response.success == true) {
						el_to_show.show();
					}
				});
        });
    });
</script>
<?php $this->endJs(); ?>
