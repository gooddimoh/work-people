<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Vacancy;
use app\models\Category;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;
use app\components\CurrencyConverterHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = Html::encode(Yii::t('category-job', $model->categoryJob->name));
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'My Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

// --
$category_list = Category::getUserSelectList();
$job_list = CategoryJob::getUserMultiSelectList();
$gender_list = Vacancy::getGenderList();
$country_list = Vacancy::getCountryList();
$currency_list = Vacancy::getCurrencyList();
$type_of_working_shift_list = Vacancy::getTypeOfWorkingShiftList();
foreach($type_of_working_shift_list as $key => $value) {
    $type_of_working_shift_list[$key] = Yii::t('vacancy', $value);
}
$documents_provided_list = Vacancy::getDocumentsProvidedList();
foreach($documents_provided_list as $key => $value) {
    $documents_provided_list[$key] = Yii::t('vacancy', $value);
}
$documents_required_list = Vacancy::getDocumentsRequiredList();
foreach($documents_required_list as $key => $value) {
    $documents_required_list[$key] = Yii::t('vacancy', $value);
}
// --
$categories = $model->getCategories()->asArray()->all();
$categories = ArrayHelper::getColumn($categories, 'name');
foreach($categories as &$category) {
	$category = Yii::t('category', $category);
}
$category_names = implode(', ', $categories);

$worker_country_names = Yii::t('vacancy', 'All countries');
$worker_countries = ArrayHelper::getColumn($model->getWorkerCountries(), 'name');
if(!empty($worker_countries)) {
	foreach($worker_countries as $key => $val) {
		$worker_countries[$key] = Yii::t('country', $val);
	}
	
	$worker_country_names = implode(', ', $worker_countries);
}

