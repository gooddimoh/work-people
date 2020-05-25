<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Vacancy;
use app\models\Category;
use app\models\CategoryJob;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/js/app/dist/vue.js');
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/libs/jquery-nice-select/js/jquery.nice-select.min.js');
$this->registerCssFile('/libs/jquery-nice-select/css/nice-select.css');
$this->registerJsFile('/js/app/component/nice-select.wrapper.js');
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js');
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js');
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css');

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
$job_list_grouped = CategoryJob::getUserMultiSelectList();
$job_list = [];
foreach($job_list_grouped as $group) {
    foreach($group['jobs'] as $job_item) {
        $job_list[$group['group_name']][$job_item['id']] = Yii::t('category-job', $job_item['name']);
    }
}

$selected_category_ids = [];
if(!$model->getIsNewRecord()) { // create/update
    $selected_category_ids = ArrayHelper::getColumn($model->categories, 'id');
}

$model_attributes = $model->getAttributes();
?>

<div id="appUpdateVacancy" class="vacancy-form">

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

    <div class="registration__input-bl">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('company_id') ?>
        </div>
        <input v-validate="vv.model_vacancy.company_id" name='Vacancy[company_id]' class="form-control" type="hidden" v-model="vacancy.company_id">
        <multiselect v-model="vacancy.company_item"
                :options="company_list"
                :multiple="false"
                placeholder="<?= Yii::t('main', 'Type to search') ?>"
                track-by="id"
                label="name"
                v-on:input="onChangeCompany"
                :searchable="true"
                :loading="isLoading"
                :internal-search="false"
                :clear-on-select="false"
                :close-on-select="false"
                :options-limit="300"
                :limit="3"
                :limit-text="limitText"
                :max-height="600"
                :show-no-results="false"
                :hide-selected="true"
                @search-change="asyncFind"
        ><span slot="noResult"><?= Yii::t('main', 'Nothing found') ?>.</span></multiselect>
