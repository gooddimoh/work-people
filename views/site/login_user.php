<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use rmrevin\yii\ulogin\ULogin;
use yii\web\View;

$this->title = Yii::t('main', 'Authorization');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('https://ulogin.ru/js/ulogin.js', ['position' => View::POS_END]); // fix
?>

<div class="registration registration--2">
    <div class="container">
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="registration__top">
                        <a href="<?= Url::home(); ?>">
                            <svg class="icon"> 
                                <use xlink:href="/img/icons-svg/prev.svg#prev" x="0" y="0" />
                            </svg>
                            <?= Yii::t('main', 'Back') ?>
                        </a>
                        <a href="<?= Url::to(['/registration']); ?>">
                            <svg class="icon"> 
                                <use xlink:href="/img/icons-svg/user.svg#user" x="0" y="0" />
                            </svg>
                            <?= Yii::t('main', 'Registration') ?>
                        </a>
                    </div>
                    <div class="registration__title">
                        <?= Yii::t('main', 'Authorization') ?>
                    </div>
                    <div class="registration__form">
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

                    <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => ['class' => 'registration__form']
                    ]); ?>
                        <?= $form->field($model, 'username')->label(false)->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('username')]) ?>

                        <?= $form->field($model, 'password')->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

                        <?php
                            $tempalte =<<<HTML
                            <label class="checkbox">
                                {input}
                                <span class="checkbox__check">
                                </span>
                                <span class="checkbox__title">
                                    {label}
                                </span>
                                {error}
                            </label>
HTML;
                            echo $form->field($model, 'rememberMe')->checkbox([
                                'template' => $tempalte,
                            ])
                        ?>

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

                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-11">
                                <?= Html::submitButton(Yii::t('main', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            </div>
                        </div>

                    <?php ActiveForm::end(); ?>

                </div>
                <div class="registration__under">
                    Еще не зарегистрированы? 
                    <a href="<?= Url::to(['/registration']); ?>">
                        <?= Yii::t('main', 'Registration') ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
