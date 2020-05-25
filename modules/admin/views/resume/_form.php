<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\ResumeEducation;
use app\models\ResumeLanguage;
use app\models\Category;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
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

$lang_list = ResumeLanguage::getLanguageList();
$level_list = ResumeLanguage::getLevelList();
$country_list = Resume::getCountryList();
$gender_list = Resume::getGenderList();
$model_attributes = $model->getAttributes();

$category_list = Category::getUserSelectList();
$selected_category_ids = [];
if(!$model->getIsNewRecord()) { // create/update
    $selected_category_ids = ArrayHelper::getColumn($model->categories, 'id');
}
?>

<div id="appUpdateResume" class="resume-form">

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

    <?= $form->field($model, 'user_id')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'resume.user_id',
            'v-validate' => 'vv.model_resume.user_id'
        ]
    ]) ?>

    <div class="registration__input-bl">
        <div class="registration__input-title" style="margin: 0px 0px 14px;">
            <?= Yii::t('resume', 'Job posting categories') ?>
        </div>

        <input v-validate="vv.model_resume.selected_categories" name='Resume[category_list]' type="hidden" v-model="resume.selected_categories">
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

    <?= $form->field($model, 'status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Resume::getStatusList(),
        'options' => [
            'v-model' => 'resume.status',
            'v-validate' => 'vv.model_resume.status'
        ]
    ]) ?>

    <?= $form->field($model, 'title')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.title',
            'v-validate' => 'vv.model_resume.title'
        ]
    ]) ?>

    <?= $form->field($model, 'use_title')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Использовать ' . $model->getAttributeLabel('title'),
            20 => 'Не использовать'
        ],
        'options' => [
            'v-model' => 'resume.use_title',
            'v-validate' => 'vv.model_resume.use_title'
        ]
    ]) ?>
    
    <?= $form->field($model, 'job_experience')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'resume.job_experience',
            'v-validate' => 'vv.model_resume.job_experience'
        ]
    ]) ?>

    <?= $form->field($model, 'use_job_experience')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Использовать ' . $model->getAttributeLabel('job_experience'),
            20 => 'Не использовать'
        ],
        'options' => [
            'v-model' => 'resume.use_job_experience',
            'v-validate' => 'vv.model_resume.use_job_experience'
        ]
    ]) ?>

    <?= $form->field($model, 'language')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.language',
            'v-validate' => 'vv.model_resume.language'
        ]
    ]) ?>

    <?= $form->field($model, 'use_language')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Использовать ' . $model->getAttributeLabel('language'),
            20 => 'Не использовать'
        ],
        'options' => [
            'v-model' => 'resume.use_language',
            'v-validate' => 'vv.model_resume.use_language'
        ]
    ]) ?>

    <?= $form->field($model, 'relocation_possible')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Да',
            20 => 'Нет'
        ],
        'options' => [
            'v-model' => 'resume.relocation_possible',
            'v-validate' => 'vv.model_resume.relocation_possible'
        ]
    ]) ?>

    <?= $form->field($model, 'full_import_description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'resume.full_import_description',
            'v-validate' => 'vv.model_resume.full_import_description'
        ]
    ]) ?>

    <?= $form->field($model, 'full_import_description_cleaned')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'resume.full_import_description_cleaned',
            'v-validate' => 'vv.model_resume.full_import_description_cleaned'
        ]
    ]) ?>

    <?= $form->field($model, 'use_full_import_description_cleaned')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            10 => 'Использовать ' . $model->getAttributeLabel('full_import_description_cleaned'),
            20 => 'Не использовать'
        ],
        'options' => [
            'v-model' => 'resume.use_full_import_description_cleaned',
            'v-validate' => 'vv.model_resume.use_full_import_description_cleaned'
        ]
    ]) ?>

    <?= $form->field($model, 'source_url')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.source_url',
            'v-validate' => 'vv.model_resume.source_url'
        ]
    ]) ?>

    <?= $form->field($model, 'first_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.first_name',
            'v-validate' => 'vv.model_resume.first_name'
        ]
    ]) ?>

    <?= $form->field($model, 'last_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.last_name',
            'v-validate' => 'vv.model_resume.last_name'
        ]
    ]) ?>

    <?= $form->field($model, 'middle_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.middle_name',
            'v-validate' => 'vv.model_resume.middle_name'
        ]
    ]) ?>

    <?= $form->field($model, 'email')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.email',
            'v-validate' => 'vv.model_resume.email'
        ]
    ]) ?>

    <?= $form->field($model, 'gender_list')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'gender_list',
        'checkBoxAttributes' => 'v-on:change="genderChange"',
        'options' => [
            'v-model' => 'resume.gender_list',
            'v-validate' => 'vv.model_resume.gender_list',
        ]
    ]) ?>

    <?= $form->field($model, 'birth_day')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'resume.birth_day',
            'v-validate' => 'vv.model_resume.birth_day'
        ]
    ]) ?>

    <?= $form->field($model, 'country_name')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($country_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'resume.country_name',
            'v-validate' => 'vv.model_resume.country_name',
            'v-on:change' => "countryChanged",
        ]
    ]) ?>

    <div class="form-group field-resume-country_city_id">
        <label for="resume-country_city_id" class="control-label"><?= $model->getAttributeLabel('country_city_id') ?></label>
        <input v-validate="vv.model_resume.country_city_id" name='Resume[country_city_id]' class="form-control edit" type="hidden" v-model="resume.country_city_id">
        <div class="row">
            <div class="col-md-12">
                <nice-select :options="country_city_list" name="Resume[country_city_id]`" class="select" v-model="resume.country_city_id" v-if="country_city_refresh_flag">
                    <option v-for="item in country_city_list" :value="item.id">
                        {{ item.city_name }}
                    </option>
                </nice-select>
            </div>
        </div>
        <span v-show="errors.has('Resume[country_city_id]')" class="validation-error-message">
            {{ errors.first('Resume[country_city_id]') }}
        </span>
    </div>

    <?= $form->field($model, 'desired_salary')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.desired_salary',
            'v-validate' => 'vv.model_resume.desired_salary'
        ]
    ]) ?>

    <?= $form->field($model, 'desired_salary_per_hour')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.desired_salary_per_hour',
            'v-validate' => 'vv.model_resume.desired_salary_per_hour'
        ]
    ]) ?>

    <?= $form->field($model, 'desired_salary_currency_code')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.desired_salary_currency_code',
            'v-validate' => 'vv.model_resume.desired_salary_currency_code'
        ]
    ]) ?>

    <?= $form->field($model, 'desired_country_of_work')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.desired_country_of_work',
            'v-validate' => 'vv.model_resume.desired_country_of_work'
        ]
    ]) ?>

    <?= $form->field($model, 'photo_path')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.photo_path',
            'v-validate' => 'vv.model_resume.photo_path'
        ]
    ]) ?>

    <?= $form->field($model, 'phone')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.phone',
            'v-validate' => 'vv.model_resume.phone'
        ]
    ]) ?>

    <?= $form->field($model, 'custom_country')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'resume.custom_country',
            'v-validate' => 'vv.model_resume.custom_country'
        ]
    ]) ?>

    <?= $form->field($model, 'description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'resume.description',
            'v-validate' => 'vv.model_resume.description'
        ]
    ]) ?>

    <?php /* $form->field($model, 'creation_time')->textInput() */ ?>

    <?php /* $form->field($model, 'upvote_time')->textInput() */ ?>

    <?php /* $form->field($model, 'update_time')->textInput() */ ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success', 'v-on:click' => 'resumeInfoSave']) ?>
    </div>

    <?php ActiveForm::end(); ?>

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