<!-- 
        <multiselect v-model="selectedCountries" id="ajax" label="name" track-by="code" placeholder="Type to search" open-direction="bottom" :options="countries" :multiple="true" :searchable="true" :loading="isLoading" :internal-search="false" :clear-on-select="false" :close-on-select="false" :options-limit="300" :limit="3" :limit-text="limitText" :max-height="600" :show-no-results="false" :hide-selected="true" @search-change="asyncFind">
            <template slot="tag" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
            <template slot="clear" slot-scope="props">
            <div class="multiselect__clear" v-if="selectedCountries.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
            </template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
        </multiselect> -->

        <span v-show="errors.has('Vacancy[company_id]')" class="validation-error-message" v-cloak>
            {{ errors.first('Vacancy[company_id]') }}
        </span>
    </div>

    <?= $form->field($model, 'company_id')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.company_id',
            'v-validate' => 'vv.model_vacancy.company_id'
        ]
    ]) ?>


    <div class="registration__input-bl">
        <div class="registration__input-title" style="margin: 0px 0px 14px;">
            <?= Yii::t('vacancy', 'Job posting categories') ?>
        </div>

        <input v-validate="vv.model_vacancy.selected_categories" name='Vacancy[category_list]' type="hidden" v-model="vacancy.selected_categories">
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

    <?= $form->field($model, 'status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getStatusList(),
        'options' => [
            'v-model' => 'vacancy.status',
            'v-validate' => 'vv.model_vacancy.status'
        ]
    ]) ?>

    <?= $form->field($model, 'pin_position')->widget(VueTextInputWidget::classname(), [
        // 'type' => VueTextInputWidget::TEXT_INPUT, // default
        'options' => [
            'v-model' => 'vacancy.pin_position',
            'v-validate' => 'vv.model_vacancy.pin_position'
        ]
    ]) ?>

    <?= $form->field($model, 'special_status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_INPUT,
        'options' => [
            'v-model' => 'vacancy.special_status',
            'v-validate' => 'vv.model_vacancy.special_status'
        ]
    ]) ?>
    
    <?= $form->field($model, 'show_on_main_page')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getShowOnMainPageStatusList(),
        'options' => [
            'v-model' => 'vacancy.show_on_main_page',
            'v-validate' => 'vv.model_vacancy.show_on_main_page'
        ]
    ]) ?>
    
    <?= $form->field($model, 'main_page_priority')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.main_page_priority',
            'v-validate' => 'vv.model_vacancy.main_page_priority'
        ]
    ]) ?>
    
    <?= $form->field($model, 'title')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.title',
            'v-validate' => 'vv.model_vacancy.title'
        ]
    ]) ?>

    <?= $form->field($model, 'company_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.company_name',
            'v-validate' => 'vv.model_vacancy.company_name'
        ]
    ]) ?>

    <?php /* $form->field($model, 'category_job_id')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.category_job_id',
            'v-validate' => 'vv.model_vacancy.category_job_id'
        ]
    ]) */ ?>
    <?= $form->field($model, 'category_job_id')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => $job_list,
        'options' => [
            'v-model' => 'vacancy.category_job_id',
            'v-validate' => 'vv.model_vacancy.category_job_id'
        ]
    ]) ?>

    <?= $form->field($model, 'gender_list')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'gender_list',
        'checkBoxAttributes' => 'v-on:change="vacancyGenderChange"',
        'options' => [
            'v-model' => 'vacancy.gender_list',
            'v-validate' => 'vv.model_vacancy.gender_list',
        ]
    ]) ?>

    <?= $form->field($model, 'age_min')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.age_min',
            'v-validate' => 'vv.model_vacancy.age_min'
        ]
    ]) ?>

    <?= $form->field($model, 'age_max')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.age_max',
            'v-validate' => 'vv.model_vacancy.age_max'
        ]
    ]) ?>

    <?= $form->field($model, 'worker_country_codes')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'country_list',
        'checkBoxAttributes' => 'v-on:change="vacancyCountryCodesChange"',
        'options' => [
            'v-model' => 'vacancy.worker_country_codes',
            'v-validate' => 'vv.model_vacancy.worker_country_codes',
        ]
    ]) ?>

    <?= $form->field($model, 'free_places')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.free_places',
            'v-validate' => 'vv.model_vacancy.free_places'
        ]
    ]) ?>

    <?= $form->field($model, 'regular_places')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getRegularPlacesList(),
        'options' => [
            'v-model' => 'vacancy.regular_places',
            'v-validate' => 'vv.model_vacancy.regular_places'
        ]
    ]) ?>

    <?= $form->field($model, 'date_start')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.date_start',
            'v-validate' => 'vv.model_vacancy.date_start'
        ]
    ]) ?>

    <?= $form->field($model, 'date_end')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.date_end',
            'v-validate' => 'vv.model_vacancy.date_end'
        ]
    ]) ?>

    <?= $form->field($model, 'date_free')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getDateFreeList(),
        'options' => [
            'v-model' => 'vacancy.date_free',
            'v-validate' => 'vv.model_vacancy.date_free'
        ]
    ]) ?>

    <?= $form->field($model, 'country_name')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($country_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'vacancy.country_name',
            'v-validate' => 'vv.model_vacancy.country_name',
            'v-on:change' => "countryChanged",
        ]
    ]) ?>

    <div class="form-group field-vacancy-country_city_id">
        <label for="vacancy-country_city_id" class="control-label"><?= $model->getAttributeLabel('country_city_id') ?></label>
        <input v-validate="vv.model_vacancy.country_city_id" name='Vacancy[country_city_id]' class="form-control edit" type="hidden" v-model="vacancy.country_city_id">
        <div class="row">
            <div class="col-md-12">
                <nice-select :options="country_city_list" name="Vacancy[country_city_id]`" class="select" v-model="vacancy.country_city_id" v-if="country_city_refresh_flag">
                    <option v-for="item in country_city_list" :value="item.id">
                        {{ item.city_name }}
                    </option>
                </nice-select>
            </div>
        </div>
        <span v-show="errors.has('Vacancy[country_city_id]')" class="validation-error-message">
            {{ errors.first('Vacancy[country_city_id]') }}
        </span>
    </div>

    <?= $form->field($model, 'salary_per_hour_min')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.salary_per_hour_min',
            'v-validate' => 'vv.model_vacancy.salary_per_hour_min'
        ]
    ]) ?>

    <?= $form->field($model, 'salary_per_hour_max')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.salary_per_hour_max',
            'v-validate' => 'vv.model_vacancy.salary_per_hour_max'
        ]
    ]) ?>

    <?= $form->field($model, 'salary_per_hour_min_src')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.salary_per_hour_min_src',
            'v-validate' => 'vv.model_vacancy.salary_per_hour_min_src'
        ]
    ]) ?>

    <?= $form->field($model, 'salary_per_hour_max_src')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.salary_per_hour_max_src',
            'v-validate' => 'vv.model_vacancy.salary_per_hour_max_src'
        ]
    ]) ?>

    <?= $form->field($model, 'currency_code')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($currency_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'vacancy.currency_code',
            'v-validate' => 'vv.model_vacancy.currency_code'
        ]
    ]) ?>

    <?= $form->field($model, 'hours_per_day_min')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.hours_per_day_min',
            'v-validate' => 'vv.model_vacancy.hours_per_day_min'
        ]
    ]) ?>

    <?= $form->field($model, 'hours_per_day_max')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.hours_per_day_max',
            'v-validate' => 'vv.model_vacancy.hours_per_day_max'
        ]
    ]) ?>

    <?= $form->field($model, 'days_per_week_min')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.days_per_week_min',
            'v-validate' => 'vv.model_vacancy.days_per_week_min'
        ]
    ]) ?>

    <?= $form->field($model, 'days_per_week_max')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.days_per_week_max',
            'v-validate' => 'vv.model_vacancy.days_per_week_max'
        ]
    ]) ?>

    <?= $form->field($model, 'prepaid_expense_min')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.prepaid_expense_min',
            'v-validate' => 'vv.model_vacancy.prepaid_expense_min'
        ]
    ]) ?>

    <?= $form->field($model, 'prepaid_expense_max')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.prepaid_expense_max',
            'v-validate' => 'vv.model_vacancy.prepaid_expense_max'
        ]
    ]) ?>

    <?= $form->field($model, 'type_of_working_shift')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'type_of_working_shift_list',
        'checkBoxAttributes' => 'v-on:change="vacancyTypeOfWorkingShiftChange"',
        'options' => [
            'v-model' => 'vacancy.type_of_working_shift',
            'v-validate' => 'vv.model_vacancy.type_of_working_shift',
        ]
    ]) ?>

    <?= $form->field($model, 'residence_provided')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getResidenceProvidedList(),
        'options' => [
            'v-model' => 'vacancy.residence_provided',
            'v-validate' => 'vv.model_vacancy.residence_provided'
        ]
    ]) ?>

    <?= $form->field($model, 'residence_amount')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.residence_amount',
            'v-validate' => 'vv.model_vacancy.residence_amount'
        ]
    ]) ?>

    <?= $form->field($model, 'residence_amount_currency_code')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($currency_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'vacancy.residence_amount_currency_code',
            'v-validate' => 'vv.model_vacancy.residence_amount_currency_code'
        ]
    ]) ?>

    <?= $form->field($model, 'residence_people_per_room')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.residence_people_per_room',
            'v-validate' => 'vv.model_vacancy.residence_people_per_room'
        ]
    ]) ?>

    <?= $form->field($model, 'documents_provided')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'documents_provided_list',
        'checkBoxAttributes' => 'v-on:change="vacancyDocumentsProvidedChange"',
        'options' => [
            'v-model' => 'vacancy.documents_provided',
            'v-validate' => 'vv.model_vacancy.documents_provided',
        ]
    ]) ?>

    <?= $form->field($model, 'documents_required')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'documents_required_list',
        'checkBoxAttributes' => 'v-on:change="vacancyDocumentsRequiredChange"',
        'options' => [
            'v-model' => 'vacancy.documents_required',
            'v-validate' => 'vv.model_vacancy.documents_required',
        ]
    ]) ?>

    <?= $form->field($model, 'full_import_description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.full_import_description',
            'v-validate' => 'vv.model_vacancy.full_import_description'
        ]
    ]) ?>

    <?= $form->field($model, 'full_import_description_cleaned')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.full_import_description_cleaned',
            'v-validate' => 'vv.model_vacancy.full_import_description_cleaned'
        ]
    ]) ?>

    <?= $form->field($model, 'use_full_import_description_cleaned')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Использовать ' . $model->getAttributeLabel('full_import_description_cleaned'),
            20 => 'Не использовать'
        ],
        'options' => [
            'v-model' => 'vacancy.use_full_import_description_cleaned',
            'v-validate' => 'vv.model_vacancy.use_full_import_description_cleaned'
        ]
    ]) ?>

    <?= $form->field($model, 'job_description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.job_description',
            'v-validate' => 'vv.model_vacancy.job_description'
        ]
    ]) ?>

    <?= $form->field($model, 'job_description_bonus')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.job_description_bonus',
            'v-validate' => 'vv.model_vacancy.job_description_bonus'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.contact_name',
            'v-validate' => 'vv.model_vacancy.contact_name'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_phone')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.contact_phone',
            'v-validate' => 'vv.model_vacancy.contact_phone'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_email_list')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.contact_email_list',
            'v-validate' => 'vv.model_vacancy.contact_email_list'
        ]
    ]) ?>

    <?= $form->field($model, 'main_image')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'vacancy.main_image',
            'v-validate' => 'vv.model_vacancy.main_image'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_accept')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getAgencyAcceptList(),
        'options' => [
            'v-model' => 'vacancy.agency_accept',
            'v-validate' => 'vv.model_vacancy.agency_accept'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_paid_document')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getAgencyPaidDocumentList(),
        'options' => [
            'v-model' => 'vacancy.agency_paid_document',
            'v-validate' => 'vv.model_vacancy.agency_paid_document'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_paid_document_price')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.agency_paid_document_price',
            'v-validate' => 'vv.model_vacancy.agency_paid_document_price'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_paid_document_currency_code')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($currency_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'vacancy.agency_paid_document_currency_code',
            'v-validate' => 'vv.model_vacancy.agency_paid_document_currency_code'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_free_document')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getAgencyFreeDocumentList(),
        'options' => [
            'v-model' => 'vacancy.agency_free_document',
            'v-validate' => 'vv.model_vacancy.agency_free_document'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_pay_commission')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getAgencyPayCommissionList(),
        'options' => [
            'v-model' => 'vacancy.agency_pay_commission',
            'v-validate' => 'vv.model_vacancy.agency_pay_commission'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_pay_commission_amount')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.agency_pay_commission_amount',
            'v-validate' => 'vv.model_vacancy.agency_pay_commission_amount'
        ]
    ]) ?>

    <?= $form->field($model, 'agency_pay_commission_currency_code')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($currency_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'vacancy.agency_pay_commission_currency_code',
            'v-validate' => 'vv.model_vacancy.agency_pay_commission_currency_code'
        ]
    ]) ?>

    <?= $form->field($model, 'secure_deal')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Vacancy::getSecureDealList(),
        'options' => [
            'v-model' => 'vacancy.secure_deal',
            'v-validate' => 'vv.model_vacancy.secure_deal'
        ]
    ]) ?>

    <?= $form->field($model, 'meta_keywords')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.meta_keywords',
            'v-validate' => 'vv.model_vacancy.meta_keywords'
        ]
    ]) ?>

    <?= $form->field($model, 'meta_description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'vacancy.meta_description',
            'v-validate' => 'vv.model_vacancy.meta_description'
        ]
    ]) ?>

    <?php /* $form->field($model, 'creation_time')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.creation_time',
            'v-validate' => 'vv.model_vacancy.creation_time'
        ]
    ]) */ ?>

    <?php /* $form->field($model, 'update_time')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'vacancy.update_time',
            'v-validate' => 'vv.model_vacancy.update_time'
        ]
    ]) */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success', 'v-on:click' => "vacancyInfoSave"]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php $this->beginJs(); ?>
