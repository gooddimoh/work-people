<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Vacancy;
use app\models\Category;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;

// var_dump(VeeValidateHelper::getVValidateString($this, $model, 'salary_per_hour_max'));
// die();

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

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
$category_list = Category::getUserSelectList();
$job_list = CategoryJob::getUserMultiSelectList();

?>

<div id="appCreateVacancy" class="registration registration--2">
    <div class="container">
        <div class="registration__number-step">
            <b><?=Yii::t('main', 'Step') ?> <span v-html="form_current_step">1</span> <?= Yii::t('main', 'of') ?> 3</b>&nbsp;
            <!-- <span v-if="form_current_step == 1"><?= Yii::t('vacancy', 'Basic information') ?></span>
            <span v-if="form_current_step == 2" v-cloak><?= Yii::t('vacancy', 'Desired area of work') ?></span>
            <span v-if="form_current_step == 3" v-cloak><?= Yii::t('vacancy', 'Experience') ?></span> -->
        </div>
        <div class="row">
            <div class="registration__main col">
                <!-- form here -->
                <?php $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => '',
                        'v-on:submit.prevent' => "onSubmit",
                        'ref' => 'form'
                    ]
                ]); ?>
                    <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                        <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
                        <ul v-for="(error_messages, field_name) of response_errors">
                            <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                        </ul>

                        <ul v-if="submit_clicked && errors.all()">
                            <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                        <ul>
                    </div>
                    <!-- step 1 -->
                    <div class="registration__animate j-step active" v-if="form_current_step == 1">
                        <div class="registration__inner" style="padding-top: 30px;">
                            <div class="registration__title2">
                                <b><?= Yii::t('vacancy', 'Post a job') ?></b> (<?= Yii::t('vacancy', 'it takes 3 minutes') ?>)
                            </div>
                            <div class="registration__form">
                                <div class="registration__input-bl">
                                    <div class="registration__input-title">
                                        <?= $model->getAttributeLabel('title') ?>
                                    </div>
                                    <input v-validate="vv.model_vacancy.title" name='Vacancy[title]' class="form-control" type="text" v-model="vacancy.title">
                                    <span v-show="errors.has('Vacancy[title]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[title]') }}
                                    </span>
                                </div>
                                <div class="registration__input-bl">
                                    <div class="registration__input-title">
                                        <?= $model->getAttributeLabel('company_name') ?>
                                        <div class="registration__input-desc">
                                            <?= Yii::t('vacancy', 'Example') ?>: skoda, panasonic
                                        </div>
                                    </div>
                                    <input v-validate="vv.model_vacancy.company_name" name='Vacancy[company_name]' class="form-control" type="text" v-model="vacancy.company_name">
                                    <span v-show="errors.has('Vacancy[company_name]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[company_name]') }}
                                    </span>
                                </div>
                                <div class="registration__input-bl">
                                    <div class="registration__input-title">
                                        <?= $model->getAttributeLabel('category_job_id') ?>
                                        <div class="registration__input-desc">
                                            <?= Yii::t('vacancy', 'Example') ?>: <?= Yii::t('vacancy', 'operator') ?>, <?= Yii::t('vacancy', 'welder') ?>
                                        </div>
                                    </div>
                                    <input v-validate="vv.model_vacancy.category_job_id" name='Vacancy[category_job_id]' class="form-control" type="hidden" v-model="vacancy.category_job_id">
                                    <multiselect v-model="vacancy.category_job_item" :options="category_job_list" :multiple="false" group-values="jobs" group-label="group_name" :group-select="false" placeholder="<?= Yii::t('main', 'Type to search') ?>" track-by="id" label="name" v-on:input="onChangeCategoryJob"><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
                                    <span v-show="errors.has('Vacancy[category_job_id]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[category_job_id]') }}
                                    </span>
                                </div>
                                <div class="registration__input-bl">
                                    <div class="registration__input-title" style="margin: 20px 0 30px;">
                                        <?= Yii::t('vacancy', 'Job posting categories') ?>
                                        <div class="registration__input-desc">
                                            <?= Yii::t('vacancy', 'Choose your preferred') ?>
                                        </div>
                                    </div>

                                    <div class="registration__row">
                                        <label v-for="category_item in category_list" class="checkbox" v-cloak>
                                            <input type="checkbox" v-model="category_item.checked" v-on:change="selectCategory">
                                            <span class="checkbox__check"></span>
                                            <span class="checkbox__title">
                                                {{category_item.name}}
                                            </span>
                                        </label>
                                    </div>

                                    <input v-validate="vv.model_vacancy.selected_categories" name='Vacancy[category_list]' type="hidden" v-model="vacancy.selected_categories">
                                    <span v-show="errors.has('Vacancy[category_list]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[category_list]') }}
                                    </span>
                                </div>
                                <div class="registration__input-bl">
                                    <div class="registration__input-title" style="margin: 20px 0 30px;">
                                        <?= $model->getAttributeLabel('worker_country_codes') ?>
                                    </div>

                                    <div class="registration__row">
                                        <label class="checkbox">
                                            <input type="checkbox" v-model="vacancy.worker_country_codes_all">
                                            <span class="checkbox__check"></span>
                                            <span class="checkbox__title">
                                                <?= Yii::t('vacancy', 'All countries') ?>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="registration__row" v-if="!vacancy.worker_country_codes_all">
                                        <label v-for="country_item in country_list" class="checkbox" v-cloak>
                                            <input type="checkbox" v-model="country_item.checked" v-on:change="vacancyCountryCodesChange">
                                            <span class="checkbox__check"></span>
                                            <span class="checkbox__title">
                                                {{country_item.name}}
                                            </span>
                                        </label>
                                    </div>

                                    <input v-validate="vv.model_vacancy.worker_country_codes" name='Vacancy[worker_country_codes]' type="hidden" v-model="vacancy.worker_country_codes">
                                    <span v-show="errors.has('Vacancy[worker_country_codes]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[worker_country_codes]') }}
                                    </span>
                                </div>
                                <div class="registration__input-bl">
                                    <div class="registration__input-title" style="margin: 20px 0 30px;">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>

                                    <div class="registration__row">
                                        <label v-for="gender_item in gender_list" class="checkbox" v-cloak>
                                            <input type="checkbox" v-model="gender_item.checked" v-on:change="vacancyGenderChange">
                                            <span class="checkbox__check"></span>
                                            <span class="checkbox__title">
                                                {{gender_item.name}}
                                            </span>
                                        </label>
                                    </div>
                                    
                                    <input v-validate="vv.model_vacancy.gender_list" name='Vacancy[gender_list]' type="hidden" v-model="vacancy.gender_list">
                                    <span v-show="errors.has('Vacancy[gender_list]')" class="validation-error-message" v-cloak>
                                        {{ errors.first('Vacancy[gender_list]') }}
                                    </span>
                                </div>
                                <div class="registration__submit">
                                    <button type="button" class="btn j-next-step" v-on:click="nextStep">
                                        <?= Yii::t('vacancy', 'Next') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ step 1 -->
                    <!-- step 2 -->
                    <div class="registration__animate registration__inner j-step active" style="padding-top: 40px;" v-if="form_current_step == 2" v-cloak>
                        <div class="registration__form">
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('free_places') ?>
                                </div>
                                
                                <div class="registration__row">
                                <?php /*
                                    <label class="checkbox" style="margin-bottom: 25px;">
                                        <input type="hidden" name="Vacancy[regular_places]`" v-bind:value="getRegularPlacesValue(vacancy.regular_places)">
                                        <input type="checkbox" v-model="vacancy.regular_places">
                                        <span class="checkbox__check"></span>
                                        <span class="checkbox__title">
                                            <?= $model->getAttributeLabel('regular_places') ?>
                                        </span>
                                    </label>
                                */ ?>
                                    <div>
                                        <input v-validate="vv.model_vacancy.free_places" name='Vacancy[free_places]' class="form-control" style="width: 100px;" type="text" v-model="vacancy.free_places">
                                    </div>
                                </div>
                                <span v-show="errors.has('Vacancy[free_places]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[free_places]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('date_start') ?>
                                </div>

                                <label class="checkbox" style="margin-top: 15px;">
                                    <input type="hidden" name="Vacancy[date_free]`" v-bind:value="getDateFreeValue(vacancy.date_free)">
                                    <input type="checkbox" v-model="vacancy.date_free">
                                    <span class="checkbox__check"></span>
                                    <span class="checkbox__title">
                                        <?= $model->getAttributeLabel('date_free') ?>
                                    </span>
                                </label>

                                <div class="input-group" style="max-width: 290px;margin-bottom: 15px;">
                                    <div class="input-group__col">
                                        <air-datepicker v-validate="vv.model_vacancy.date_start" name='Vacancy[date_start]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="vacancy.date_start" class="j-edit-input edit"></air-datepicker>
                                        <!-- <input v-validate="vv.model_vacancy.date_start" name='Vacancy[date_start]' type="text" id="vacancy-date_start" v-model="vacancy.date_start" class="j-edit-input edit"> -->
                                        <span v-show="errors.has('Vacancy[date_start]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[date_start]') }}
                                        </span>
                                    </div>
                                    <div class="input-group__col">
                                        <air-datepicker v-validate="vv.model_vacancy.date_end" name='Vacancy[date_end]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="vacancy.date_end" class="j-edit-input edit"></air-datepicker>
                                        <!-- <input v-validate="vv.model_vacancy.date_end" name='Vacancy[date_end]' type="text" id="vacancy-date_end" v-model="vacancy.date_end" class="j-edit-input edit"> -->
                                        <span v-show="errors.has('Vacancy[date_end]')" class="validation-error-message">
                                            {{ errors.first('Vacancy[date_end]') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('country_name') ?>
                                </div>
                                <nice-select :options="country_list" name="Vacancy[country_name]`" class="select select--double" v-model="vacancy.country_name" v-on:input="countryChanged">
                                    <option v-for="item in country_list" :value="item.char_code" >
                                        {{ item.name }}
                                    </option>
                                </nice-select>
                                <input v-validate="vv.model_vacancy.country_name" name='Vacancy[country_name]' type="hidden" v-model="vacancy.country_name">
                                <span v-show="errors.has('Vacancy[country_name]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[country_name]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('country_city_id') ?>
                                </div>
                                <nice-select :options="country_city_list" name="Vacancy[country_city_id]`" class="select" v-model="vacancy.country_city_id" v-if="country_city_refresh_flag">
                                    <option v-for="item in country_city_list" :value="item.id">
                                        {{ item.city_name }}
                                    </option>
                                </nice-select>
                                <input v-validate="vv.model_vacancy.country_city_id" name='Vacancy[country_city_id]' type="hidden" v-model="vacancy.country_city_id">
                                <span v-show="errors.has('Vacancy[country_city_id]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[country_city_id]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= Yii::t('vacancy', 'Salary per hour') ?>
                                </div>
                                <div class="input-group">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.salary_per_hour_min" name='Vacancy[salary_per_hour_min]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'from') ?>" v-model="vacancy.salary_per_hour_min">
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.salary_per_hour_max" name='Vacancy[salary_per_hour_max]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'to') ?>" v-model="vacancy.salary_per_hour_max">
                                    </div>
                                    <div class="input-group__col">
                                        <nice-select :options="currency_list" name="Vacancy[currency_code]`" class="select" v-model="vacancy.currency_code">
                                            <option v-for="item in currency_list" :value="item.char_code" >
                                                {{ item.name }}
                                            </option>
                                        </nice-select>
                                    </div>
                                </div>
                                <div v-show="errors.has('Vacancy[salary_per_hour_min]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[salary_per_hour_min]') }}
                                </div>
                                <div v-show="errors.has('Vacancy[salary_per_hour_max]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[salary_per_hour_max]') }}
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= Yii::t('vacancy', 'Hours per day') ?>
                                </div>
                                <div class="input-group" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.hours_per_day_min" name='Vacancy[hours_per_day_min]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'from')?>" v-model="vacancy.hours_per_day_min">
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.hours_per_day_max" name='Vacancy[hours_per_day_max]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'to')?>" v-model="vacancy.hours_per_day_max">
                                    </div>
                                </div>
                                <div v-show="errors.has('Vacancy[hours_per_day_min]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[hours_per_day_min]') }}
                                </div>
                                <div v-show="errors.has('Vacancy[hours_per_day_max]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[hours_per_day_max]') }}
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= Yii::t('vacancy', 'Days per week') ?>
                                </div>
                                <div class="input-group" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.days_per_week_min" name='Vacancy[days_per_week_min]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'from')?>" v-model="vacancy.days_per_week_min">
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.days_per_week_max" name='Vacancy[days_per_week_max]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'to')?>" v-model="vacancy.days_per_week_max">
                                    </div>
                                </div>
                                <div v-show="errors.has('Vacancy[days_per_week_min]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[days_per_week_min]') }}
                                </div>
                                <div v-show="errors.has('Vacancy[days_per_week_max]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[days_per_week_max]') }}
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= Yii::t('vacancy', 'Preliminary salary per month') ?>
                                </div>
                                <div class="input-group" style="max-width: 290px;">
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.prepaid_expense_min" name='Vacancy[prepaid_expense_min]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'from') ?>" v-model="vacancy.prepaid_expense_min">
                                    </div>
                                    <div class="input-group__col">
                                        <input v-validate="vv.model_vacancy.prepaid_expense_max" name='Vacancy[prepaid_expense_max]' class="form-control" type="text" placeholder="<?= Yii::t('vacancy', 'to') ?>" v-model="vacancy.prepaid_expense_max">
                                    </div>
                                </div>
                                <span v-show="errors.has('Vacancy[prepaid_expense_min]')" class="validation-error-message error_tool_tip">
                                    {{ errors.first('Vacancy[prepaid_expense_min]') }}
                                </span>
                                <span v-show="errors.has('Vacancy[prepaid_expense_max]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[prepaid_expense_max]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin: 25px 0 25px;">
                                    <?= $model->getAttributeLabel('type_of_working_shift') ?>
                                </div>

                                <div class="registration__row">
                                
                                    <label v-for="type_of_working_shift_item in type_of_working_shift_list" class="checkbox" v-cloak>
                                        <input type="checkbox" v-model="type_of_working_shift_item.checked" v-on:change="vacancyTypeOfWorkingShiftChange">
                                        <span class="checkbox__check"></span>
                                        <span class="checkbox__title">
                                            {{type_of_working_shift_item.name}}
                                        </span>
                                    </label>
                                </div>

                                <input v-validate="vv.model_vacancy.type_of_working_shift" name='Vacancy[type_of_working_shift]' type="hidden" v-model="vacancy.type_of_working_shift">
                                <span v-show="errors.has('Vacancy[type_of_working_shift]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[type_of_working_shift]') }}
                                </span>
                            </div>
                            <div class="registration__submit">
                                <div>
                                    <button type="button" class="btn btn--transparent j-prev-step" v-on:click="previousStep">
                                        <?= Yii::t('vacancy', 'Previous step') ?>
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn j-next-step" v-on:click="nextStep">
                                        <?= Yii::t('main', 'Next') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ step 2 -->
                    <!-- step 3 -->
                    <div class="registration__animate registration__inner j-step active" style=" padding-top: 40px;" v-if="form_current_step == 3" v-cloak>
                        <div class="registration__form">
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin-bottom: 15px;">
                                    <?= $model->getAttributeLabel('residence_provided') ?>
                                </div>
                                <div class="registration__prop">
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[residence_provided]" class="btn" value="<?= Vacancy::RESIDENCE_PROVIDED_YES ?>" v-model="vacancy.residence_provided">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('vacancy', 'Provided') ?>
                                        </span>
                                    </label>
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[residence_provided]" class="btn" value="<?= Vacancy::RESIDENCE_PROVIDED_NO ?>" v-model="vacancy.residence_provided">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('vacancy', 'Not provided') ?>
                                        </span>
                                    </label>
                                    <div v-if="vacancy.residence_provided == <?= Vacancy::RESIDENCE_PROVIDED_YES ?>" class="registration__price-month j-block-prop" style="display: block;">
                                        <div class="registration__input-bl">
                                            <div class="registration__input-title">
                                                <?= $model->getAttributeLabel('residence_amount') ?>
                                            </div>
                                            <div class="input-group" style="max-width: 250px;">
                                                <div class="input-group__col">
                                                    <input v-validate="vv.model_vacancy.residence_amount" name='Vacancy[residence_amount]' class="form-control" type="text" v-model="vacancy.residence_amount">
                                                    <span v-show="errors.has('Vacancy[residence_amount]')" class="validation-error-message">
                                                        {{ errors.first('Vacancy[residence_amount]') }}
                                                    </span>
                                                </div>
                                                <div class="input-group__col" style="width: 100px;flex-shrink: 0;">
                                                    <nice-select :options="currency_list" name="Vacancy[residence_amount_currency_code]`" class="select" v-model="vacancy.residence_amount_currency_code">
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
                                            <input v-validate="vv.model_vacancy.residence_people_per_room" name='Vacancy[residence_people_per_room]' class="form-control" type="text" style="width: 100px;" v-model="vacancy.residence_people_per_room">
                                            <span v-show="errors.has('Vacancy[residence_people_per_room]')" class="validation-error-message">
                                                {{ errors.first('Vacancy[residence_people_per_room]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin: 25px 0 25px;">
                                    <?= $model->getAttributeLabel('documents_provided') ?>
                                    <div class="registration__input-desc">
                                        <?= Yii::t('vacancy', 'Choose some') ?>
                                    </div>
                                </div>
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
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin: 20px 0 30px;">
                                    <?= $model->getAttributeLabel('documents_required') ?>
                                    <div class="registration__input-desc">
                                        <?= Yii::t('vacancy', 'Choose some') ?>
                                    </div>
                                </div>
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
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin: 20px 0 10px;">
                                    <?= $model->getAttributeLabel('job_description') ?>
                                    <div class="registration__input-desc">
                                        <?= Yii::t('vacancy', 'The more you write, the more chances to get the best candidates') ?>
                                    </div>
                                </div>
                                <textarea v-validate="vv.model_vacancy.job_description" name='Vacancy[job_description]' cols="30" rows="10" v-model="vacancy.job_description"></textarea>
                                <span v-show="errors.has('Vacancy[job_description]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[job_description]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin: 20px 0 10px;">
                                    <?= $model->getAttributeLabel('job_description_bonus') ?>
                                    <div class="registration__input-desc">
                                        <?= Yii::t('vacancy', 'Write the benefits to get more resumes') ?>
                                    </div>
                                </div>
                                <div class="textarea">
                                    <textarea v-validate="vv.model_vacancy.job_description_bonus" name='Vacancy[job_description_bonus]' cols="30" rows="10" v-model="vacancy.job_description_bonus"></textarea>
                                    <div class="textarea__col">
                                        <div class="textarea__inner">
                                            <div class="textarea__title">
                                                <?=Yii::t('vacancy', 'example') ?>:
                                            </div>
                                            <ul>
                                                <li><?= Yii::t('vacancy', 'We work 24/7.') ?></li>
                                                <li><?= Yii::t('vacancy', 'Near the house.') ?></li>
                                                <li><?= Yii::t('vacancy', 'Introducing Documents.') ?></li>
                                                <li><?= Yii::t('vacancy', 'Residence permit.') ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <span v-show="errors.has('Vacancy[job_description_bonus]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[job_description_bonus]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('contact_name') ?>
                                </div>
                                <input v-validate="vv.model_vacancy.contact_name" name='Vacancy[contact_name]' class="form-control" type="text" v-model="vacancy.contact_name">
                                <span v-show="errors.has('Vacancy[contact_name]')" class="validation-error-message" v-cloak>
                                    {{ errors.first('Vacancy[contact_name]') }}
                                </span>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <?= $model->getAttributeLabel('contact_phone') ?>
                                </div>
                                <span v-for="(contact_phone_item, index) of vacancy.contact_phones">
                                    <div class="reg-lang__top" v-if="index != 0">
                                        <div class="reg-lang__title">
                                        </div>
                                        <div class="reg-lang__edit">
                                            <a class="pointer" v-on:click="removePhone(index)"><?= Yii::t('main', 'Remove') ?></a>
                                        </div>
                                    </div>
                                    <input v-validate="vv.model_vacancy.contact_phones" v-bind:name='`Vacancy[contact_phones][${index}]`' class="form-control" type="text" v-model="vacancy.contact_phones[index]" v-on:change="changePhone">
                                    <span v-show="errors.has('Vacancy[contact_phones][' + index + ']')" class="validation-error-message">
                                        {{ errors.first('Vacancy[contact_phones][' + index + ']') }}
                                    </span>
                                </span>
                                <input v-validate="vv.model_vacancy.contact_phone_limit" name='Vacancy[contact_phone]' class="form-control" type="hidden" v-model="vacancy.contact_phone">
                                <span v-show="errors.has('Vacancy[contact_phone]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[contact_phone]') }}
                                </span>
                                <div class="registration__add-email" v-on:click="addPhone">
                                    <?= Yii::t('vacancy', 'Add an alternate phone') ?>
                                </div>
                            </div>
                            <div class="registration__input-bl">
                                <div class="registration__input-title">
                                    <!-- <?= $model->getAttributeLabel('contact_email_list') ?> -->
                                    <?= Yii::t('vacancy', 'Applications for this work will be sent to the following email addresses') ?>
                                </div>
                                <span v-for="(email_item, index) of vacancy.contact_emails">
                                    <div class="reg-lang__top" v-if="index != 0">
                                        <div class="reg-lang__title">
                                        </div>
                                        <div class="reg-lang__edit">
                                            <a class="pointer" v-on:click="removeEmail(index)"><?= Yii::t('main', 'Remove') ?></a>
                                        </div>
                                    </div>
                                    <input v-validate="vv.model_vacancy.contact_email_list" v-bind:name='`Vacancy[contact_emails][${index}]`' class="form-control" type="text" v-model="vacancy.contact_emails[index]" v-on:change="changeEmail">
                                    <span v-show="errors.has('Vacancy[contact_emails][' + index + ']')" class="validation-error-message">
                                        {{ errors.first('Vacancy[contact_emails][' + index + ']') }}
                                    </span>
                                </span>
                                <input v-validate="vv.model_vacancy.contact_email_limit" name='Vacancy[contact_email_list]' class="form-control" type="hidden" v-model="vacancy.contact_email_list">
                                <span v-show="errors.has('Vacancy[contact_email_list]')" class="validation-error-message">
                                    {{ errors.first('Vacancy[contact_email_list]') }}
                                </span>
                                <div class="registration__add-email" v-on:click="addEmail">
                                    <?= Yii::t('vacancy', 'Add an alternate email address') ?>
                                </div>
                            </div>
                            <div class="registration__input-bl j-input" style="margin-top: 30px;">
                                <div class="registration__input-title">
                                    <?= Yii::t('vacancy', 'If you have a photo of the enterprise, people, housing, dining room - upload them') ?>
                                </div>
                                <div class="upload__preview">
                                    <div v-for="(photo, index) of vacancy.photos_list" class="upload__item" v-on:click="removePhoto(index)">
                                        <img :src="photo.photo_src" :alt="photo.path_name">
                                    </div>
                                </div>
                                <input type="file" id="photos" name="photos" ref="photos" style="display: none;" v-on:change="addPhoto" multiple>
                                <label class="upload" for="photos">
                                    <div class="btn btn--transparent">
                                        <?= Yii::t('main', 'Upload') ?>
                                    </div>
                                </label>
                                <div class="validation-error-message" v-if="file_format_error"><?= Yii::t('vacancy', 'Invalid file format') ?></div>
                            </div>
                            <div class="registration__submit">
                                <div>
                                    <button type="button" class="btn btn--transparent j-prev-step" v-on:click="previousStep">
                                        <?= Yii::t('vacancy', 'Previous step') ?>
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn j-next-step" v-on:click="nextStep">
                                        <!-- <?= Yii::t('vacancy', 'Next') ?> -->
                                        <?= Yii::t('vacancy', 'Create a job') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ step 3 -->
                    <!-- step 4 -->
                    <div class="registration__animate registration__inner j-step active" style=" padding-top: 40px;" v-if="form_current_step == 4" v-cloak>
                        <div class="registration__form">
                            <div class="registration__input-bl">
                                <div class="registration__input-title" style="margin-bottom: 15px;">
                                    <?= $model->getAttributeLabel('agency_accept') ?>
                                    <div class="registration__input-desc">
                                        "<?= Yii::t('vacancy', 'Agencies and recruiters will help you find staff') ?>"
                                    </div>
                                </div>
                                <div class="registration__prop registration__prop--full">
                                    
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[agency_accept]" class="btn" value="<?= Vacancy::AGENCY_ACCEPT_YES ?>" v-model="vacancy.agency_accept">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('main', 'Yes') ?>
                                        </span>
                                    </label>
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[agency_accept]" class="btn" value="<?= Vacancy::AGENCY_ACCEPT_NO ?>" v-model="vacancy.agency_accept">
                                        <span class="radio__title btn btn--transparent">
                                            <?= Yii::t('main', 'No') ?>
                                        </span>
                                    </label>
                                    <div v-if="vacancy.agency_accept == <?= Vacancy::AGENCY_ACCEPT_YES ?>" class="registration__price-month j-block-prop" style="display: block;">
                                        <div>
                                            <label class="checkbox j-radio-prop">
                                                <input type="hidden" name="Vacancy[agency_paid_document]`" v-bind:value="getAgencyPaidDocumentValue(vacancy.agency_paid_document)" class="btn">
                                                <input type="checkbox" v-model="vacancy.agency_paid_document">
                                                <span class="checkbox__check"></span>
                                                <span class="checkbox__title">
                                                    <?= $model->getAttributeLabel('agency_paid_document') ?>
                                                </span>
                                            </label>
                                            <div v-if="vacancy.agency_paid_document" class="registration__input-bl registration__input-bl--white j-block-prop">
                                                <div class="registration__input-title">
                                                    <?= $model->getAttributeLabel('agency_paid_document_price') ?>
                                                </div>
                                                <div class="input-group" style="max-width: 250px;">
                                                    <div class="input-group__col">
                                                        <input v-validate="vv.model_vacancy.agency_paid_document_price" name='Vacancy[agency_paid_document_price]' class="form-control" type="text" v-model="vacancy.agency_paid_document_price">
                                                        <span v-show="errors.has('Vacancy[agency_paid_document_price]')" class="validation-error-message">
                                                            {{ errors.first('Vacancy[agency_paid_document_price]') }}
                                                        </span>
                                                    </div>
                                                    <div class="input-group__col" style="width: 100px;flex-shrink: 0;">
                                                        <nice-select :options="currency_list" name="Vacancy[agency_paid_document_currency_code]`" class="select" v-model="vacancy.agency_paid_document_currency_code">
                                                            <option v-for="item in currency_list" :value="item.char_code" >
                                                                {{ item.name }}
                                                            </option>
                                                        </nice-select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="checkbox j-radio-prop">
                                            <input type="hidden" name="Vacancy[agency_free_document]`" v-bind:value="getAgencyFreeDocumentValue(vacancy.agency_free_document)" class="btn">
                                            <input type="checkbox" v-model="vacancy.agency_free_document">
                                            <span class="checkbox__check"></span>
                                            <span class="checkbox__title">
                                                <?= $model->getAttributeLabel('agency_free_document') ?>
                                            </span>
                                        </label>
                                        <div>
                                            <label class="checkbox j-radio-prop">
                                                <input type="hidden" name="Vacancy[agency_pay_commission]`" v-bind:value="getAgencyPayCommissionValue(vacancy.agency_pay_commission)" class="btn">
                                                <input type="checkbox" v-model="vacancy.agency_pay_commission">
                                                <span class="checkbox__check"></span>
                                                <span class="checkbox__title">
                                                    <?= $model->getAttributeLabel('agency_pay_commission') ?>
                                                </span>
                                            </label>
                                            <div v-if="vacancy.agency_pay_commission" class="registration__input-bl registration__input-bl--white j-block-prop">
                                                <div class="registration__input-title">
                                                    <?= $model->getAttributeLabel('agency_pay_commission_amount') ?>
                                                </div>
                                                <div class="input-group" style="max-width: 250px;">
                                                    <div class="input-group__col">
                                                        <input v-validate="vv.model_vacancy.agency_pay_commission_amount" name='Vacancy[agency_pay_commission_amount]' class="form-control" type="text" v-model="vacancy.agency_pay_commission_amount">
                                                        <span v-show="errors.has('Vacancy[agency_pay_commission_amount]')" class="validation-error-message">
                                                            {{ errors.first('Vacancy[agency_pay_commission_amount]') }}
                                                        </span>
                                                    </div>
                                                    <div class="input-group__col" style="width: 100px;flex-shrink: 0;">
                                                        <nice-select :options="currency_list" name="Vacancy[agency_pay_commission_currency_code]`" class="select" v-model="vacancy.agency_pay_commission_currency_code">
                                                            <option v-for="item in currency_list" :value="item.char_code" >
                                                                {{ item.name }}
                                                            </option>
                                                        </nice-select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="registration__input-bl j-prop-multiple">
                                <div class="registration__input-title" style="margin: 30px 0 20px;">
                                    <?= Yii::t('vacancy', 'Payment method') ?>:
                                </div>
                                <div>
                                    <div v-if="vacancy.secure_deal == <?= Vacancy::SECURE_DEAL_YES ?>" class="registration__safe j-block-prop">
                                        <div class="registration__input-title">
                                            <?= Yii::t('vacancy', "Secure Deal it's") ?>:
                                        </div>
                                        <div class="registration__safe-desc">
                                            <?= Yii::t('vacancy', "Secure Deal it's") ?>:
                                            <?= Yii::t('vacancy', 'Reserving funds on the service will allow you to be sure that payment will be received by the selected date and that you will not be deceived by unscrupulous employees - we will act as a guarantor and transfer the reserved funds to you, after confirmation of fulfillment of obligations.') ?>
                                        </div>
                                    </div>
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[secure_deal]" class="btn" value="<?= Vacancy::SECURE_DEAL_YES ?>" v-model="vacancy.secure_deal">
                                        <span class="radio__radio"></span>
                                        <span class="radio__title">
                                            <b><?= Yii::t('vacancy', 'Work through “Safe Deal”') ?></b>
                                            <div class="registration__input-desc">
                                                <?= Yii::t('vacancy', 'The number of candidates will increase 5 times') ?>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                                <div>
                                    <label class="radio j-radio-prop">
                                        <input type="radio" name="Vacancy[secure_deal]" class="btn" value="<?= Vacancy::SECURE_DEAL_NO ?>" v-model="vacancy.secure_deal">
                                        <span class="radio__radio"></span>
                                        <span class="radio__title">
                                            <?= Yii::t('vacancy', 'Direct settlement with the candidate') ?>
                                            <div class="registration__input-desc">
                                                <?= Yii::t('vacancy', 'Candidates trust less and try to choose a “Safe Deal”') ?>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="registration__submit">
                                <!-- <div>
                                    <button type="submit" class="btn" onclick="document.location.href='register-end.php'; return false;">
                                        Разместить вакансию
                                    </button>
                                </div>
                                <div>
                                    <a href="how-view.php" target="_blank" class="btn btn--transparent">
                                        Как видят кандидаты
                                    </a>
                                </div> -->
                                <div>
                                    <button type="button" class="btn btn--transparent j-prev-step" v-on:click="previousStep">
                                        <?= Yii::t('vacancy', 'Previous step') ?>
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn j-next-step" v-on:click="nextStep">
                                        <?= Yii::t('vacancy', 'Create a job') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ step 4 -->
                <?php ActiveForm::end(); ?>
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
        return '<?= Yii::t('vacancy', 'The value «Applications for this work will be sent to the following email addresses» is not a valid email address.') ?>';
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
  el: '#appCreateVacancy',
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
            name: '<?= Yii::t('category', $category->name ) ?>',
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
    form_current_step: 1,
    response_errors: void 0,
    submit_clicked: false,
    file_format_error: false,
    country_char_code_last: '',
    country_city_refresh_flag: true,
    vacancy: {
        title: '',
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
        date_free: '<?= Vacancy::DATE_FREE_YES ?>',
        country_name: '',
        country_city_id: '',
        salary_per_hour_min: '',
        salary_per_hour_max: '',
        salary_per_hour_min_src: '',
        salary_per_hour_max_src: '',
        currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>', // '',
        hours_per_day_min: '',
        hours_per_day_max: '',
        days_per_week_min: '',
        days_per_week_max: '',
        prepaid_expense_min: '',
        prepaid_expense_max: '',
        type_of_working_shift: '',
        residence_provided: <?= Vacancy::RESIDENCE_PROVIDED_YES ?>,
        residence_amount: '',
        residence_amount_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>', // '',
        residence_people_per_room: '',
        documents_provided: '',
        documents_required: '',
        job_description: '',
        job_description_bonus: '',
        contact_name: '',
        contact_phone: '',
        contact_email_list: '',
        main_image: '',
        agency_accept: <?= Vacancy::AGENCY_ACCEPT_NO ?>,
        agency_paid_document: '',
        agency_paid_document_price: '',
        agency_paid_document_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>', // '',
        agency_free_document: '',
        agency_pay_commission: '',
        agency_pay_commission_amount: '',
        agency_pay_commission_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>', // '',
        secure_deal: <?= Vacancy::SECURE_DEAL_NO ?>,
        // -- relations:
        category_job_item: null,
        worker_country_codes_all: true,
        selected_categories: [],
        contact_emails: [
            ''
        ],
        contact_phones: [
            ''
        ],
        photos_list: [],
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
            country_city_id:  '', // '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
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
        },
    }
  },
  mounted: function() {
    // -
    for(let i = 0; i < this.$data.type_of_working_shift_list.length; i++) {
        if (this.$data.type_of_working_shift_list[i].id == <?= Vacancy::TYPE_OF_WORKING_SHIFT_DAY ?>
         || this.$data.type_of_working_shift_list[i].id == <?= Vacancy::TYPE_OF_WORKING_SHIFT_EVENING ?>
        ) {
            this.$data.type_of_working_shift_list[i].checked = true;
        }
    }
    this.vacancyTypeOfWorkingShiftChange();
    
    this.upgradeStepAnchor();
  },
  methods: {
    onSubmit () {
      return; // supress submit
    },
    previousStep: function() {
        this.$data.form_current_step = this.$data.form_current_step - 1;
        this.upgradeStepAnchor();
        
        //! BUG, need fix navigate(validate) previous step when invalid data on current step(or send data to server)
        // + need use data-vv-scope to prevent validate next step when user step back
    },
    nextStep: function() {
        this.$data.submit_clicked = true;

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
                    'Vacancy[title]': this.$data.vacancy.title,
                    'Vacancy[company_name]': this.$data.vacancy.company_name,
                    'Vacancy[category_job_id]': this.$data.vacancy.category_job_id,
                    'Vacancy[gender_list]': this.$data.vacancy.gender_list, //
                    // 'Vacancy[age_min]': this.$data.vacancy.age_min, //! not found in template
                    // 'Vacancy[age_max]': this.$data.vacancy.age_max, //! not found in template
                    'Vacancy[free_places]': this.$data.vacancy.free_places,
                    'Vacancy[regular_places]': this.getRegularPlacesValue(this.$data.vacancy.regular_places), //
                    'Vacancy[date_start]': this.$data.vacancy.date_start ? moment(this.$data.vacancy.date_start, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
                    'Vacancy[date_end]': this.$data.vacancy.date_end ? moment(this.$data.vacancy.date_end, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
                    'Vacancy[date_free]': this.getDateFreeValue(this.$data.vacancy.date_free), //
                    'Vacancy[country_name]': this.$data.vacancy.country_name,
                    'Vacancy[country_city_id]': this.$data.vacancy.country_city_id,
                    'Vacancy[salary_per_hour_min]': this.$data.vacancy.salary_per_hour_min,
                    'Vacancy[salary_per_hour_max]': this.$data.vacancy.salary_per_hour_max,
                    'Vacancy[salary_per_hour_min_src]': this.$data.vacancy.salary_per_hour_min_src,
                    'Vacancy[salary_per_hour_max_src]': this.$data.vacancy.salary_per_hour_max_src,
                    'Vacancy[currency_code]': this.$data.vacancy.currency_code,
                    'Vacancy[hours_per_day_min]': this.$data.vacancy.hours_per_day_min,
                    'Vacancy[hours_per_day_max]': this.$data.vacancy.hours_per_day_max,
                    'Vacancy[days_per_week_min]': this.$data.vacancy.days_per_week_min,
                    'Vacancy[days_per_week_max]': this.$data.vacancy.days_per_week_max,
                    'Vacancy[prepaid_expense_min]': this.$data.vacancy.prepaid_expense_min,
                    'Vacancy[prepaid_expense_max]': this.$data.vacancy.prepaid_expense_max,
                    'Vacancy[type_of_working_shift]': this.$data.vacancy.type_of_working_shift, //
                    'Vacancy[residence_provided]': this.$data.vacancy.residence_provided,
                    'Vacancy[residence_amount]': this.$data.vacancy.residence_amount,
                    'Vacancy[residence_amount_currency_code]': this.$data.vacancy.residence_amount_currency_code,
                    'Vacancy[residence_people_per_room]': this.$data.vacancy.residence_people_per_room,
                    'Vacancy[documents_provided]': this.$data.vacancy.documents_provided, //
                    'Vacancy[documents_required]': this.$data.vacancy.documents_required, //
                    'Vacancy[job_description]': this.$data.vacancy.job_description,
                    'Vacancy[job_description_bonus]': this.$data.vacancy.job_description_bonus,
                    'Vacancy[contact_name]': this.$data.vacancy.contact_name,
                    'Vacancy[contact_phone]': this.$data.vacancy.contact_phone,
                    'Vacancy[contact_email_list]': this.$data.vacancy.contact_email_list, //! BUG
                    'Vacancy[main_image]': this.$data.vacancy.main_image,
                    'Vacancy[agency_accept]': this.$data.vacancy.agency_accept,
                    'Vacancy[agency_paid_document]': this.getAgencyPaidDocumentValue(this.$data.vacancy.agency_paid_document), //
                    'Vacancy[agency_paid_document_price]': this.$data.vacancy.agency_paid_document_price,
                    'Vacancy[agency_paid_document_currency_code]': this.$data.vacancy.agency_paid_document_currency_code,
                    'Vacancy[agency_free_document]': this.getAgencyFreeDocumentValue(this.$data.vacancy.agency_free_document), //
                    'Vacancy[agency_pay_commission]': this.getAgencyPayCommissionValue(this.$data.vacancy.agency_pay_commission), //
                    'Vacancy[agency_pay_commission_amount]': this.$data.vacancy.agency_pay_commission_amount,
                    'Vacancy[agency_pay_commission_currency_code]': this.$data.vacancy.agency_pay_commission_currency_code,
                    'Vacancy[secure_deal]': this.$data.vacancy.secure_deal,
                };

                if (!this.$data.vacancy.worker_country_codes_all) {
                    post_data['Vacancy[worker_country_codes]'] = this.$data.vacancy.worker_country_codes;
                } // else null

                // selected_categories (categoryVacancies)
                for(let i = 0; i < this.$data.vacancy.selected_categories.length; i++) {
                    post_data['Vacancy[categoryVacancies]['+ i +'][category_id]'] = this.$data.vacancy.selected_categories[i].id;
                }
                
                // photos_list (vacancyImages)
                for(let i = 0; i < this.$data.vacancy.photos_list.length; i++) {
                    post_data['Vacancy[vacancyImages]['+ i +'][name]'] = this.$data.vacancy.photos_list[i].name;
                    // post_data['Vacancy[vacancyImages]['+ i +'][path_name]'] = this.$data.vacancy.photos_list[i].path_name;
                    post_data['Vacancy[vacancyImages]['+ i +'][src]'] = this.$data.vacancy.photos_list[i].photo_src;
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
    vacancyGenderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.vacancy.gender_list = selected_genders;
    },
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.vacancy.selected_categories = _.filter(this.$data.category_list, function(p) {
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

        this.$data.vacancy.worker_country_codes = selected_codes;
    },
    onChangeCategoryJob: function() {
        this.$data.vacancy.category_job_id = this.$data.vacancy.category_job_item.id;
    },
    //-- step 2
    vacancyTypeOfWorkingShiftChange: function() {
        let selected_type_of_working_shifts = '';
        for(let i = 0; i < this.$data.type_of_working_shift_list.length; i++) {
            if(this.$data.type_of_working_shift_list[i].checked) {
                selected_type_of_working_shifts += this.$data.type_of_working_shift_list[i].id + ';';
            }
        }

        this.$data.vacancy.type_of_working_shift = selected_type_of_working_shifts;
    },
    countryChanged: function() {
        let country_char_code = this.$data.vacancy.country_name;
        
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

        this.$data.vacancy.documents_provided = selected_documents_provided;
    },
    vacancyDocumentsRequiredChange: function() {
        let selected_documents_required = '';
        for(let i = 0; i < this.$data.documents_required_list.length; i++) {
            if(this.$data.documents_required_list[i].checked) {
                selected_documents_required += this.$data.documents_required_list[i].id + ';';
            }
        }

        this.$data.vacancy.documents_required = selected_documents_required;
    },
    addEmail: function() {
        this.$data.vacancy.contact_emails.push('');
    },
    changeEmail: function() {
        this.$data.vacancy.contact_email_list = this.$data.vacancy.contact_emails.join(';');
    },
    removeEmail: function(index) {
        this.$data.vacancy.contact_emails.splice(index, 1);
        this.changeEmail();
    },
    addPhone: function() {
        this.$data.vacancy.contact_phones.push('');
    },
    changePhone: function() {
        this.$data.vacancy.contact_phone = this.$data.vacancy.contact_phones.join(';');
    },
    removePhone: function(index) {
        this.$data.vacancy.contact_phones.splice(index, 1);
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

                    me.$data.vacancy.photos_list.push({
                        photo_src: img.src,
                        name: file_name,
                        path_name: file_full_name
                    });
                }
            }

            reader.readAsDataURL(file);
        }
    },
    removePhoto: function(index) {
        this.$data.vacancy.photos_list.splice(index, 1);
    },
    //-- step 4
    
    // --
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
                scrollTop: $("#appCreateVacancy").offset().top - 200
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