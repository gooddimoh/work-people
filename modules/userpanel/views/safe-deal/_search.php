<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SafeDeal;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDealSearch */
/* @var $form yii\widgets\ActiveForm */

$currency_list = SafeDeal::getCurrencyList();
?>

<div class="balans-filter">
    <div class="container">
        <!-- <div class="balans-filter__row"> -->
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'class' => 'balans-filter__row',
            ]
        ]); ?>
            <div class="balans-filter__col">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'Deal number') ?>:
                    </div>
                    <?= $form->field($model, 'id')->label(false) ?>
                </div>
            </div>
            <div class="balans-filter__col balans-filter__col--multiple1">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        С:
                    </div>
                    <?= $form->field($model, 'created_at')->label(false)->textInput(['placeholder' => '__.__.____', 'class' => 'j-date']) ?>
                </div>
            </div>
            <div class="balans-filter__col balans-filter__col--multiple2">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        По:
                    </div>
                    <?= $form->field($model, 'updated_at')->label(false)->textInput(['placeholder' => '__.__.____', 'class' => 'j-date']) ?>
                </div>
            </div>
            <div class="balans-filter__col">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'Action type') ?>:
                    </div>
                    <select name="SafeDealSearch[creator_id]" id="" class="select j-select">
                        <option value=""><?= Yii::t('deal', 'All') ?></option>
                        <option value="<?= Yii::$app->user->id ?>"><?= Yii::t('deal', 'Sales') ?></option>
                        <option value="!<?= Yii::$app->user->id ?>"><?= Yii::t('deal', 'Purchases') ?></option>
                    </select>
                </div>
            </div>
            <div class="balans-filter__col balans-filter__col--multiple1">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'Date start') ?>:
                    </div>
                    <?= $form->field($model, 'started_at')->label(false)->textInput(['placeholder' => '__.__.____ __:__', 'class' => 'j-date-time']) ?>
                </div>
            </div>
            <div class="balans-filter__col balans-filter__col--multiple2">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'Date end') ?>:
                    </div>
                    <?= $form->field($model, 'finished_at')->label(false)->textInput(['placeholder' => '__.__.____ __:__', 'class' => 'j-date-time']) ?>
                </div>
            </div>
            <div class="balans-filter__col">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'Amount') ?>:
                    </div>
                    <div class="input-group">
                        <div class="input-group__col">
                            <?= $form->field($model, 'amount_total')->label(false) ?>
                        </div>
                        <div class="input-group__col" style="width: 65px;flex-shrink: 0;">
                            <!-- need drop down filter -->
                            <select name="SafeDealSearch[amount_currency_code]" id="" class="j-select select" value="<?= Yii::$app->params['defaultCurrencyCharCode'] ?>">
                                <?php foreach($currency_list as $currency): ?>
                                    <option value="<?= $currency['char_code'] ?>" <?= Yii::$app->params['defaultCurrencyCharCode'] == $currency['char_code'] ? 'selected' : '' ?>><?= $currency['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- <select name="" id="" class="j-select select">
                                <option value="">ГРН</option>
                                <option value="">EUR</option>
                                <option value="">USD</option>
                                <option value="">DEM</option>
                                <option value="">CZK</option>
                            </select> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="balans-filter__col">
                <div class="balans-filter__item">
                    <div class="balans-filter__title">
                        <?= Yii::t('deal', 'User') ?>:
                    </div>
                    <select name="" id="" class="select j-select">
                        <option data-display="Все пользователи" value="">Все</option>
                        <option value="">Агенты</option>
                        <option value="">Работадатели</option>
                        <option value="">Соискатели</option>
                    </select>
                </div>
            </div>
            <div class="balans-filter__col">
                <div class="balans-filter__item">
                    <!-- <a href="#" class="btn">Применить</a> -->
                    <?= Html::submitButton(Yii::t('main', 'Search'), ['class' => 'btn']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
        <!-- </div> -->
    </div>
</div>
