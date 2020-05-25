<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('main', 'Registration');
// $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration">
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
                        <a href="<?= Url::to(['/site/login']); ?>">
                            <svg class="icon"> 
                                <use xlink:href="/img/icons-svg/user.svg#user" x="0" y="0" />
                            </svg>
                            <?= Yii::t('main', 'Login') ?>
                        </a>
                    </div>
                    <div class="registration__title">
                        Зарегистрируйтесь
                        <div>
                            Для работадателей
                        </div>
                    </div>
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
                <div class="registration__under">
                    Ищете работу?
                    <a href="#">
                        Регистрация для кандидатов
                    </a>
                </div>
            </div>
            <div class="registration-sidebar col">
                <div class="registration-sidebar__inner">
                    <div class="registration-sidebar__title">
                        Что дает регистрация?
                    </div>
                    <ul class="registration-sidebar__list">
                        <li>
                            <span>1</span>
                            Сохранять вакансии и просматривать историю своих откликов
                        </li>
                        <li>
                            <span>2</span>
                            Управлять рассылками вакансий
                        </li>
                        <li>
                            <span>3</span>
                            Видеть кто просматривал вашу анкету
                        </li>
                    </ul>
                    <div class="registration-sidebar__center">
                        <b>Зарегистрируйтесь - это просто и бесплатно!</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>