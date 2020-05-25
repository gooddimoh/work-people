<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Profile;
use app\models\ProfileJob;
use app\models\ProfileEducation;
use app\models\ProfileLanguage;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

$gender_list = Profile::getGenderList();
$country_list = Profile::getCountryList();

?>

<div id="appCreateProfile" class="registration registration--2" v-bind:class="{ 'registration--sfera': form_current_step == 2 }">
    <div class="container">
        <div class="registration__number-step">
            <b><?= Yii::t('main', 'Step') ?> <span v-html="form_current_step">1</span> <?= Yii::t('main', 'of') ?> 3</b>&nbsp;
            <span v-if="form_current_step == 1"><?= Yii::t('profile', 'For candidate') ?></span>
        </div>
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="registration__title">
                    
                        <span v-if="form_current_step == 1"><?= Yii::t('profile', 'For candidate') ?></span>
                        
                        <div v-if="form_current_step == 1">
                            <?= Yii::t('profile', 'Sign up') ?>
                        </div>
                    </div>

<!-- form here -->
<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => [
        'class' => 'registration__form create-profile-form',
        'v-on:submit.prevent' => "onSubmit",
        'ref' => 'form'
    ]
]);?>
    <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
        <div class="error-message-alert"><?=Yii::t('main', 'Errors') ?>:</div>
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
        <input v-validate="vv.model_profile.first_name" name='Profile[first_name]' class="form-control" type="text" v-model="profile.first_name">
        <span v-show="errors.has('Profile[first_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[first_name]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('last_name') ?>
        </div>
        <input v-validate="vv.model_profile.last_name" name='Profile[last_name]' class="form-control" type="text" v-model="profile.last_name">
        <span v-show="errors.has('Profile[last_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[last_name]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('email') ?>
        </div>
        <input v-validate="vv.model_profile.email" name='Profile[email]' class="form-control" type="text" v-model="profile.email">
        <span v-show="errors.has('Profile[email]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[email]') }}
        </span>
    </div>

<?php /*
    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('middle_name') ?>
        </div>
        <input v-validate="vv.model_profile.middle_name" name='Profile[middle_name]' class="form-control" type="text" v-model="profile.middle_name">
        <span v-show="errors.has('Profile[middle_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[middle_name]') }}
        </span>
    </div>
*/ ?>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('birth_day') ?>
        </div>
        <!-- <input v-validate="vv.model_profile.birth_day" name='Profile[birth_day]' type="text" id="profile-birth_day"> -->
        <air-datepicker v-validate="vv.model_profile.birth_day" name='Profile[birth_day]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="profile.birth_day" class="j-edit-input edit"></air-datepicker>
        <span v-show="errors.has('Profile[birth_day]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[birth_day]') }}
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
        
        <input v-validate="vv.model_profile.gender_list" name='Profile[gender_list]' type="hidden" v-model="profile.gender_list">
        <span v-show="errors.has('Profile[gender_list]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[gender_list]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('country_name') ?>
        </div>
        <nice-select :options="country_list" name="Profile[country_name]`" class="select select--double" v-model="profile.country_name" v-on:input="countryChanged">
            <option v-for="item in country_list" :value="item.char_code" >
                {{ item.name }}
            </option>
        </nice-select>
        <input v-validate="vv.model_profile.country_name" name='Profile[country_name]' type="hidden" v-model="profile.country_name">
        <span v-show="errors.has('Profile[country_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[country_name]') }}
        </span>
    </div>

    <div class="registration__input-bl" v-if="form_current_step == 1">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('country_city_id') ?>
        </div>
        <nice-select :options="country_city_list" name="Profile[country_city_id]`" class="select" v-model="profile.country_city_id" v-if="country_city_refresh_flag">
            <option v-for="item in country_city_list" :value="item.id">
                {{ item.city_name }}
            </option>
        </nice-select>
        <input v-validate="vv.model_profile.country_city_id" name='Profile[country_city_id]' type="hidden" v-model="profile.country_city_id">
        <span v-show="errors.has('Profile[country_city_id]')" class="validation-error-message" v-cloak>
            {{ errors.first('Profile[country_city_id]') }}
        </span>
    </div>
    <!--/ step 1 -->

    <div class="form-group">
        <input type="submit" class="btn" value="<?= Yii::t('profile', 'Finish registration') ?>" v-on:click="nextStep">
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
Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'validation-error-input',
    },
    events: 'change|blur|keyup'
});

VeeValidate.Validator.extend('profile_selected_profile_category_jobs_validator_required', {
    getMessage: function(field) {
        return '<?= Yii::t('resume', 'You must fill out the «Preferred job».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

new Vue({
  el: '#appCreateProfile',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
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
    gender_list: [
        <?php foreach( $gender_list as $key_id => $gender): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $gender ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    //-------
    form_current_step: 1,
    response_errors: void 0,
    country_char_code_last: '',
    submit_clicked: false,
    country_city_refresh_flag: true,
    profile: {
        // id: '',
        // user_id: '',
        status: '<?= Profile::STATUS_VISIBLE ?>',
        first_name: '',
        last_name: '',
        middle_name: '',
        email: '',
        gender_list: '',
        birth_day: '',
        country_name: '',
        country_city_id: '',
        // photo_path: '',
        // phone: '',
        // objects:
        selected_profile_category_jobs: [],
        category_job_item: null,
        selected_desired_country_of_work: [],
    },
    vv: {
        model_profile: {
            selected_category_jobs: 'profile_selected_profile_category_jobs_validator_required|required',
            first_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'first_name') ?>',
            last_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'last_name') ?>',
            email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'email') ?>',
            <?php /* middle_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'middle_name') ?>', */ ?>
            gender_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'gender_list') ?>',
            birth_day: '<?= VeeValidateHelper::getVValidateString($this, $model, 'birth_day') ?>',
            country_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_name') ?>',
            country_city_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
        },
    }
  },
  mounted: function() {
    // --
  },
  methods: {
    onSubmit () {
        return; // supress submit
    },
    previousStep: function() {
        this.$data.form_current_step = this.$data.form_current_step - 1;
    },
    nextStep: function() {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            if(this.$data.form_current_step < 1) {
                this.$validator.reset();
                this.$data.submit_clicked = false;
                this.$data.form_current_step = this.$data.form_current_step + 1;
                this.scrollToTop();
            } else {
                // this.$refs.form.submit();

                let post_data = {
                    'Profile[status]': this.$data.profile.status,
                    'Profile[type]': this.$data.profile.type,
                    'Profile[first_name]': this.$data.profile.first_name,
                    'Profile[last_name]': this.$data.profile.last_name,
                    // 'Profile[middle_name]': this.$data.profile.middle_name,
                    'Profile[email]': this.$data.profile.email,
                    'Profile[gender_list]': this.$data.profile.gender_list,
                    'Profile[birth_day]': this.$data.profile.birth_day ? moment(this.$data.profile.birth_day, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD') : void 0,
                    'Profile[country_name]': this.$data.profile.country_name,
                    'Profile[country_city_id]': this.$data.profile.country_city_id,
                    // 'Profile[photo_path]': this.$data.profile.photo_path,
                    // 'Profile[phone]': this.$data.profile.phone,
                };

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
    genderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.profile.gender_list = selected_genders;
    },
    countryChanged: function() {
        let country_char_code = this.$data.profile.country_name;
        
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
                scrollTop: $("#appCreateProfile").offset().top - 200
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