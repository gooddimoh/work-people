<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
/* @var $this yii\web\View */

$this->title = Yii::t('user', 'Revenues and expenses through a safe deal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
	<div class="container">
		<div class="title-sec">
            <?= Html::encode($this->title) ?>
		</div>
	</div>
	<div class="cat-nav">
		<div class="container">
			<ul class="cat-nav__cat">
				<li>
					<a href="" class="active"><?= Yii::t('deal', 'Active deals') ?></a>
				</li>
				<li>
					<a href=""><?= Yii::t('deal', 'Archive deals') ?></a>
				</li>
			</ul>
		</div>
	</div>
	<?php echo $this->render('_search', [
		'model' => $searchModel,
	]); ?>
	<form class="balans">
		<div class="container">
			<?php
				echo ListView::widget([
					'layout' => '
						<!-- {sorter} -->
						{items}
						<div class="pagination">{pager}</div>
					',
					'dataProvider' => $dataProvider,
					'itemView' => '_list',
					'itemOptions' => [
						'class' => 'balans__item'
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
						'options' => [
							'class' => ''
						],
					]
				]);
			?>
		</div>
	</form>
</div>