<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view edit-info">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('<i class="fa fa-pencil"></i>&nbsp;'. Yii::t('main', 'Edit'), ['edit'], ['class' => 'btn']) ?>
        </p>

        <div class="table">
            <div class="table__tr">
                <div class="table__td table__td--first">
                    <?= $model->getAttributeLabel('login') ?>
                </div>
                <div class="table__td">
                    <div class="edit-info__input">
                        <?= Html::encode($model->login) ?>
                    </div>
                </div>
            </div>
            <div class="table__tr">
                <div class="table__td table__td--first">
                    <?= $model->getAttributeLabel('username') ?>
                </div>
                <div class="table__td">
                    <div class="edit-info__input">
                        <?= Html::encode($model->username) ?>
                    </div>
                </div>
            </div>
            <div class="table__tr">
                <div class="table__td table__td--first">
                    <?= $model->getAttributeLabel('email') ?>
                </div>
                <div class="table__td">
                    <div class="edit-info__input">
                        <?= Html::encode($model->email) ?>
                    </div>
                </div>
            </div>
            <div class="table__tr">
                <div class="table__td table__td--first">
                    <?= $model->getAttributeLabel('phone') ?>
                </div>
                <div class="table__td">
                    <div class="edit-info__input">
                        <?= Html::encode($model->phone) ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