new Vue({
  el: '#appUpdateResume',
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
    //-------
    resume: {
        <?php foreach($model_attributes as $name => $val): ?>
            <?php echo "$name: ".json_encode($val).", \r\n" ?>
        <?php endforeach; ?>
        // -- relations:
        selected_categories: [],
    },
    job_list: [],
    foreign_job_list: [],
    language_list: [],
    response_errors: void 0,
    submit_clicked: false,
    country_char_code_last: '',
	country_city_refresh_flag: false,
    vv: {
        model_resume: {
            selected_categories: 'resume_selected_categories_validator_required|required',
            <?php
            foreach($model_attributes as $name => $XD) {
                $validator_str = VeeValidateHelper::getVValidateString($this, $model, $name);
                if(empty($validator_str)) continue; // skip empty validators
                echo $name . ': \'' . $validator_str . "',\r\n";
            }
            ?>
        },
        model_job: { // $modelJob
            company_name: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'company_name') ?>',
            position: '<?= VeeValidateHelper::getVValidateString($this, $modelJob, 'position') ?>',
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

    // upgrade selected list
    this.$data.resume.selected_categories = _.filter(this.$data.category_list, function(p) {
        return p.checked;
    });

    this.countryChanged();
  },
  methods: {
    onSubmit () {
      return; // supress submit
    },
    resumeInfoSave: function() {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            // this.$refs.form.submit();
            let post_data = {
                'Resume[user_id]': this.$data.resume.user_id,
                'Resume[status]': this.$data.resume.status,
                'Resume[title]' : this.$data.resume.title,
                'Resume[use_title]' : this.$data.resume.use_title,
                'Resume[job_experience]' : this.$data.resume.job_experience,
                'Resume[use_job_experience]' : this.$data.resume.use_job_experience,
                'Resume[language]' : this.$data.resume.language,
                'Resume[use_language]' : this.$data.resume.use_language,
                'Resume[relocation_possible]' : this.$data.resume.relocation_possible,
                'Resume[full_import_description]' : this.$data.resume.full_import_description,
                'Resume[full_import_description_cleaned]' : this.$data.resume.full_import_description_cleaned,
                'Resume[use_full_import_description_cleaned]' : this.$data.resume.use_full_import_description_cleaned,
                'Resume[source_url]' : this.$data.resume.source_url,
                'Resume[first_name]': this.$data.resume.first_name,
                'Resume[last_name]': this.$data.resume.last_name,
                'Resume[middle_name]': this.$data.resume.middle_name,
                'Resume[email]': this.$data.resume.email,
                'Resume[gender_list]': this.$data.resume.gender_list,
                'Resume[birth_day]': this.$data.resume.birth_day,
                'Resume[country_name]': this.$data.resume.country_name,
                'Resume[country_city_id]': this.$data.resume.country_city_id,
                'Resume[desired_salary]': this.$data.resume.desired_salary,
                'Resume[desired_salary_per_hour]': this.$data.desired_salary_per_hour,
                'Resume[desired_salary_currency_code]': this.$data.desired_salary_currency_code,
                'Resume[desired_country_of_work]': this.$data.resume.desired_country_of_work,
                'Resume[photo_path]': this.$data.resume.photo_path,
                'Resume[phone]': this.$data.resume.phone,
                'Resume[custom_country]': this.$data.resume.custom_country,
                'Resume[description]': this.$data.resume.description,
                'Resume[creation_time]': this.$data.resume.creation_time,
                'Resume[upvote_time]': this.$data.resume.upvote_time,
                'Resume[update_time]': this.$data.resume.update_time,
            };

            // language_list (resumeLanguages)
            // for(let i = 0; i < this.$data.language_list.length; i++) {
            //     post_data['Resume[resumeLanguages]['+i+'][country_code]'] = this.$data.language_list[i].edit_data.country_code;
            //     post_data['Resume[resumeLanguages]['+i+'][level]'] = this.$data.language_list[i].edit_data.level;
            //     post_data['Resume[resumeLanguages]['+i+'][can_be_interviewed]'] = this.getCanBeInterviewedValue(this.$data.language_list[i].edit_data.can_be_interviewed);
            // }

            // job_list (resumeJobs)
            // for(let i = 0; i < this.$data.job_list.length; i++) {
            //     post_data['Resume[resumeJobs]['+i+'][company_name]'] = this.$data.job_list[i].edit_data.company_name;
            //     post_data['Resume[resumeJobs]['+i+'][position]'] = this.$data.job_list[i].edit_data.position;
            //     post_data['Resume[resumeJobs]['+i+'][month]'] = this.$data.job_list[i].edit_data.month;
            //     post_data['Resume[resumeJobs]['+i+'][years]'] = this.$data.job_list[i].edit_data.years;
            //     post_data['Resume[resumeJobs]['+i+'][for_now]'] = this.getForNowValue(this.$data.job_list[i].edit_data.for_now);
            // }

            // foreign_job_list (resumeForeignJob)
            // for(let i = 0; i < this.$data.foreign_job_list.length; i++) {
            //     post_data['Resume[resumeForeignJob]['+i+'][country_code]'] = this.$data.foreign_job_list[i].edit_data.country_code;
            //     post_data['Resume[resumeForeignJob]['+i+'][position]'] = this.$data.foreign_job_list[i].edit_data.position;
            // }

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
        });
    },
    selectCategory: function(category_item) {
        category_item.checked = !category_item.checked;
        
        // upgrade selected list
        this.$data.resume.selected_categories = _.filter(this.$data.category_list, function(p) {
            return p.checked;
        });
    },
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