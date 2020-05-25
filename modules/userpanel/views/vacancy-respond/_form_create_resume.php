<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\VeeValidateHelper;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\VacancyRespond */
/* @var $model_resume app\models\Vacancy */
/* @var $my_vacancy_list app\models\Vacancy */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

?>

<div id="appCreateVacancyRespond" class="registration registration--2">
    <div class="container">
        <div class="registration__inner">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => [
                    'class' => 'registration__form',
                    'v-on:submit.prevent' => "onSubmit",
                    'ref' => 'form'
                ]
            ]); ?>
                <div class="registration__title">
                    <?= Html::encode($this->title) ?>
                </div>

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
                        <?= Yii::t('user', 'Full Name') ?>
                    </div>
                    <div style="margin:20px;">
                        <a href="<?= Url::to(['/resume/view', 'id' => $model_resume->id]) ?>" target="_blank">#<?= $model_resume->id ?>&nbsp;<?= $model_resume->getFullName() ?></a>
                    </div>
                </div>
                <div class="registration__input-bl">
                    <div class="registration__input-title">
                        <?= Yii::t('vacancy', 'Attach a vacancy to your job offer') ?>
                    </div>
                    <input v-validate="vv.model_vacancy_respond.vacancy_id" name='VacancyRespond[vacancy_id]' type="hidden" v-model="vacancy_respond.vacancy_id">
                    <nice-select :options="my_vacancy_list" name="VacancyRespond[vacancy_id]`" class="select" v-model="vacancy_respond.vacancy_id">
                        <option v-for="item in my_vacancy_list" :value="item.id" v-cloak>
                            {{ item.title }}
                        </option>
                    </nice-select>
                    <span v-show="errors.has('VacancyRespond[vacancy_id]')" class="validation-error-message" v-cloak>
                        {{ errors.first('VacancyRespond[vacancy_id]') }}
                    </span>
                </div>
                <div class="registration__input-bl">
                    <div class="registration__input-title" style="margin-top: 25px;">
                        <?= Yii::t('vacancy', 'Transmittal letter') ?>
                    </div>
                    <div class="textarea">
                        <textarea v-validate="vv.model_vacancy_respond.message" name='VacancyRespond[message]' cols="30" rows="10" v-model="vacancy_respond.message"></textarea>
                        <span v-show="errors.has('VacancyRespond[message]')" class="validation-error-message" v-cloak>
                            {{ errors.first('VacancyRespond[message]') }}
                        </span>
                        <div class="textarea__col">
                            <div class="textarea__inner">
                                <!-- <div class="textarea__title">
                                    Например:
                                </div> -->
                                <ul>
                                    <li>
                                        <?= Yii::t('vacancy', 'You can accompany your response with a few words to draw attention to your most important professional or personal qualities.') ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="registration__submit registration__submit--one" style="margin-top: 20px;">
                    <button type="button" class="btn" v-on:click="onSubmit">
                        <?= Yii::t('vacancy', 'Confirm') ?>
                    </button>
                </div>
            <?php ActiveForm::end(); ?>
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

new Vue({
  el: '#appCreateVacancyRespond',
  // --
  data: {
    my_vacancy_list: [
        {
            id: '',
            title: '<?= Yii::t('vacancy', 'Not selected') ?>',
        },
        <?php foreach( $my_vacancy_list as $vacancy) { ?>
        {
            id: '<?= $vacancy->id ?>',
            title: '#<?= $vacancy->id ?> - <?= $vacancy->categoryJob->name ?>',
        },
        <?php } ?>
    ],
    //-------
    response_errors: void 0,
    submit_clicked: false,
    file_format_error: false,
    vacancy_respond: {
        vacancy_id: '',
        message: '',
    },
    vv: {
        model_vacancy_respond: {
            vacancy_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'vacancy_id') ?>',
            message: '<?= VeeValidateHelper::getVValidateString($this, $model, 'message') ?>',
        },
    }
  },
  mounted: function() {
    // -
  },
  methods: {
    onSubmit () {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            // this.$refs.form.submit();
            let post_data = {
                'VacancyRespond[vacancy_id]': this.$data.vacancy_respond.vacancy_id,
                'VacancyRespond[message]': this.$data.vacancy_respond.message,
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
                scrollTop: $("#appCreateVacancyRespond").offset().top - 200
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