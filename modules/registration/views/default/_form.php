<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use rmrevin\yii\ulogin\ULogin;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile('https://ulogin.ru/js/ulogin.js', ['position' => View::POS_END]); // fix
?>
<div class="registration__form">
    <? /*
    <a href="#" class="registration__soc registration__soc--google">
        <svg class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            
            <path class="st0" d="M113.5,309.4l-17.8,66.5l-65.1,1.4C11,341.2,0,299.9,0,256c0-42.5,10.3-82.5,28.6-117.7h0l58,10.6l25.4,57.6
                c-5.3,15.5-8.2,32.1-8.2,49.5C103.8,274.8,107.2,292.8,113.5,309.4z"/>
            <path class="st1" d="M507.5,208.2c2.9,15.5,4.5,31.5,4.5,47.8c0,18.3-1.9,36.2-5.6,53.5c-12.5,58.7-45,109.9-90.1,146.2l0,0l-73-3.7
                l-10.3-64.5c29.9-17.6,53.3-45,65.6-77.9H261.6V208.2h138.9H507.5L507.5,208.2z"/>
            <path class="st2" d="M416.3,455.6L416.3,455.6C372.4,490.9,316.7,512,256,512c-97.5,0-182.3-54.5-225.5-134.7l83-67.9
                c21.6,57.7,77.3,98.8,142.5,98.8c28,0,54.3-7.6,76.9-20.8L416.3,455.6z"/>
            <path class="st3" d="M419.4,58.9l-82.9,67.9c-23.3-14.6-50.9-23-80.5-23c-66.7,0-123.4,43-144,102.7l-83.4-68.3h0
                C71.2,56.1,157.1,0,256,0C318.1,0,375.1,22.1,419.4,58.9z"/>
        </svg>
        Регистрация через Google
    </a>
    <a href="#" class="registration__soc registration__soc--facebook">
        <svg class="icon"> 
            <use xlink:href="/img/icons-svg/facebook.svg#icon" x="0" y="0" />
        </svg>
        Регистрация через Facebook
    </a>
    */ ?>
    <?php
        echo ULogin::widget([
            // widget look'n'feel
            'display' => ULogin::D_PANEL,
        
            // required fields
            'fields' => [
                ULogin::F_FIRST_NAME,
                ULogin::F_LAST_NAME,
                ULogin::F_EMAIL,
                // ULogin::F_PHONE,
                // ULogin::F_CITY,
                // ULogin::F_COUNTRY,
                ULogin::F_PHOTO_BIG
            ],
        
            // optional fields
            'optional' => [ULogin::F_BDATE],
        
            // login providers
            'providers' => [ULogin::P_GOOGLE, ULogin::P_FACEBOOK, ULogin::P_VKONTAKTE, ULogin::P_TWITTER],
        
            // login providers that are shown when user clicks on additonal providers button
            'hidden' => [],
        
            // where to should ULogin redirect users after successful login
            'redirectUri' => ['site/ulogin'],
        
            // force use https in redirect uri
            // 'forceRedirectUrlScheme' => 'https',
        
            // optional params (can be ommited)
            // force widget language (autodetect by default)
            // 'language' => ULogin::L_RU,
        
            // providers sorting ('relevant' by default)
            'sortProviders' => ULogin::S_RELEVANT,
        
            // verify users' email (disabled by default)
            'verifyEmail' => '0',
        
            // mobile buttons style (enabled by default)
            'mobileButtons' => '0',
        ]);
    ?>
</div>
<div class="registration__or">
    <span>или</span>
</div>
<?php $form = ActiveForm::begin(['options' => ['class' => 'registration__form']]); ?>
    <!-- <input type="text" placeholder="Имя"> -->

    <!-- <input type="tel" placeholder="+38 (___) ___ - __ - __" class="j-phone"> -->
    <?= $form->field($model, 'login')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('login')]) ?>
    <?= $form->field($model, 'phone')->label(false)->textInput(['maxlength' => true, 'class' => 'j-phone', 'placeholder'=>'+_(___)_______']) ?>

    <label class="checkbox">
        <input type="checkbox">
        <span class="checkbox__check">
        </span>
        <span class="checkbox__title">
            На этом телефоне установлен <b>Viber</b><img src="/img/icons-svg/viber.svg" alt="viber">
        </span>
    </label>
    <label class="checkbox">
        <input type="checkbox">
        <span class="checkbox__check">
        </span>
        <span class="checkbox__title">
            На этом телефоне установлен <b>Whatsapp</b><img src="/img/icons-svg/whatsapp.svg" alt="whatsapp">
        </span>
    </label>
    <label class="checkbox">
        <input type="checkbox">
        <span class="checkbox__check">
        </span>
        <span class="checkbox__title">
            На этом телефоне установлен <b>Telegram</b><img src="/img/icons-svg/telegram.svg" alt="telegram">
        </span>
    </label>
    <!-- <input type="email" placeholder="{{ __( 'Эл. почта' ) }}"> -->
    <!-- <input type="password" placeholder="{{ __( 'Пароль' ) }}"> -->
    <!-- <input type="password" placeholder="{{ __( 'Повторите пароль' ) }}"> -->
    <?= $form->field($model, 'email')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('email')]) ?>
    <?= $form->field($model, 'password')->label(false)->passwordInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('password')]) ?>
    <?= $form->field($model, 'password_repeat')->label(false)->passwordInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('password_repeat')]) ?>
    <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::className()) ?>

    <div class="registration__desc">
        Нажимая кнопку «Зарегистрироваться», вы принимаете правила сайта и политику конфиденциальности.
    </div>
    <!-- <input type="submit" class="btn" value="Зарегистрироваться" onclick="document.location.href='register-candidate-resume.php';"> -->
    <?= Html::submitButton(Yii::t('main', 'To register'), ['class' => 'btn']) ?>

<?php ActiveForm::end(); ?>
