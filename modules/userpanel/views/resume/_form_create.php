<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Vacancy;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\ResumeEducation;
use app\models\ResumeLanguage;
use app\models\Category;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

$profile = Yii::$app->user->identity->profile;
$gender_list = Resume::getGenderList();
$lang_list = ResumeLanguage::getLanguageList();
$level_list = ResumeLanguage::getLevelList();
$employer_country_list = Vacancy::getCountryList(); // employer country list
$worker_country_list = Resume::getCountryList(); // all country list
$job_list = CategoryJob::getUserMultiSelectList();
$category_list = Category::getUserSelectList();
$currency_list = Resume::getCurrencyList();

?>

<div id="appCreateResume" class="registration registration--2" v-bind:class="{ 'registration--sfera': form_current_step == 2 }">
    <div class="container">
        <div class="registration__number-step">
            <b><?= Yii::t('main', 'Step') ?> <span v-html="form_current_step">1</span> <?= Yii::t('main', 'of') ?> 3</b>&nbsp;
            <span v-if="form_current_step == 1"><?= Yii::t('resume', 'Basic information') ?></span>
            <span v-if="form_current_step == 2" v-cloak><?= Yii::t('resume', 'What kind of job are you looking for?') ?></span>
            <span v-if="form_current_step == 3" v-cloak><?= Yii::t('resume', 'Skills') ?></span>
        </div>
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="registration__title">
                    
                        <span v-if="form_current_step == 1"><?= Yii::t('resume', 'Basic information') ?></span>
                        <span v-if="form_current_step == 2" v-cloak><?= Yii::t('resume', 'What kind of job are you looking for?') ?></span>
                        <span v-if="form_current_step == 3" v-cloak><?= Yii::t('resume', 'Skills') ?></span>
                        
                        <div v-if="form_current_step == 1">
                            <?= Yii::t('resume', 'Fill out basic information about yourself') ?>
                        </div>
                    </div>

<!-- form here -->
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'class' => 'registration__form create-resume-form',
        'v-on:submit.prevent' => "onSubmit",
        'ref' => 'form'
    ]
]);?>
    <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
        <div class="error-message-alert"><?= Yii::t('main', 'Errors')?>:</div>
        <ul v-for="(error_messages, field_name) of response_errors">
            <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
        </ul>

        <ul v-if="submit_clicked && errors.all()">
            <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
        <ul>
    </div>

    <!-- step 1 -->
    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('first_name') ?>
        </div>
        <input v-validate="vv.model_resume.first_name" name='Resume[first_name]' class="form-control" type="text" v-model="resume.first_name">
        <span v-show="errors.has('Resume[first_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[first_name]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('last_name') ?>
        </div>
        <input v-validate="vv.model_resume.last_name" name='Resume[last_name]' class="form-control" type="text" v-model="resume.last_name">
        <span v-show="errors.has('Resume[last_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[last_name]') }}
        </span>
    </div>
