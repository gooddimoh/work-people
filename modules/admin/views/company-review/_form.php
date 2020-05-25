<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CompanyReview;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/js/app/dist/vue.js');
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js');
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js');

$model_attributes = $model->getAttributes();
?>

<div id="appUpdateCompanyReview" class="company-review-form">

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

    <?= $form->field($model, 'status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => CompanyReview::getStatusList(),
        'options' => [
            'v-model' => 'company_review.status',
            'v-validate' => 'vv.model_company_review.status'
        ]
    ]) ?>

    <?= $form->field($model, 'company_id')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.company_id',
            'v-validate' => 'vv.model_company_review.company_id'
        ]
    ]) ?>

    <?= $form->field($model, 'position')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.position',
            'v-validate' => 'vv.model_company_review.position'
        ]
    ]) ?>

    <?= $form->field($model, 'worker_status')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => CompanyReview::getWorkerStatusList(),
        'options' => [
            'v-model' => 'company_review.worker_status',
            'v-validate' => 'vv.model_company_review.worker_status'
        ]
    ]) ?>

    <?= $form->field($model, 'department')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.department',
            'v-validate' => 'vv.model_company_review.department'
        ]
    ]) ?>

    <?= $form->field($model, 'date_end')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.date_end',
            'v-validate' => 'vv.model_company_review.date_end'
        ]
    ]) ?>

    <?= $form->field($model, 'rating_total')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.rating_total',
            'v-validate' => 'vv.model_company_review.rating_total'
        ]
    ]) ?>

    <?= $form->field($model, 'rating_salary')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.rating_salary',
            'v-validate' => 'vv.model_company_review.rating_salary'
        ]
    ]) ?>

    <?= $form->field($model, 'rating_opportunities')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.rating_opportunities',
            'v-validate' => 'vv.model_company_review.rating_opportunities'
        ]
    ]) ?>

    <?= $form->field($model, 'rating_bosses')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'v-model' => 'company_review.rating_bosses',
            'v-validate' => 'vv.model_company_review.rating_bosses'
        ]
    ]) ?>

    <?= $form->field($model, 'general_impression')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.general_impression',
            'v-validate' => 'vv.model_company_review.general_impression'
        ]
    ]) ?>

    <?= $form->field($model, 'pluses_impression')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.pluses_impression',
            'v-validate' => 'vv.model_company_review.pluses_impression'
        ]
    ]) ?>

    <?= $form->field($model, 'minuses_impression')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.minuses_impression',
            'v-validate' => 'vv.model_company_review.minuses_impression'
        ]
    ]) ?>

    <?= $form->field($model, 'tips_for_bosses')->widget(VueTextInputWidget::classname(), [
        'options' => [
            'maxlength' => true,
            'v-model' => 'company_review.tips_for_bosses',
            'v-validate' => 'vv.model_company_review.tips_for_bosses'
        ]
    ]) ?>

    <?= $form->field($model, 'worker_recommendation')->widget(VueTextInputWidget::classname(), [
        'type' => VueTextInputWidget::DROP_DOWN_LIST,
        'items' => CompanyReview::getWorkerRecommendationList(),
        'options' => [
            'v-model' => 'company_review.worker_recommendation',
            'v-validate' => 'vv.model_company_review.worker_recommendation'
        ]
    ]) ?>

    <div class="form-group">
    <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => 'btn btn-success', 'v-on:click' => 'companyReviewInfoSave']) ?>
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
  el: '#appUpdateCompanyReview',
  data: {
    response_errors: void 0,
    submit_clicked: false,
    vv: {
        model_company_review: {
            <?php
            foreach($model_attributes as $name => $XD) {
                $validator_str = VeeValidateHelper::getVValidateString($this, $model, $name);
                if(empty($validator_str)) continue; // skip empty validators
                echo $name . ': \'' . $validator_str . "',\r\n";
            }
            ?>
        },
	},
	company_review: {
		<?php foreach($model_attributes as $name => $val): ?>
            <?php echo "$name: '$val', \r\n" ?>
        <?php endforeach; ?>
	}
  },
  mounted: function() {
	// --
  },
  methods: {
    onSubmit () {
        return; // supress submit
    },
    companyReviewInfoSave: function() {
        this.$validator.validate().then(valid => {
        this.$data.submit_clicked = true;
        if (!valid) {
            this.scrollToErrorblock();
            return;
        }

        let post_data = {
            // 'CompanyReview[id]': this.$data.company_review.id,
            'CompanyReview[status]': this.$data.company_review.status,
            'CompanyReview[company_id]': this.$data.company_review.company_id,
            'CompanyReview[position]': this.$data.company_review.position,
            'CompanyReview[worker_status]': this.$data.company_review.worker_status,
            'CompanyReview[department]': this.$data.company_review.department,
            'CompanyReview[date_end]': this.$data.company_review.date_end,
            'CompanyReview[rating_total]': this.$data.company_review.rating_total,
            'CompanyReview[rating_salary]': this.$data.company_review.rating_salary,
            'CompanyReview[rating_opportunities]': this.$data.company_review.rating_opportunities,
            'CompanyReview[rating_bosses]': this.$data.company_review.rating_bosses,
            'CompanyReview[general_impression]': this.$data.company_review.general_impression,
            'CompanyReview[pluses_impression]': this.$data.company_review.pluses_impression,
            'CompanyReview[minuses_impression]': this.$data.company_review.minuses_impression,
            'CompanyReview[tips_for_bosses]': this.$data.company_review.tips_for_bosses,
            'CompanyReview[worker_recommendation]': this.$data.company_review.worker_recommendation,

        };
        for(let i = 0; i < this.$refs.form.length; i++) {
            if(!this.$refs.form[i].name) {
                continue;
            }
            
            post_data[this.$refs.form[i].name] = this.$refs.form[i].value;
		}

		// fix radio value
		post_data['CompanyReview[worker_status]'] = this.$data.company_review.worker_status;
		post_data['CompanyReview[worker_recommendation]'] = this.$data.company_review.worker_recommendation;
		
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