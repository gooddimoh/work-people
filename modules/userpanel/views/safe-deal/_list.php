<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */
?>

<div class="balans__row">
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?= Yii::t('deal', 'Deal number') ?>:
            </div>
            <?= $model->id ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                С:
            </div>
            <?= Yii::$app->formatter->format($model->created_at, 'date') ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                По:
            </div>
            <?= Yii::$app->formatter->format($model->updated_at, 'date') ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?=Yii::t('deal', 'Action type') ?>:
            </div>
            <?php if($model->isCreator()): ?>
                <?= Yii::t('deal', 'Sale') ?>
            <?php else: ?>
                <?= Yii::t('deal', 'Buy') ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?= Yii::t('deal', 'Date start') ?>:
            </div>
            <?= Yii::$app->formatter->format($model->started_at, 'datetime') ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?= Yii::t('deal', 'Date end') ?>:
            </div>
            <?= Yii::$app->formatter->format($model->finished_at, 'datetime') ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?= Yii::t('deal', 'Amount') ?>:
            </div>
            <?= $model->amount_total ?>&nbsp;<?= Html::encode($model->amount_currency_code) ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <div class="balans__col-title">
                <?= Yii::t('deal', 'User') ?>:
            </div>
            <?= $model->assignedSafeDealUser->user_id ?>
        </div>
    </div>
    <div class="balans__col">
        <div class="balans__col-inner">
            <a href="<?= Url::to(['view', 'id' => $model->id]) ?>" class="btn btn--trans-yellow">
                <?= Yii::t('deal', 'Go to Deal') ?>
            </a>
        </div>
    </div>
</div>