<?php /*
    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('middle_name') ?>
        </div>
        <input v-validate="vv.model_resume.middle_name" name='Resume[middle_name]' class="form-control" type="text" v-model="resume.middle_name">
        <span v-show="errors.has('Resume[middle_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[middle_name]') }}
        </span>
    </div>
*/ ?>
    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('email') ?>
        </div>
        <input v-validate="vv.model_resume.email" name='Resume[email]' class="form-control" type="text" v-model="resume.email">
        <span v-show="errors.has('Resume[email]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[email]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('birth_day') ?>
        </div>
        <!-- <input v-validate="vv.model_resume.birth_day" name='Resume[birth_day]' type="text" id="resume-birth_day"> -->
        <air-datepicker v-validate="vv.model_resume.birth_day" name='Resume[birth_day]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="resume.birth_day" class="j-edit-input edit"></air-datepicker>
        <span v-show="errors.has('Resume[birth_day]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[birth_day]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('gender_list') ?>
        </div>
        <div class="registration__row">
            <label v-for="gender_item in gender_list" class="checkbox" v-cloak>
                <input type="checkbox" v-model="gender_item.checked" v-on:change="genderChange">
                <span class="checkbox__check"></span>
                <span class="checkbox__title">
                    {{gender_item.name}}
                </span>
            </label>
        </div>
        
        <input v-validate="vv.model_resume.gender_list" name='Resume[gender_list]' type="hidden" v-model="resume.gender_list">
        <span v-show="errors.has('Resume[gender_list]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[gender_list]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('country_name') ?>
        </div>
        <nice-select :options="worker_country_list" name="Resume[country_name]`" class="select select--double" v-model="resume.country_name" v-on:input="countryChanged">
            <option v-for="item in worker_country_list" :value="item.char_code" >
                {{ item.name }}
            </option>
        </nice-select>
        <input v-validate="vv.model_resume.country_name" name='Resume[country_name]' type="hidden" v-model="resume.country_name">
        <span v-show="errors.has('Resume[country_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[country_name]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('country_city_id') ?>
        </div>
        <nice-select :options="country_city_list" name="Resume[country_city_id]`" class="select" v-model="resume.country_city_id" v-if="country_city_refresh_flag">
            <option v-for="item in country_city_list" :value="item.id">
                {{ item.city_name }}
            </option>
        </nice-select>
        <input v-validate="vv.model_resume.country_city_id" name='Resume[country_city_id]' type="hidden" v-model="resume.country_city_id">
        <span v-show="errors.has('Resume[country_city_id]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[country_city_id]') }}
        </span>
    </div>
    <!--/ step 1 -->
    
    <!-- step 2 relations: -->
    <div class="registration__input-bl" v-if="form_current_step == 2">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('desired_salary') ?>
        </div>

        <div class="input-group">
            <div class="input-group__col">
                <input v-validate="vv.model_resume.desired_salary" name='Resume[desired_salary]' class="form-control" type="text" v-model="resume.desired_salary">
            </div>
            <div class="input-group__col">
                <nice-select :options="currency_list" name="Resume[desired_salary_currency_code]`" class="select" v-model="resume.desired_salary_currency_code">
                    <option v-for="item in currency_list" :value="item.char_code" >
                        {{ item.name }}
                    </option>
                </nice-select>
            </div>
        </div>

        <span v-show="errors.has('Resume[desired_salary]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[desired_salary]') }}
        </span>
    </div>
