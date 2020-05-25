<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\VeeValidateHelper;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

$country_list = Company::getCountryList();
?>

<?php $form = ActiveForm::begin([
    'id' => 'appCreateCompany',
    'enableClientValidation' => false,
    'options' => [
        'class' => 'registration__form create-company-form',
        'v-on:submit.prevent' => "onSubmit",
        'ref' => 'form'
    ]
]);?>
    <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
        <div class="error-message-alert"><?=Yii::t('main', 'Errors')?>:</div>
        <ul v-for="(error_messages, field_name) of response_errors">
            <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
        </ul>

        <ul v-if="submit_clicked && errors.all()">
            <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
        <ul>
    </div>

    
    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <!-- <?= $model->getAttributeLabel('company_name') ?> -->
            <?= Yii::t('company', 'Your company name') ?>
        </div>
        <input v-validate="vv.model_company.company_name" name='Company[company_name]' class="form-control" type="text" v-model="company.company_name">
        <span v-show="errors.has('Company[company_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[company_name]') }}
        </span>
    </div>

    <label class="radio">
        <input type="radio" name="Company[type]" class="btn" value="<?= Company::TYPE_EMPLOYER ?>" v-model="company.type">
        <span class="radio__radio"></span>
        <span class="radio__title">
            <b><?= Yii::t('company', 'You are an employer') ?></b>
            <?= Yii::t('company', 'Are you an employer or outstaffing company') ?>
        </span>
    </label>
    <label class="radio">
        <input type="radio" name="Company[type]" class="btn" value="<?= Company::TYPE_HR_AGENCY ?>" v-model="company.type">
        <span class="radio__radio"></span>
        <span class="radio__title">
            <b><?= Yii::t('company', 'You are an agency') ?></b>
            <?= Yii::t('company', 'Collaborate with enterprises and other recruiting companies') ?>
        </span>
    </label>
    <br>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('company_phone') ?>
        </div>
        <input v-validate="vv.model_company.company_phone" name='Company[company_phone]' class="form-control" type="text" v-model="company.company_phone">
        <span v-show="errors.has('Company[company_phone]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[company_phone]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('site') ?>
        </div>
        <input v-validate="vv.model_company.site" name='Company[site]' class="form-control" type="text" v-model="company.site">
        <span v-show="errors.has('Company[site]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[site]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('company_country_code') ?>
        </div>
        <input v-validate="vv.model_company.company_country_code" name='Company[company_country_code]' class="form-control" type="hidden" v-model="company.company_country_code">
        <nice-select :options="country_list" v-bind:name="`Company[company_country_code]`" class="select select--double" v-model="company.company_country_code" v-on:input="countryChanged">
            <option v-for="item in country_list" :value="item.char_code" >
                {{ item.name }}
            </option>
        </nice-select>

        <span v-show="errors.has('Company[company_country_code]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[company_country_code]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title" style="margin-top: 25px;">
            <?= $model->getAttributeLabel('country_city_id') ?>
        </div>
        <nice-select :options="country_city_list" name="Company[country_city_id]`" class="select select--double" v-model="company.country_city_id" v-if="country_city_refresh_flag">
            <option v-for="item in country_city_list" :value="item.id">
                {{ item.city_name }}
            </option>
        </nice-select>
        <input v-validate="vv.model_company.country_city_id" name='Company[country_city_id]' type="hidden" v-model="company.country_city_id">
        <span v-show="errors.has('Company[country_city_id]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[country_city_id]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('description') ?>
        </div>
        <textarea v-validate="vv.model_company.description" name='Company[description]' cols="30" rows="10" v-model="company.description"></textarea>
        <span v-show="errors.has('Company[description]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[description]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $modelUserPhone->getAttributeLabel('company_worker_name') ?>
        </div>
        <input v-validate="vv.model_user_phone.company_worker_name" name='UserPhone[company_worker_name]' class="form-control" type="text" v-model="company.user_phone.company_worker_name">
        <span v-show="errors.has('UserPhone[company_worker_name]')" class="validation-error-message" v-cloak>
            {{ errors.first('UserPhone[company_worker_name]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $modelUserPhone->getAttributeLabel('contact_phone_for_admin') ?>
            <div class="registration__input-desc">
                <?= Yii::t('company', 'to contact you, is not published on the site') ?>
            </div>
        </div>
        <input v-validate="vv.model_user_phone.contact_phone_for_admin" name='UserPhone[contact_phone_for_admin]' class="form-control" type="text" v-model="company.user_phone.contact_phone_for_admin">
        <span v-show="errors.has('UserPhone[contact_phone_for_admin]')" class="validation-error-message" v-cloak>
            {{ errors.first('UserPhone[contact_phone_for_admin]') }}
        </span>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $modelUserPhone->getAttributeLabel('company_worker_email') ?>
        </div>
        <input v-validate="vv.model_user_phone.company_worker_email" name='UserPhone[company_worker_email]' class="form-control" type="text" v-model="company.user_phone.company_worker_email">
        <span v-show="errors.has('UserPhone[company_worker_email]')" class="validation-error-message" v-cloak>
            {{ errors.first('UserPhone[company_worker_email]') }}
        </span>
    </div>
<?php /*
    <div class="registration__input-bl">
        <div class="registration__input-title">
            <?= $model->getAttributeLabel('contact_phone') ?>
            <div class="registration__input-desc">
                <?= Yii::t('company', 'to contact you, is not published on the site') ?>
            </div>
        </div>
        <span v-for="(email_item, index) of company.contact_phone_list">
            <div class="reg-lang__top" v-if="index != 0" v-cloak>
                <div class="reg-lang__title">
                </div>
                <div class="reg-lang__edit">
                    <a class="pointer" v-on:click="removePhone(index)"><?= Yii::t('main', 'Remove') ?></a>
                </div>
            </div>
            <input v-validate="vv.model_company.contact_phone" v-bind:name='`Contact[contact_phone_list][${index}]`' class="form-control" type="text" v-model="company.contact_phone_list[index]" v-on:change="changePhone">
            <span v-show="errors.has('Contact[contact_phone_list][' + index + ']')" class="validation-error-message" v-cloak>
                {{ errors.first('Contact[contact_phone_list][' + index + ']') }}
            </span>
        </span>
        <input v-validate="vv.model_company.contact_phone_limit" name='Contact[contact_phone]' class="form-control" type="hidden" v-model="company.contact_phone">
        <span v-show="errors.has('Contact[contact_phone]')" class="validation-error-message" v-cloak>
            {{ errors.first('Contact[contact_phone]') }}
        </span>
        <div class="registration__add-email" v-on:click="addPhone">
            <?= Yii::t('company', 'Add an alternate phone number') ?>
        </div>
    </div>

    <div class="registration__input-bl">
        <div class="registration__input-title"  style="margin-top: 25px;">
            <?= $model->getAttributeLabel('contact_email') ?>
        </div>
        <input v-validate="vv.model_company.contact_email" name='Company[contact_email]' class="form-control" type="text" v-model="company.contact_email">
        <span v-show="errors.has('Company[contact_email]')" class="validation-error-message" v-cloak>
            {{ errors.first('Company[contact_email]') }}
        </span>
    </div>
*/ ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('company', 'Finish registration'), ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

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

VeeValidate.Validator.extend('company_contact_phone_item_required', {
    getMessage: function(field) {
        return '<?= Yii::t('company', 'You must fill in the «Contact Phone».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('company_contact_phone_item_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('company', 'The aggregate value of all «Contact Phone» must contain a maximum of 255 characters.') ?>';
    },
    validate: function(value) {
        return value.trim().length < 255;
    }
});

new Vue({
  el: '#appCreateCompany',
  data: {
    company: {
        company_name: '',
        company_phone: '',
        company_country_code: '',
        country_city_id: '',
        type: '<?= Company::TYPE_EMPLOYER ?>',
        site: '',
        contact_name: '',
        contact_phone: '',
        contact_email: '',
        description: '',
        user_phone: {
            contact_phone_for_admin: '',
            company_worker_name: '',
            company_worker_email: '',
        },
        // --
        contact_phone_limit: '',
        contact_phone_list: [
            ''
        ],
    },
    response_errors: void 0,
    submit_clicked: false,
    country_city_refresh_flag: true,
    country_char_code_last: '',
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
    vv: {
        model_company: {
            company_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_name') ?>',
            company_phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_phone') ?>',
            type: '<?= VeeValidateHelper::getVValidateString($this, $model, 'type') ?>',
            site: '<?= VeeValidateHelper::getVValidateString($this, $model, 'site') ?>',
            company_country_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_country_code') ?>',
            country_city_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
            contact_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_name') ?>',
            contact_phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_phone') ?>',
            contact_phone_limit: 'company_contact_phone_item_required|required|company_contact_phone_item_limit',
            contact_email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_email') ?>',
            description: '<?= VeeValidateHelper::getVValidateString($this, $model, 'description') ?>',
        },
        model_user_phone: {
            contact_phone_for_admin: '<?= VeeValidateHelper::getVValidateString($this, $modelUserPhone, 'contact_phone_for_admin') ?>',
            company_worker_name: '<?= VeeValidateHelper::getVValidateString($this, $modelUserPhone, 'company_worker_name') ?>',
            company_worker_email: '<?= VeeValidateHelper::getVValidateString($this, $modelUserPhone, 'company_worker_email') ?>',
        }
    }
  },
  mounted: function() {
    // $("input[name='Company[company_phone]']").inputmask('+9{*}');
    // this.countryChanged();
  },
  methods: {
    onSubmit () {
      this.$validator.validate().then(valid => {
        this.$data.submit_clicked = true;
        if (!valid) {
            this.scrollToErrorblock();
            return;
        }

        // this.$refs.form.submit();

        let post_data = {
            'Company[company_name]': this.$data.company.company_name,
            'Company[company_phone]': this.$data.company.company_phone,
            'Company[company_country_code]': this.$data.company.company_country_code,
            'Company[country_city_id]': this.$data.company.country_city_id,
            'Company[type]': this.$data.company.type,
            'Company[site]': this.$data.company.site,
            'Company[description]': this.$data.company.description,
            // relation:
            'UserPhone[contact_phone_for_admin]': this.$data.company.user_phone.contact_phone_for_admin,
            'UserPhone[company_worker_name]': this.$data.company.user_phone.company_worker_name,
            'UserPhone[company_worker_email]': this.$data.company.user_phone.company_worker_email,
        };

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
    countryChanged: function() {
        let country_char_code = this.$data.company.company_country_code;
        
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
    addPhone: function() {
        this.$data.company.contact_phone_list.push('');
    },
    changePhone: function() {
        this.$data.company.contact_phone = this.$data.company.contact_phone_list.join(';');
    },
    removePhone: function(index) {
        this.$data.company.contact_phone_list.splice(index, 1);
        this.changePhone();
    },
    // --
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