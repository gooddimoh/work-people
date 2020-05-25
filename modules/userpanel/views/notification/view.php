<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Notification */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('message', 'Alerts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="message-all message">
    <div class="message__top">
        <div class="container">
            <div class="message-all__row message__top-row">
                <div class="message-all__col">
                    <a href="<?= Url::to(['index']) ?>" class="link"><?=Yii::t('message', 'Back')?></a>
                </div>
                <div class="message-all__col">
                    <div class="message-all__row message__top-row">
                        <!-- <label class="message-all__star message__flex">
                            <input type="checkbox">
                            <span class="message-all__star-title"></span>
                            <span>Отметить сообщение</span>
                        </label>
                        <a href="#" class="message__delete message__flex">
                            <span class="message__delete-icon"></span>
                            <span>Переместить в архив</span>
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="message-box">
        <div class="container">
            <div class="hr"></div>
            <div class="message-box__inner scrollbar-inner">
                <div class="message-box__item">
                    <?= $model->text ?>
                </div>
            </div>
        </div>
    </div>
</div>