<?php /*
    <div class="registration__input-bl" v-if="form_current_step == 2">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('desired_salary_per_hour') ?>
        </div>

        <div class="input-group">
            <div class="input-group__col">
                <input v-validate="vv.model_resume.desired_salary_per_hour" name='Resume[desired_salary_per_hour]' class="form-control" type="text" v-model="resume.desired_salary_per_hour">
            </div>
            <div class="input-group__col">
                <span class="currency-desired_salary_per_hour-label">
                    {{ getCurrencyCodeLabel(resume.desired_salary_currency_code) }}
                </span>
            </div>
        </div>

        <span v-show="errors.has('Resume[desired_salary_per_hour]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[desired_salary_per_hour]') }}
        </span>
    </div>
*/ ?>
    <div class="registration__input-bl" v-if="form_current_step == 2">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('desired_country_of_work') ?>
        </div>
        <input v-validate="vv.model_resume.desired_country_of_work" name='Resume[desired_country_of_work]' class="form-control" type="hidden" v-model="resume.desired_country_of_work">
        <multiselect v-model="resume.selected_desired_country_of_work" :options="employer_country_list" :multiple="true" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="char_code" label="name" v-on:input="onChangeDesiredCountryOfWork"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
        <span v-show="errors.has('Resume[desired_country_of_work]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[desired_country_of_work]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 2">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= Yii::t('resume', 'Preferred job') ?>
            <div class="registration__input-desc">
                <?= Yii::t('resume', 'Choose some jobs') ?>
            </div>
        </div>
        <multiselect v-model="resume.selected_resume_category_jobs" :options="category_job_list" :multiple="true" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeResumeCategoryJob"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>

        <input v-validate="vv.model_resume.selected_category_jobs" name='Resume[selected_resume_category_jobs]' type="hidden" v-model="resume.selected_resume_category_jobs">
        <span v-show="errors.has('Resume[selected_resume_category_jobs]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[selected_resume_category_jobs]') }}
        </span>
    </div>

    <!-- step 2 - category -->
    <div class="registration__input-title" style="margin: 20px 0 10px;" v-if="form_current_step == 2" v-cloak>
        <?= Yii::t('resume', 'Choose categories in which you would like to work.') ?>
        <div class="registration__input-desc">
            <?= Yii::t('resume', 'You can select up to 5 categories.') ?>
        </div>
    </div>

    <div class="reg-sfera" v-if="form_current_step == 2" v-cloak>
        <input v-validate="vv.model_resume.selected_categories" name='Resume[selected_categories]' class="form-control" type="hidden" v-model="resume.selected_categories">
        <span v-show="errors.has('Resume[selected_categories]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[selected_categories]') }}
        </span>
        
        <ul class="reg-sfera__selected j-sfera-selected">
            <li v-for="category_item of resume.selected_categories">
                {{category_item.name}}
                <div class="reg-sfera__close" v-on:click="selectCategory(category_item)"></div>
            </li>
        </ul>

        <div class="cats cats--register fix-resume-create">
            <div class="cats__row cats__accordion">
                <label v-for="category_item of category_list" v-bind:key="category_item.id" class="cats__item" v-bind:class="{ 'create-profile-category-selected': category_item.checked }">
                    <input type="checkbox" class="j-sfera-input" v-bind:value="category_item.checked" v-on:click="selectCategory(category_item)">
                    <div class="cats__img">
                        <img v-bind:src="category_item.img" alt="">
                    </div>
                    <div class="cats__title">
                        {{category_item.name}}
                        <span>{{category_item.ads_count}} <?= Yii::t('site', 'ads') ?></span>
                    </div>	
                </label>
            </div>
        </div>
    </div>
    <!--/ step 2 - category -->

    <!-- step 3 - skills -->
    <div class="edit-info__bl" id="bl3" v-if="form_current_step == 3" v-cloak>
        <div class="registration__input-title" style="margin-bottom: 0;">
            <?= Yii::t('user', 'Experience') ?>
        </div>
        <div v-for="(job_item, index) of job_list" class="edit-info__work" style="margin: 10px 0;">
            <div class="registration__price-month" data-id="#last-work" style="display: block;">
                <div class="registration__input-bl">
                    <div class="reg-lang__row">
                        <div class="reg-lang__bl">
                            <div class="registration__input-title">
                                <?= $modelJob->getAttributeLabel('company_name') ?>
                            </div>
                            <input v-validate="vv.model_job.company_name" v-bind:name='`Resume[resumeJobs][${index}][company_name]`' class="form-control" type="text" v-model="job_item.company_name">
                            <span v-show="errors.has('Resume[resumeJobs][' + index + '][company_name]')" class="validation-error-message" v-cloak>
                                {{ errors.first('Resume[resumeJobs][' + index + '][company_name]') }}
                            </span>
                        </div>
                        <div class="reg-lang__bl">
                            <div class="registration__input-title">
                                <?= $modelJob->getAttributeLabel('category_job_id') ?>
                            </div>
                            <input v-validate="vv.model_job.category_job_id" v-bind:name='`Resume[resumeJobs][${index}][category_job_id]`' class="form-control" type="hidden" v-model="job_item.category_job_id">
                            <multiselect v-model="select_category_job_items[index]" :options="category_job_list" :multiple="false" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeResumeJobsCategoryJob(index)"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
                            <span v-show="errors.has('Resume[resumeJobs][' + index + '][category_job_id]')" class="validation-error-message" v-cloak>
                                {{ errors.first('Resume[resumeJobs][' + index + '][category_job_id]') }}
                            </span>
                        </div>
                    </div>
                    <div class="registration__input-bl">
                        <div class="registration__input-title">
                            <?= Yii::t('user', 'Worked here') ?>
                        </div>
                        <div class="input-group" style="margin-bottom: 15px;">
                            <div class="input-group__col">
                                <input v-validate="vv.model_job.years" v-bind:name='`Resume[resumeJobs][${index}][years]`' class="form-control" type="text" v-model="job_item.years" placeholder="<?= $modelJob->getAttributeLabel('years') ?>">
                            </div>
                            <div class="input-group__col">
                                <input v-validate="vv.model_job.month" v-bind:name='`Resume[resumeJobs][${index}][month]`' class="form-control" type="text" v-model="job_item.month" placeholder="<?= $modelJob->getAttributeLabel('month') ?>">
                            </div>
                        </div>	
                        <div v-show="errors.has('Resume[resumeJobs][' + index + '][years]')" class="validation-error-message" v-cloak>
                            {{ errors.first('Resume[resumeJobs][' + index + '][years]') }}
                        </div>
                        <div v-show="errors.has('Resume[resumeJobs][' + index + '][month]')" class="validation-error-message" v-cloak>
                            {{ errors.first('Resume[resumeJobs][' + index + '][month]') }}
                        </div>
                    </div>
                    
                    <label class="checkbox">
                        <!-- <input type="hidden" v-bind:name="`Resume[resumeJobs][${index}][for_now]`" v-bind:value="getForNowValue(job_list[index].for_now)" v-model="job_item.for_now"> -->
                        <input type="checkbox" v-model="job_list[index].for_now" <?= ($modelJob->for_now == ResumeJob::STATUS_FOR_NOW_YES) ? 'checked' : '' ?>>
                        <span class="checkbox__check"></span>
                        <span class="checkbox__title">
                            <?= $modelJob->getAttributeLabel('for_now') ?>
                        </span>
                    </label>
                </div>
            </div>
            <a class="btn btn--small btn--trans-yellow" style="margin-top:10px;" v-on:click="removeJob(index)"><?= Yii::t('main', 'Remove') ?></a>
        </div>
        <a class="btn edit-info__add-btn" style="margin-top:10px;" v-on:click="addJob"><?= Yii::t('user', 'Add a job') ?></a>
    </div>

    <div class="edit-info__bl" id="bl4" v-if="form_current_step == 3" v-cloak>
        <div class="registration__input-title">
            <?= Yii::t('user', 'Education') ?>
            <div class="registration__input-desc">
                <?= Yii::t('resume', 'indicate studies trainings, courses, personal achievements') ?>
            </div>
        </div>
        <div v-for="(education_item, index) of education_list" style="margin-bottom: 10px;">
            <div class="table">
                <div class="table__tr">
                    <div class="table__td">
                        <div class="edit-info__input">
                            <input v-validate="vv.model_education.description" v-bind:name='`Resume[resumeEducations][${index}][description]`' class="form-control" type="text" v-model="education_item.description">
                            <span v-show="errors.has('Resume[resumeEducations][' + index + '][description]')" class="validation-error-message" v-cloak>
                                {{ errors.first('Resume[resumeEducations][' + index + '][description]') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <a class="btn btn--small btn--trans-yellow" style="margin-top: 10px;"><?= Yii::t('main', 'Edit') ?></a> -->
            <a class="btn btn--small btn--trans-yellow" style="margin-top:10px; margin-left: 5px;" v-on:click="removeEducation(index)"><?= Yii::t('main', 'Remove') ?></a>
        </div>
        <a class="btn edit-info__add-btn" v-on:click="addEducation"><?= Yii::t('user', 'Add study place') ?></a>
    </div>

    <!-- langs: -->
    <div class="edit-info__bl" v-if="form_current_step == 3">
        <div class="registration__input-title">
            <?= Yii::t('resume', 'Language proficiency') ?>
        </div>
        <div class="reg-lang" style="margin-top: 10px;">
			<div v-for="(language_item, index) of language_list" class="j-prop-lang-wrapper" v-cloak>
				<div class="reg-lang__top">
					<div class="reg-lang__title">
						<span class="j-prop-title-lang">{{getLanguageLabelByCode(language_item.source_data.country_code)}}</span> 
						<span class="reg-lang__title-desc">
							(<span class="j-prop-title-level">{{getLanguageLevelLabelById(language_item.source_data.level)}}</span>)
						</span>
					</div>
					<div class="reg-lang__edit">
						<a class="pointer" v-on:click="editLanguage(index)" v-if="!language_item.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
						<a class="pointer" v-on:click="removeLanguage(index)"><?= Yii::t('main', 'Remove') ?></a>
					</div>
				</div>
				<div class="registration__price-month" style="display: block;" v-if="language_item.edit_mode">
					<div class="registration__input-bl">
						<div class="reg-lang__row">
							<div class="reg-lang__bl">
								<div class="registration__input-title">
                                    <?= $modelLang->getAttributeLabel('country_code') ?>
								</div>
								<nice-select :options="lang_list" v-bind:name="`Resume[resumeLanguages][${index}][country_code]`" class="select j-prop-lang" v-model="language_item.edit_data.country_code">
                                    <option v-for="item in lang_list" :value="item.char_code" >
                                        {{ item.name }}
                                    </option>
                                </nice-select>
							</div>
							<div class="reg-lang__bl">
								<div class="registration__input-title">
                                    <?= $modelLang->getAttributeLabel('level') ?>
								</div>
								<nice-select v-bind:name="`Resume[resumeLanguages][${index}][level]`" class="select j-prop-lang" v-model="language_item.edit_data.level">
                                    <option v-for="item in level_list" :value="item.id" >
                                        {{ item.name }}
                                    </option>
                                </nice-select>
							</div>
						</div>
						<label class="checkbox">
                            <input type="hidden" v-bind:name="`Resume[resumeLanguages][${index}][can_be_interviewed]`" v-bind:value="getCanBeInterviewedValue(language_item.edit_data.can_be_interviewed)">
                            <input type="checkbox" v-model="language_item.edit_data.can_be_interviewed">
                            <span class="checkbox__check"></span>
                            <span class="checkbox__title">
                                <?= $modelLang->getAttributeLabel('can_be_interviewed') ?>
                            </span>
                        </label>
					</div>
					<button type="button" class="btn" v-on:click="saveLanguage(index)" v-if="language_item.edit_mode"><?= Yii::t('main', 'Save') ?></button>
				</div>
			</div>
		</div>
        <div class="registration__add-email" v-on:click="addLanguage">
            <?= Yii::t('resume', 'Add language') ?>
        </div>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 3" v-cloak>
        <div class="registration__input-title" style="margin-top: 25px;">
            <?= Yii::t('resume', 'Indicate your skills') ?>
        </div>
        <div class="textarea">
            <textarea v-validate="vv.model_resume.description" name='Resume[description]' cols="30" rows="10" v-model="resume.description"></textarea>

            <div class="textarea__col">
                <div class="textarea__inner">
                    <div class="textarea__title">
                        <?= Yii::t('resume', 'Example') ?>:
                    </div>
                    <ul>
                        <li><?= Yii::t('resume', 'Driver category B 5 years.') ?></li>
                        <li><?= Yii::t('resume', 'Welder 1 year.') ?></li>
                        <li><?= Yii::t('resume', 'I made the repair myself, I can glue the wallpaper.') ?></li>
                        <li><?= Yii::t('resume', 'Repairing equipment.') ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <span v-show="errors.has('Resume[description]')" class="validation-error-message" v-cloak>
            {{ errors.first('Resume[description]') }}
        </span>
    </div>

    <!--/ step 3 - skills -->

    <div class="registration__submit" style="justify-content: flex-end">
        <div v-if="form_current_step != 1" v-cloak>
            <input type="submit" class="btn btn--transparent" value="<?= Yii::t('resume', 'Previous Step') ?>" style="max-width: 220px;" v-on:click="previousStep">
        </div>
        <div v-if="form_current_step != 3">
            <input type="submit" class="btn" value="<?= Yii::t('resume', 'Next Step') ?>" style="max-width: 320px;margin-left: auto;display: block;" v-on:click="nextStep">
        </div>
        <div v-if="form_current_step == 3" v-cloak>
            <input type="submit" class="btn" value="<?= Yii::t('resume', 'Create Resume') ?>" style="max-wid3h: 220px;margin-left: auto;display: block;" v-on:click="nextStep">
        </div>
    </div>

<?php ActiveForm::end(); ?>
<!--/ form here -->

                </div>
            </div>
        </div>
    </div>
</div>

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

VeeValidate.Validator.extend('resume_selected_categories_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', 'It is necessary to fill in the “Desired area of work”.') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('resume_selected_categories_validator_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', '“Desired area of work”, - you can choose up to 5 areas.') ?>';
    },
    validate: function(value) {
        // count.true >= 5

        return value !== void 0 && value.length <= CATEGORIES_MAX_SELECTED_LIMIT;
    }
});

