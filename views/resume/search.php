<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('resume', 'Resumes');
// $this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Search resume'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$sort = $dataProvider->getSort();
$list_view = empty($_COOKIE['list_view_vacancy']) ? 'tile' : $_COOKIE['list_view_vacancy']; // got unsafe value from JS
?>
<div class="bg-grey-cat">
    <div class="container">
    	<div class="btn-filter">
	    	<div class="btn btn--mobile j-filter-btn">
	    		<?= Yii::t('vacancy', 'Open filter') ?>
	    	</div>
    	</div>
        <?php $form = ActiveForm::begin([
			'action' => ['search'],
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
            <div class="cat-nav__sort-wrapper">
	    		<div class="cat-nav__sort-title">
	    			<?= Yii::t('vacancy', 'Sorting') ?>:
	    		</div>
	        	<ul class="cat-nav__sort">
	        		<li <?= $sort->getAttributeOrder('newest') ? 'class="active"' : '' ?>><?= $sort->link('newest') ?></li>
					<?php /*
	        		<li <?= $sort->getAttributeOrder('salary') ? 'class="active"' : '' ?>><?= $sort->link('salary') ?></li>
					<li <?= $sort->getAttributeOrder('salary_month') ? 'class="active"' : '' ?>><?= $sort->link('salary_month') ?></li>
					*/ ?>
	        	</ul>
	        </div>
		<?php ActiveForm::end(); ?>
    </div>
    <div class="cat-nav cat-nav--hide-mobile">
    	<div class="container">
	    	<div class="cat-nav__sort-wrapper">
	    		<div class="cat-nav__sort-title">
	    			<?= Yii::t('vacancy', 'Sorting') ?>:
	    		</div>
	        	<ul class="cat-nav__sort">
					<li <?= $sort->getAttributeOrder('newest') ? 'class="active"' : '' ?>><?= $sort->link('newest') ?></li>
					<?php /*
	        		<li <?= $sort->getAttributeOrder('salary') ? 'class="active"' : '' ?>><?= $sort->link('salary') ?></li>
					<li <?= $sort->getAttributeOrder('salary_month') ? 'class="active"' : '' ?>><?= $sort->link('salary_month') ?></li>
					*/ ?>
	        	</ul>
	        </div>
    	</div>
    </div>
</div>

<div class="container">
	<!-- <div class="title-sec" style="margin-bottom: 10px;">
		<?= $this->title ?>
	</div> -->
	<div class="row cat-nav-main" style="margin-bottom: 0;">
		<div class="cat-nav-main__col">
		</div>
		<div class="cat-nav-main__col">
			<ul class="view-list j-view-list col">
				<li><?= Yii::t('main', 'List view') ?>:</li>
				<li>
					<a <?= ($list_view == 'list') ? 'class="active"' : '' ?>href="list"><?= Yii::t('main', 'list') ?></a>
				</li>
				<li>
					<a <?= ($list_view == 'tile') ? 'class="active"' : '' ?>href="tile"><?= Yii::t('main', 'tile') ?></a>
				</li>
			</ul>
		</div>
	</div>
	<hr style="margin-top: 0;">
	<div class="cat-main">
        <?php
            echo ListView::widget([
                'layout' => '
                    <!-- {sorter} -->
                    <div class="vacancy vacancy--4 vacancy--candidate vacancy-fix-resize '. (($list_view == 'list') ? 'vacancy--tile' : '') .'">
                        {items}
                    </div>
                    {pager}
                ',
                'dataProvider' => $dataProvider,
				'itemView' => '_list',
				'viewParams' => [
					'favorite_ids' => $favorite_ids,
				],
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
		
	</div>
</div>

<div class="history-views">
	<div class="container">
		<div class="title-sec-wrap j-arrows-wrap">
			<div class="title-sec">
		        <?= Yii::t('resume', 'Browsing history') ?>
		    </div>
		    <div class="j-arrows carousel-arrows"></div>
		</div>
		<div class="vacancy" slick-slider data-tablet="4" data-tablet-v="3" data-mobile="2" data-mobile-v="1">
	    	<?php for ($i = 1; $i <= 5; $i++) { ?>
		    	<div class="vacancy__item">
		    		<div class="vacancy__inner">
		    			<div class="vacancy__overlay"></div>
		    			<a href="" class="vacancy__title">
		    				Металлургический завод
		    			</a>
		    			<ul class="vacancy__place">
		    				<li>
		    					<a href="#" class="vacancy__country">
			    					Германия
			    				</a>
		    				</li>
		    				<li><a href="#">Фёльклинген</a></li>
		    			</ul>
		    			<a href="#" class="vacancy__img">
		    				<img src="/img/vacancy/1.jpg" alt="">
		    			</a>
		    			<div class="vacancy__price">
		    				от <b>12 000</b> до <b>25 000</b> грн.
		    			</div>
		    			<div class="vacancy__like-wrap">
		    				<a href="#" class="vacancy__like">
			    				Добавить в избранное
			    			</a>
		    			</div>
		    		</div>
		    	</div>
	    	<?php } ?>
	    </div>
	</div>
</div>

<?php $this->beginJs(); ?>
<script>
    $(document).ready(function() {
		// add remove favorite resume
        $( ".jqh-resume__like" ).click(function(e) {
			e.stopPropagation();
			let request_url_like = "<?= Url::to(['/userpanel/resume/add-favorite']) ?>";
			let request_url_unlike = "<?= Url::to(['/userpanel/resume/remove-favorite']) ?>";

			let resume_id = $(this).data('id');
			let request_type = $(this).data('rtype');
			let request_url = '';
			let el_to_show = {};

			if(request_type == 'add-like') {
				request_url = request_url_like;
				el_to_show = $('.resume_remove_like_' + resume_id);
			} else { // request_type == 'remove-like'
				request_url = request_url_unlike;
				el_to_show = $('.resume_add_like_' + resume_id);
			}

			$(this).hide();
			let loader_wait = $('#resume_like_loading_' + resume_id);
			loader_wait.show();
			$.post( request_url + '?id=' + resume_id, [])
				.always(function ( response ) {
					loader_wait.hide();
					if(response.success == true) {
						el_to_show.show();
					}
				});
        });
    });
</script>
<?php $this->endJs(); ?>
