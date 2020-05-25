<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */
/* @var $model_company app\models\Company */

$this->title = Yii::t('company-review', 'Review added');
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Company Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="registration registration--2 registration--success-review">
    <div class="container">
        <div class="registration__inner">
            <div class="registration__form">
                <center>
                	<div class="title-sec title-sec--success">
	                    <?= Yii::t('company-review', 'Thank you!') ?>
	                </div>
                </center>
                <div class="registration__success-desc">
                    <?= Yii::t('company-review', 'After moderation, your review will be published.') ?>
                </div>
                <div class="registration__submit">
					<a href="<?= Url::to(['/company-review/index']) ?>" class="btn"><?= Yii::t('company-review', 'Leave feedback on other company') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>