VeeValidate.Validator.extend('resume_selected_resume_category_jobs_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', 'You must fill out the «Preferred job».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

new Vue({
  el: '#appCreateResume',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
    lang_list: [
        <?php foreach($lang_list as $lang): ?>
        {
            char_code: '<?= $lang['char_code'] ?>',
            name: '<?= Yii::t('lang', $lang['name']) ?>'
        },
        <?php endforeach; ?>
    ],
    level_list: [
        <?php foreach($level_list as $key_code => $level): ?>
        {
            id: '<?= $key_code ?>',
            name: '<?= $level ?>'
        },
        <?php endforeach; ?>
    ],
    category_list: [
        <?php foreach( $category_list as $category): ?>
        {
            id: '<?= $category->id ?>',
            name: '<?= Yii::t('category', $category->name) ?>',
            img: '<?= $category->getImage() ?>',
            ads_count: '<?= $category->getAdsCount() ?>',
            checked: false // for stilization
        },
        <?php endforeach; ?>
    ],
    employer_country_list: [
        <?php foreach( $employer_country_list as $country): ?>
        {
            char_code: '<?= $country['char_code'] ?>',
            name: '<?= Yii::t('country', $country['name']) ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    worker_country_list: [
        <?php foreach( $worker_country_list as $country): ?>
        {
            char_code: '<?= $country['char_code'] ?>',
            name: '<?= Yii::t('country', $country['name']) ?>',
        },
        <?php endforeach; ?>
    ],
    country_city_list: [],
    gender_list: [
        <?php foreach( $gender_list as $key_id => $gender): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $gender ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    currency_list: [
        <?php foreach( $currency_list as $currency): ?>
        {
            char_code: '<?= $currency['char_code'] ?>',
            name: '<?= Yii::t('curr', $currency['name']) ?>',
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
    form_current_step: <?= $is_registration ? 2 : 1 ?>, // ?
    response_errors: void 0,
    submit_clicked: false,
    country_city_refresh_flag: false,
    resume: {
        // get data from profile:
        first_name: '<?= Html::encode($profile->first_name) ?>',
        last_name: '<?= Html::encode($profile->last_name) ?>',
        middle_name: '<?= Html::encode($profile->middle_name) ?>',
        email: '<?= Html::encode($profile->email) ?>',
        gender_list: '<?= Html::encode($profile->gender_list) ?>',
        birth_day: '<?= Yii::$app->formatter->asDate($profile->birth_day) ?>',
        country_name: '<?= Html::encode($profile->country_name) ?>',
        country_city_id: '<?= Html::encode($profile->country_city_id) ?>',
        // --
        desired_salary: '',
        desired_salary_per_hour: '',
        desired_salary_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>',
        category_job_item: void 0,
        description: '',
        // --
        selected_categories: [],
        selected_resume_category_jobs: [],
        selected_desired_country_of_work: [],
    },
    job_list: [],
    job_list_category_job_item: [], // tmp array
    education_list: [],
    language_list: [],
    select_category_job_items: [], // fix
    vv: {
        model_resume: {
            selected_categories: 'resume_selected_categories_validator_required|required|resume_selected_categories_validator_limit',
            selected_category_jobs: 'resume_selected_resume_category_jobs_validator_required|required',
            first_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'first_name') ?>',
            last_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'last_name') ?>',
            <?php /* middle_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'middle_name') ?>', */ ?>
            email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'email') ?>',
            gender_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'gender_list') ?>',
            birth_day: '<?= VeeValidateHelper::getVValidateString($this, $model, 'birth_day') ?>',
            country_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_name') ?>',
            country_city_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
            desired_salary: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary') ?>',
            desired_salary_per_hour: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary_per_hour') ?>',
            desired_salary_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary_currency_code') ?>',
            desired_country_of_work: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_country_of_work') ?>',
            photo_path: '<?= VeeValidateHelper::getVValidateString($this, $model, 'photo_path') ?>',
            phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'phone') ?>',
            custom_country: '<?= VeeValidateHelper::getVValidateString($this, $model, 'custom_country') ?>',
            description: '<?= VeeValidateHelper::getVValidateString($this, $model, 'description') ?>',
        },
        model_job: { // $modelJob
            category_job_id: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'category_job_id') ?>',
            company_name: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'company_name') ?>',
            month: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'month') ?>',
            years: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'years') ?>',
        },
        model_education: {
            description: '<?= VeeValidateHelper::getVValidateString($this, $modelEducation, 'description') ?>',
        },
    }
  },
  mounted: function() {
    // gender_list
    for (let i = 0; i < this.$data.gender_list.length; i++) {
        this.$data.gender_list[i].checked = false;
        if ( this.$data.resume.gender_list.indexOf(this.$data.gender_list[i].id + ';') !== -1) {
            this.$data.gender_list[i].checked = true;
        }
    }

    this.countryChanged();
    // this.addJob();
    this.upgradeStepAnchor();
  },
  methods: {
    onSubmit () {
      return; // supress submit
    },
    previousStep: function() {
        this.$data.form_current_step = this.$data.form_current_step - 1;
        this.upgradeStepAnchor();
    },
    nextStep: function() {
        this.$data.submit_clicked = true;

        //! BUG, need use data-vv-scope to prevent validate next step when user step back
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            if(this.$data.form_current_step < 3) {
                this.$validator.reset();
                this.$data.submit_clicked = false;
                this.$data.form_current_step = this.$data.form_current_step + 1;
                this.scrollToTop();
                this.upgradeStepAnchor();
            } else {
                // this.$refs.form.submit();
                let post_data = {
                    'Resume[first_name]': this.$data.resume.first_name,
                    'Resume[last_name]': this.$data.resume.last_name,
                    'Resume[middle_name]': this.$data.resume.middle_name,
                    'Resume[email]': this.$data.resume.email,
                    'Resume[gender_list]': this.$data.resume.gender_list,
                    'Resume[birth_day]': this.$data.resume.birth_day ? moment(this.$data.resume.birth_day, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
                    'Resume[country_name]': this.$data.resume.country_name,
                    'Resume[country_city_id]': this.$data.resume.country_city_id,
                    'Resume[desired_salary]': this.$data.resume.desired_salary,
                    'Resume[desired_salary_per_hour]': this.$data.resume.desired_salary_per_hour,
                    'Resume[desired_salary_currency_code]': this.$data.resume.desired_salary_currency_code,
                    'Resume[desired_country_of_work]': this.$data.resume.desired_country_of_work,
                    'Resume[photo_path]': this.$data.resume.photo_path,
                    // 'Resume[phone]': this.$data.resume.phone,
                    'Resume[description]': this.$data.resume.description,
                };

                // language_list (resumeLanguages)
                for(let i = 0; i < this.$data.language_list.length; i++) {
                    post_data['Resume[resumeLanguages]['+i+'][country_code]'] = this.$data.language_list[i].edit_data.country_code;
                    post_data['Resume[resumeLanguages]['+i+'][level]'] = this.$data.language_list[i].edit_data.level;
                    post_data['Resume[resumeLanguages]['+i+'][can_be_interviewed]'] = this.getCanBeInterviewedValue(this.$data.language_list[i].edit_data.can_be_interviewed);
                }

                // selected_categories (categoryResumes)
                for(let i = 0; i < this.$data.resume.selected_categories.length; i++) {
                    post_data['Resume[categoryResumes]['+i+'][category_id]'] = this.$data.resume.selected_categories[i].id;
                }

                // job_list (resumeJobs)
                for(let i = 0; i < this.$data.job_list.length; i++) {
                    post_data['Resume[resumeJobs]['+i+'][company_name]'] = this.$data.job_list[i].company_name;
                    post_data['Resume[resumeJobs]['+i+'][category_job_id]'] = this.$data.job_list[i].category_job_id;
                    post_data['Resume[resumeJobs]['+i+'][month]'] = this.$data.job_list[i].month;
                    post_data['Resume[resumeJobs]['+i+'][years]'] = this.$data.job_list[i].years;
                    post_data['Resume[resumeJobs]['+i+'][for_now]'] = this.getForNowValue(this.$data.job_list[i].for_now);
                }

                // selected_resume_category_jobs (resumeCategoryJobs)
                for(let i = 0; i < this.$data.resume.selected_resume_category_jobs.length; i++) {
                    post_data['Resume[resumeCategoryJobs]['+i+'][category_job_id]'] = this.$data.resume.selected_resume_category_jobs[i].id;
                }

                // education_list (resumeEducations)
                for(let i = 0; i < this.$data.education_list.length; i++) {
                    post_data['Resume[resumeEducations]['+i+'][description]'] = this.$data.education_list[i].description;
                }
                
                // find and add '_csrf' to post data
                for(let i = 0; i < this.$refs.form.length; i++) {
                    if (this.$refs.form[i].name == '_csrf') {
                        post_data._csrf = this.$refs.form[i].value;
                    }
                }

                // send data to server via AJAX POST
                let me = this;
                this.loaderShow();
                $.post( window.location.pathname, post_data)
                    .always(function ( response ) { // got response http redirrect or got response validation error messages
                        me.loaderHide();
                        if(response.success === false && response.errors) {
                            // insert errors
                            me.$data.response_errors = response.errors;
                            // scroll to top
                            me.scrollToErrorblock();
                        } else {
                            if(response.status == 302) { // success, then redirrect
                                return;
                            }
                            
                            if(response && response.status) {
                                alert('Error, code: ' + response.status + ', message: ' + response.statusText);
                            } else {
                                alert('Connection problem, try again later');
                            }
                        }
                    });
            }
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

        this.$data.resume.gender_list = selected_genders;
    },
    countryChanged: function() {
        let country_char_code = this.$data.resume.country_name;
        
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
    onChangeDesiredCountryOfWork: function() {
        let selected_codes = '';
        for(let i = 0; i < this.$data.resume.selected_desired_country_of_work.length; i++) {
            selected_codes += this.$data.resume.selected_desired_country_of_work[i].char_code + ';';
        }

        this.$data.resume.desired_country_of_work = selected_codes;
    },
    //-- ResumeLanguage
    addLanguage: function() {
        let item_lang = _.first(this.$data.lang_list);
        let item_level = _.first(this.$data.level_list);
        
        let new_language_item = {
            edit_mode: false,
            is_new: true,
            source_data: {
                country_code: item_lang.char_code,
                level: item_level.id,
                can_be_interviewed: false
            },
            edit_data: { // init required for correct binding work, copy object on edit
                country_code: '',
                level: '',
                can_be_interviewed: false
            }
        };

        this.$data.language_list.push(new_language_item);

        this.editLanguage(this.$data.language_list.length - 1);
    },
    editLanguage: function(index) {
        // show edit form for current element
        this.$data.language_list[index].edit_mode = true;

        // make a copy source data
        this.$data.language_list[index].edit_data.country_code = this.$data.language_list[index].source_data.country_code;
        this.$data.language_list[index].edit_data.level = this.$data.language_list[index].source_data.level;
        this.$data.language_list[index].edit_data.can_be_interviewed = this.$data.language_list[index].source_data.can_be_interviewed;
    },
    saveLanguage: function(index) {
        this.$data.language_list[index].source_data = JSON.parse(JSON.stringify(this.$data.language_list[index].edit_data));
        this.$data.language_list[index].edit_mode = false;
    },
    removeLanguage: function(index) {
        if (confirm("<?= Yii::t('resume', 'Remove this record?') ?>")) {
            // switch form to edit mode
            this.$data.profile_language_is_edit_mode = true;

            let is_new = this.$data.language_list[index].is_new;
            this.$data.language_list.splice(index, 1);
            if (!is_new) {
                this.saveLanguage();
            }
        }
    },
    getLanguageLabelByCode: function(char_code) {
        let item = _.find(this.$data.lang_list, function(p) {
            return char_code == p.char_code;
        });

        return item.name;
    },
    getLanguageLevelLabelById: function(id) {
        let item = _.find(this.$data.level_list, function(p) {
            return id == p.id;
        });

        return item.name;
    },
    //-- step 2
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.resume.selected_categories = _.filter(this.$data.category_list, function(p) {
            return p.checked;
        });
    },
    addJob: function() {
        let new_job_item = {
            source_data: {
                category_job_id: "",
                company_name: "",
                month: "",
                years: "",
                for_now: <?= ResumeJob::STATUS_FOR_NOW_NO ?>,
                category_job_item: void 0
            },
        };

        this.$data.job_list.push(new_job_item);
    },
    removeJob: function(index) {
        if (confirm("<?= Yii::t('resume', 'Remove this record?') ?>")) {
            this.$data.job_list.splice(index, 1);
        }
    },
    onChangeResumeCategoryJob: function() {
        // this.$data.resume.selected_resume_category_jobs;
    },
    onChangeResumeJobsCategoryJob: function(index) {
        this.$data.job_list[index].category_job_id = this.$data.select_category_job_items[index].id;
    },
    addEducation: function() {
        let new_education_item = {
            
        };

        this.$data.education_list.push(new_education_item);
    },
    removeEducation: function(index) {
        this.$data.education_list.splice(index, 1);
    },
    // --
    getCurrencyCodeLabel: function(desired_salary_currency_code) {
        let item = _.find(this.$data.currency_list, function(p) {
            return p.char_code == desired_salary_currency_code;
        });

        if(item) {
            return item.name;
        }

        return '-'; // impossible
    },
    getForNowValue: function(checked) {
        if(checked) {
            return <?= ResumeJob::STATUS_FOR_NOW_YES ?>;
        } else {
            return <?= ResumeJob::STATUS_FOR_NOW_NO ?>;
        }
    },
    getCanBeInterviewedValue: function(checked) {
        if(checked) {
            return <?= ResumeLanguage::CAN_BE_IN_INTERVIEWED_YES ?>;
        } else {
            return <?= ResumeLanguage::CAN_BE_IN_INTERVIEWED_NO ?>;
        }
    },
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
                scrollTop: $("#appCreateResume").offset().top - 200
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
    upgradeStepAnchor() {
        location.hash = 'step' + this.form_current_step;
    }
  }
})
</script>
<?php $this->endJs(); ?>