<script>
Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'validation-error-input',
    },
    events: 'change|blur|keyup'
});

VeeValidate.Validator.extend('app_models_Vacancy_contact_email_list_yii_validators_EmailValidator', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'The value «Applications for this work will be sent to the following email addresses" is not a valid email address».') ?>';
    },
    validate: function(value) {
        return /^[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/.test(value);
    }
});

VeeValidate.Validator.extend('vacancy_selected_categories_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('vacancy', 'You must fill out the «Categories of job posting».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

new Vue({
  el: '#appUpdateVacancy',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
    category_list: [
        <?php foreach( $category_list as $category): ?>
        {
            id: '<?= $category->id ?>',
            name: '<?= Yii::t('category', $category->name ) ?>',
            checked: <?= in_array($category->id, $selected_category_ids) ? 'true' : 'false' ?> // for stilization
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
    company_list: [

    ],
    isLoading: false,
    //-------
    response_errors: void 0,
    submit_clicked: false,
    file_format_error: false,
    country_char_code_last: '',
	country_city_refresh_flag: false,
    vacancy: {
        <?php foreach($model_attributes as $name => $val): ?>
            <?php switch($name) {
                case 'job_description':
                case 'job_description_bonus':
                case 'meta_keywords':
                case 'meta_description':
                ?>
                    <?php echo "$name: " . json_encode(Html::encode($val)) . ", \r\n" ?>
                <?php break; ?>
                <?php default: ?>
                    <?php echo "$name: '$val', \r\n" ?>
            <?php } ?>
        <?php endforeach; ?>
        // -- relations:
        selected_categories: [],
        contact_emails: [
            ''
        ],
        photos_list: [],
    },
    vv: {
        model_vacancy: {
            selected_categories: 'vacancy_selected_categories_validator_required|required',
            <?php
            foreach($model_attributes as $name => $XD) {
                $validator_str = VeeValidateHelper::getVValidateString($this, $model, $name);
                if(empty($validator_str)) continue; // skip empty validators
                switch($name) {
                    case 'contact_email_list':
                        echo $name . ': \'' . $validator_str . "|app_models_Vacancy_contact_email_list_yii_validators_EmailValidator',\r\n";
                        echo "contact_email_limit: 'vacancy_contact_email_list_item_required|required|vacancy_contact_email_list_item_limit', \r\n";
                        break;
                    default:
                        echo $name . ': \'' . $validator_str . "',\r\n";
                }
            }
            ?>
        },
    }
  },
  mounted: function() {
    // gender_list
    for (let i = 0; i < this.$data.gender_list.length; i++) {
        this.$data.gender_list[i].checked = false;
        if ( this.$data.vacancy.gender_list.indexOf(this.$data.gender_list[i].id + ';') !== -1) {
            this.$data.gender_list[i].checked = true;
        }
    }

    // worker_country_codes
    for (let i = 0; i < this.$data.country_list.length; i++) {
        this.$data.country_list[i].checked = false;
        if ( this.$data.vacancy.worker_country_codes.indexOf(this.$data.country_list[i].char_code + ';') !== -1) {
            this.$data.country_list[i].checked = true;
        }
    }

    // type_of_working_shift
    for (let i = 0; i < this.$data.type_of_working_shift_list.length; i++) {
        this.$data.type_of_working_shift_list[i].checked = false;
        if ( this.$data.vacancy.type_of_working_shift.indexOf(this.$data.type_of_working_shift_list[i].id + ';') !== -1) {
            this.$data.type_of_working_shift_list[i].checked = true;
        }
    }

    // documents_provided
    for (let i = 0; i < this.$data.documents_provided_list.length; i++) {
        this.$data.documents_provided_list[i].checked = false;
        if ( this.$data.vacancy.documents_provided.indexOf(this.$data.documents_provided_list[i].id + ';') !== -1) {
            this.$data.documents_provided_list[i].checked = true;
        }
    }

    // documents_required
    for (let i = 0; i < this.$data.documents_required_list.length; i++) {
        this.$data.documents_required_list[i].checked = false;
        if ( this.$data.vacancy.documents_required.indexOf(this.$data.documents_required_list[i].id + ';') !== -1) {
            this.$data.documents_required_list[i].checked = true;
        }
    }

    // upgrade selected list
    this.$data.vacancy.selected_categories = _.filter(this.$data.category_list, function(p) {
        return p.checked;
    });

    this.countryChanged();
  },
  methods: {
    onSubmit () {
		// lock send form on key 'enter'
      	return; // supress submit
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
        $.post( window.location.pathname, post_data)
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                me.loaderHide();
                if(response.success === false && response.errors) {
                    // insert errors
                    me.$data.response_errors = response.errors;
                    // scroll to top
                    me.scrollToErrorblock();
                } else {
                    if(response.status !== 302) {
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
				// id: '1', 
                'Vacancy[company_id]': this.$data.vacancy.company_id,
                'Vacancy[category_job_id]': this.$data.vacancy.category_job_id,
                'Vacancy[status]': this.$data.vacancy.status,
                'Vacancy[pin_position]': this.$data.vacancy.pin_position,
                'Vacancy[special_status]': this.$data.vacancy.special_status,
                'Vacancy[show_on_main_page]': this.$data.vacancy.show_on_main_page,
                'Vacancy[main_page_priority]': this.$data.vacancy.main_page_priority,
                'Vacancy[title]': this.$data.vacancy.title,
                'Vacancy[company_name]': this.$data.vacancy.company_name,
                'Vacancy[gender_list]': this.$data.vacancy.gender_list,
                'Vacancy[age_min]': this.$data.vacancy.age_min,
                'Vacancy[age_max]': this.$data.vacancy.age_max,
                'Vacancy[worker_country_codes]': this.$data.vacancy.worker_country_codes,
                'Vacancy[free_places]': this.$data.vacancy.free_places,
                'Vacancy[regular_places]': this.$data.vacancy.regular_places,
                'Vacancy[date_start]': this.$data.vacancy.date_start,
                'Vacancy[date_end]': this.$data.vacancy.date_end,
                'Vacancy[date_free]': this.$data.vacancy.date_free,
                'Vacancy[country_name]': this.$data.vacancy.country_name,
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
                'Vacancy[type_of_working_shift]': this.$data.vacancy.type_of_working_shift,
                'Vacancy[residence_provided]': this.$data.vacancy.residence_provided,
                'Vacancy[residence_amount]': this.$data.vacancy.residence_amount,
                'Vacancy[residence_amount_currency_code]': this.$data.vacancy.residence_amount_currency_code,
                'Vacancy[residence_people_per_room]': this.$data.vacancy.residence_people_per_room,
                'Vacancy[documents_provided]': this.$data.vacancy.documents_provided,
                'Vacancy[documents_required]': this.$data.vacancy.documents_required,
                'Vacancy[job_description]': this.$data.vacancy.job_description,
                'Vacancy[job_description_bonus]': this.$data.vacancy.job_description_bonus,
                'Vacancy[contact_email_list]': this.$data.vacancy.contact_email_list,
                'Vacancy[main_image]': this.$data.vacancy.main_image,
                'Vacancy[agency_accept]': this.$data.vacancy.agency_accept,
                'Vacancy[agency_paid_document]': this.$data.vacancy.agency_paid_document,
                'Vacancy[agency_paid_document_price]': this.$data.vacancy.agency_paid_document_price,
                'Vacancy[agency_paid_document_currency_code]': this.$data.vacancy.agency_paid_document_currency_code,
                'Vacancy[agency_free_document]': this.$data.vacancy.agency_free_document,
                'Vacancy[agency_pay_commission]': this.$data.vacancy.agency_pay_commission,
                'Vacancy[agency_pay_commission_amount]': this.$data.vacancy.agency_pay_commission_amount,
                'Vacancy[agency_pay_commission_currency_code]': this.$data.vacancy.agency_pay_commission_currency_code,
                'Vacancy[secure_deal]': this.$data.vacancy.secure_deal,
                'Vacancy[meta_keywords]': this.$data.vacancy.meta_keywords,
                'Vacancy[meta_description]': this.$data.vacancy.meta_description,
                'Vacancy[creation_time]': this.$data.vacancy.creation_time,
                'Vacancy[update_time]': this.$data.vacancy.update_time,
                'Vacancy[country_city_id]': this.$data.vacancy.country_city_id,
                
				'relations[0]': 'categoryVacancies',
				// 'relations[1]': 'vacancyImages'
			};

			// categoryVacancies
			let counter_cv = 0;
			for(let i = 0; i < this.$data.category_list.length; i++) {
				if(this.$data.category_list[i].checked) {
					post_data['Vacancy[categoryVacancies]['+ (counter_cv++) +'][category_id]'] = this.$data.category_list[i].id;
				}
			}

			// photos_list (vacancyImages)
			// for(let i = 0; i < this.$data.vacancy.vacancyImages.length; i++) {
			// 	if (this.$data.vacancy.vacancyImages[i].is_new) { // new files
			// 		post_data['Vacancy[vacancyImages]['+ i +'][name]'] = this.$data.vacancy.vacancyImages[i].name;
			// 		// post_data['Vacancy[vacancyImages]['+ i +'][path_name]'] = this.$data.vacancy.vacancyImages[i].path_name;
			// 		post_data['Vacancy[vacancyImages]['+ i +'][src]'] = this.$data.vacancy.vacancyImages[i].photo_src;
			// 	} else { // old files
			// 		post_data['Vacancy[vacancyImages]['+ i +'][name]'] = this.$data.vacancy.vacancyImages[i].name;
			// 		post_data['Vacancy[vacancyImages]['+ i +'][path_name]'] = this.$data.vacancy.vacancyImages[i].path_name;
			// 		// post_data['Vacancy[vacancyImages]['+ i +'][src]'] = this.$data.vacancy.vacancyImages[i].photo_src;
			// 	}
			// }

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
					// me.$data.vacancy = JSON.parse(JSON.stringify(me.$data.vacancy));
					// me.$data.vacancy.categoryVacancies = _.map(me.$data.vacancy.selected_categories, function(p) { return p.id });
					me.$data.submit_clicked = false;
				}
			);
        });
    },
    // -- company
    limitText: function (count) {
      return `and ${count} other companies`
    },
    asyncFind (query) {
        this.isLoading = true
    //   ajaxFindCountry(query).then(response => {
    //     this.countries = response
    //     this.isLoading = false
    //   })
    
        this.isLoading = false
        this.company_list = [{
            id: 1,
            name: 'test 1'
        }, {
            id: 1,
            name: 'test 2'
        }];
        
    },
    clearAll: function () {
      this.selectedCountries = []
    },
    onChangeCompany: function () {
        
    },
    // --
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.vacancy.selected_categories = _.filter(this.$data.category_list, function(p) {
            return p.checked;
        });
    },
    vacancyGenderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.vacancy.gender_list = selected_genders;
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
    // --------
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
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
  }
})
</script>
<?php $this->endJs(); ?>
