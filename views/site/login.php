<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginPhoneForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use rmrevin\yii\ulogin\ULogin;
use yii\web\View;

$this->title = Yii::t('main', 'Authorization');
$this->params['breadcrumbs'][] = $this->title;

$session = Yii::$app->session;
$user_type = $session->get('interface_for');
?>

<div class="registration registration--2">
    <div class="container">
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="cat-nav login-tabs">
                        <ul class="cat-nav__cat">
                            <li>
                                <a href="<?= Url::to(['/site/for-candidate']) ?>" <?= $user_type != 'employer' ? 'class="active"' : ''?>>
                                    <img src="/img/vacancy/person.png" alt="" style="">
                                    <?= Yii::t('main', 'For candidates') ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/site/for-employer']) ?>" <?= $user_type == 'employer' ? 'class="active"' : ''?>>
                                    <img src="/img/vacancy/list1.png" alt="">
                                    <?= Yii::t('main', 'For employers') ?>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="registration__title">
                        <?= Yii::t('main', 'Authorization') ?>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-phone-form',
                        'action' => ['/site/login'],
                        'options' => ['class' => 'registration__form']
                    ]); ?>
                        <div id="step_enter_phone" <?= empty($model->phone) ? '' : 'style="display: none;"' ?>>
                            <div class="registration__input-bl" style="margin-bottom: 40px;">
                                <div class="registration__input-title" style="margin-top: 35px;">
                                    <?= Yii::t('main', 'Enter your phone number') ?>, <br>
                                    <?= Yii::t('main', 'SMS with a code for authorization will come to it') ?>
                                </div>
                                <?= $form->field($model, 'phone')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('phone')]) ?>
                                <label class="checkbox">
                                    <input type="checkbox" checked="" disabled>
                                    <span class="checkbox__check"></span>
                                    <span class="checkbox__title">
                                        <div>
                                            <?= Yii::t('site', 'I accept') ?> <a href="<?= Url::to(['/site/terms-of-use']); ?>" class="link"><?= Yii::t('site', 'terms and conditions of service') ?></a>
                                            <?= Yii::t('site', 'and') ?>  <a href="#" class="link">политику конфиденциальности</a>
                                        </div>
                                    </span>
                                </label>
                            </div>
                            <!-- <button id="request_sms_code_btn" class="btn" style="width: 100%;"><?= Yii::t('main', 'Continue') ?></button> -->
                            <?= Html::submitButton(Yii::t('main', 'Continue'), ['class' => 'btn btn-primary', 'name' => 'login-button', 'id'=>'request_sms_code_btn']) ?>
                            <div class="sms-code-loading" id="send_sms_loading" style="display:none;"><?= Yii::t('vacancy', 'Loading...') ?></div>
                        </div>
                        <div id="step_validate_code" <?= empty($model->phone) ? 'style="display: none;"' : '' ?>>
                            <div class="registration__input-bl" style="margin-bottom: 40px;">
                                <div class="registration__input-title" style="margin-top: 35px;">
                                    <?= Yii::t('main', 'A 6-digit password has been sent to your phone.') ?><br> 
                                    <div class="registration__input-desc"><?= Yii::t('main', 'Enter it in the box below.') ?></div>
                                </div>
                                <?= $form->field($model, 'sms_code')->label(false)->textInput(['placeholder' => $model->getAttributeLabel('sms_code')]) ?>

                                <div id="send_sms_again_message" style="display:none;">
                                    <?= Yii::t('main', 'You can request SMS code again after') ?> <span id="seconds_until_again_request"><?= Yii::$app->params['smsSendDelay'] ?></span> <?= Yii::t('main', 'seconds') ?>
                                </div>
                                
                                <a href="#" id="send_sms_again_btn" class="link"><?= Yii::t('main', 'Resend Password') ?></a>
                            </div>
                            <?= Html::submitButton(Yii::t('main', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->beginJs(); ?>
<script>
    $(document).ready(function() {
        $('#loginphoneform-phone').inputmask('+9{*}');
        $('#loginphoneform-sms_code').inputmask('999999');

		// add send sms code
        $( "#request_sms_code_btn, #send_sms_again_btn" ).click(function(e) {
            if($('#step_enter_phone').css('display') == 'none') { // send sms code on press Enter
                $('#login-phone-form').submit(); // trigger validation error message empty phone
                return;
            }

            let phone = $("#loginphoneform-phone").val();
            let request_url = "<?= Url::to(['/site/send-sms-code']) ?>";
            let sms_send_delay = <?= Yii::$app->params['smsSendDelay'] ?>;
            
            if(!phone) {
                // $('#login-phone-form').submit(); // trigger validation error message empty phone
                return;
            }
            
            e.preventDefault();

			let loader_wait = $('#send_sms_loading');
			loader_wait.show();
			$.post( request_url, {
                'LoginPhoneForm[phone]': phone
            }).always(function ( response ) {
                loader_wait.hide();
                if(response.status == 500) {
                    alert(response.responseText);
                    return;
                }

                if(!response.success) {
                    if (!response.errors) {
                        alert(response);
                        return;
                    }

                    $.each(response.errors, function(key, value) {
                        $('.field-loginphoneform-' + key + '').addClass('has-error');
                        $('.field-loginphoneform-' + key + ' .help-block-error').append(value[0]);
                    });
                } else {
                    $('#step_enter_phone').hide();
                    $('#step_validate_code').show();

                    // hide sms send btn, show repeate request after message
                    let me = this;
                    $('#send_sms_again_message').show();
                    $('#send_sms_again_btn').hide();
                    let timer_until_el = $('#seconds_until_again_request');
                    let timeout = 0;
                    timer_until_el.html(sms_send_delay);
                    let stop_timer = setInterval(() => {
                        timeout++;
                        timer_until_el.html(+sms_send_delay - timeout);
                        if(timeout > +sms_send_delay) {
                            $(me).show();
                            $('#send_sms_again_message').hide();
                            $('#send_sms_again_btn').show();
                            clearInterval(stop_timer);
                        }
                    }, 1000);
                }
                
                // $form.reset().trigger('reset');
            });
        });
    });
</script>
<?php $this->endJs(); ?>
