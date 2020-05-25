<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VacancySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('vacancy', 'My vacancies and responses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
	<div class="vacancy-review">
		<div class="vacancy-review__top">
			<div class="title-sec">
				<?= Yii::t('vacancy', 'My vacancies and responses') ?>
			</div>
			<?php echo $this->render('_search', [
				'model' => $searchModel,
				'status_show_count' => $status_show_count,
				'total_count_my' => $total_count_my,
			]); ?>
		</div>
		<hr style="margin-bottom: 0;">
        <?php Pjax::begin(); ?>
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
                    'class' => ''
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
        <?php Pjax::end(); ?>
        <div class="buy-services">
			<a href="<?= Url::to(['/userpanel/vacancy/create']) ?>" class="btn" style="margin-top: 30px;"> <?= Yii::t('vacancy', 'Create Vacancy') ?></a>
		</div>
	</div>

</div>
