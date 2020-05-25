<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Company;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/js/app/dist/vue.js');
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js');
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js');

$model_attributes = $model->getAttributes();
$model_user_attributes = $modelUser->getAttributes();
$number_of_employees_list = Company::getNumberOfEmployeesList();
$country_list = Company::getCountryList();
?>

<div id="appUpdateCompany" class="company-form">

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

    <legend>Данные аккаунта</legend>

    <?= $form->field($modelUser, 'login')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.user.login',
            'v-validate' => 'vv.model_user.login'
        ]
    ]) ?>

    <?= $form->field($modelUser, 'username')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.user.username',
            'v-validate' => 'vv.model_user.username'
        ]
    ]) ?>

    <?= $form->field($modelUser, 'email')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.user.email',
            'v-validate' => 'vv.model_user.email'
        ]
    ])->label($modelUser->getAttributeLabel('email') . ' <i>(на этот email будет направлено письмо с регистрацией)</i>') ?>

    <?= $form->field($modelUser, 'phone')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.user.phone',
            'v-validate' => 'vv.model_user.phone'
        ]
    ]) ?>

    <?= $form->field($modelUser, 'role')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => [
            User::ROLE_ADMINISTRATOR => 'Администратор сайта',
            User::ROLE_USER => 'Пользователь',
        ],
        'options' => [
            'v-model' => 'company.user.role',
            'v-validate' => 'vv.model_company.role'
        ]
    ]) ?>

    <hr>
    <legend>Данные компании</legend>
    <?= $form->field($model, 'status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Company::getStatusList(),
        'options' => [
            'v-model' => 'company.status',
            'v-validate' => 'vv.model_company.status'
        ]
    ]) ?>

    <?= $form->field($model, 'company_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.company_name',
            'v-validate' => 'vv.model_company.company_name'
        ]
    ]) ?>

    <?= $form->field($model, 'company_phone')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.company_phone',
            'v-validate' => 'vv.model_company.company_phone'
        ]
    ]) ?>

    <?= $form->field($model, 'company_email')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.company_email',
            'v-validate' => 'vv.model_company.company_email'
        ]
    ]) ?>

    <?= $form->field($model, 'company_country_code')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => ArrayHelper::map($country_list, 'char_code', 'name'),
        'options' => [
            'v-model' => 'company.company_country_code',
            'v-validate' => 'vv.model_company.company_country_code',
            // 'v-on:change' => "countryChanged",
        ]
    ]) ?>

    <?= $form->field($model, 'logo')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company.logo',
            'v-validate' => 'vv.model_company.logo'
        ]
    ]) ?>

    <?= $form->field($model, 'type')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Company::getTypeList(),
        'options' => [
            'v-model' => 'company.type',
            'v-validate' => 'vv.model_company.type'
        ]
    ]) ?>

    <?= $form->field($model, 'type_industry')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.type_industry',
            'v-validate' => 'vv.model_company.type_industry'
        ]
    ]) ?>

    <?= $form->field($model, 'number_of_employees')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => Company::getNumberOfEmployeesList(),
        'options' => [
            'v-model' => 'company.number_of_employees',
            'v-validate' => 'vv.model_company.number_of_employees'
        ]
    ]) ?>

    <?= $form->field($model, 'worker_country_codes')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::MULTI_SELECT_CHECKBOX,
        'items' => 'country_list',
        'checkBoxAttributes' => 'v-on:change="workerCountryCodesChange"',
        'options' => [
            'v-model' => 'company.worker_country_codes',
            'v-validate' => 'vv.model_company.worker_country_codes',
        ]
    ]) ?>

    <?= $form->field($model, 'site')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.site',
            'v-validate' => 'vv.model_company.site'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_name')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.contact_name',
            'v-validate' => 'vv.model_company.contact_name'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_phone')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.contact_phone',
            'v-validate' => 'vv.model_company.contact_phone'
        ]
    ]) ?>

    <?= $form->field($model, 'contact_email')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.contact_email',
            'v-validate' => 'vv.model_company.contact_email'
        ]
    ]) ?>

    <?= $form->field($model, 'description')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::TEXT_AREA,
        'options' => [
            'rows' => 6,
            'v-model' => 'company.description',
            'v-validate' => 'vv.model_company.description'
        ]
    ]) ?>

    <?= $form->field($model, 'document_code')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company.document_code',
            'v-validate' => 'vv.model_company.document_code'
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success', 'v-on:click' => 'companyInfoSave']) ?>
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

