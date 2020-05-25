<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SafeDeal;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

$currency_list = SafeDeal::getCurrencyList();
$execution_range_list = SafeDeal::getExecutionRangeList();
?>

<div id="appCreateSafeDeal" class="bg-grey-front">
    <div class="container">

        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'options' => [
                'class' => 'safe-deal-sheet',
                'v-on:submit.prevent' => "onSubmit",
                'ref' => 'form'
            ]
        ]);?>
            <div class="title-sec"><?= Yii::t('deal', 'Safe Deal') ?></div>
            <h3><?= Yii::t('deal', 'Fill in the safe deal fields') ?></h3>

            <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
                <ul v-for="(error_messages, field_name) of response_errors">
                    <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                </ul>

                <ul v-if="submit_clicked && errors.all()">
                    <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                <ul>
            </div>

            <div class="deal-step">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('title') ?></h4>
                <p class="deal-step-description"><?= Yii::t('deal', 'For example: “Payment for documents and vacancy” or “Payment of commission for the provided candidate”') ?></p>
                <input v-validate="vv.model_safe_deal.title" name='SafeDeal[first_name]' class="form-control" type="text" v-model="safe_deal.title">
                <span v-show="errors.has('SafeDeal[first_name]')" class="validation-error-message" v-cloak>
                    {{ errors.first('SafeDeal[first_name]') }}
                </span>
            </div>

            <div class="deal-step deal-sum">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('amount_total') ?></h4>
                <div class="input-group">
                    <div class="input-group__col">
                        <input v-validate="vv.model_safe_deal.amount_total" name='SafeDeal[amount_total]' class="form-control" type="text" v-model="safe_deal.amount_total">
                        <span v-show="errors.has('SafeDeal[amount_total]')" class="validation-error-message" v-cloak>
                            {{ errors.first('SafeDeal[amount_total]') }}
                        </span>
                    </div>
                    <div class="input-group__col">
                        <nice-select :options="currency_list" name="SafeDeal[amount_currency_code]`" class="select" v-model="safe_deal.amount_currency_code">
                            <option v-for="item in currency_list" :value="item.char_code" >
                                {{ item.name }}
                            </option>
                        </nice-select>
                    </div>
                </div>
                <p class="deal-step-description"><?= Yii::t('deal', 'You have {amount} in your account, which you can use to reserve payment. Currency balance and transactions must match.', ['amount' => '1500€']) ?></p>
            </div>

            <div class="deal-step deal-sum prepayment">
                <label class="checkbox">
                    <input type="hidden" name="SafeDeal[has_prepaid]`" v-bind:value="getHasPrepaidValue(safe_deal.has_prepaid)">
                    <input type="checkbox" v-model="safe_deal.has_prepaid">
                    <span class="checkbox__check"></span>
                    <span class="checkbox__title">
                        <span><?= Yii::t('deal', 'Confirm if your transaction requires prepayment and postpay') ?></span>
                    </span>
                </label>

                <div class="prepayment-grey" v-if="getHasPrepaidValue(safe_deal.has_prepaid) == <?= SafeDeal::HAS_PREPAID_YES ?>">
                    <h4 class="deal-step-title"><?= Yii::t('deal', 'Enter prepayment amount') ?></h4>
                    <div class="input-group">
                        <div class="input-group__col">
                            <input v-validate="vv.model_safe_deal.amount_prepaid" name='SafeDeal[amount_prepaid]' class="form-control" type="text" v-model="safe_deal.amount_prepaid">
                            <span v-show="errors.has('SafeDeal[amount_prepaid]')" class="validation-error-message" v-cloak>
                                {{ errors.first('SafeDeal[amount_prepaid]') }}
                            </span>
                        </div>
                        <div class="input-group__col">
                            <nice-select :options="currency_list" name="SafeDeal[amount_prepaid_currency_code]`" class="select" v-model="safe_deal.amount_prepaid_currency_code">
                                <option v-for="item in currency_list" :value="item.char_code" >
                                    {{ item.name }}
                                </option>
                            </nice-select>
                        </div>
                    </div>
                </div>


            </div>

            <div class="deal-step deal-party">
                <h4 class="deal-step-title"><?= Yii::t('deal', 'Enter the ID (number) of the second party to the transaction') ?></h4>
                <span class="link open-modal id-modal-trigger" v-on:click="showIdModal"><?= Yii::t('deal', 'How to find ID (number)') ?></span>
                <input v-validate="vv.model_safe_deal_user.user_id" name='SafeDeal[safeDealUsers][0][user_id]' class="form-control" type="text" v-model="safe_deal.user_id">
                <span v-show="errors.has('SafeDeal[safeDealUsers][0][user_id]')" class="validation-error-message" v-cloak>
                    {{ errors.first('SafeDeal[safeDealUsers][0][user_id]') }}
                </span>
            </div>

            <div class="deal-step conditions" v-if="getHasPrepaidValue(safe_deal.has_prepaid) == <?= SafeDeal::HAS_PREPAID_YES ?>">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('condition_prepaid') ?></h4>
                <p class="deal-step-description"><?= Yii::t('deal', 'Describe the conditions for sending an advance payment') ?></p>
                <div class="description-block">
                    <textarea v-validate="vv.model_safe_deal.condition_prepaid" name='SafeDeal[condition_prepaid]' cols="30" rows="10" v-model="safe_deal.condition_prepaid"></textarea>

                    <div class="description-example">
                        <h4 class="deal-step-title"><?= Yii::t('deal', 'For example') ?>:</h4>
                        <p class="descriptiom-text">
                            <?= Yii::t('deal', 'Prepayment must be sent after the first package of documents has been submitted, in which there will be an agreement with the employer, registration, confirmation from the embassy.') ?>
                            <br>
                            <?= Yii::t('deal', 'A package of documents will be provided in 20-40 days, tentatively from 1.01.2020 to 02.10.2020. (and in the transaction (in messages), both users will see a button “Send an advance payment” and, accordingly, “Receive an advance payment” and payment will take place after clicking the “Send an advance payment” button') ?>
                        </p>
                    </div>
                </div>
                <span v-show="errors.has('SafeDeal[condition_prepaid]')" class="validation-error-message" v-cloak>
                    {{ errors.first('Resume[condition_prepaid]') }}
                </span>
            </div>

            <div class="deal-step conditions">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('condition_deal') ?></h4>
                <p class="deal-step-description"><?= Yii::t('deal', 'Describe in detail the terms of the transaction') ?></p>
                <div class="description-block">
                    <textarea v-validate="vv.model_safe_deal.condition_deal" name='SafeDeal[condition_deal]' cols="30" rows="10" v-model="safe_deal.condition_deal"></textarea>
                    <div class="description-example">
                        <h4 class="deal-step-title"><?= Yii::t('deal', 'For example') ?>:</h4>
                        <p class="descriptiom-text align-left bordered">
                            <?= Yii::t('deal', 'Payment for documents and vacancy') ?> <br>
                            <?= Yii::t('deal', 'Paperwork for a working visa to the Czech Republic in 60 days +-15 days') ?>
                            <?= Yii::t('deal', 'Job placement in the Czech Republic with conditions from $ 5 per hour.') ?><br>
                            <?= Yii::t('deal', 'The cost of housing up to $ 100 per month.') ?></p>
                        <p class="descriptiom-text align-left">
                            <?= Yii::t('deal', 'Payment of commission for the provision of the candidate.') ?>
                            <?= Yii::t('deal', 'We will pay out after 10 days worked by the candidate.') ?><br>
                            <?= Yii::t('deal', 'The candidate is required to prove the ability to cook by welding at the facility.') ?><br>
                            <?= Yii::t('deal', 'Tentative start date of the candidate 1.01.2020') ?></p>
                    </div>
                </div>
                <span v-show="errors.has('SafeDeal[condition_deal]')" class="validation-error-message" v-cloak>
                    {{ errors.first('Resume[condition_deal]') }}
                </span>
            </div>

            <div class="deal-step deal-term">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('execution_period') ?></h4>
                <div class="input-group">
                    <div class="input-group__col">
                        <input v-validate="vv.model_safe_deal.execution_period" name='SafeDeal[execution_period]' class="form-control" type="text" v-model="safe_deal.execution_period">
                        <span v-show="errors.has('SafeDeal[execution_period]')" class="validation-error-message" v-cloak>
                            {{ errors.first('SafeDeal[execution_period]') }}
                        </span>
                    </div>
                    <div class="input-group__col">
                        <nice-select :options="execution_range_list" name="SafeDeal[execution_range]`" class="select" v-model="safe_deal.execution_range">
                            <option v-for="item in execution_range_list" :value="item.id" v-cloak>
                                {{ item.name }}
                            </option>
                        </nice-select>
                    </div>
                </div>
            </div>

            <div class="deal-step delay">
                <h4 class="deal-step-title"><?= $model->getAttributeLabel('possible_delay_days') ?></h4>
                <div class="select-buttons">
                    <label class="delay">
                        <input type="radio" name="SafeDeal[possible_delay_days]" class="delay-radio" value="<?= SafeDeal::POSSIBLE_DELAY_DAYS_1_10 ?>" v-model="safe_deal.possible_delay_days">
                        <span class="btn">1 - 10 <?= Yii::t('deal', 'days') ?></span>
                    </label>
                    <label class="delay">
                        <input type="radio" name="SafeDeal[possible_delay_days]" class="delay-radio" value="<?= SafeDeal::POSSIBLE_DELAY_DAYS_10_30 ?>" v-model="safe_deal.possible_delay_days">
                        <span class="btn">10 - 30 <?= Yii::t('deal', 'days') ?></span>
                    </label>
                    <label class="delay">
                        <input type="radio" name="SafeDeal[possible_delay_days]" class="delay-radio" value="<?= SafeDeal::POSSIBLE_DELAY_DAYS_30_90 ?>" v-model="safe_deal.possible_delay_days">
                        <span class="btn">30 - 90 <?= Yii::t('deal', 'days') ?></span>
                    </label>
                </div>
            </div>

            <div class="deal-step commission">
                <h4 class="deal-step-title"><?= Yii::t('deal', 'Comission') ?></h4>
                <div class="commission-payer">
                    <input type="radio" name="SafeDeal[comission]" id="commission-1" class="commission-payer-radio" value="<?= SafeDeal::COMISSION_TYPE_1 ?>" v-model="safe_deal.comission">
                    <label for="commission-1"><?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                        <span class="payer"><?= Yii::t('deal', 'I pay the commission') ?></span>
                    </label>
                </div>
                <div class="commission-payer">
                    <input type="radio" name="SafeDeal[comission]" id="commission-2" class="commission-payer-radio" value="<?= SafeDeal::COMISSION_TYPE_2 ?>" v-model="safe_deal.comission">
                    <label for="commission-2"><?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                        <span class="payer"><?= Yii::t('deal', 'The commission is divided in half') ?></span>
                    </label>
                </div>
                <div class="commission-payer">
                    <input type="radio" name="SafeDeal[comission]" id="commission-3" class="commission-payer-radio" value="<?= SafeDeal::COMISSION_TYPE_3 ?>" v-model="safe_deal.comission">
                    <label for="commission-3"><?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                        <span class="payer"><?= Yii::t('deal', 'The commission is paid by the second party to the transaction.') ?></span>
                    </label>
                </div>
            </div>

            <div class="deal-step payment">
                <button class="btn open-modal" type="submit" data-modal="#messengers-modal"><?= Yii::t('deal', 'Make payment') ?></button>
                <div class="payments">
                    <div class="payments-item"><img src="/img/safe-deal/payments-item-1.png" alt=""></div>
                    <div class="payments-item"><img src="/img/safe-deal/payments-item-2.png" alt=""></div>
                    <div class="payments-item"><img src="/img/safe-deal/payments-item-3.png" alt=""></div>
                    <div class="payments-item"><img src="/img/safe-deal/payments-item-4.png" alt=""></div>
                    <div class="payments-item"><img src="/img/safe-deal/payments-item-5.png" alt=""></div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="id-modal modal" id="id-modal">
        <div class="content">
            <h3 class="title"><?= Yii::t('deal', 'How to find ID (number)') ?></h3>
            <div class="img-block">
                <div class="id-modal-image">
                    <img src="/img/safe-deal/id-modal-1.png" alt="">
                </div>
                <div class="id-modal-image">
                    <img src="/img/safe-deal/id-modal-2.png" alt="">
                </div>
            </div>
            <div class="input-block">
                <h4>Или введите название вакансии/фамилию кандидата</h4>
                <input type="text" class="form-control" name="DealPartyInfo" value="" placeholder="">
            </div>
        </div>
    </div>

