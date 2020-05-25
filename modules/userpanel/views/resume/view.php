<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Vacancy;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\Category;
use app\models\ResumeLanguage;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$this->title = Yii::t('resume', 'Congratulations! Now your employers will see your resume.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'My resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

$employer_country_list = Vacancy::getCountryList();
$worker_country_list = Resume::getCountryList(); // all country list
$gender_list = Resume::getGenderList();

$genders = $model->getGenders();
$gender_names = implode(', ', $genders);

// get resume country name label
$country_name = $model->country_name;
foreach($worker_country_list as $country) {
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

$currency_list = Resume::getCurrencyList();

$desired_countries_of_work = ArrayHelper::getColumn($model->getDesiredCountryOfWork(), 'name');
foreach($desired_countries_of_work as $key => $val) {
	$desired_countries_of_work[$key] = Yii::t('country', $val);
}

$desired_country_of_work_names = implode(', ', $desired_countries_of_work);

$category_jobs = $model->getCategoryJobs()->asArray()->all();
$category_jobs = ArrayHelper::getColumn($category_jobs, 'name');
foreach($category_jobs as &$category_job) {
    $category_job = Yii::t('category-job', $category_job);
}
$category_job_names = implode(', ', $category_jobs);

$category_list = Category::getUserSelectList();

$categories = $model->getCategories()->asArray()->all();
$categories = ArrayHelper::getColumn($categories, 'name');
foreach($categories as &$category) {
    $category = Yii::t('category', $category);
}
$category_names = implode(', ', $categories);

$lang_list = ResumeLanguage::getLanguageList();
$level_list = ResumeLanguage::getLevelList();
$job_list = CategoryJob::getUserMultiSelectList();

?>
<div class="info-company edit-info">
	<div class="container">
		<div class="title-sec">
            <?= Html::encode($this->title) ?>
		</div>
		        
        <?php $form = ActiveForm::begin([
            'id' => 'appUpdateResume',
            'options' => [
                'class' => 'row info-company__row',
                'v-on:submit.prevent' => "onSubmit",
                'ref' => 'form'
            ],
        ]); ?>
            <div class="col">
                <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                    <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
                    <ul v-for="(error_messages, field_name) of response_errors">
                        <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                    </ul>

                    <ul v-if="submit_clicked && errors.all()">
                        <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                    <ul>
                </div>

                <hr style="margin-top: 0;margin-bottom: 30px;">
                <div class="edit-info__bl" id="bl1">
                    <div class="row edit-info__row">
                        <!-- resume info view mode -->
                        <div class="col" v-if="!resume_info_is_edit_mode">
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
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Yii::$app->formatter->asDate($model->birth_day); ?>
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
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($model->email); ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('phone') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($model->phone); ?>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn--small btn--trans-yellow" v-on:click="resumeInfoEdit"><?= Yii::t('main', 'Edit') ?></a>
                        </div>
                        <!--/ resume info view mode -->
                        <!-- resume info edit mode -->
                        <div class="col" v-if="resume_info_is_edit_mode" v-cloak>
                            <div class="info-company__title">
                                <?= Yii::t('user', 'Personal data') ?>
                            </div>
                            <div class="table" v-if="!resume_info.edit_mode">
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'Full Name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getFullName(resume_info.source_data)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{formatDate(resume_info.source_data.birth_day)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getGenderListLabel(resume_info.source_data.gender_list)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getCountryNameLabel(resume_info.source_data.country_name)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_city_id') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getCountryCityLabel(resume_info.source_data.country_city_id)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{resume_info.source_data.email}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('phone') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{resume_info.source_data.phone}}
                                    </div>
                                </div>
                            </div>
                            <div class="table" v-if="resume_info.edit_mode">
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('first_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_resume.first_name" name='Resume[first_name]' class="j-edit-input edit" type="text" v-model="resume_info.edit_data.first_name">
                                            <span v-show="errors.has('Resume[first_name]')" class="validation-error-message">
                                                {{ errors.first('Resume[first_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('last_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_resume.last_name" name='Resume[last_name]' class="j-edit-input edit" type="text" v-model="resume_info.edit_data.last_name">
                                            <span v-show="errors.has('Resume[last_name]')" class="validation-error-message">
                                                {{ errors.first('Resume[last_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('middle_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_resume.middle_name" name='Resume[middle_name]' class="j-edit-input edit" type="text" v-model="resume_info.edit_data.middle_name">
                                            <span v-show="errors.has('Resume[middle_name]')" class="validation-error-message">
                                                {{ errors.first('Resume[middle_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <air-datepicker v-validate="vv.model_resume.birth_day" name='Resume[birth_day]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="resume_info.edit_data.birth_day" class="j-edit-input edit"></air-datepicker>
                                            <span v-show="errors.has('Resume[birth_day]')" class="validation-error-message">
                                                {{ errors.first('Resume[birth_day]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <div class="registration__row">
                                                <label v-for="gender_item in gender_list" class="checkbox" v-cloak>
                                                    <input type="checkbox" v-model="gender_item.checked" v-on:change="genderChange">
                                                    <span class="checkbox__check"></span>
                                                    <span class="checkbox__title">
                                                        {{gender_item.name}}
                                                    </span>
                                                </label>
                                            </div>

                                            <input v-validate="vv.model_resume.gender_list" name='Resume[gender_list]' type="hidden" v-model="resume_info.edit_data.gender_list">
                                            <span v-show="errors.has('Resume[gender_list]')" class="validation-error-message">
                                                {{ errors.first('Resume[gender_list]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <nice-select :options="worker_country_list" name="Resume[country_name]`" class="select select--double" v-model="resume_info.edit_data.country_name" v-on:input="countryChanged">
                                                <option v-for="item in worker_country_list" :value="item.char_code" >
                                                    {{ item.name }}
                                                </option>
                                            </nice-select>
                                            <input v-validate="vv.model_resume.country_name" name='Resume[country_name]' class="j-edit-input edit" type="hidden" v-model="resume_info.edit_data.country_name">
                                            <span v-show="errors.has('Resume[country_name]')" class="validation-error-message">
                                                {{ errors.first('Resume[country_name]') }}
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
                                            <nice-select :options="country_city_list" name="Resume[country_city_id]`" class="select" v-model="resume_info.edit_data.country_city_id" v-if="country_city_refresh_flag">
                                                <option v-for="item in country_city_list" :value="item.id">
                                                    {{ item.city_name }}
                                                </option>
                                            </nice-select>
                                            <input v-validate="vv.model_resume.country_city_id" name='Resume[country_city_id]' class="j-edit-input edit" type="hidden" v-model="resume_info.edit_data.country_city_id">
                                            <span v-show="errors.has('Resume[country_city_id]')" class="validation-error-message">
                                                {{ errors.first('Resume[country_city_id]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_resume.email" name='Resume[email]' class="j-edit-input edit" type="text" v-model="resume_info.edit_data.email">
                                            <span v-show="errors.has('Resume[email]')" class="validation-error-message">
                                                {{ errors.first('Resume[email]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('phone') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_resume.phone" name='Resume[phone]' class="j-edit-input edit" type="text" v-model="resume_info.edit_data.phone">
                                            <span v-show="errors.has('Resume[phone]')" class="validation-error-message">
                                                {{ errors.first('Resume[phone]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="resumeInfoEditSave" v-if="resume_info.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                            <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="resumeInfoEditCancel" v-if="resume_info.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                            <a class="btn btn--small btn--trans-yellow" v-on:click="resumeInfoEdit" v-if="!resume_info.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                        </div>
                        <!--/ resume info edit mode -->
                        <div class="col" v-if="!resume_info3_is_edit_mode">
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img src="<?= empty($model->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($model->getImageWebPath()); ?>" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <?php if(!empty($model->photo_path)): ?>
                                    <div>
                                        <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto"><?= Yii::t('main', 'Remove') ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col" v-if="resume_info3_is_edit_mode && !resume_info3.edit_mode" v-cloak>
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img :src="getImagePath(resume_info3.source_data.photo_src)" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" id="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto" v-if="resume_info3.source_data.photo_src"><?= Yii::t('main', 'Remove') ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col" v-if="resume_info3_is_edit_mode && resume_info3.edit_mode" v-cloak>
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img :src="getImagePath(resume_info3.edit_data.photo_src)" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" id="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto" v-if="resume_info3.edit_data.photo_src"><?= Yii::t('main', 'Remove') ?></a>
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <div>
                                    <a class="btn btn--small" v-on:click="savePhoto"><?= Yii::t('main', 'Save') ?></a>
                                </div>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="cancelEditPhoto"><?= Yii::t('main', 'Cancel') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-info__bl" id="bl2">
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
                        <a class="btn btn--small btn--trans-yellow" v-on:click="resumeInfo2Edit"><?= Yii::t('main', 'Edit') ?></a>
                    </div>
                    <!--/ resume info 2 view mode -->
                    <!-- resume info 2 edit mode -->
                    <div class="table" v-if="resume_info2_is_edit_mode && !resume_info2.edit_mode" v-cloak>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                {{resume_info2.source_data.desired_salary}} {{getCurrencyCodeLabel(resume_info2.source_data.desired_salary_currency_code)}}
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary_per_hour') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                {{resume_info2.source_data.desired_salary_per_hour}} {{getCurrencyCodeLabel(resume_info2.source_data.desired_salary_currency_code)}}
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_country_of_work') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                {{getDesiredCountryOfWorkLabel(resume_info2.source_data.desired_country_of_work)}}
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Preferred job') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                {{resumeCategoryJobsLabel(resume_info2.source_data.resumeCategoryJobs)}}
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Categories in which you would like to work') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                                {{getCategoryNameLabel(resume_info2.source_data.categoryResumes)}}
                            </div>
                        </div>
                        <a class="btn btn--small btn--trans-yellow" v-on:click="resumeInfo2Edit"><?= Yii::t('main', 'Edit') ?></a>
                    </div>
                    <div class="table" v-if="resume_info2_is_edit_mode && resume_info2.edit_mode" v-cloak>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary') ?>
                            </div>
                            <div class="table__td">
                                <div class="edit-info__input">
                                    <div class="input-group">
                                        <div class="input-group__col">
                                            <input v-validate="vv.model_resume.desired_salary" name='Resume[desired_salary]' class="j-edit-input edit" type="text" v-model="resume_info2.edit_data.desired_salary">
                                        </div>
                                        <div class="input-group__col">
                                            <nice-select :options="currency_list" name="Resume[desired_salary_currency_code]`" class="select" v-model="resume_info2.edit_data.desired_salary_currency_code">
                                                <option v-for="item in currency_list" :value="item.char_code" >
                                                    {{ item.name }}
                                                </option>
                                            </nice-select>
                                        </div>
                                    </div>
                                    <span v-show="errors.has('Resume[desired_salary]')" class="validation-error-message">
                                        {{ errors.first('Resume[desired_salary]') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_salary_per_hour') ?>
                            </div>
                            <div class="table__td">
                                <div class="edit-info__input">
                                    <div class="input-group">
                                        <div class="input-group__col">
                                            <input v-validate="vv.model_resume.desired_salary_per_hour" name='Resume[desired_salary_per_hour]' class="j-edit-input edit" type="text" v-model="resume_info2.edit_data.desired_salary_per_hour">
                                        </div>
                                        <div class="input-group__col">
                                            <span class="currency-desired_salary_per_hour-label">
                                                {{ getCurrencyCodeLabel(resume_info2.edit_data.desired_salary_currency_code) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <span v-show="errors.has('Resume[desired_salary_per_hour]')" class="validation-error-message">
                                        {{ errors.first('Resume[desired_salary_per_hour]') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('desired_country_of_work') ?>
                            </div>
                            <div class="table__td">
                                <div class="edit-info__input">
                                    <input v-validate="vv.model_resume.desired_country_of_work" name='Resume[desired_country_of_work]' class="j-edit-input edit" type="hidden" v-model="resume_info2.edit_data.desired_country_of_work">
                                    <multiselect v-model="resume_info2.edit_data.selected_desired_country_of_work" :options="employer_country_list" :multiple="true" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="char_code" label="name" v-on:input="onChangeDesiredCountryOfWork"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
                                    <span v-show="errors.has('Resume[desired_country_of_work]')" class="validation-error-message">
                                        {{ errors.first('Resume[desired_country_of_work]') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Preferred job') ?>
                            </div>
                            <div class="table__td">
                                <div class="edit-info__input">
                                    <multiselect v-model="resume_info2.edit_data.selected_resume_category_jobs" :options="category_job_list" :multiple="true" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeResumeCategoryJob"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>

                                    <input v-validate="vv.model_resume.selected_category_jobs" name='Resume[category_job_list]' type="hidden" v-model="resume_info2.edit_data.selected_resume_category_jobs">
                                    <span v-show="errors.has('Resume[category_job_list]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Resume[category_job_list]') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('resume', 'Categories in which you would like to work') ?>
                            </div>
                            <div class="table__td table__td--second-view">
                            <input v-validate="vv.model_resume.selected_categories" name='Resume[category_list]' type="hidden" v-model="resume_info2.edit_data.selected_categories">
								<span v-show="errors.has('Resume[category_list]')" class="validation-error-message" v-cloak>
									{{ errors.first('Resume[category_list]') }}
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
                        <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="resumeInfo2EditSave" v-if="resume_info2.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                        <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="resumeInfo2EditCancel" v-if="resume_info2.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                        <a class="btn btn--small btn--trans-yellow" v-on:click="resumeInfo2Edit" v-if="!resume_info2.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                    </div>
                    <!--/ resume info 2 edit mode -->
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
                                        <?= $resumeJobModel->getAttributeLabel('years') ?>: <?= Html::encode($resumeJobModel->years); ?>,&nbsp;
                                        <?= $resumeJobModel->getAttributeLabel('month') ?>: <?= Html::encode($resumeJobModel->month); ?>&nbsp;
                                        <?php if($resumeJobModel->for_now == ResumeJob::STATUS_FOR_NOW_YES): ?>
                                            <span class="reg-lang__title-desc">(<?= $resumeJobModel->getAttributeLabel('for_now') ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn--small btn--trans-yellow" style="margin-top: 10px;" v-on:click="editJob(<?= $index ?>)"><?= Yii::t('main', 'Edit') ?></a>
                            <a class="btn btn--small btn--trans-yellow" style="margin-top:10px; margin-left: 5px;" v-on:click="removeJob(<?= $index ?>)"><?= Yii::t('main', 'Remove') ?></a>
                        <?php endforeach; ?>
                    </div>
                    <!--/ resume job view mode -->
                    <!-- resume job edit mode -->
                    <div v-for="(job_item, index) of job_list" class="edit-info__work" style="margin: 10px 0;" v-if="resume_job_is_edit_mode" v-cloak>
                        <div class="table">
                            <div class="table__tr">
                                <div class="table__td table__td--second-view">
                                    {{job_item.source_data.company_name}},&nbsp;
                                    {{select_job_list_source[index].name}},&nbsp;
                                    <?= $modelJob->getAttributeLabel('years') ?>: {{job_item.source_data.years}},&nbsp;
                                    <?= $modelJob->getAttributeLabel('month') ?>: {{job_item.source_data.month}}&nbsp;
                                    <span v-if="job_item.source_data.for_now" class="reg-lang__title-desc">(<?= $modelJob->getAttributeLabel('for_now') ?>)</span>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn--small btn--trans-yellow" style="margin-top: 10px;" v-on:click="editJob(index)" v-if="!job_item.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                        <a class="btn btn--small btn--trans-yellow" style="margin-top:10px; margin-left: 5px;" v-on:click="removeJob(index)"><?= Yii::t('main', 'Remove') ?></a>
                        <div class="registration__price-month" style="display: block;" v-if="job_item.edit_mode">
                            <div class="registration__input-bl">
                                <div class="reg-lang__row">
                                    <div class="reg-lang__bl">
                                        <div class="registration__input-title">
                                            <?= $modelJob->getAttributeLabel('company_name') ?>
                                        </div>
                                        <input v-validate="vv.model_job.company_name" v-bind:name='`Resume[resumeJobs][${index}][company_name]`' class="form-control" type="text" v-model="job_item.edit_data.company_name">
                                        <span v-show="errors.has('Resume[resumeJobs][' + index + '][company_name]')" class="validation-error-message">
                                            {{ errors.first('Resume[resumeJobs][' + index + '][company_name]') }}
                                        </span>
                                    </div>
                                    <div class="reg-lang__bl">
                                        <div class="registration__input-title">
                                            <?= $modelJob->getAttributeLabel('category_job_id') ?>
                                        </div>
                                        <input v-validate="vv.model_job.category_job_id" v-bind:name='`Resume[resumeJobs][${index}][category_job_id]`' class="form-control" type="hidden" v-model="job_item.edit_data.category_job_id">
                                        <multiselect v-model="select_job_list_edit[index]" :options="category_job_list" :multiple="false" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeResumeJobsCategoryJob(index)"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
                                        <span v-show="errors.has('Resume[resumeJobs][' + index + '][category_job_id]')" class="validation-error-message">
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
                                            <input v-validate="vv.model_job.years" v-bind:name='`Resume[resumeJobs][${index}][years]`' class="form-control" type="text" v-model="job_item.edit_data.years" placeholder="<?= $modelJob->getAttributeLabel('years') ?>">
                                            <span v-show="errors.has('Resume[resumeJobs][' + index + '][years]')" class="validation-error-message">
                                                {{ errors.first('Resume[resumeJobs][' + index + '][years]') }}
                                            </span>
                                        </div>
                                        <div class="input-group__col">
                                            <input v-validate="vv.model_job.month" v-bind:name='`Resume[resumeJobs][${index}][month]`' class="form-control" type="text" v-model="job_item.edit_data.month" placeholder="<?= $modelJob->getAttributeLabel('month') ?>">
                                            <span v-show="errors.has('Resume[resumeJobs][' + index + '][month]')" class="validation-error-message">
                                                {{ errors.first('Resume[resumeJobs][' + index + '][month]') }}
                                            </span>
                                        </div>
                                    </div>	
                                </div>
                                
                                <label class="checkbox">
                                    <input type="hidden" v-bind:name="`Resume[resumeJobs][${index}][for_now]`" v-bind:value="getForNowValue(job_item.edit_data.for_now)">
                                    <input type="checkbox" v-model="job_item.edit_data.for_now">
                                    <span class="checkbox__check"></span>
                                    <span class="checkbox__title">
                                        <?= $modelJob->getAttributeLabel('for_now') ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="saveJob(index)" v-if="job_item.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                        <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="cancelEditJob(index)" v-if="job_item.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                    </div>
                    <!--/ resume job edit mode -->

                    <a class="btn edit-info__add-btn" style="margin-top:10px;" v-on:click="addJob"><?= Yii::t('resume', 'Add a job') ?></a>
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
                            <a class="btn btn--small btn--trans-yellow" style="margin-top: 10px;" v-on:click="editEducation(<?= $index ?>)"><?= Yii::t('main', 'Edit') ?></a>
                            <a class="btn btn--small btn--trans-yellow" style="margin-top:10px; margin-left: 5px;" v-on:click="removeEducation(<?= $index ?>)"><?= Yii::t('main', 'Remove') ?></a>
                        <?php endforeach; ?>
                    </div>
                    <!--/ resume education view mode -->
                    <!-- resume education edit mode -->
                    <div v-for="(education_item, index) of education_list" class="edit-info__work" style="margin: 10px 0;" v-if="resume_education_is_edit_mode" v-cloak>
                        <div class="table">
                            <div class="table__tr">
                                <div class="table__td table__td--second-view">
                                    {{education_item.source_data.description}}
                                </div>
                            </div>
                        </div>
                        <a class="btn btn--small btn--trans-yellow" style="margin-top: 10px;" v-on:click="editEducation(index)" v-if="!education_item.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                        <a class="btn btn--small btn--trans-yellow" style="margin-top:10px; margin-left: 5px;" v-on:click="removeEducation(index)"><?= Yii::t('main', 'Remove') ?></a>
                        <div class="registration__price-month" style="display: block;" v-if="education_item.edit_mode">
                            <div class="registration__input-bl">
                                <div class="reg-lang__row">
                                    <div class="reg-lang__bl">
                                        <div class="registration__input-title">
                                        </div>
                                        <input v-validate="vv.model_education.description" v-bind:name='`Resume[resumeEducations][${index}][description]`' class="form-control" type="text" v-model="education_item.edit_data.description">
                                        <span v-show="errors.has('Resume[resumeEducations][' + index + '][description]')" class="validation-error-message">
                                            {{ errors.first('Resume[resumeEducations][' + index + '][description]') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="saveEducation(index)" v-if="education_item.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                        <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="cancelEditEducation(index)" v-if="education_item.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                    </div>
                    <!--/ resume education edit mode -->
                    <a class="btn edit-info__add-btn j-add-input" v-on:click="addEducation"><?= Yii::t('user', 'Add study place') ?></a>
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
                                    <div class="reg-lang__edit">
                                        <a class="btn btn--small btn--trans-yellow" v-on:click="editLanguage(<?= $index ?>)"><?= Yii::t('main', 'Edit') ?></a>
                                        <a class="btn btn--small btn--trans-yellow" v-on:click="removeLanguage(<?= $index ?>)"><?= Yii::t('main', 'Remove') ?></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!--/ resume language view mode -->
                        <!-- resume language edit mode -->
                        <div class="reg-lang" style="margin-top: 10px;" v-if="resume_language_is_edit_mode" v-cloak>
                            <div v-for="(language_item, index) of language_list" class="j-prop-lang-wrapper">
                                <div class="edit-info__lang-top">
                                    <b><span>{{getLanguageLabelByCode(language_item.source_data.country_code)}}</span> </b>
                                    <span class="reg-lang__title-desc">
                                        (<span class="j-prop-title-level">{{getLanguageLevelLabelById(language_item.source_data.level)}}</span>)
                                    </span>
                                </div>
                                <div class="reg-lang__edit">
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="editLanguage(index)" v-if="!language_item.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="removeLanguage(index)"><?= Yii::t('main', 'Remove') ?></a>
                                </div>
                                <div class="registration__price-month" style="display: block;" v-if="language_item.edit_mode">
                                    <div class="registration__input-bl">
                                        <div class="reg-lang__row edit-info__lang-row" style="margin-bottom: 15px;">
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
                                    <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="saveLanguage(index)" v-if="language_item.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                                    <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="cancelEditLanguage(index)" v-if="language_item.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                                </div>
                            </div>
                        </div>
                        <!--/ resume language edit mode -->
                        <div class="registration__add-email j-add-input" v-on:click="addLanguage"><?= Yii::t('user', 'Add language') ?></div>
                    </div>
                </div>
            </div>
            <!-- right block -->
            <div class="col info-company__col">
                <div>
                <?php /*
                    <button type="submit" class="edit-info__edit-btn btn" style="width: 100%; margin-top:0;margin-bottom: 20px;"><?= Yii::t('user', 'Complete Edit') ?></button>
                */ ?>
                    <div class="sidebar">
                        <div class="sidebar__title">
                            <?= Yii::t('user', 'Fill out a resume and increase the chances of quickly finding work') ?> <br>
                            <a href="#"><?= Yii::t('main', 'More') ?></a>
                        </div>
                        <ul class="sidebar__list-add">
                            <li>
                                <a href="#bl1" class="j-scroll">
                                    <span><?= Yii::t('main', 'Edit') ?></span>
                                    <?= Yii::t('user', 'Personal data') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl2" class="j-scroll">
                                    <span><?= Yii::t('main', 'Edit') ?></span>
                                    <?= Yii::t('user', 'General information') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl3" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Job') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl4" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Place of study') ?>
                                </a>
                            </li>
                            <?php /*
                            <li>
                                <a href="#bl5" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Course or training') ?>
                                </a>
                            </li>
                            */ ?>
                            <li>
                                <a href="#bl6" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Language') ?>
                                </a>
                            </li>
                            <?php /*
                            <li>
                                <a href="#bl7" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Additional Information') ?>
                                </a>
                            </li>
                            */ ?>
                        </ul>
                        <a href="<?= Url::to(['/userpanel']) ?>" class="edit-info__edit-btn btn" style="width: 100%; margin-bottom: -20px;"><?= Yii::t('user', 'Go to personal account') ?></a>
                    </div>
                </div>
            </div>
            <!--/ right block -->
        <?php ActiveForm::end(); ?>

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

VeeValidate.Validator.extend('resume_selected_resume_category_jobs_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', 'You must fill out the «Preferred job».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('resume_selected_categories_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', 'You must fill out the «Categories in which you would like to work».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('resume_selected_categories_validator_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', '«Categories in which you would like to work», - you can select up to 5 categories.') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length <= CATEGORIES_MAX_SELECTED_LIMIT;
    }
});

new Vue({
  el: '#appUpdateResume',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
    default_resume_image: '/img/icons-svg/user.svg',
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
            checked: false
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
    category_job_list_ungrouped: [],
    response_errors: void 0,
    submit_clicked: false,
    country_char_code_last: '',
    country_city_refresh_flag: true,
    //-------
    resume_info_is_edit_mode: false,
    resume_info2_is_edit_mode: false,
    resume_info3_is_edit_mode: false,
    resume_job_is_edit_mode: false,
    resume_education_is_edit_mode: false,
    resume_language_is_edit_mode: false,
    resume_info: {
        edit_mode: false,
        source_data: {
            first_name: '<?= Html::encode($model->first_name) ?>',
            last_name: '<?= Html::encode($model->last_name) ?>',
            middle_name: '<?= Html::encode($model->middle_name) ?>',
            birth_day: '<?= Yii::$app->formatter->asDate($model->birth_day) ?>',
            email: '<?= Html::encode($model->email); ?>',
            gender_list: '<?= Html::encode($model->gender_list); ?>',
            country_name: '<?= Html::encode($model->country_name); ?>',
            country_city_id: '<?= Html::encode($model->country_city_id); ?>',
            phone: '<?= Html::encode($model->phone); ?>',
        },
        edit_data: {
            first_name: '',
            last_name: '',
            middle_name: '',
            birth_day: '',
            email: '',
            gender_list: '',
            country_name: '',
            country_city_id: '',
            phone: '',
        }
    },
    resume_info2: {
        edit_mode: false,
        source_data: {
            desired_salary: '<?= Html::encode($model->desired_salary) ?>',
            desired_salary_per_hour: '<?= Html::encode($model->desired_salary_per_hour) ?>',
            desired_salary_currency_code: '<?= Html::encode($model->desired_salary_currency_code) ?>',
            desired_country_of_work: '<?= Html::encode($model->desired_country_of_work) ?>',
            categoryResumes: [
                <?php foreach($model->categoryResumes as $category) {
                    echo '"' . $category->category_id . '"' . ',';
                } ?>
            ],
            selected_desired_country_of_work: [],
            resumeCategoryJobs: [
                <?php foreach($model->resumeCategoryJobs as $category) {
                    echo '"' . $category->category_job_id . '"' . ',';
                } ?>
            ],
        },
        edit_data: { // copy object on edit
            desired_salary: '',
            desired_salary_per_hour: '',
            desired_salary_currency_code: '',
            desired_country_of_work: '',
            category_id: '',
            selected_categories: [], // need for validation
        },
    },
    resume_info3: {
        edit_mode: false,
        source_data: {
            photo_path: '<?= Html::encode($model->photo_path); ?>',
            photo_src: '<?= Html::encode($model->getImageWebPath()); ?>',
        },
        edit_data: { // copy object on edit
            photo_path: '',
            photo_src: '',
        }
    },
    job_list: [
        <?php foreach($model->resumeJobs as $resumeJobModel): ?>
        {
            edit_mode: false,
            is_new: false,
            source_data: {
                id: '<?= Html::encode($resumeJobModel->id) ?>',
                category_job_id: '<?= Html::encode($resumeJobModel->category_job_id) ?>',
                company_name: '<?= Html::encode($resumeJobModel->company_name) ?>',
                month: '<?= $resumeJobModel->month ?>',
                years: '<?= $resumeJobModel->years ?>',
                for_now: <?= $resumeJobModel->for_now ?> == <?= ResumeJob::STATUS_FOR_NOW_YES ?> ,
            },
            // edit_data: {} // copy object on edit
        },
        <?php endforeach; ?>
    ],
    education_list: [
        <?php foreach($model->resumeEducations as $resumeEducationModel): ?>
        {
            edit_mode: false,
            is_new: false,
            source_data: {
                id: '<?= Html::encode($resumeEducationModel->id) ?>',
                description: <?= json_encode($resumeEducationModel->description) ?>,
            },
            // edit_data: {} // copy object on edit
        },
        <?php endforeach; ?>
    ],
    language_list: [
        <?php foreach($model->resumeLanguages as $resumeLanguageModel): ?>
        {
            edit_mode: false,
            is_new: false,
            source_data: {
                id: '<?= Html::encode($resumeLanguageModel->id) ?>',
                country_code: '<?= Html::encode($resumeLanguageModel->country_code) ?>',
                level: '<?= $resumeLanguageModel->level ?>',
                can_be_interviewed: <?= $resumeLanguageModel->can_be_interviewed ?> == <?= ResumeLanguage::CAN_BE_IN_INTERVIEWED_YES ?>,
            },
            edit_data: { // init required for correct binding work, copy object on edit
                id: '',
                country_code: '',
                level: '',
                can_be_interviewed: false
            }
        },
        <?php endforeach; ?>
    ],
    select_job_list_source: [], // fix
    select_job_list_edit: [], // fix
    vv: {
        model_resume: {
            selected_category_jobs: 'resume_selected_resume_category_jobs_validator_required|required',
            selected_categories: 'resume_selected_categories_validator_required|required|resume_selected_categories_validator_limit',
            first_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'first_name') ?>',
            last_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'last_name') ?>',
            middle_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'middle_name') ?>',
            birth_day: '<?= VeeValidateHelper::getVValidateString($this, $model, 'birth_day') ?>',
            email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'email') ?>',
            gender_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'gender_list') ?>',
            country_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_name') ?>',
            country_city_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
            phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'phone') ?>',
            desired_salary: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary') ?>',
            desired_salary_per_hour: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary_per_hour') ?>',
            desired_salary_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_salary_currency_code') ?>',
            desired_country_of_work: '<?= VeeValidateHelper::getVValidateString($this, $model, 'desired_country_of_work') ?>',
            category_id: '<? /* VeeValidateHelper::getVValidateString($this, $model, 'category_id') */ ?>',
        },
        model_job: { // $modelJob
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
    // --
    this.$data.category_job_list_ungrouped = [];
    for(let i = 0; i < this.$data.category_job_list.length; i++) {
        for(let j = 0; j < this.$data.category_job_list[i]['jobs'].length; j++) {
            this.$data.category_job_list_ungrouped.push(this.$data.category_job_list[i]['jobs'][j]);
        }
    }

    // fill job_list
    for (let i = 0; i < this.$data.job_list.length; i++) {
        for (let j = 0; j < this.$data.category_job_list_ungrouped.length; j++) {
            if (this.$data.job_list[i].source_data.category_job_id == this.$data.category_job_list_ungrouped[j].id) {
                this.$data.select_job_list_source[i] = this.$data.category_job_list_ungrouped[j];
                this.$data.select_job_list_edit[i] = this.$data.category_job_list_ungrouped[j];
                break; // founded exit
            }
        }
    }
  },
  methods: {
    onSubmit () {
        // lock send form on key 'enter'
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
        $.post( '<?= Url::to(['/userpanel/resume/update/' . $model->id]) ?>', post_data)
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
    //-- Resume
    resumeInfoEdit: function() {
        let edit_data = JSON.parse(JSON.stringify(this.$data.resume_info.source_data));

        // gender_list
		for (let i = 0; i < this.$data.gender_list.length; i++) {
			this.$data.gender_list[i].checked = false;
            if ( edit_data.gender_list.indexOf(this.$data.gender_list[i].id + ';') !== -1) {
				this.$data.gender_list[i].checked = true;
            }
        }

        this.$data.resume_info.edit_data = edit_data;
        this.$data.resume_info_is_edit_mode = true;
        this.$data.resume_info.edit_mode = true;

        this.countryChanged();
    },
    resumeInfoEditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Resume[first_name]': this.$data.resume_info.edit_data.first_name,
                'Resume[last_name]': this.$data.resume_info.edit_data.last_name,
                'Resume[middle_name]': this.$data.resume_info.edit_data.middle_name,
                'Resume[birth_day]': this.$data.resume_info.edit_data.birth_day ? moment(this.$data.resume_info.edit_data.birth_day, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
                'Resume[email]': this.$data.resume_info.edit_data.email,
                'Resume[gender_list]': this.$data.resume_info.edit_data.gender_list,
                'Resume[country_name]': this.$data.resume_info.edit_data.country_name,
                'Resume[country_city_id]': this.$data.resume_info.edit_data.country_city_id,
                'Resume[phone]': this.$data.resume_info.edit_data.phone,
            };

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.resume_info.source_data = JSON.parse(JSON.stringify(me.$data.resume_info.edit_data));
                    me.$data.resume_info.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    resumeInfoEditCancel: function() {
        // this.$data.resume_info_is_edit_mode = false;
        this.$data.resume_info.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    resumeInfo2Edit: function() {
        let edit_data = JSON.parse(JSON.stringify(this.$data.resume_info2.source_data));

        // selected_categories (categoryResumes)
		for(let i = 0; i < this.$data.category_list.length; i++) {
			this.$data.category_list[i].checked = false;

			if(edit_data.categoryResumes.indexOf( this.$data.category_list[i].id ) !== -1) {
				this.$data.category_list[i].checked = true;
			}
		}

		edit_data.selected_categories = _.filter(this.$data.category_list, function(p) {
			return p.checked;
		});
        
        // selected_desired_country_of_work
        edit_data.selected_desired_country_of_work = [];
		for (let i = 0; i < this.$data.employer_country_list.length; i++) {
            if (edit_data.desired_country_of_work.indexOf(this.$data.employer_country_list[i].char_code + ';') !== -1) {
                edit_data.selected_desired_country_of_work.push(this.$data.employer_country_list[i]);
            }
        }

        // selected_categories (resumeCategoryJobs)
        edit_data.selected_resume_category_jobs = [];
		for(let i = 0; i < this.$data.category_job_list_ungrouped.length; i++) {
			if(edit_data.resumeCategoryJobs.indexOf(this.$data.category_job_list_ungrouped[i].id ) !== -1) {
				edit_data.selected_resume_category_jobs.push(this.$data.category_job_list_ungrouped[i]);
			}
		}

        this.$data.resume_info2.edit_data = edit_data;

        this.$data.resume_info2_is_edit_mode = true;
        this.$data.resume_info2.edit_mode = true;
    },
    resumeInfo2EditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Resume[desired_salary]': this.$data.resume_info2.edit_data.desired_salary,
                'Resume[desired_salary_per_hour]': this.$data.resume_info2.edit_data.desired_salary_per_hour,
                'Resume[desired_salary_currency_code]': this.$data.resume_info2.edit_data.desired_salary_currency_code,
                'Resume[desired_country_of_work]': this.$data.resume_info2.edit_data.desired_country_of_work,
                // 'Resume[category_id]': this.$data.resume_info2.edit_data.category_id,

                'relations[0]': 'categoryResumes',
                'relations[1]': 'resumeCategoryJobs',
            };

            // categoryResumes
			let counter_cv = 0;
			for(let i = 0; i < this.$data.category_list.length; i++) {
				if(this.$data.category_list[i].checked) {
					post_data['Resume[categoryResumes]['+ (counter_cv++) +'][category_id]'] = this.$data.category_list[i].id;
				}
            }

            // selected_resume_category_jobs (resumeCategoryJobs)
            for(let i = 0; i < this.$data.resume_info2.edit_data.selected_resume_category_jobs.length; i++) {
                post_data['Resume[resumeCategoryJobs]['+i+'][category_job_id]'] = this.$data.resume_info2.edit_data.selected_resume_category_jobs[i].id;
            }

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.resume_info2.source_data = JSON.parse(JSON.stringify(me.$data.resume_info2.edit_data));
                    me.$data.resume_info2.source_data.categoryResumes = _.map(me.$data.resume_info2.edit_data.selected_categories, function(p) { return p.id });
                    me.$data.resume_info2.source_data.resumeCategoryJobs = _.map(me.$data.resume_info2.edit_data.selected_resume_category_jobs, function(p) { return p.id });
                    me.$data.resume_info2.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    resumeInfo2EditCancel: function() {
        // this.$data.resume_info2_is_edit_mode = false;
        this.$data.resume_info2.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.resume_info2.edit_data.selected_categories = _.filter(this.$data.category_list, function(p) {
            return p.checked;
        });
    },
    onChangeDesiredCountryOfWork: function() {
        let selected_codes = '';
        for(let i = 0; i < this.$data.resume_info2.edit_data.selected_desired_country_of_work.length; i++) {
            selected_codes += this.$data.resume_info2.edit_data.selected_desired_country_of_work[i].char_code + ';';
        }

        this.$data.resume_info2.edit_data.desired_country_of_work = selected_codes;
    },
    getDesiredCountryOfWorkLabel: function(desired_country_of_work) {
		let selected_list = _.map(
			_.filter(this.$data.employer_country_list, function(p) {
				return desired_country_of_work.indexOf(p.char_code + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
    resumeCategoryJobsLabel: function(job_list) {
        let selected_list = _.map(
			_.filter(this.$data.category_job_list_ungrouped, function(p) {
				return job_list.indexOf( p.id ) !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
    },
    onChangeResumeCategoryJob: function() {
        // this.$data.resume.selected_resume_category_jobs;
    },
    onChangeResumeJobsCategoryJob: function(index) {
        this.$data.job_list[index].edit_data.category_job_id = this.$data.select_job_list_edit[index].id;
    },
    getFullName: function(resume_info) {
        let full_name_arr = [resume_info.first_name];
        
        if (resume_info.middle_name) {
            full_name_arr.push(resume_info.middle_name);
        }

        if (resume_info.last_name) {
            full_name_arr.push(resume_info.last_name);
        }

        return full_name_arr.join(' ');
    },
    genderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.resume_info.edit_data.gender_list = selected_genders;
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
    countryChanged: function() {
        let country_char_code = this.$data.resume_info.edit_data.country_name;
        
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
		let item = _.find(this.$data.worker_country_list, function(p) {
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
    formatDate: function(date_obj) {
        return date_obj;
    },
    changePhoto: function() {
        if( this.$refs.photo.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let file = this.$refs.photo.files[0];
        let img_supported_types = ['image/png', 'image/jpeg'];

        let regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        if ( !(regex.test(file.name.toLowerCase()))
            || img_supported_types.indexOf(file.type) == -1) {
            alert('Unsupported file type');
            return;
        }

        let reader = new FileReader();

        let me = this;
        reader.onload = function (e) {
            // clientside resize image
            let img = new Image();
            img.src = e.target.result;
            img.onload = function() {
                img = me.compressImage(img, 75, 1024);

                me.$data.resume_info3.edit_data = JSON.parse(JSON.stringify(me.$data.resume_info3.source_data));
                me.$data.resume_info3.edit_data.photo_src = img.src;
                me.$data.resume_info3.edit_data.photo_path = file.name.toLowerCase();

                me.$data.resume_info3_is_edit_mode = true;
                me.$data.resume_info3.edit_mode = true;
            }
        }

        reader.readAsDataURL(file);
    },
    savePhoto: function() {
        this.$data.submit_clicked = true;

        let post_data = {
            'Resume[photo_path]': this.$data.resume_info3.edit_data.photo_path,
            'Resume[src]': this.$data.resume_info3.edit_data.photo_src,
        };

        let me = this;
        this.sendFieldsToServer(
            post_data,
            function(data) {
                me.$data.resume_info3.source_data = JSON.parse(JSON.stringify(me.$data.resume_info3.edit_data));
                me.$data.resume_info3.edit_mode = false;
                me.$data.submit_clicked = false;
            }
        );
    },
    cancelEditPhoto: function() {
        // this.$data.resume_info3_is_edit_mode = false;
        this.$data.resume_info3.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    removePhoto: function() {
        this.$data.resume_info3.edit_data.photo_path = '';
        this.$data.resume_info3.edit_data.photo_src = void 0;

        this.$data.resume_info3_is_edit_mode = true;
        this.$data.resume_info3.edit_mode = true;
    },
    getImagePath: function(photo_src) {
        if(photo_src)
            return photo_src;
        
        return this.$data.default_resume_image;
    },
    //-- ResumeJob
    addJob: function() {
        this.$data.resume_job_is_edit_mode = true;
        
        let new_job_item = {
            edit_mode: false,
            is_new: true,
            source_data: {
                category_job_id: "",
                company_name: "",
                month: "",
                years: "",
                for_now: <?= ResumeJob::STATUS_FOR_NOW_NO ?>
            },
            // edit_data: {} // copy object on edit
        };

        this.$data.job_list.push(new_job_item);

        this.editJob(this.$data.job_list.length - 1);
    },
    editJob: function(index) {
        // switch form to edit mode
        this.$data.resume_job_is_edit_mode = true;

        // make a copy source data
        this.$data.job_list[index].edit_data = JSON.parse(JSON.stringify(this.$data.job_list[index].source_data));

        
        // fill job_list
        for (let j = 0; j < this.$data.category_job_list_ungrouped.length; j++) {
            if (this.$data.job_list[index].edit_data.category_job_id == this.$data.category_job_list_ungrouped[j].id) {
                this.$data.select_job_list_edit[index] = this.$data.category_job_list_ungrouped[j];
                break; // founded exit
            }
        }
        
        // show edit form for current element
        this.$data.job_list[index].edit_mode = true;
    },
    saveJob: function(index) {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = { // flag server to clear this relations:
                'relations[0]': 'resumeJobs'
            };

            for(let i = 0; i < this.$data.job_list.length; i++) {
                let form_data = this.$data.job_list[i].source_data;

                if(this.$data.job_list[i].edit_data) { // just modifed
                    form_data = this.$data.job_list[i].edit_data;
                }

                post_data['Resume[resumeJobs]['+i+'][id]'] = form_data.id; // need for optimization SQL update request
                post_data['Resume[resumeJobs]['+i+'][category_job_id]'] = form_data.category_job_id;
                post_data['Resume[resumeJobs]['+i+'][company_name]'] = form_data.company_name;
                post_data['Resume[resumeJobs]['+i+'][month]'] = form_data.month;
                post_data['Resume[resumeJobs]['+i+'][years]'] = form_data.years;
                post_data['Resume[resumeJobs]['+i+'][for_now]'] = this.getForNowValue(form_data.for_now);
            }

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.submit_clicked = false;
                    if (index !== void 0) { // if index empty then just removed
                        me.$data.job_list[index].source_data = JSON.parse(JSON.stringify(me.$data.job_list[index].edit_data));
                        me.$data.select_job_list_source[index] = me.$data.select_job_list_edit[index];
                        me.$data.job_list[index].edit_mode = false;
                        me.$data.job_list[index].is_new = false;
                    }
                }
            );
        });
    },
    cancelEditJob: function(index) {
        this.$data.job_list[index].edit_mode = false;
        this.$data.submit_clicked = false;
        
        // if iten not saved to server just remove it from list
        if(this.$data.job_list[index].is_new) {
            this.$data.job_list.splice(index, 1);
        }
    },
    removeJob: function(index) {
        if (confirm("<?= Yii::t('main', 'Remove') ?> эту запись?")) {
            // switch form to edit mode
            this.$data.resume_job_is_edit_mode = true;

            let is_new = this.$data.job_list[index].is_new;
            this.$data.job_list.splice(index, 1);
            if (!is_new) {
                this.saveJob();
            }
        }
    },
    //--
    //-- ResumeEducation
    addEducation: function() {
        this.$data.resume_education_is_edit_mode = true;
        
        let new_education_item = {
            edit_mode: false,
            is_new: true,
            source_data: {
                description: "",
            },
            // edit_data: {} // copy object on edit
        };

        this.$data.education_list.push(new_education_item);

        this.editEducation(this.$data.education_list.length - 1);
    },
    editEducation: function(index) {
        // switch form to edit mode
        this.$data.resume_education_is_edit_mode = true;

        // make a copy source data
        this.$data.education_list[index].edit_data = JSON.parse(JSON.stringify(this.$data.education_list[index].source_data));

        // show edit form for current element
        this.$data.education_list[index].edit_mode = true;
    },
    saveEducation: function(index) {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = { // flag server to clear this relations:
                'relations[0]': 'resumeEducations'
            };
            
            for(let i = 0; i < this.$data.education_list.length; i++) {
                let form_data = this.$data.education_list[i].source_data;

                if(this.$data.education_list[i].edit_data) { // just modifed
                    form_data = this.$data.education_list[i].edit_data;
                }

                post_data['Resume[resumeEducations]['+i+'][id]'] = form_data.id; // need for optimization SQL update request
                post_data['Resume[resumeEducations]['+i+'][description]'] = form_data.description;
            }

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.submit_clicked = false;
                    if (index !== void 0) { // if index empty then just removed
                        me.$data.education_list[index].source_data = JSON.parse(JSON.stringify(me.$data.education_list[index].edit_data));
                        me.$data.education_list[index].edit_mode = false;
                        me.$data.education_list[index].is_new = false;
                    }
                }
            );
        });
    },
    cancelEditEducation: function(index) {
        this.$data.education_list[index].edit_mode = false;
        this.$data.submit_clicked = false;
        
        // if iten not saved to server just remove it from list
        if(this.$data.education_list[index].is_new) {
            this.$data.education_list.splice(index, 1);
        }
    },
    removeEducation: function(index) {
        if (confirm("<?= Yii::t('main', 'Remove') ?> эту запись?")) {
            // switch form to edit mode
            this.$data.resume_education_is_edit_mode = true;

            let is_new = this.$data.education_list[index].is_new;
            this.$data.education_list.splice(index, 1);
            if (!is_new) {
                this.saveEducation();
            }
        }
    },
    //--
    //-- ResumeLanguage
    addLanguage: function() {
        this.$data.resume_language_is_edit_mode = true;

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
        // switch form to edit mode
        this.$data.resume_language_is_edit_mode = true;

        // show edit form for current element
        this.$data.language_list[index].edit_mode = true;

        // make a copy source data
        this.$data.language_list[index].edit_data.country_code = this.$data.language_list[index].source_data.country_code;
        this.$data.language_list[index].edit_data.level = this.$data.language_list[index].source_data.level;
        this.$data.language_list[index].edit_data.can_be_interviewed = this.$data.language_list[index].source_data.can_be_interviewed;
    },
    saveLanguage: function(index) {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = { // flag server to clear this relations:
                'relations[0]': 'resumeLanguages'
            };
            
            for(let i = 0; i < this.$data.language_list.length; i++) {
                let form_data = this.$data.language_list[i].source_data;

                if(this.$data.language_list[i].edit_mode) { // just modifed
                    form_data = this.$data.language_list[i].edit_data;
                }

                post_data['Resume[resumeLanguages]['+i+'][id]'] = form_data.id; // need for optimization SQL update request
                post_data['Resume[resumeLanguages]['+i+'][country_code]'] = form_data.country_code;
                post_data['Resume[resumeLanguages]['+i+'][level]'] = form_data.level;
                post_data['Resume[resumeLanguages]['+i+'][can_be_interviewed]'] = this.getCanBeInterviewedValue(form_data.can_be_interviewed);
            }

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.submit_clicked = false;
                    if (index !== void 0) { // if index empty then just removed
                        me.$data.language_list[index].source_data = JSON.parse(JSON.stringify(me.$data.language_list[index].edit_data));
                        me.$data.language_list[index].edit_mode = false;
                        me.$data.language_list[index].is_new = false;
                    }
                }
            );
        });
    },
    cancelEditLanguage: function(index) {
        this.$data.language_list[index].edit_mode = false;
        this.$data.submit_clicked = false;
        
        // if iten not saved to server just remove it from list
        if(this.$data.language_list[index].is_new) {
            this.$data.language_list.splice(index, 1);
        }
    },
    removeLanguage: function(index) {
        if (confirm("<?= Yii::t('main', 'Remove') ?> эту запись?")) {
            // switch form to edit mode
            this.$data.resume_language_is_edit_mode = true;

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
    //--
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
    collectFormFields: function() {
        let post_data = [];
        for(let i = 0; i < this.$refs.form.length; i++) {
            post_data.push(this.$refs.form[i].name);
        }

        console.log(post_data.join(','));
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
});
</script>
<?php $this->endJs(); ?>