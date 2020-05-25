<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VacancySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('vacancy', 'Response History');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-grey-cat">
    <div class="container">
    	<div class="btn-filter">
	    	<div class="btn btn--mobile j-filter-btn">
	    		<?= Yii::t('vacancy', 'Open filter') ?>
	    	</div>
    	</div>
        <?php $form = ActiveForm::begin([
			'action' => ['index'],
			'method' => 'get',
			'options' => [
				'class' => 'search-cat j-filter',
			],
			'fieldConfig' => ['options' => ['class' => '']],
		]); ?>
            <?php echo $this->render('_search', [
				'model' => $searchModel,
				'form' => $form,
			]); ?>
		<?php ActiveForm::end(); ?>
    </div>
</div>

<div class="container">
	<div class="title-sec" style="margin-bottom: 10px;">
		<?= Html::encode($this->title) ?>
	</div>
	<div class="row cat-nav-main" style="margin-bottom: 0;">
		<div class="cat-nav-main__col">
		</div>
		<div class="cat-nav-main__col">
			<ul class="view-list j-view-list col">
				<li><?= Yii::t('main', 'List view') ?>:</li>
				<li>
					<a href="list"><?= Yii::t('main', 'list') ?></a>
				</li>
				<li>
					<a class="active" href="tile"><?= Yii::t('main', 'tile') ?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr style="margin-top: 0;">
	<div class="cat-main j-trigger">
		<div>
			<?php
				echo ListView::widget([
					'layout' => '
						<!-- {sorter} -->
						<div class="vacancy vacancy--history">
							{items}
						</div>
						{pager}
					',
					'dataProvider' => $dataProvider,
					'itemView' => '_list',
					'itemOptions' => [
						'class' => 'vacancy__item'
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
		    <!-- <ul class="pagination pagination--mobile">
		    	<li class="prev"><a href="#" class="pagination__number">« назад</a></li>
		    	<li><a href="#" class="pagination__number">1</a></li>
		    	<li class="active"><div class="pagination__number">2</div></li>
		    	<li><a href="#" class="pagination__number">...</a></li>
		    	<li><a href="#" class="pagination__number">58</a></li>
		    	<li><a href="#" class="pagination__number">59</a></li>
		    	<li class="next"><a href="#" class="pagination__number">вперед »</a></li>
		    </ul> -->
		</div>
	</div>
</div>