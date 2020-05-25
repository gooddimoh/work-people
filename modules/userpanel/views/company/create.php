<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('company', 'Information about company');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration registration--2">
    <div class="container">
        <div class="row">
            <div class="registration__main col">
                <div class="registration__inner">
                    <div class="registration__title">
                        <?= Html::encode($this->title) ?>
                        <div>
                            <?= Yii::t('company', 'Fill out the basic information about the company') ?>
                        </div>
                    </div>

                    <?= $this->render('_form_create', [
                        'model' => $model,
                        'modelUserPhone' => $modelUserPhone,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
