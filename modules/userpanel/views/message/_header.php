<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
?>
<div class="container">
    <div class="title-sec">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="message-all__desc">
        <?= Yii::t('user', 'Here you will find all the correspondence and reviews written by you and about you.') ?>
    </div>
</div>
<div class="cat-nav cat-nav--message">
    <div class="container">
        <ul class="cat-nav__cat">
            <li>
                <a href="<?= Url::to(['/userpanel/notification']) ?>" <?= Yii::$app->controller->id == 'notification' ? 'class="active"' : '' ?>><?= Yii::t('message', 'Alerts') ?></a>
            </li>
            <!-- <li><a href="#">Полученные</a></li> -->
            <li>
                <a href="<?= Url::to(['/userpanel/message']) ?>" <?= Yii::$app->controller->id == 'message' ? 'class="active"' : '' ?>><?= Yii::t('message', 'Messages')?></a>
            </li>
            <!-- <li>
                <a href=""><?=Yii::t('message', 'Archive') ?></a>
            </li> -->
        </ul>
        <form action="" class="vacancy-review__search">
            <input type="text" placeholder="<?=Yii::t('message', 'Search by messages...')?>">
            <input type="submit" value="">
        </form>
    </div>
</div>
