<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Company;

/* @var $this yii\web\View */
/* @var $model app\models\Company */
?>
<div class="single-top">
    <div class="container">
        <div class="row">
            <div class="single-top__col col">
                <div class="row single-top__row">
                    <div class="single-top__main col">
                        <div class="title-sec">
                            <?= $this->title ?>
                        </div>
                        <ul class="single-top__list">
                            <li>
                                <?= Yii::t('company', 'Safe deals') ?>: <span class="green">0</span>
                            </li>
                            <li>
                                <?= Yii::t('company', 'Reviews') ?>: <span class="green">+<?= $model->getCompanyReviewsPositive()->count() ?></span> / <span class="red">-<?= $model->getCompanyReviewsNegative()->count() ?></span>
                            </li>
                            <li>
                                <?= Yii::t('company', 'In favorites') ?>  <span class="blue">-</span>
                            </li>
                        </ul>
                    </div>
                    <div class="single-top__numbers col">
                        <ul>
                            <li>
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false"><?= Html::encode($model->contact_phone) ?></div>
                            </li>
                            <li>
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                            <li class="j-more-hide" style="display: none;">
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                            <li class="j-more-hide" style="display: none;">
                            	<div class="single-top__phone" onmousedown="return false" onselectstart="return false">+380 (93) <span class="single-top__hide">295-78-24</span></div>
                            </li>
                        </ul>
                        <span class="single-top__more j-more"><?=Yii::t('company', 'More...') ?></span> <br>
                        <a href="<?= Url::to(['/userpanel/message/view', 'id' => $model->user_id]) ?>" class="btn">
                        	<?= Yii::t('company', 'Write to employer') ?>
                        </a>
                        <div class="single-top__message">
                        	<?= Yii::t('company', 'Write to the employer directly') ?>
                        </div>
                    </div>
                </div>
                <div class="single-top__nav">
                    <a href="<?= Url::to(['company/vacancy', 'id' => $model->id ]) ?>" class="btn single-top__nav-rezume">
                    	<?= Yii::t('company', 'Jobs') ?>
                    </a>
                    <a href="<?= Url::to(['/company-review/viewcompany', 'id' => $model->id]) ?>#reviews" class="btn btn--transparent single-top__nav-rezume j-scroll">
                    	<?= Yii::t('company', 'Reviews') ?>
                    </a>
                    <a href="<?= Url::to(['company/view', 'id' => $model->id ]) ?>" class="btn btn--transparent single-top__nav-rezume">
                    	<?= Yii::t('company', 'About company') ?>
                    </a>
                </div>
            </div>
            <?php /*
            <div class="single-top__col2 col">
            	<div class="single-top__subscribe">
            		<div class="single-top__subscribe-title">
            			<?= Yii::t('company', 'Get the first proposal') ?>
            		</div>
            		<a href="#" class="btn">
            			<?= Yii::t('company', 'Follow company news') ?>
            		</a>
            		<div class="single-top__subscribe-desc">
            			<?= Yii::t('company', 'Sign up for the newsletter and watch for vacancies in your category') ?>
            		</div>
            	</div>
            </div>
            */ ?>
        </div>
    </div>
</div>