new Vue({
  el: '#appUpdateCompany',
  data: {
    company: {
        <?php foreach($model_attributes as $name => $val): ?>
            <?php echo "$name: ". json_encode($val) .", \r\n" ?>
        <?php endforeach; ?>
        user: {
            <?php foreach($model_user_attributes as $name => $val): ?>
                <?php echo "$name: '$val', \r\n" ?>
            <?php endforeach; ?>
        }
    },
    response_errors: void 0,
    submit_clicked: false,
    number_of_employees_list: [
        <?php foreach( $number_of_employees_list as $key => $value): ?>
        {
            id: '<?= $key ?>',
            name: '<?= $value ?>',
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
    vv: {
        model_company: {
            <?php
            foreach($model_attributes as $name => $XD) {
                $validator_str = VeeValidateHelper::getVValidateString($this, $model, $name);
                if(empty($validator_str)) continue; // skip empty validators
                echo $name . ': \'' . $validator_str . "',\r\n";
            }
            ?>
        },
        model_user: {
            <?php
            foreach($model_user_attributes as $name => $XD) {
                $validator_str = VeeValidateHelper::getVValidateString($this, $modelUser, $name);
                if(empty($validator_str)) continue; // skip empty validators
                echo $name . ': \'' . $validator_str . "',\r\n";
            }
            ?>
        },
    }
  },
  mounted: function() {
    if(this.$data.company.worker_country_codes == null) { // fix null value
        this.$data.company.worker_country_codes = '';
    }

    // worker_country_codes
    for (let i = 0; i < this.$data.country_list.length; i++) {
        this.$data.country_list[i].checked = false;
        if ( this.$data.company.worker_country_codes.indexOf(this.$data.country_list[i].char_code + ';') !== -1) {
            this.$data.country_list[i].checked = true;
        }
    }
  },
  methods: {
    onSubmit: function() {
        return; // supress submit
    },
    companyInfoSave: function() {
      this.$validator.validate().then(valid => {
        this.$data.submit_clicked = true;
        if (!valid) {
            this.scrollToErrorblock();
            return;
        }

        let post_data = {
            // 'Company[id]': this.$data.company.id,
            // 'Company[user_id]': this.$data.company.user_id,
            'Company[status]': this.$data.company.status,
            'Company[company_name]': this.$data.company.company_name,
            'Company[logo]': this.$data.company.logo,
            'Company[type]': this.$data.company.type,
            'Company[type_industry]': this.$data.company.type_industry,
            'Company[number_of_employees]': this.$data.company.number_of_employees,
            'Company[worker_country_codes]': this.$data.company.worker_country_codes,
            'Company[site]': this.$data.company.site,
            'Company[contact_name]': this.$data.company.contact_name,
            'Company[contact_phone]': this.$data.company.contact_phone,
            'Company[contact_email]': this.$data.company.contact_email,
            'Company[description]': this.$data.company.description,
            'Company[document_code]': this.$data.company.document_code,
            // user
            'User[login]': this.$data.company.user.login,
            'User[status]': this.$data.company.user.status,
            'User[username]': this.$data.company.user.username,
            'User[email]': this.$data.company.user.email,
            'User[phone]': this.$data.company.user.phone,
            // 'User[phone_messangers]': this.$data.company.user.phone_messangers,
            'User[role]': this.$data.company.user.role,
            // 'User[auth_key]': this.$data.company.user.auth_key,
            // 'User[password_hash]': this.$data.company.user.password_hash,
            // 'User[password_reset_token]': this.$data.company.user.password_reset_token,
            // 'User[email_confirm_token]': this.$data.company.user.email_confirm_token,
            // 'User[created_at]': this.$data.company.user.created_at,
            // 'User[updated_at]': this.$data.company.user.updated_at,
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
    workerCountryCodesChange: function() {
        let selected_codes = '';
        for(let i = 0; i < this.$data.country_list.length; i++) {
            if(this.$data.country_list[i].checked) {
                selected_codes += this.$data.country_list[i].char_code + ';';
            }
        }

        this.$data.company.worker_country_codes = selected_codes;
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