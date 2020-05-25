<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AutoMail */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('automail', 'Auto Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="registration registration--2 auto-mailing-edit">
    <div class="container">
        <div class="registration__number-step">
            <b>Редактирование рассылки вакансий</b>
        </div>
        <div class="registration__main">
            <div class="registration__inner" style="padding-top: 30px;">
                <div class="registration__form">
                    <div class="registration__input-bl">
                        <div class="registration__input-title">Запрос</div>
                        <div class="input-delete-value j-delete-wrap">
                            <input type="text" class="j-delete-value">
                        </div>
                    </div>
                    <div class="registration__input-bl">
                        <div class="registration__input-title">Страна</div>
                        <div class="input-delete-value j-delete-wrap">
                            <input type="text" class="j-delete-value">
                        </div>
                    </div>
                    <div class="registration__input-bl">
                        <div class="registration__input-title">Город</div>
                        <input type="text">
                    </div>
                    <div class="registration__input-bl">
                        <div class="registration__input-title">Категория</div>
                        <select name="" id="" class="j-select select select--double" style="display: none;">
                            <option value="1">Все категории</option>
                            <option value="1">Автомобильная промышленность</option>
                            <option value="1">Все фабрики</option>
                            <option value="1">Склады и магазины</option>
                            <option value="1">Мясокомбинаты</option>
                            <option value="1">Строительство</option>
                            <option value="1">Водители</option>
                            <option value="1">Обслуживающий персонал</option>
                            <option value="1">Сельское хозяйство</option>
                            <option value="">Сварочные работы</option>
                            <option value="">Электричество</option>
                            <option value="">Швейное производство</option>
                            <option value="">Металлургическая промышленность</option>
                            <option value="">Деревообрабатывающая промышленность</option>
                        </select>
                    </div>
                    <div class="registration__input-bl">
                        <label class="checkbox">
                            <input type="checkbox">
                            <span class="checkbox__check"></span>
                            <span class="checkbox__title">
                                Присылать новые вакансии в Telegram или Viber
                            </span>
                        </label>
                    </div>
                    <div class="registration__submit">
                        <div>
                            <button type="submit" class="btn btn" onclick="document.location.href='auto-mailing.php';">
                                Сохранить
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn--transparent" onclick="document.location.href='auto-mailing.php';">
                                Удалить
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn--transparent" onclick="document.location.href='auto-mailing.php';">
                                Отменить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