<div class="messengers-modal modal" id="messengers-modal">
    <div class="content">
        <div class="title-sec">
            Подпишитесь на новые вакансии
        </div>
        <div class="messengers">
            <div class="messenger-item"><a href="#"><img src="/img/messengers-modal/telegramm-big.png" alt=""></a></div>
            <div class="messenger-item"><a href="#"><img src="/img/messengers-modal/whatsapp-big.png" alt=""></a></div>
            <div class="messenger-item"><a href="#"><img src="/img/messengers-modal/viber-big.png" alt=""></a></div>
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
  el: '#appCreateSafeDeal',
  data: {
    currency_list: [
        <?php foreach( $currency_list as $currency): ?>
        {
            char_code: '<?= $currency['char_code'] ?>',
            name: '<?= Yii::t('curr', $currency['name']) ?>',
        },
        <?php endforeach; ?>
    ],
    execution_range_list: [
        <?php foreach( $execution_range_list as $key_id => $execution_range): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $execution_range ?>',
        },
        <?php endforeach; ?>
    ],
    //-------
    safe_deal: {
        status: '',
        deal_type: '',
        has_prepaid: '<?= SafeDeal::HAS_PREPAID_YES ?>',
        title: '',
        amount_total: '',
        amount_total_src: '',
        amount_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>',
        amount_prepaid: '',
        amount_prepaid_currency_code: '<?= Yii::$app->params['defaultCurrencyCharCode'] ?>',
        condition_prepaid: '',
        condition_deal: '',
        execution_period: '14',
        execution_range: '<?= SafeDeal::EXECUTION_RANGE_DAYS ?>',
        possible_delay_days: '<?= SafeDeal::POSSIBLE_DELAY_DAYS_1_10 ?>',
        comission: '<?= SafeDeal::COMISSION_TYPE_1 ?>',
        user_id: '<?= Yii::$app->request->get('id', '') ?>'
    },
    response_errors: void 0,
    submit_clicked: false,
    vv: {
        model_safe_deal: {
            // status: '<?= VeeValidateHelper::getVValidateString($this, $model, 'status') ?>',
            deal_type: '<?= VeeValidateHelper::getVValidateString($this, $model, 'deal_type') ?>',
            has_prepaid: '<?= VeeValidateHelper::getVValidateString($this, $model, 'has_prepaid') ?>',
            title: '<?= VeeValidateHelper::getVValidateString($this, $model, 'title') ?>',
            amount_total: '<?= VeeValidateHelper::getVValidateString($this, $model, 'amount_total') ?>',
            amount_total_src: '<?= VeeValidateHelper::getVValidateString($this, $model, 'amount_total_src') ?>',
            amount_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'amount_currency_code') ?>',
            amount_prepaid: '<?= VeeValidateHelper::getVValidateString($this, $model, 'amount_prepaid') ?>',
            amount_prepaid_currency_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'amount_prepaid_currency_code') ?>',
            condition_prepaid: '<?= VeeValidateHelper::getVValidateString($this, $model, 'condition_prepaid') ?>',
            condition_deal: '<?= VeeValidateHelper::getVValidateString($this, $model, 'condition_deal') ?>',
            execution_period: '<?= VeeValidateHelper::getVValidateString($this, $model, 'execution_period') ?>',
            execution_range: '<?= VeeValidateHelper::getVValidateString($this, $model, 'execution_range') ?>',
            possible_delay_days: '<?= VeeValidateHelper::getVValidateString($this, $model, 'possible_delay_days') ?>',
            comission: '<?= VeeValidateHelper::getVValidateString($this, $model, 'comission') ?>',
        },
        model_safe_deal_user: {
            user_id: '<?= VeeValidateHelper::getVValidateString($this, $modelDealUser, 'user_id') ?>',
        }
    }
  },
  mounted: function() {
    $(".modal").each( function(){
        $(this).wrap('<div class="overlay"></div>')
    });
  },
  methods: {
    onSubmit () {
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
            } else {
                // this.$refs.form.submit();
                let post_data = {
                    // 'SafeDeal[deal_type]': this.$data.safe_deal.deal_type,
                    'SafeDeal[has_prepaid]': this.getHasPrepaidValue(this.$data.safe_deal.has_prepaid),
                    'SafeDeal[title]': this.$data.safe_deal.title,
                    'SafeDeal[amount_total]': this.$data.safe_deal.amount_total,
                    // 'SafeDeal[amount_total_src]': this.$data.safe_deal.amount_total_src,
                    'SafeDeal[amount_currency_code]': this.$data.safe_deal.amount_currency_code,
                    'SafeDeal[amount_prepaid]': this.$data.safe_deal.amount_prepaid,
                    'SafeDeal[amount_prepaid_currency_code]': this.$data.safe_deal.amount_prepaid_currency_code,
                    'SafeDeal[condition_prepaid]': this.$data.safe_deal.condition_prepaid,
                    'SafeDeal[condition_deal]': this.$data.safe_deal.condition_deal,
                    'SafeDeal[execution_period]': this.$data.safe_deal.execution_period,
                    'SafeDeal[execution_range]': this.$data.safe_deal.execution_range,
                    'SafeDeal[possible_delay_days]': this.$data.safe_deal.possible_delay_days,
                    'SafeDeal[comission]': this.$data.safe_deal.comission,
                    'SafeDeal[safeDealUsers][0][user_id]': this.$data.safe_deal.user_id,
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
            }
        });
    },
    showIdModal: function() {
        let modal = $('#id-modal');

        $(modal).parents(".overlay").addClass("open");
        setTimeout( function(){
            $(modal).addClass("open");
        }, 350);
        $(document.body).addClass('modal-active')

        $(document).on('click', function(e){
            let target = $(e.target);

            if ($(target).hasClass("overlay")){
                $(target).find(".modal").each( function(){
                    $(this).removeClass("open");
                    $(document.body).removeClass('modal-active')
                });
                setTimeout( function(){
                    $(target).removeClass("open");
                }, 350);
            }


        });
    },
    // --
    getHasPrepaidValue: function(checked) {
        if(checked) {
            return <?= SafeDeal::HAS_PREPAID_YES ?>;
        } else {
            return <?= SafeDeal::HAS_PREPAID_NO ?>;
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
    }
  }
})
</script>
<?php $this->endJs(); ?>