<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\VacancyRespond */

$this->title = Yii::t('vacancy', 'Offer for a job');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'Response History'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="registration registration--2 registration--success">
    <div class="container">
        <div class="registration__inner">
            <div class="registration__form">
                <div class="title-sec title-sec--success">
                    <?= Yii::t('vacancy', 'Congratulations!') ?>
                </div>
                <div class="registration__success-desc">
                    <?= Yii::t('vacancy', 'You have sent offer for a job') ?> "<a href="<?= Url::to(['/vacancy/view', 'id' => $model->vacancy->id]) ?>" target="_blank"><?= $model->vacancy->categoryJob->name ?></a>".
                </div>
                <div class="registration__success-desc2">
                    <?= Yii::t('vacancy', 'Expect worker response') ?>, <br>
                    <?= Yii::t('vacancy', 'you can also write to him or call') ?>.
                </div>
            </div>
        </div>
    </div>
</div>
