<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyReviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('company', 'Company Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="bg-grey-searchworkers">
    <div class="container">
    	<div class="search-workers">
    		<h1 class="title-sec" style="margin: 10px 0 30px;">
	    		<?= Yii::t('company-review', 'Find reviews about employers, salaries and selection in the company') ?>
	    	</h1>
    	</div>
        <form action="" class="search search--workers">
            <div class="search__row">
                <div class="search__col1 search__col">
                    <input type="text" placeholder="<?= Yii::t('company-review', 'Enter your request...') ?>">
                </div>
                <div class="search__submit search__col">
                    <input type="submit" value="<?= Yii::t('main', 'Find') ?>">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="home-reviews">
	<div class="container">
		<div class="title-sec">
			<?= Yii::t('company-review', 'Employers on') ?> <?= Yii::$app->name ?>
		</div>
		<?php
			echo ListView::widget([
				'layout' => '
					<!-- {sorter} -->
					<div class="home-reviews__wrapper">
						{items}
					</div>
					{pager}
				',
				'dataProvider' => $dataProviderCompany,
				'itemView' => '_list_company',
				'itemOptions' => [
					'class' => 'home-reviews__item'
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
		<div class="home-reviews__btn">
			<a href="<?= Url::to(['/company/index']); ?>" class="btn"><?= Yii::t('company-review', 'All employers') ?></a>
		</div>
	</div>
</div>
<div class="reviews-banner">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="reviews-banner__img">
					<img src="img/reviews-company/banner.jpg" alt="">
				</div>
			</div>
			<div class="col">
				<div class="reviews-banner__title">
					<?= Yii::t('company-review', 'Unlock all sections of the site by sharing a review.') ?>
				</div>
				<div class="reviews-banner__desc">
					<?= Yii::t('company-review', 'About current or former employee, salary, attitude towards employees') ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="last-reviews">
	<div class="container">
		<div class="title-sec">
			<?= Yii::t('company-review', 'Latest reviews') ?>
		</div>
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
</div>

<?php echo $this->render('seo_review') ?>
