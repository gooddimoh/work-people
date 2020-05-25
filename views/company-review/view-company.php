<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Company Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?php echo $this->render('@app/views/company/_view_header', [
    'model' => $model
]) ?>

<div class="container review-single" id="reviews">
	<div class="row cat-main j-trigger">
		<div class="col-cat-main">
			<div class="review">
				<?php
					echo ListView::widget([
						'layout' => '
							<!-- {sorter} -->
							<div class="vacancy vacancy--cat">
								{items}
							</div>
							{pager}
						',
						'dataProvider' => $dataProvider,
						'itemView' => '_list',
						'itemOptions' => [
							'class' => 'review__item'
						],
						'sorter' => [
							'options' => [
								'class' => 'list-view-sorter'
							],
							// 'linkOptions' => [
							//     'class' => ''
							// ]
						],
						'pager' => [
							// 'firstPageLabel' => '<< ',
							'prevPageLabel'  => '« ' . Yii::t('main', 'previous'),
							'nextPageLabel'  => Yii::t('main', 'next') . ' »',
							// 'lastPageLabel'  => ' >>',
							'options' => [
								'class' => 'pagination',
								// 'class' => 'pagination pagination--mobile'
							],
							'linkOptions' => [
								'class' => 'pagination__number'
							]
						]
					]);
				?>
			</div>
		</div>
		<div class="col-cat-sidebar j-height-sticky-column">
			<div class="sidebar j-sticky">
				<div class="sidebar__title">
					<?= Yii::t('company-review', 'Do you work or have you worked for this company?') ?>
				</div>
				<div class="sidebar__content">
					<?= Yii::t('company-review', 'We guarantee complete anonymity.') ?>
					<a href="<?= Url::to(['add', 'id' => $model->id]) ?>" class="btn edit-info__edit-btn" style="width: 100%;margin-top: 25px;"><?= Yii::t('company-review', 'Leave feedback') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