// get vacancy country name label
$country_name = $model->country_name;
foreach($country_list as $country) {
	if($country['char_code'] == $country_name) {
		$country_name = Yii::t('country', $country['name']);
		break;
	}
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

$vacancy_images = $model->vacancyImages;
$main_image = $model->getImageWebPath();
$country_city_name = '-';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}
?>
<div id="appUpadeVacancy">
<div class="single-edit-buttons" style="margin-bottom:0px;">
	<div class="container">
		<div class="row">
			<div class="col">
                <?php if($model->status == Vacancy::STATUS_SHOW): ?>
					<?= Html::beginForm(['changestatus', 'id' => $model->id], 'post')
						. Html::submitButton(
							Yii::t('vacancy', 'Hide'),
							['class' => 'btn btn-danger']
						)
						. Html::endForm()
					?>
                <?php else: ?>
					<?= Html::beginForm(['changestatus', 'id' => $model->id], 'post')
						. Html::submitButton(
							Yii::t('vacancy', 'Show'),
							['class' => 'btn']
						)
						. Html::endForm()
					?>
                <?php endif; ?>
			</div>
			<div class="col" v-if="!vacancy_info_is_edit_mode || !vacancy_info.edit_mode">
				<button class="btn btn--transparent" v-on:click="vacancyInfoEdit">
					<?= Yii::t('vacancy', 'Edit'); ?>
				</button>
			</div>
			<div class="col" style="width: 25%;" v-if="vacancy_info_is_edit_mode && vacancy_info.edit_mode" v-cloak>
				<button class="btn btn--transparent" v-on:click="vacancyInfoSave">
					<?= Yii::t('vacancy', 'Save'); ?>
				</button>
			</div>
			<div class="col" v-if="vacancy_info_is_edit_mode && vacancy_info.edit_mode" style="width: 25%;" v-cloak>
				<button class="btn btn--transparent" v-on:click="vacancyInfoEditCancel" v-cloak>
					<?= Yii::t('vacancy', 'Cancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- vacancy info view mode -->
<div class="single" v-if="!vacancy_info_is_edit_mode">
	<div class="container">
		<h1>
			<?= Yii::t('vacancy', 'How candidates see the vacancy') ?>
		</h1>
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
							<b><?= $model->prepaid_expense_min ?></b> <?= Yii::t('curr', $model->currency_code) ?> - <b><?= $model->prepaid_expense_max ?></b> <?= Yii::t('curr', $model->currency_code) ?> (<b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_max, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?> - <b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_max, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::t('curr', Yii::$app->params['sourceCurrencyCharCode']) ?>)
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
							<b><?= Yii::$app->formatter->format($model->date_start, 'date') ?> - <?= Yii::$app->formatter->format($model->date_end, 'date') ?></b>
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
                                    <b><?= $model->residence_amount ?></b> <?= $model->residence_amount_currency_code ?> (<b><?= CurrencyConverterHelper::currencyToCurrency($model->residence_amount, $model->residence_amount_currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?> )
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
			<? /*
				<div class="single__gallery-item col">
					<a href="https://www.youtube.com/watch?v=z0mnx8OA5D0" class="single__gallery-img single__gallery-img--video" data-fancybox>
						<img src="/img/single/6.jpg" alt="">
					</a>
				</div>
			*/ ?>
		</div>
	</div>
</div>
<!--/ vacancy info view mode -->

<!-- <div class="row"> -->
<div class="container response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
	<div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
	<ul v-for="(error_messages, field_name) of response_errors">
		<li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
	</ul>

	<ul v-if="submit_clicked && errors.all()">
		<li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
	<ul>
</div>
<!-- </div> -->

<!-- vacancy info edit mode -->
<div class="single" v-if="vacancy_info_is_edit_mode" v-cloak>
	<div class="container">
		<h1>
			<?= Yii::t('vacancy', 'How candidates see the vacancy') ?>
		</h1>
		<div class="single__top">
			<div class="single__top-img">
				<img :src="getMainPhoto()" alt="">
			</div>
			<div class="single__top-text">
				<div class="single__top-date">
					<?= Yii::t('vacancy', 'Vacancy date') ?> <span><?= Yii::$app->formatter->format($model->update_time, 'date') ?></span>
				</div>
				<h1 v-if="!vacancy_info.edit_mode">
					{{vacancy_info.source_data.title}}
					&nbsp;-&nbsp;
					{{vacancy_info.source_data.category_job_item.name}}
				</h1>

				<div v-if="vacancy_info.edit_mode" style="padding-top:14px;">
					<input v-validate="vv.model_vacancy.title" name='Vacancy[title]' class="form-control edit" type="text" v-model="vacancy_info.edit_data.title">
					<span v-show="errors.has('Vacancy[title]')" class="validation-error-message">
						{{ errors.first('Vacancy[title]') }}
					</span>

					<div style="max-width: 500px;">
						<input v-validate="vv.model_vacancy.category_job_id" name='Vacancy[category_job_id]' class="form-control" type="hidden" v-model="vacancy_info.edit_data.category_job_id">
						<multiselect v-model="vacancy_info.edit_data.category_job_item" :options="category_job_list" :multiple="false" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeCategoryJob"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
						<span v-show="errors.has('Vacancy[category_job_id]')" class="validation-error-message" v-cloak>
							{{ errors.first('Vacancy[category_job_id]') }}
						</span>
					</div>
				</div>

			</div>
		</div>
		<div class="row">
			<!-- after edit mode -->
			<div class="single__table col" v-if="!vacancy_info.edit_mode">
				<div class="table">
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= $model->getAttributeLabel('company_name') ?>
						</div>
						<div class="table__td">
							<b>{{vacancy_info.source_data.company_name}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('vacancy', 'Job posting categories') ?>
						</div>
						<div class="table__td">
							<b>{{getCategoryNameLabel(vacancy_info.source_data.categoryVacancies)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('country_name') ?>
						</div>
						<div class="table__td">
                            <b>{{getCountryNameLabel(vacancy_info.source_data.country_name)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('country_city_id') ?>
						</div>
						<div class="table__td">
							<b>{{getCountryCityLabel(vacancy_info.source_data.country_city_id)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'For citizens of next countries') ?>
						</div>
						<div class="table__td">
                            <b>{{getWorkerCountryCodesLabel(vacancy_info.source_data.worker_country_codes)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('gender_list') ?>
						</div>
						<div class="table__td">
							<b>{{getGenderListLabel(vacancy_info.source_data.gender_list)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Salary per hour') ?>
						</div>
						<div class="table__td">
                            <b>{{vacancy_info.source_data.salary_per_hour_min}}</b> {{getCurrencyCodeLabel(vacancy_info.source_data.currency_code)}} - <b>{{vacancy_info.source_data.salary_per_hour_max}}</b> {{getCurrencyCodeLabel(vacancy_info.source_data.currency_code)}} (<b><?= $model->salary_per_hour_min_src ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?> - <b><?= $model->salary_per_hour_max_src ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?>)
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Salary per month from-to') ?>
						</div>
						<div class="table__td">
							<b>{{vacancy_info.source_data.prepaid_expense_min}}</b> {{getCurrencyCodeLabel(vacancy_info.source_data.currency_code)}} - <b>{{vacancy_info.source_data.prepaid_expense_max}}</b> {{getCurrencyCodeLabel(vacancy_info.source_data.currency_code)}} (<b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_min, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?> - <b><?= CurrencyConverterHelper::currencyToCurrency($model->prepaid_expense_max, $model->currency_code, Yii::$app->params['sourceCurrencyCharCode']) ?></b> <?= Yii::$app->params['sourceCurrencyCharCode'] ?>)
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Number of hours from-to') ?>
						</div>
						<div class="table__td">
							<b>{{vacancy_info.source_data.hours_per_day_min}} - {{vacancy_info.source_data.hours_per_day_max}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Number of days from-to (per week)') ?>
						</div>
						<div class="table__td">
							<b>{{vacancy_info.source_data.days_per_week_min}} - {{vacancy_info.source_data.days_per_week_max}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Arrival date') ?>
						</div>
						<div class="table__td">
							<b>{{vacancy_info.source_data.date_start}} - {{vacancy_info.source_data.date_end}}</b>
							&nbsp;
							<span v-if="vacancy_info.source_data.date_free == <?= Vacancy::DATE_FREE_YES ?>">
								(<?= Yii::t('vacancy', 'free') ?>)
							</span>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Shifts') ?>
						</div>
						<div class="table__td">
							<b>{{getTypeOfWorkingShiftLabel(vacancy_info.source_data.type_of_working_shift)}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Residence') ?>
						</div>
						<div class="table__td table__td--multiple" v-if="vacancy_info.source_data.residence_provided == <?= Vacancy::RESIDENCE_PROVIDED_YES ?>">
							<div>
								<b><?= Yii::t('main', 'Yes') ?></b>
							</div>
							<div>
								<b>{{vacancy_info.source_data.residence_amount}}</b> {{getCurrencyCodeLabel(vacancy_info.source_data.residence_amount_currency_code)}} (<b>000</b> ГРН)
							</div>
							<div>
								<b>{{vacancy_info.source_data.residence_people_per_room}}</b> <?= Yii::t('vacancy', 'peoples per the room') ?>
							</div>
						</div>
						<div class="table__td table__td--multiple" v-if="vacancy_info.source_data.residence_provided != <?= Vacancy::RESIDENCE_PROVIDED_YES ?>">
							<div>
								<b><?= Yii::t('main', 'No') ?></b>
							</div>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= Yii::t('vacancy', 'Documents from the employee') ?>
                            <!-- <?= $model->getAttributeLabel('documents_required') ?> -->
						</div>
						<div class="table__td">
							{{getDocumentsRequiredLabel(vacancy_info.source_data.documents_required)}}
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('documents_provided') ?>
						</div>
						<div class="table__td">
							{{getDocumentsProvidedLabel(vacancy_info.source_data.documents_provided)}}
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
							<?= $model->getAttributeLabel('job_description') ?>
						</div>
						<div class="table__td table__td--multiple">
							<div>
								<textarea wrap="hard" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="edit-info__textarea" v-model="vacancy_info.source_data.job_description"></textarea>
							</div>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_name') ?>
						</div>
						<div class="table__td">
                            <b>{{vacancy_info.source_data.contact_name}}</b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('contact_phone') ?>
						</div>
						<div class="table__td">
                            <b>{{vacancy_info.source_data.contact_phone}}</b>
						</div>
					</div>
				</div>
				<div class="single__table-title">
					<?= Yii::t('vacancy', 'Bonuses') ?>
				</div>
				<div class="table">
					<div class="table__tr">
						<div class="table__td">
							<textarea wrap="hard" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="edit-info__textarea" v-model="vacancy_info.source_data.job_description_bonus"></textarea>
						</div>
					</div>
				</div>
			</div>
			<!--/ after edit mode -->
			<!-- vacancy info edit mode -->
			<div class="single__table col" v-if="vacancy_info.edit_mode">
				<?php $form = ActiveForm::begin([
					'options' => [
						'class' => 'edit-info',
						'v-on:submit.prevent' => "onSubmit",
						'ref' => 'form'
					],
				]); ?>
					<div class="table">
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('company_name') ?>
							</div>
							<div class="table__td">
								<div class="edit-info__input">
									<input v-validate="vv.model_vacancy.company_name" name='Vacancy[company_name]' class="form-control edit" type="text" v-model="vacancy_info.edit_data.company_name">
                                    <span v-show="errors.has('Vacancy[company_name]')" class="validation-error-message">
                                        {{ errors.first('Vacancy[company_name]') }}
                                    </span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Job posting categories') ?>
							</div>
							<div class="table__td">
								<input v-validate="vv.model_vacancy.selected_categories" name='Vacancy[category_list]' type="hidden" v-model="vacancy_info.edit_data.selected_categories">
								<span v-show="errors.has('Vacancy[category_list]')" class="validation-error-message" v-cloak>
									{{ errors.first('Vacancy[category_list]') }}
								</span>

								<div class="registration__row">
									<label v-for="category_item in category_list" class="checkbox" v-cloak>
										<input type="checkbox" v-model="category_item.checked" v-on:change="selectCategory">
										<span class="checkbox__check"></span>
										<span class="checkbox__title">
											{{category_item.name}}
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('country_name') ?>
							</div>
							<div class="table__td">
								<div class="edit-info__input">
									<input v-validate="vv.model_vacancy.country_name" name='Vacancy[country_name]' type="hidden" v-model="vacancy_info.edit_data.country_name">
									<nice-select :options="country_list" name="Vacancy[country_name]`" class="select select--double" v-model="vacancy_info.edit_data.country_name" v-on:input="countryChanged">
										<option v-for="item in country_list" :value="item.char_code" >
											{{ item.name }}
										</option>
									</nice-select>
									<span v-show="errors.has('Vacancy[country_name]')" class="validation-error-message">
										{{ errors.first('Vacancy[country_name]') }}
									</span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('country_city_id') ?>
							</div>
							<div class="table__td">
								<div class="edit-info__input">
									<input v-validate="vv.model_vacancy.country_city_id" name='Vacancy[country_city_id]' class="form-control edit" type="hidden" v-model="vacancy_info.edit_data.country_city_id">
									<nice-select :options="country_city_list" name="Vacancy[country_city_id]`" class="select" v-model="vacancy_info.edit_data.country_city_id" v-if="country_city_refresh_flag">
										<option v-for="item in country_city_list" :value="item.id">
											{{ item.city_name }}
										</option>
									</nice-select>
									<span v-show="errors.has('Vacancy[country_city_id]')" class="validation-error-message">
										{{ errors.first('Vacancy[country_city_id]') }}
									</span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'For citizens of next countries') ?>
							</div>
							<div class="table__td">
								<input v-validate="vv.model_vacancy.worker_country_codes" name='Vacancy[worker_country_codes]' type="hidden" v-model="vacancy_info.edit_data.worker_country_codes">
								<span v-show="errors.has('Vacancy[worker_country_codes]')" class="validation-error-message" v-cloak>
									{{ errors.first('Vacancy[worker_country_codes]') }}
								</span>

								<div class="registration__row">
									<label class="checkbox">
										<input type="checkbox" v-model="vacancy_info.edit_data.worker_country_codes_all">
										<span class="checkbox__check"></span>
										<span class="checkbox__title">
											<?= Yii::t('vacancy', 'All countries') ?>
										</span>
									</label>
								</div>
								
								<div class="registration__row" v-if="!vacancy_info.edit_data.worker_country_codes_all">
									<label v-for="country_item in country_list" class="checkbox" v-cloak>
										<input type="checkbox" v-model="country_item.checked" v-on:change="vacancyCountryCodesChange">
										<span class="checkbox__check"></span>
										<span class="checkbox__title">
											{{country_item.name}}
										</span>
									</label>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('gender_list') ?>
							</div>
							<div class="table__td">
								<div class="registration__row">
									<label v-for="gender_item in gender_list" class="checkbox" v-cloak>
										<input type="checkbox" v-model="gender_item.checked" v-on:change="genderChange">
										<span class="checkbox__check"></span>
										<span class="checkbox__title">
											{{gender_item.name}}
										</span>
									</label>
								</div>

								<input v-validate="vv.model_vacancy.gender_list" name='Vacancy[gender_list]' type="hidden" v-model="vacancy_info.edit_data.gender_list">
								<span v-show="errors.has('Vacancy[gender_list]')" class="validation-error-message" v-cloak>
									{{ errors.first('Vacancy[gender_list]') }}
								</span>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Salary per hour') ?>
							</div>
							<div class="table__td">
								<div class="input-group">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.salary_per_hour_min" name='Vacancy[salary_per_hour_min]' class="form-control edit" type="text" placeholder="<?= $model->getAttributeLabel('salary_per_hour_min') ?>" v-model="vacancy_info.edit_data.salary_per_hour_min">
                                        <span v-show="errors.has('Vacancy[salary_per_hour_min]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[salary_per_hour_min]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.salary_per_hour_max" name='Vacancy[salary_per_hour_max]' class="form-control edit" type="text" placeholder="<?= $model->getAttributeLabel('salary_per_hour_max') ?>" v-model="vacancy_info.edit_data.salary_per_hour_max">
                                        <span v-show="errors.has('Vacancy[salary_per_hour_max]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[salary_per_hour_max]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <nice-select :options="currency_list" name="Vacancy[currency_code]`" class="select" v-model="vacancy_info.edit_data.currency_code">
                                            <option v-for="item in currency_list" :value="item.char_code" >
                                                {{ item.name }}
                                            </option>
                                        </nice-select>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Salary per month from-to') ?>
							</div>
							<div class="table__td">
								<div class="input-group salary" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.prepaid_expense_min" name='Vacancy[prepaid_expense_min]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'from')?>" v-model="vacancy_info.edit_data.prepaid_expense_min">
                                        <!-- <div class="salary__grn">- {{getCurrencyCodeLabel(vacancy_info.edit_data.currency_code)}}</div> -->
                                        <span v-show="errors.has('Vacancy[prepaid_expense_min]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[prepaid_expense_min]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.prepaid_expense_max" name='Vacancy[prepaid_expense_max]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'to')?>" v-model="vacancy_info.edit_data.prepaid_expense_max">
                                        <!-- <div class="salary__grn">- {{getCurrencyCodeLabel(vacancy_info.edit_data.currency_code)}}</div> -->
                                        <span v-show="errors.has('Vacancy[prepaid_expense_max]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[prepaid_expense_max]') }}
                                        </span>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Number of hours from-to') ?>
							</div>
							<div class="table__td">
								<div class="input-group" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.hours_per_day_min" name='Vacancy[hours_per_day_min]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'from')?>" v-model="vacancy_info.edit_data.hours_per_day_min">
                                        <span v-show="errors.has('Vacancy[hours_per_day_min]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[hours_per_day_min]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.hours_per_day_max" name='Vacancy[hours_per_day_max]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'to')?>" v-model="vacancy_info.edit_data.hours_per_day_max">
                                        <span v-show="errors.has('Vacancy[hours_per_day_max]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[hours_per_day_max]') }}
                                        </span>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Number of days from-to (per week)') ?>
							</div>
							<div class="table__td">
								<div class="input-group" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.days_per_week_min" name='Vacancy[days_per_week_min]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'from')?>" v-model="vacancy_info.edit_data.days_per_week_min">
                                        <span v-show="errors.has('Vacancy[days_per_week_min]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[days_per_week_min]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.days_per_week_max" name='Vacancy[days_per_week_max]' class="form-control edit" type="text" placeholder="<?= Yii::t('vacancy', 'to')?>" v-model="vacancy_info.edit_data.days_per_week_max">
                                        <span v-show="errors.has('Vacancy[days_per_week_max]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[days_per_week_max]') }}
                                        </span>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Arrival date') ?>
							</div>
							<div class="table__td">
								<label class="checkbox" style="margin-top: 15px;">
                                    <input type="hidden" name="Vacancy[date_free]`" v-bind:value="getDateFreeValue(vacancy_info.edit_data.date_free)">
                                    <input type="checkbox" v-model="vacancy_info.edit_data.date_free">
                                    <span class="checkbox__check"></span>
                                    <span class="checkbox__title">
                                        <?= $model->getAttributeLabel('date_free') ?>
                                    </span>
                                </label>

                                <div class="input-group" style="max-width: 290px;margin-bottom: 15px;">
                                    <div class="input-group__col">
                                        <air-datepicker v-validate="vv.model_vacancy.date_start" name='Vacancy[date_start]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="vacancy_info.edit_data.date_start" class="j-edit-input edit"></air-datepicker>
                                        <span v-show="errors.has('Vacancy[date_start]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[date_start]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <air-datepicker v-validate="vv.model_vacancy.date_end" name='Vacancy[date_end]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="vacancy_info.edit_data.date_end" class="j-edit-input edit"></air-datepicker>
                                        <span v-show="errors.has('Vacancy[date_end]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[date_end]') }}
                                        </span>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Shifts') ?>
							</div>
							<div class="table__td">
								<input v-validate="vv.model_vacancy.type_of_working_shift" name='Vacancy[type_of_working_shift]' type="hidden" v-model="vacancy_info.edit_data.type_of_working_shift">
                                <span v-show="errors.has('Vacancy[type_of_working_shift]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[type_of_working_shift]') }}
                                </span>

                                <div class="registration__row">
                                
                                    <label v-for="type_of_working_shift_item in type_of_working_shift_list" class="checkbox" v-cloak>
                                        <input type="checkbox" v-model="type_of_working_shift_item.checked" v-on:change="vacancyTypeOfWorkingShiftChange">
                                        <span class="checkbox__check"></span>
                                        <span class="checkbox__title">
                                            {{type_of_working_shift_item.name}}
                                        </span>
                                    </label>
                                </div>
							</div>
						</div>
						<?php /*
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Experience') ?>
							</div>
							<div class="table__td">
								<!-- ??? -->
								<b><?= Yii::t('vacancy', 'without experience') ?></b>
							</div>
						</div>
						*/ ?>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Residence') ?>
							</div>
							<div class="table__td">
								<div class="registration__prop">
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[residence_provided]" class="btn" value="<?= Vacancy::RESIDENCE_PROVIDED_YES ?>" v-model="vacancy_info.edit_data.residence_provided">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('vacancy', 'Provided') ?>
                                        </span>
                                    </label>
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[residence_provided]" class="btn" value="<?= Vacancy::RESIDENCE_PROVIDED_NO ?>" v-model="vacancy_info.edit_data.residence_provided">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('vacancy', 'Not provided') ?>
                                        </span>
                                    </label>
                                    <div v-if="vacancy_info.edit_data.residence_provided == <?= Vacancy::RESIDENCE_PROVIDED_YES ?>" class="registration__price-month j-block-prop" style="display: block;">
                                        <div class="registration__input-bl">
                                            <div class="registration__input-title">
                                                <?= $model->getAttributeLabel('residence_amount') ?>
                                            </div>
                                            <div class="input-group" style="max-width: 250px;">
                                                <div class="input-group__col">
                                                    <input v-validate="vv.model_vacancy.residence_amount" name='Vacancy[residence_amount]' class="form-control edit" type="text" v-model="vacancy_info.edit_data.residence_amount">
                                                    <span v-show="errors.has('Vacancy[residence_amount]')" class="validation-error-message">
                                                        {{ errors.first('Vacancy[residence_amount]') }}
                                                    </span>
                                                </div>
                                                <div class="input-group__col" style="width: 100px;flex-shrink: 0;">
                                                    <nice-select :options="currency_list" name="Vacancy[residence_amount_currency_code]`" class="select" v-model="vacancy_info.edit_data.residence_amount_currency_code">
                                                        <option v-for="item in currency_list" :value="item.char_code" >
                                                            {{ item.name }}
                                                        </option>
                                                    </nice-select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="registration__input-bl">
                                            <div class="registration__input-title">
                                                <?= $model->getAttributeLabel('residence_people_per_room') ?>
                                            </div>
                                            <input v-validate="vv.model_vacancy.residence_people_per_room" name='Vacancy[residence_people_per_room]' class="form-control edit" type="text" style="width: 100px;" v-model="vacancy_info.edit_data.residence_people_per_room">
                                            <span v-show="errors.has('Vacancy[residence_people_per_room]')" class="validation-error-message">
                                                {{ errors.first('Vacancy[residence_people_per_room]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= Yii::t('vacancy', 'Documents from the employee') ?>
								<!-- <?= $model->getAttributeLabel('documents_required') ?> -->
							</div>
							<div class="table__td">
								<div class="registration__row">
                                    <label v-for="documents_required_item in documents_required_list" class="checkbox" v-cloak>
                                        <input type="checkbox" v-model="documents_required_item.checked" v-on:change="vacancyDocumentsRequiredChange">
                                        <span class="checkbox__check"></span>
                                        <span class="checkbox__title">
                                            {{documents_required_item.name}}
                                        </span>
                                    </label>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('documents_provided') ?>
							</div>
							<div class="table__td">
								<div class="registration__row">
                                    <label v-for="documents_provided_item in documents_provided_list" class="checkbox" v-cloak>
                                        <input type="checkbox" v-model="documents_provided_item.checked" v-on:change="vacancyDocumentsProvidedChange">
                                        <span class="checkbox__check"></span>
                                        <span class="checkbox__title">
                                            {{documents_provided_item.name}}
                                        </span>
                                    </label>
                                </div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('job_description') ?>
							</div>
							<div class="table__td">
								<div class="registration__row">
									<textarea v-validate="vv.model_vacancy.job_description" name='Vacancy[job_description]' cols="30" rows="10" style="width: 100%;" v-model="vacancy_info.edit_data.job_description"></textarea>
									<span v-show="errors.has('Vacancy[job_description]')" class="validation-error-message" v-cloak>
										{{ errors.first('Vacancy[job_description]') }}
									</span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('contact_name') ?>
							</div>
							<div class="table__td">
								<div class="registration__row">
									<input v-validate="vv.model_vacancy.contact_name" name='Vacancy[contact_name]' class="form-control edit" type="text" v-model="vacancy_info.edit_data.contact_name">
									<span v-show="errors.has('Vacancy[contact_name]')" class="validation-error-message">
										{{ errors.first('Vacancy[contact_name]') }}
									</span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
								<?= $model->getAttributeLabel('contact_name') ?>
							</div>
							<div class="table__td">
								<div class="registration__row">
									<div v-for="(contact_phone_item, index) of vacancy_info.edit_data.contact_phones" style="width:100%">
										<div class="reg-lang__top" v-if="index != 0">
											<div class="reg-lang__title">
											</div>
											<div class="reg-lang__edit">
												<a class="pointer" v-on:click="removePhone(index)"><?= Yii::t('main', 'Remove') ?></a>
											</div>
										</div>
										<input v-validate="vv.model_vacancy.contact_phones" v-bind:name='`Vacancy[contact_phones][${index}]`' class="edit" type="text" v-model="vacancy_info.edit_data.contact_phones[index]" v-on:change="changePhone">
										<span v-show="errors.has('Vacancy[contact_phones][' + index + ']')" class="validation-error-message">
											{{ errors.first('Vacancy[contact_phones][' + index + ']') }}
										</span>
									</div>
									<input v-validate="vv.model_vacancy.contact_phone_limit" name='Vacancy[contact_phone]' type="hidden" v-model="vacancy_info.edit_data.contact_phone">
									<span v-show="errors.has('Vacancy[contact_phone]')" class="validation-error-message">
										{{ errors.first('Vacancy[contact_phone]') }}
									</span>
									<div class="registration__add-email" v-on:click="addPhone">
										<?= Yii::t('vacancy', 'Add an alternate phone') ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="single__table-title">
						<?= Yii::t('vacancy', 'Bonuses') ?>
					</div>
					<div class="table">
						<div class="table__tr">
							<div class="table__td">
								<textarea v-validate="vv.model_vacancy.job_description_bonus" name='Vacancy[job_description_bonus]' cols="30" rows="10" style="width: 100%;" v-model="vacancy_info.edit_data.job_description_bonus"></textarea>
								<span v-show="errors.has('Vacancy[job_description_bonus]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[job_description_bonus]') }}
                                </span>
							</div>
						</div>
					</div>
					<div class="single__table-title">
						<?= Yii::t('vacancy', 'Applications for this work will be sent to the following email addresses') ?>
					</div>
					<div class="table">
						<div class="table__tr">
							<div class="table__td">
								<input v-validate="vv.model_vacancy.contact_email_limit" name='Vacancy[contact_email_list]' class="form-control" type="hidden" v-model="vacancy_info.edit_data.contact_email_list">
                                <span v-show="errors.has('Vacancy[contact_email_list]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[contact_email_list]') }}
                                </span>
                                <span v-for="(email_item, index) of vacancy_info.edit_data.contact_emails">
                                    <div class="reg-lang__top">
                                        <div class="reg-lang__title">
                                        </div>
                                        <div class="reg-lang__edit">
                                            <a class="pointer" v-on:click="removeEmail(index)"><?= Yii::t('main', 'Remove') ?></a>
                                        </div>
                                    </div>
                                    <input v-validate="vv.model_vacancy.contact_email_list" v-bind:name='`Vacancy[contact_emails][${index}]`' class="form-control edit" type="text" v-model="vacancy_info.edit_data.contact_emails[index]" v-on:change="changeEmail">
                                    <span v-show="errors.has('Vacancy[contact_emails][' + index + ']')" class="validation-error-message">
                                        {{ errors.first('Vacancy[contact_emails][' + index + ']') }}
                                    </span>
                                </span>
                                <div class="registration__add-email" v-on:click="addEmail">
                                    <?= Yii::t('vacancy', 'Add an alternate email address') ?>
                                </div>
							</div>
						</div>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
			<!--/ vacancy info edit mode -->

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
		<!-- vacancy info view mode -->
		<div class="single__gallery row" slick-slider data-tablet="4" data-tablet-v="3" data-mobile="2" data-mobile-v="1" v-if="!vacancy_info.edit_mode">
			<div v-for="(image_item, index) of vacancy_info.source_data.vacancyImages" class="single__gallery-item col">
				<a :href="image_item.photo_src" class="single__gallery-img" :alt="image_item.name" data-fancybox>
					<img :src="image_item.photo_src" alt="">
				</a>
			</div>
			<!-- <div class="single__gallery-item col">
				<a href="https://www.youtube.com/watch?v=z0mnx8OA5D0" class="single__gallery-img single__gallery-img--video" data-fancybox>
					<img src="/img/single/6.jpg" alt="">
				</a>
			</div> -->
		</div>
		<!--/ vacancy info view mode -->
		<!-- vacancy info edit mode -->
		<div class="single__gallery row" slick-slider data-tablet="4" data-tablet-v="3" data-mobile="2" data-mobile-v="1" v-if="vacancy_info.edit_mode">
			<div v-for="(image_item, index) of vacancy_info.edit_data.vacancyImages" class="upload__item col">
				<a class="single__gallery-img" :alt="image_item.name" v-on:click="removePhoto(index)">
					<img :src="image_item.photo_src" alt="">
				</a>
			</div>
		</div>
		<input type="file" id="photos" name="photos" ref="photos" style="display: none;" v-on:change="addPhoto" multiple>
		<label class="upload" for="photos" v-if="vacancy_info.edit_mode">
			<div class="btn btn--transparent">
				<?= Yii::t('main', 'Upload') ?>
			</div>
		</label>
		<div class="validation-error-message" v-if="file_format_error && vacancy_info.edit_mode"><?= Yii::t('vacancy', 'Invalid file format') ?></div>
		<!--/ vacancy info edit mode -->
	</div>
</div>
<!--/ vacancy info edit mode -->

<div class="single-edit-buttons">
	<div class="container">
		<div class="row">
			<div class="col">
                <?php if($model->status == Vacancy::STATUS_SHOW): ?>
					<?= Html::beginForm(['changestatus', 'id' => $model->id], 'post')
						. Html::submitButton(
							Yii::t('vacancy', 'Hide'),
							['class' => 'btn btn-danger']
						)
						. Html::endForm()
					?>
                <?php else: ?>
					<?= Html::beginForm(['changestatus', 'id' => $model->id], 'post')
						. Html::submitButton(
							Yii::t('vacancy', 'Show'),
							['class' => 'btn']
						)
						. Html::endForm()
					?>
                <?php endif; ?>
			</div>
			<div class="col" v-if="!vacancy_info_is_edit_mode || !vacancy_info.edit_mode">
				<button class="btn btn--transparent" v-on:click="vacancyInfoEdit">
					<?= Yii::t('vacancy', 'Edit'); ?>
				</button>
			</div>
			<div class="col" style="width: 25%;" v-if="vacancy_info_is_edit_mode && vacancy_info.edit_mode" v-cloak>
				<button class="btn btn--transparent" v-on:click="vacancyInfoSave">
					<?= Yii::t('vacancy', 'Save'); ?>
				</button>
			</div>
			<div class="col" v-if="vacancy_info_is_edit_mode && vacancy_info.edit_mode" style="width: 25%;" v-cloak>
				<button class="btn btn--transparent" v-on:click="vacancyInfoEditCancel">
					<?= Yii::t('vacancy', 'Cancel'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

</div>

<?php echo $this->render('subscribe') ?>

<?php $this->beginJs(); ?>
<script>
var CATEGORIES_MAX_SELECTED_LIMIT = 5;
Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'validation-error-input',
    },
    events: 'change|blur|keyup'
});

VeeValidate.Validator.extend('vacancy_selected_categories_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'You must fill out the «Categories of job posting».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('vacancy_contact_email_list_item_required', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'You must fill in the «email address».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});
VeeValidate.Validator.extend('app_models_Vacancy_contact_email_list_yii_validators_EmailValidator', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'The value «Applications for this work will be sent to the following email addresses" is not a valid email address».') ?>';
    },
    validate: function(value) {
        return /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/.test(value);
    }
});

VeeValidate.Validator.extend('vacancy_contact_email_list_item_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'The aggregate value of «Applications for this work will be sent to the following email addresses» must contain a maximum of 255 characters.') ?>';
    },
    validate: function(value) {
        return value.trim().length < 255;
    }
});

VeeValidate.Validator.extend('vacancy_contact_phones_item_required', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'You must fill in the «Contact Phone».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('vacancy_contact_phones_item_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'The aggregate value of «Contact Phone» must contain a maximum of 255 characters.') ?>';
    },
    validate: function(value) {
        return value.trim().length < 255;
    }
});

VeeValidate.Validator.extend('vacancy_selected_categories_validator_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', '«Categories of job posting», - you can select up to 5 categories.') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length <= CATEGORIES_MAX_SELECTED_LIMIT;
    }
});

new Vue({
  el: '#appUpadeVacancy',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
    lang_list: [
    ],
    level_list: [
    ],
    category_list: [
        <?php foreach( $category_list as $category): ?>
        {
            id: '<?= $category->id ?>',
            name: '<?= Yii::t('category', $category->name) ?>',
            // img: '<?= $category->getImage() ?>',
            // ads_count: '<?= $category->getAdsCount() ?>',
            checked: false // for stilization
        },
        <?php endforeach; ?>
    ],
    gender_list: [
        <?php foreach( $gender_list as $key_id => $gender): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $gender ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    country_list: [
        <?php foreach( $country_list as $country): ?>
        {
            char_code: '<?= $country['char_code'] ?>',
            name: '<?= Yii::t('country', $country['name']) ?>',
            checked: false
        },
        <?php endforeach; ?>
	],
	country_city_list: [],
    currency_list: [
        <?php foreach( $currency_list as $currency): ?>
        {
            char_code: '<?= $currency['char_code'] ?>',
            name: '<?= Yii::t('curr', $currency['name']) ?>',
        },
        <?php endforeach; ?>
    ],
    type_of_working_shift_list: [
        <?php foreach( $type_of_working_shift_list as $key_id => $type_of_working_shift): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $type_of_working_shift ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    documents_provided_list: [
        <?php foreach( $documents_provided_list as $key_id => $documents_provided): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $documents_provided ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    documents_required_list: [
        <?php foreach( $documents_required_list as $key_id => $documents_required): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $documents_required ?>',
            checked: false
        },
        <?php endforeach; ?>
	],
	category_job_list: [
        <?php foreach($job_list as $category_job_item): ?>
            {
                group_name: <?= json_encode(Yii::t('category-job', $category_job_item['group_name'])) ?>,
                jobs: [
                    <?php foreach($category_job_item['jobs'] as $job_item): ?>
                    {
                        id: '<?= $job_item['id'] ?>',
                        name: <?= json_encode(Yii::t('category-job', $job_item['name'])) ?>,
                        parent_id: '<?= $job_item['parent_id'] ?>',
                    },
                    <?php endforeach; ?>
                ]
            },
        <?php endforeach; ?>
    ],
    //-------
	vacancy_info_is_edit_mode: false,
	file_format_error: false,
	response_errors: void 0,
	submit_clicked: false,
	country_char_code_last: '',
	country_city_refresh_flag: true,
    vacancy_info: {
		edit_mode: false,
		source_data: {
			id: '<?= $model->id ?>',
			status: '<?= Html::encode($model->status) ?>',
			title: '<?= Html::encode($model->title) ?>',
			company_name: '<?= Html::encode($model->company_name) ?>',
			category_job_id: '<?= Html::encode($model->category_job_id) ?>',
			gender_list: '<?= Html::encode($model->gender_list) ?>',
			age_min: '<?= Html::encode($model->age_min) ?>',
			age_max: '<?= Html::encode($model->age_max) ?>',
			worker_country_codes: '<?= Html::encode($model->worker_country_codes) ?>',
			free_places: '<?= Html::encode($model->free_places) ?>',
			regular_places: <?= Vacancy::REGULAR_PLACES_YES ?> == <?= $model->regular_places ?>,
			date_start: '<?= Yii::$app->formatter->format($model->date_start, 'date') ?>', //!
			date_end: '<?= Yii::$app->formatter->format($model->date_end, 'date') ?>', //!
			date_free: <?= Vacancy::DATE_FREE_YES ?> == <?= $model->date_free ?>,
			country_name: '<?= Html::encode($model->country_name) ?>',
			salary_per_hour_min: '<?= Html::encode($model->salary_per_hour_min) ?>',
			salary_per_hour_max: '<?= Html::encode($model->salary_per_hour_max) ?>',
			salary_per_hour_min_src: '<?= Html::encode($model->salary_per_hour_min_src) ?>',
			salary_per_hour_max_src: '<?= Html::encode($model->salary_per_hour_max_src) ?>',
			currency_code: '<?= Html::encode($model->currency_code) ?>',
			hours_per_day_min: '<?= Html::encode($model->hours_per_day_min) ?>',
			hours_per_day_max: '<?= Html::encode($model->hours_per_day_max) ?>',
			days_per_week_min: '<?= Html::encode($model->days_per_week_min) ?>',
			days_per_week_max: '<?= Html::encode($model->days_per_week_max) ?>',
			prepaid_expense_min: '<?= Html::encode($model->prepaid_expense_min) ?>',
			prepaid_expense_max: '<?= Html::encode($model->prepaid_expense_max) ?>',
			type_of_working_shift: '<?= Html::encode($model->type_of_working_shift) ?>',
			residence_provided: '<?= Html::encode($model->residence_provided) ?>',
			residence_amount: '<?= Html::encode($model->residence_amount) ?>',
			residence_amount_currency_code: '<?= Html::encode($model->residence_amount_currency_code) ?>',
			residence_people_per_room: '<?= Html::encode($model->residence_people_per_room) ?>',
			documents_provided: '<?= Html::encode($model->documents_provided) ?>',
			documents_required: '<?= Html::encode($model->documents_required) ?>',
			job_description: <?= json_encode(Html::encode($model->job_description)) ?>,
			job_description_bonus: <?= json_encode(Html::encode($model->job_description_bonus)) ?>,
			contact_name: '<?= Html::encode($model->contact_name) ?>',
        	contact_phone: '<?= Html::encode($model->contact_phone) ?>',
			contact_email_list: '<?= Html::encode($model->contact_email_list) ?>',
			main_image: '<?= Html::encode($model->main_image) ?>',
			agency_accept: '<?= Html::encode($model->agency_accept) ?>',
			agency_paid_document: <?= Vacancy::AGENCY_PAID_DOCUMENT_YES ?> == <?= $model->agency_paid_document ?>,
			agency_paid_document_price: '<?= Html::encode($model->agency_paid_document_price) ?>',
			agency_paid_document_currency_code: '<?= Html::encode($model->agency_paid_document_currency_code) ?>',
			agency_free_document: <?= Vacancy::AGENCY_FREE_DOCUMENT_YES ?> == <?= $model->agency_free_document ?>,
			agency_pay_commission: <?= Vacancy::AGENCY_PAY_COMMISSION_YES ?> == <?= $model->agency_pay_commission ?>,
			agency_pay_commission_amount: '<?= Html::encode($model->agency_pay_commission_amount) ?>',
			agency_pay_commission_currency_code: '<?= Html::encode($model->agency_pay_commission_currency_code) ?>',
			secure_deal: '<?= Html::encode($model->secure_deal) ?>',
			country_city_id: '<?= Html::encode($model->country_city_id) ?>',
			// -- relations:
			categoryVacancies: [
				<?php foreach($model->categoryVacancies as $category) {
					echo '"' .$category->category_id . '"' . ',';
				} ?>
			],
			vacancyImages: [
				<?php foreach($vacancy_images as $image): ?>
				{
					id: <?= $image->id ?>,
					name: '<?= $image->name ?>',
					path_name: '<?= Html::encode($image->path_name) ?>',
					photo_src: '<?= Html::encode($image->getImageWebPath()); ?>',
				},
				<?php endforeach; ?>
			],
			category_job_item: null,
			worker_country_codes_all: <?= $model->worker_country_codes == null ? 'true' : 'false' ?>,
		},
		edit_data: {
			title: '',
			status: '',
			company_name: '',
			category_job_id: '',
			gender_list: '',
			age_min: '',
			age_max: '',
			worker_country_codes: '',
			free_places: '',
			regular_places: '',
			date_start: '',
			date_end: '',
			date_free: '',
			country_name: '',
			city_name: '',
			salary_per_hour_min: '',
			salary_per_hour_max: '',
			salary_per_hour_min_src: '',
			salary_per_hour_max_src: '',
			currency_code: '',
			hours_per_day_min: '',
			hours_per_day_max: '',
			days_per_week_min: '',
			days_per_week_max: '',
			prepaid_expense_min: '',
			prepaid_expense_max: '',
			type_of_working_shift: '',
			residence_provided: '',
			residence_amount: '',
			residence_amount_currency_code: '',
			residence_people_per_room: '',
			documents_provided: '',
			documents_required: '',
			job_description: '',
			job_description_bonus: '',
			contact_name: '',
        	contact_phone: '',
			contact_email_list: '',
			main_image: '',
			agency_accept: '',
			agency_paid_document: '',
			agency_paid_document_price: '',
			agency_paid_document_currency_code: '',
			agency_free_document: '',
			agency_pay_commission: '',
			agency_pay_commission_amount: '',
			agency_pay_commission_currency_code: '',
			secure_deal: '',
			country_city_id: '',
			// -- edit data components v-models
			selected_categories: [], // need for validation
			contact_emails: [],
			contact_phones: [],
			category_job_item: null,
			worker_country_codes_all: true,
		},
    },
    vv: {
        model_vacancy: {
            selected_categories: 'vacancy_selected_categories_validator_required|required|vacancy_selected_categories_validator_limit',
            category_job_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'category_job_id') ?>',
			title: '<?= VeeValidateHelper::getVValidateString($this, $model, 'title') ?>',
            company_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_name') ?>',
            gender_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'gender_list') ?>',
            age_min: '<?= VeeValidateHelper::getVValidateString($this, $model, 'age_min') ?>',
            age_max: '<?= VeeValidateHelper::getVValidateString($this, $model, 'age_max') ?>',
            worker_country_codes: '<?= VeeValidateHelper::getVValidateString($this, $model, 'worker_country_codes') ?>',
            free_places: '<?= VeeValidateHelper::getVValidateString($this, $model, 'free_places') ?>',
            regular_places: '<?= VeeValidateHelper::getVValidateString($this, $model, 'regular_places') ?>',
            date_start: '<?= VeeValidateHelper::getVValidateString($this, $model, 'date_start') ?>',
            date_end: '<?= VeeValidateHelper::getVValidateString($this, $model, 'date_end') ?>',
            date_free: '<?= VeeValidateHelper::getVValidateString($this, $model, 'date_free') ?>',
            country_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_name') ?>',
            city_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'city_name') ?>',
            salary_per_hour_min: '<?= VeeValidateHelper::getVValidateString($this, $model, 'salary_per_hour_min') ?>',
            salary_per_hour_max: '<?= VeeValidateHelper::getVValidateString($this, $model, 'salary_per_hour_max') ?>',
            salary_per_hour_min_src: '<?= VeeValidateHelper::getVValidateString($this, $model, 'salary_per_hour_min_src') ?>',
            salary_per_hour_max_src: '<?= VeeValidateHelper::getVValidateString($this, $model, 'salary_per_hour_max_src') ?>',
            currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'currency_code') ?>',
            hours_per_day_min: '<?= VeeValidateHelper::getVValidateString($this, $model, 'hours_per_day_min') ?>',
            hours_per_day_max: '<?= VeeValidateHelper::getVValidateString($this, $model, 'hours_per_day_max') ?>',
            days_per_week_min: '<?= VeeValidateHelper::getVValidateString($this, $model, 'days_per_week_min') ?>',
            days_per_week_max: '<?= VeeValidateHelper::getVValidateString($this, $model, 'days_per_week_max') ?>',
            prepaid_expense_min: '<?= VeeValidateHelper::getVValidateString($this, $model, 'prepaid_expense_min') ?>',
            prepaid_expense_max: '<?= VeeValidateHelper::getVValidateString($this, $model, 'prepaid_expense_max') ?>',
            type_of_working_shift: '<?= VeeValidateHelper::getVValidateString($this, $model, 'type_of_working_shift') ?>',
            residence_provided: '<?= VeeValidateHelper::getVValidateString($this, $model, 'residence_provided') ?>',
            residence_amount: '<?= VeeValidateHelper::getVValidateString($this, $model, 'residence_amount') ?>',
            residence_amount_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'residence_amount_currency_code') ?>',
            residence_people_per_room: '<?= VeeValidateHelper::getVValidateString($this, $model, 'residence_people_per_room') ?>',
            documents_provided: '<?= VeeValidateHelper::getVValidateString($this, $model, 'documents_provided') ?>',
            documents_required: '<?= VeeValidateHelper::getVValidateString($this, $model, 'documents_required') ?>',
            job_description: '<?= VeeValidateHelper::getVValidateString($this, $model, 'job_description') ?>',
            job_description_bonus: '<?= VeeValidateHelper::getVValidateString($this, $model, 'job_description_bonus') ?>',
			contact_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_name') ?>',
            contact_phones: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_phone') ?>',
            contact_phone_limit: 'vacancy_contact_phones_item_required|required|vacancy_contact_phones_item_limit',
            contact_email_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_email_list') ?>|app_models_Vacancy_contact_email_list_yii_validators_EmailValidator',
			contact_email_limit: 'vacancy_contact_email_list_item_required|required|vacancy_contact_email_list_item_limit',
            main_image: '<?= VeeValidateHelper::getVValidateString($this, $model, 'main_image') ?>',
            agency_accept: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_accept') ?>',
            agency_paid_document: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_paid_document') ?>',
            agency_paid_document_price: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_paid_document_price') ?>',
            agency_paid_document_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_paid_document_currency_code') ?>',
            agency_free_document: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_free_document') ?>',
            agency_pay_commission: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_pay_commission') ?>',
            agency_pay_commission_amount: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_pay_commission_amount') ?>',
            agency_pay_commission_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'agency_pay_commission_currency_code') ?>',
            secure_deal: '<?= VeeValidateHelper::getVValidateString($this, $model, 'secure_deal') ?>',
            country_city_id: '', // '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
        },
    }
  },
  mounted: function() {
	// fill select by selected job
	let me = this;
	for (let i = 0; i < this.$data.category_job_list.length; i++) {
		this.$data.vacancy_info.source_data.category_job_item = _.find(this.$data.category_job_list[i].jobs, function(p) {
			return p.id == me.$data.vacancy_info.source_data.category_job_id;
		});

		if (this.$data.vacancy_info.source_data.category_job_item) { // if founded exit;
			break;
		}
	}
  },
  methods: {
    onSubmit () {
		// lock send form on key 'enter'
      	return; // supress submit
    },
    vacancyInfoEdit: function() {
		// prepair data for v-model
		let edit_data = JSON.parse(JSON.stringify(this.$data.vacancy_info.source_data));
		let me = this;
		
		// selected_categories (categoryVacancies)
		for(let i = 0; i < this.$data.category_list.length; i++) {
			this.$data.category_list[i].checked = false;

			if(edit_data.categoryVacancies.indexOf( this.$data.category_list[i].id ) !== -1) {
				this.$data.category_list[i].checked = true;
			}
		}

		edit_data.selected_categories = _.filter(this.$data.category_list, function(p) {
			return p.checked;
		});

		// worker_country_codes
		for (let i = 0; i < this.$data.country_list.length; i++) {
			this.$data.country_list[i].checked = false;
            if ( edit_data.worker_country_codes.indexOf(this.$data.country_list[i].char_code + ';') !== -1) {
				this.$data.country_list[i].checked = true;
            }
        }

		// gender_list
		for (let i = 0; i < this.$data.gender_list.length; i++) {
			this.$data.gender_list[i].checked = false;
            if ( edit_data.gender_list.indexOf(this.$data.gender_list[i].id + ';') !== -1) {
				this.$data.gender_list[i].checked = true;
            }
        }

		// type_of_working_shift
		for (let i = 0; i < this.$data.type_of_working_shift_list.length; i++) {
			this.$data.type_of_working_shift_list[i].checked = false;
            if ( edit_data.type_of_working_shift.indexOf(this.$data.type_of_working_shift_list[i].id + ';') !== -1) {
				this.$data.type_of_working_shift_list[i].checked = true;
            }
        }

		// documents_provided
		for (let i = 0; i < this.$data.documents_provided_list.length; i++) {
			this.$data.documents_provided_list[i].checked = false;
            if ( edit_data.documents_provided.indexOf(this.$data.documents_provided_list[i].id + ';') !== -1) {
				this.$data.documents_provided_list[i].checked = true;
            }
        }

		// documents_required
		for (let i = 0; i < this.$data.documents_required_list.length; i++) {
			this.$data.documents_required_list[i].checked = false;
            if ( edit_data.documents_required.indexOf(this.$data.documents_required_list[i].id + ';') !== -1) {
				this.$data.documents_required_list[i].checked = true;
            }
        }

		// contact_email_list
		edit_data.contact_emails = edit_data.contact_email_list.split(';');

		// contact_phones
		edit_data.contact_phones = edit_data.contact_phone.split(';');

		// fill select by selected job
		edit_data.category_job_item = null;
		for (let i = 0; i < this.$data.category_job_list.length; i++) {
			edit_data.category_job_item = _.find(this.$data.category_job_list[i].jobs, function(p) {
				return p.id == edit_data.category_job_id;
			});

			if(edit_data.category_job_item) { // if founded exit;
				break;
			}
		}

		edit_data.worker_country_codes_all = true;
		if (edit_data.worker_country_codes) {
			edit_data.worker_country_codes_all = false;
		}

		// enable edit mode
		this.$data.vacancy_info.edit_data = edit_data;
		this.$data.vacancy_info_is_edit_mode = true;
        this.$data.vacancy_info.edit_mode = true;
		
		this.countryChanged();

		this.scrollToTop();
	},
	vacancyInfoEditCancel: function() {
		// this.$data.vacancy_info_is_edit_mode = false;
        this.$data.vacancy_info.edit_mode = false;
		this.$data.submit_clicked = false;
		this.$data.file_format_error = false;
	},
	sendFieldsToServer: function(post_data, cb) {
        // find and add '_csrf' to post data
        for(let i = 0; i < this.$refs.form.length; i++) {
            if (this.$refs.form[i].name == '_csrf') {
                post_data._csrf = this.$refs.form[i].value;
            }
        }

        // send data to server via AJAX POST
		let me = this;
        this.loaderShow();
        $.post( '<?= Url::to(['/userpanel/vacancy/update/' . $model->id]) ?>', post_data)
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                me.loaderHide();
                if(response.success === false && response.errors) {
                    // insert errors
                    me.$data.response_errors = response.errors;
                    // scroll to top
                    me.scrollToErrorblock();
                } else {
                    if(response.success !== true) {
                        alert(response.responseText ? response.responseText : response.statusText);
                        return; // exit
                    }

                    cb(response);
                }
            });
    },
    vacancyInfoSave: function() {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

			// this.$refs.form.submit();
			let post_data = {
				'Vacancy[title]': this.$data.vacancy_info.edit_data.title,
				'Vacancy[company_name]': this.$data.vacancy_info.edit_data.company_name,
				'Vacancy[category_job_id]': this.$data.vacancy_info.edit_data.category_job_id,
				'Vacancy[gender_list]': this.$data.vacancy_info.edit_data.gender_list, //
				// 'Vacancy[age_min]': this.$data.vacancy_info.edit_data.age_min, //! not found in template
				// 'Vacancy[age_max]': this.$data.vacancy_info.edit_data.age_max, //! not found in template
				'Vacancy[free_places]': this.$data.vacancy_info.edit_data.free_places,
				'Vacancy[regular_places]': this.getRegularPlacesValue(this.$data.vacancy_info.edit_data.regular_places), //
				'Vacancy[date_start]': this.$data.vacancy_info.edit_data.date_start ? moment(this.$data.vacancy_info.edit_data.date_start, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
				'Vacancy[date_end]': this.$data.vacancy_info.edit_data.date_end ? moment(this.$data.vacancy_info.edit_data.date_end, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
				'Vacancy[date_free]': this.getDateFreeValue(this.$data.vacancy_info.edit_data.date_free), //
				'Vacancy[country_name]': this.$data.vacancy_info.edit_data.country_name,
				'Vacancy[city_name]': this.$data.vacancy_info.edit_data.city_name,
				'Vacancy[salary_per_hour_min]': this.$data.vacancy_info.edit_data.salary_per_hour_min,
				'Vacancy[salary_per_hour_max]': this.$data.vacancy_info.edit_data.salary_per_hour_max,
				'Vacancy[salary_per_hour_min_src]': this.$data.vacancy_info.edit_data.salary_per_hour_min_src,
				'Vacancy[salary_per_hour_max_src]': this.$data.vacancy_info.edit_data.salary_per_hour_max_src,
				'Vacancy[currency_code]': this.$data.vacancy_info.edit_data.currency_code,
				'Vacancy[hours_per_day_min]': this.$data.vacancy_info.edit_data.hours_per_day_min,
				'Vacancy[hours_per_day_max]': this.$data.vacancy_info.edit_data.hours_per_day_max,
				'Vacancy[days_per_week_min]': this.$data.vacancy_info.edit_data.days_per_week_min,
				'Vacancy[days_per_week_max]': this.$data.vacancy_info.edit_data.days_per_week_max,
				'Vacancy[prepaid_expense_min]': this.$data.vacancy_info.edit_data.prepaid_expense_min,
				'Vacancy[prepaid_expense_max]': this.$data.vacancy_info.edit_data.prepaid_expense_max,
				'Vacancy[type_of_working_shift]': this.$data.vacancy_info.edit_data.type_of_working_shift, //
				'Vacancy[residence_provided]': this.$data.vacancy_info.edit_data.residence_provided,
				'Vacancy[residence_amount]': this.$data.vacancy_info.edit_data.residence_amount,
				'Vacancy[residence_amount_currency_code]': this.$data.vacancy_info.edit_data.residence_amount_currency_code,
				'Vacancy[residence_people_per_room]': this.$data.vacancy_info.edit_data.residence_people_per_room,
				'Vacancy[documents_provided]': this.$data.vacancy_info.edit_data.documents_provided, //
				'Vacancy[documents_required]': this.$data.vacancy_info.edit_data.documents_required, //
				'Vacancy[job_description]': this.$data.vacancy_info.edit_data.job_description,
				'Vacancy[job_description_bonus]': this.$data.vacancy_info.edit_data.job_description_bonus,
				'Vacancy[contact_name]': this.$data.vacancy_info.edit_data.contact_name,
				'Vacancy[contact_phone]': this.$data.vacancy_info.edit_data.contact_phone,
				'Vacancy[contact_email_list]': this.$data.vacancy_info.edit_data.contact_email_list, //
				'Vacancy[main_image]': this.$data.vacancy_info.edit_data.main_image,
				'Vacancy[agency_accept]': this.$data.vacancy_info.edit_data.agency_accept,
				'Vacancy[agency_paid_document]': this.getAgencyPaidDocumentValue(this.$data.vacancy_info.edit_data.agency_paid_document), //
				'Vacancy[agency_paid_document_price]': this.$data.vacancy_info.edit_data.agency_paid_document_price,
				'Vacancy[agency_paid_document_currency_code]': this.$data.vacancy_info.edit_data.agency_paid_document_currency_code,
				'Vacancy[agency_free_document]': this.getAgencyFreeDocumentValue(this.$data.vacancy_info.edit_data.agency_free_document), //
				'Vacancy[agency_pay_commission]': this.getAgencyPayCommissionValue(this.$data.vacancy_info.edit_data.agency_pay_commission), //
				'Vacancy[agency_pay_commission_amount]': this.$data.vacancy_info.edit_data.agency_pay_commission_amount,
				'Vacancy[agency_pay_commission_currency_code]': this.$data.vacancy_info.edit_data.agency_pay_commission_currency_code,
				'Vacancy[secure_deal]': this.$data.vacancy_info.edit_data.secure_deal,
				'Vacancy[country_city_id]': this.$data.vacancy_info.edit_data.country_city_id,
				
				'relations[0]': 'categoryVacancies',
				'relations[1]': 'vacancyImages'
			};

			debugger;
			if (!this.$data.vacancy_info.edit_data.worker_country_codes_all) {
				post_data['Vacancy[worker_country_codes]'] = this.$data.vacancy_info.edit_data.worker_country_codes;
			}


			// categoryVacancies
			let counter_cv = 0;
			for(let i = 0; i < this.$data.category_list.length; i++) {
				if(this.$data.category_list[i].checked) {
					post_data['Vacancy[categoryVacancies]['+ (counter_cv++) +'][category_id]'] = this.$data.category_list[i].id;
				}
			}

			// photos_list (vacancyImages)
			for(let i = 0; i < this.$data.vacancy_info.edit_data.vacancyImages.length; i++) {
				if (this.$data.vacancy_info.edit_data.vacancyImages[i].is_new) { // new files
					post_data['Vacancy[vacancyImages]['+ i +'][name]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].name;
					// post_data['Vacancy[vacancyImages]['+ i +'][path_name]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].path_name;
					post_data['Vacancy[vacancyImages]['+ i +'][src]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].photo_src;
				} else { // old files
					post_data['Vacancy[vacancyImages]['+ i +'][name]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].name;
					post_data['Vacancy[vacancyImages]['+ i +'][path_name]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].path_name;
					// post_data['Vacancy[vacancyImages]['+ i +'][src]'] = this.$data.vacancy_info.edit_data.vacancyImages[i].photo_src;
				}
			}

			// find and add '_csrf' to post data
			for(let i = 0; i < this.$refs.form.length; i++) {
				if (this.$refs.form[i].name == '_csrf') {
					post_data._csrf = this.$refs.form[i].value;
				}
			}

			// send data to server via AJAX POST
			let me = this;
			this.sendFieldsToServer(
				post_data,
				function(data) {
					me.$data.vacancy_info.source_data = JSON.parse(JSON.stringify(me.$data.vacancy_info.edit_data));
					me.$data.vacancy_info.source_data.categoryVacancies = _.map(me.$data.vacancy_info.edit_data.selected_categories, function(p) { return p.id });
					me.$data.vacancy_info.edit_mode = false;
					me.$data.submit_clicked = false;

					// fill select by selected job
					for (let i = 0; i < me.$data.category_job_list.length; i++) {
						me.$data.vacancy_info.source_data.category_job_item = _.find(me.$data.category_job_list[i].jobs, function(p) {
							return p.id == me.$data.vacancy_info.source_data.category_job_id;
						});

						if (me.$data.vacancy_info.source_data.category_job_item) { // if founded exit;
							break;
						}
					}
				}
			);
        });
    },
    //-- step 1
    genderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.vacancy_info.edit_data.gender_list = selected_genders;
    },
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.vacancy_info.edit_data.selected_categories = _.filter(this.$data.category_list, function(p) {
            return p.checked;
        });
    },
    vacancyCountryCodesChange: function() {
        let selected_codes = '';
        for(let i = 0; i < this.$data.country_list.length; i++) {
            if(this.$data.country_list[i].checked) {
                selected_codes += this.$data.country_list[i].char_code + ';';
            }
        }

        this.$data.vacancy_info.edit_data.worker_country_codes = selected_codes;
	},
	onChangeCategoryJob: function() {
        this.$data.vacancy_info.edit_data.category_job_id = this.$data.vacancy_info.edit_data.category_job_item.id;
    },
    //-- step 2
    vacancyTypeOfWorkingShiftChange: function() {
        let selected_type_of_working_shifts = '';
        for(let i = 0; i < this.$data.type_of_working_shift_list.length; i++) {
            if(this.$data.type_of_working_shift_list[i].checked) {
                selected_type_of_working_shifts += this.$data.type_of_working_shift_list[i].id + ';';
            }
        }

        this.$data.vacancy_info.edit_data.type_of_working_shift = selected_type_of_working_shifts;
	},
	countryChanged: function() {
        let country_char_code = this.$data.vacancy_info.edit_data.country_name;
        
        // fix double request
        if(country_char_code == this.$data.country_char_code_last) {
            return;
        }

        this.$data.country_char_code_last = country_char_code;
        this.$data.country_city_refresh_flag = false;

        let me = this;
        this.loaderShow();
        $.get( '/api/country?country_char_code=' + country_char_code, function( data ) {
			me.loaderHide();
            me.$data.country_city_refresh_flag = true;
            me.$data.country_city_list = data.items
        });
    },
    
    //-- step 3
    vacancyDocumentsProvidedChange: function() {
        let selected_documents_provided = '';
        for(let i = 0; i < this.$data.documents_provided_list.length; i++) {
            if(this.$data.documents_provided_list[i].checked) {
                selected_documents_provided += this.$data.documents_provided_list[i].id + ';';
            }
        }

        this.$data.vacancy_info.edit_data.documents_provided = selected_documents_provided;
    },
    vacancyDocumentsRequiredChange: function() {
        let selected_documents_required = '';
        for(let i = 0; i < this.$data.documents_required_list.length; i++) {
            if(this.$data.documents_required_list[i].checked) {
                selected_documents_required += this.$data.documents_required_list[i].id + ';';
            }
        }

        this.$data.vacancy_info.edit_data.documents_required = selected_documents_required;
    },
	addEmail: function() {
        this.$data.vacancy_info.edit_data.contact_emails.push('');
    },
    changeEmail: function() {
        this.$data.vacancy_info.edit_data.contact_email_list = this.$data.vacancy_info.edit_data.contact_emails.join(';');
    },
    removeEmail: function(index) {
        this.$data.vacancy_info.edit_data.contact_emails.splice(index, 1);
		this.changeEmail();
	},
	addPhone: function() {
        this.$data.vacancy_info.edit_data.contact_phones.push('');
    },
    changePhone: function() {
        this.$data.vacancy_info.edit_data.contact_phone = this.$data.vacancy_info.edit_data.contact_phones.join(';');
    },
    removePhone: function(index) {
        this.$data.vacancy_info.edit_data.contact_phones.splice(index, 1);
        this.changePhone();
    },
	addPhoto: function() {
        if( this.$refs.photos.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let me = this;
        let img_supported_types = ['image/png', 'image/jpeg'];
        let regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        me.$data.file_format_error = false;

        for (let i = 0; i < this.$refs.photos.files.length; i++) {
            let file = this.$refs.photos.files[i];
            if ( !(regex.test(file.name.toLowerCase()))
                || img_supported_types.indexOf(file.type) == -1) {
                me.$data.file_format_error = true;
                return;
            }

            let reader = new FileReader();

            reader.onload = function (e) {
                // clientside resize image
                let img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    img = me.compressImage(img, 75, 1024);

                    let file_full_name = file.name.toLowerCase();
                    let file_name_arr = file_full_name.split('.');
                    file_name_arr.pop();
                    let file_name = file_name_arr.join('.');

                    me.$data.vacancy_info.edit_data.vacancyImages.push({
                        photo_src: img.src,
                        name: file_name,
						path_name: file_full_name,
						is_new: true,
                    });
                }
            }

            reader.readAsDataURL(file);
        }
    },
	removePhoto: function(index) {
		this.$data.vacancy_info.edit_data.vacancyImages.splice(index, 1);
	},
	getMainPhoto: function() {
		if (this.$data.vacancy_info.edit_mode) {
			if(this.$data.vacancy_info.edit_data.vacancyImages.length > 0) {
				return this.$data.vacancy_info.edit_data.vacancyImages[0].photo_src;
			}
		} else {
			if(this.$data.vacancy_info.source_data.vacancyImages.length > 0) {
				return this.$data.vacancy_info.source_data.vacancyImages[0].photo_src;
			}
		}

        return void 0;
    },
    //-- step 4
    
    // --
	// convert v-model value to db value
    getRegularPlacesValue: function(checked) {
        if(checked) {
            return <?= Vacancy::REGULAR_PLACES_YES ?>;
        } else {
            return <?= Vacancy::REGULAR_PLACES_NO ?>;
        }
    },
    getDateFreeValue: function(checked) {
        if(checked) {
            return <?= Vacancy::DATE_FREE_YES ?>;
        } else {
            return <?= Vacancy::DATE_FREE_NO ?>;
        }
    },
    getAgencyPaidDocumentValue: function(checked) {
        if(checked) {
            return <?= Vacancy::AGENCY_PAID_DOCUMENT_YES ?>;
        } else {
            return <?= Vacancy::AGENCY_PAID_DOCUMENT_NO ?>;
        }
    },
    getAgencyFreeDocumentValue: function(checked) {
        if(checked) {
            return <?= Vacancy::AGENCY_FREE_DOCUMENT_YES ?>;
        } else {
            return <?= Vacancy::AGENCY_FREE_DOCUMENT_NO ?>;
        }
    },
    getAgencyPayCommissionValue: function(checked) {
        if(checked) {
            return <?= Vacancy::AGENCY_PAY_COMMISSION_YES ?>;
        } else {
            return <?= Vacancy::AGENCY_PAY_COMMISSION_NO ?>;
        }
    },
	getCurrencyCodeLabel: function(desired_salary_currency_code) {
        let item = _.find(this.$data.currency_list, function(p) {
            return p.char_code == desired_salary_currency_code;
        });

        if(item) {
            return item.name;
        }

        return '-'; // impossible
    },
	// --
	getCategoryNameLabel: function(category_list) {
		let selected_list = _.map(
			_.filter(this.$data.category_list, function(p) {
				return category_list.indexOf( p.id ) !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
	getCountryNameLabel: function(char_code) {
		let item = _.find(this.$data.country_list, function(p) {
			return p.char_code == char_code;
		});

		if(item)
			return item.name;
		
		return '-'; // impossible value
	},
	getCountryCityLabel: function(country_city_id) {
		let item = _.find(this.$data.country_city_list, function(p) {
			return p.id == country_city_id;
		});

		if(item)
			return item.city_name;
		
		return '-'; // impossible value
	},
	getWorkerCountryCodesLabel: function(worker_country_codes) {
		if(!worker_country_codes) {
			return '<?= Yii::t('vacancy', 'All countries') ?>';
		}

		let selected_list = _.map(
			_.filter(this.$data.country_list, function(p) {
				return worker_country_codes.indexOf(p.char_code + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
	getGenderListLabel: function(gender_list_codes) {
		let selected_list = _.map(
			_.filter(this.$data.gender_list, function(p) {
				return gender_list_codes.indexOf(p.id + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
	getTypeOfWorkingShiftLabel: function(type_of_working_shift) {
		let selected_list = _.map(
			_.filter(this.$data.type_of_working_shift_list, function(p) {
				return type_of_working_shift.indexOf(p.id + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
	getDocumentsRequiredLabel: function(documents_required) {
		let selected_list = _.map(
			_.filter(this.$data.documents_required_list, function(p) {
				return documents_required.indexOf(p.id + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
	getDocumentsProvidedLabel: function(documents_provided) {
		let selected_list = _.map(
			_.filter(this.$data.documents_provided_list, function(p) {
				return documents_provided.indexOf(p.id + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
    // --
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
            }, 600);
        });
    },
    scrollToTop: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#appUpadeVacancy").offset().top
            }, 600);
        });
    },
    loaderShow() {
        let loaderWaitElement = document.getElementById('loaderWait');
        if(!loaderWaitElement.dataset.countShows) {
            loaderWaitElement.dataset.countShows = 0;
        }

        loaderWaitElement.dataset.countShows++
        loaderWaitElement.style.display = "initial";
    },
    loaderHide() {
        let loaderWaitElement = document.getElementById('loaderWait');
        
        loaderWaitElement.dataset.countShows--;
        if( loaderWaitElement.dataset.countShows <= 0) {
            loaderWaitElement.dataset.countShows = 0;
            loaderWaitElement.style.display = "none";
        }
	},
	compressImage: function (source_img_obj, quality, maxWidth, output_format){
        var mime_type = "image/jpeg";
        if(typeof output_format !== "undefined" && output_format=="png"){
            mime_type = "image/png";
        }

        maxWidth = maxWidth || 1000;
        var natW = source_img_obj.naturalWidth;
        var natH = source_img_obj.naturalHeight;
        var ratio = natH / natW;
        if (natW > maxWidth) {
            natW = maxWidth;
            natH = ratio * maxWidth;
        }

        var cvs = document.createElement('canvas');
        cvs.width = natW;
        cvs.height = natH;

        var ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
        var newImageData = cvs.toDataURL(mime_type, quality/100);
        var result_image_obj = new Image();
        result_image_obj.src = newImageData;
        return result_image_obj;
    }
  }
})
</script>
<?php $this->endJs(); ?>
