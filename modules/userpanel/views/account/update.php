<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('main', 'Edit account') . ' ' . $model->login;
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'User Panel'), 'url' => ['/userpanel']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('main', 'Account'), 'url' => ['/userpanel/account/view']];
// $this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('main', 'Edit');
?>

<div class="registration registration--2" id="appCreateProfile">
    <div class="container">
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="registration__title">
                        <?= Html::encode($this->title) ?>
                    </div>

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
