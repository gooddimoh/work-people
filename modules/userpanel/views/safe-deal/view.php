<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\SafeDeal;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('deal', 'Safe Deals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$execution_range_list = SafeDeal::getExecutionRangeList();
?>
<div class="info-company">
	<div class="container">
		<div class="title-sec">
            <?= Html::encode($this->title) ?>
		</div>
		<div class="row info-company__row j-trigger">
			<div class="col">
				<hr style="margin-top: 0;">
					<!-- deal config -->
                    <div class="container">
                        <div class="message__title">
                            <?=Yii::t('message', 'Deal management') ?>
                        </div>

                        <div class="message__btns">
                            <a href="<?= Url::to(['/userpanel/message/view/', 'id' => $model->assignedSafeDealUser->user_id]) ?>" class="btn"><?= Yii::t('deal', 'Go to correspondence') ?></a>
                        </div>
                        
                        <hr>

                        <div class="message__btns">
                            <div class="message__btn-col">
                                <div class="btn btn--grey">
                                    <?= Yii::t('deal', 'Deal <b>inprogress</b>') ?>
                                </div>
                            </div>
                            <div class="message__btn-col">
                                <div class="btn btn--grey">
                                    <?= Yii::t('deal', 'Until completion') ?> <b>5 <?= Yii::t('deal', 'days')?> 8 <?= Yii::t('deal', 'hours')?></b>
                                </div>
                            </div>
                            <div class="message__btn-col">
                                <div class="btn btn--grey">
                                    <?= Yii::t('deal', 'Payment reserved') ?> (<b>$1500</b>)
                                </div>
                            </div>
                        </div>
                        <div class="message__time-long">
                            <div>
                                <?= Yii::t('deal', 'Extend a deal to') ?>
                            </div>
                            <div class="btn">1 <?= Yii::t('deal', 'day') ?></div>
                            <div><?= Yii::t('deal', 'or') ?></div>
                            <div class="btn">3 <?= Yii::t('deal', 'days') ?></div>
                        </div>
                        <div class="message__btns">
                            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                                <div class="btn btn--green j-tooltip-btn">
                                    <?= Yii::t('deal', 'Deal Completed') ?>
                                </div>
                                <div class="j-tooltip tooltip">
                                    <?= Yii::t('deal', 'The freelancer successfully completed the transaction: close as completed and write a review on cooperation.') ?>
                                </div>
                            </div>
                            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                                <div class="btn btn--transparent j-tooltip-btn">
                                    <?= Yii::t('deal', 'Deal Failed') ?>
                                </div>
                                <div class="j-tooltip tooltip">
                                    <?= Yii::t('deal', 'Your closed deal time') ?> (28 апреля в 18:47) <?= Yii::t('deal', 'not expired yet') ?>
                                </div>
                            </div>
                            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                                <div class="btn j-tooltip-btn">
                                    <?= Yii::t('deal', 'Turn to arbitration') ?>
                                </div>
                                <div class="j-tooltip tooltip">
                                    <?= Yii::t('deal', 'In the event of a dispute, you can file a complaint with the arbitration tribunal, we will provide the first response within 24 hours on business days.') ?>
                                    <?= Yii::t('deal', 'Also note that correspondence on arbitration is conducted exclusively in the Work Area and is available to its participants in the transaction.') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end deal config -->
				<hr>
				<div class="table">
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('amount_total') ?>
						</div>
						<div class="table__td">
							<b><?= $model->amount_total ?>&nbsp;<?= Html::encode($model->amount_currency_code) ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('deal', 'Deal involves prepayment and postpay') ?>
						</div>
						<div class="table__td">
							<b>
                                <?php if($model->has_prepaid == SafeDeal::HAS_PREPAID_YES): ?>
                                    <?= Yii::t('main', 'Yes') ?>
                                <?php else: ?>
                                    <?= Yii::t('main', 'No') ?>
                                <?php endif; ?>
                            </b>
						</div>
					</div>
                    <?php if($model->has_prepaid == SafeDeal::HAS_PREPAID_YES): ?>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('deal', 'Prepayment amount') ?>
                            </div>
                            <div class="table__td">
                                <b><?= $model->amount_prepaid ?>&nbsp;<?= Html::encode($model->amount_prepaid_currency_code) ?></b>
                            </div>
                        </div>
                        <div class="table__tr">
                            <div class="table__td table__td--first">
                                <?= Yii::t('deal', 'Prepayment Terms') ?>
                            </div>
                            <div class="table__td">
                                <b><?= Html::encode($model->condition_prepaid) ?></b>
                            </div>
                        </div>
                    <?php endif; ?>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('deal', 'ID (number) of the second party to the deal') ?>
						</div>
						<div class="table__td">
							<b><?= $model->assignedSafeDealUser->user_id ?></b>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('deal', 'Deal Terms') ?>
						</div>
						<div class="table__td">
							<?= Html::encode($model->condition_deal) ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('execution_period') ?>
						</div>
						<div class="table__td">
							<?= $model->execution_period ?>&nbsp;<?= $execution_range_list[$model->execution_range] ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= $model->getAttributeLabel('possible_delay_days') ?>
						</div>
						<div class="table__td">
							<?= $model->possible_delay_days ?>
						</div>
					</div>
					<div class="table__tr">
						<div class="table__td table__td--first">
                            <?= Yii::t('deal', 'Commission payment') ?>
						</div>
						<div class="table__td">
                            <?php if($model->comission == SafeDeal::COMISSION_TYPE_1): ?>
                                <?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                                <span class="payer"><?= Yii::t('deal', 'I pay the commission') ?></span>
                            <?php elseif($model->comission == SafeDeal::COMISSION_TYPE_2): ?>
                                <?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                                <span class="payer"><?= Yii::t('deal', 'The commission is divided in half') ?></span>
                            <?php elseif($model->comission == SafeDeal::COMISSION_TYPE_3): ?>
                                <?= Yii::t('deal', 'Safe work through') ?> <span class="link"><?= Yii::t('deal', 'safe deal') ?></span>
                                <span class="payer"><?= Yii::t('deal', 'Комиссию оплачивает второй участник сделки') ?></span>
                            <?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col info-company__col j-height-sticky-column">
				<div class="sidebar j-sticky">
					<div class="sidebar__title">
						<?= Yii::t('deal', 'Write a message to the user') ?>
					</div>
					<div class="sidebar__content">
                        <a href="<?= Url::to(['/userpanel/message/view/', 'id' => $model->assignedSafeDealUser->user_id]) ?>" class="btn"><?= Yii::t('deal', 'Go to correspondence') ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>