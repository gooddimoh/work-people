<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\models\VacancySearch;
use app\models\Vacancy;
use app\models\CategoryJob;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $category_main_page app\models\Category */

$this->title = Yii::$app->name;

$searchModel = new VacancySearch();
$country_list_full = Vacancy::getCountryList();
$country_list = [];
// translate
foreach($country_list_full as $country) {
    $country_list[$country['char_code']] = Yii::t('country', $country['name']);
}

$session = Yii::$app->session;
$user_type = $session->get('interface_for');
$search_url = '/vacancy/index';
if ($user_type == 'employer') {
	$search_url = '/resume/search';
}

$job_list_grouped = CategoryJob::getUserMultiSelectList();
$job_list = [];
foreach($job_list_grouped as $group) {
	$jobs = [];
    foreach($group['jobs'] as $job_item) {
        $jobs[$job_item['id']] = Yii::t('category-job', $job_item['name']);
	}
	$job_list[Yii::t('category-job', $group['group_name'])] = $jobs;
}
?>
<div class="bg-grey-front">
    <div class="container">
		<?php $form = ActiveForm::begin([
			'action' => ['/vacancy/index'],
			'method' => 'get',
			'options' => [
				'class' => 'search',
			],
			'fieldConfig' => ['options' => ['class' => '']],
		]); ?>
            <div class="search__row">
                <div class="search__col1 search__col main-page-search">
                    <?php
						echo $form->field($searchModel, 'category_job_list')->widget(Select2::classname(), [
							'data' => $job_list,
							'size' => Select2::LARGE,
							'options' => [
								'multiple' => true,
								'placeholder' => Yii::t('main', 'Enter your request ...'),
							],
							'showToggleAll' => false,
							'pluginOptions' => [
								// 'tags' => true,
								// 'maximumSelectionLength' => 1
							],
						])->label(false);
					?>
                    <div class="search__primer">
                    	<span><?= Yii::t('main', 'for example') ?>:</span>
                    	<a href="<?= Url::to(['/vacancy/index', 'VacancySearch[category_job_list]' => '92']) ?>"><?= Yii::t('site', 'search_example_1') ?></a>,
                    	<a href="<?= Url::to(['/vacancy/index', 'VacancySearch[category_job_list]' => '83']) ?>"><?= Yii::t('site', 'search_example_2') ?></a>
                    </div>
                </div>
                <div class="search__col3 search__col">
					<?php echo $form->field($searchModel, 'country_name')->dropDownList(
						$country_list,
						[
							'prompt' => Yii::t('vacancy', 'Employer countries'),
							'class' => 'j-select select select--double'
						]
					)->label(false); ?>
                </div>
                <div class="search__submit search__col">
                    <input type="submit" value="<?= Yii::t('main', 'Search To') ?>">
                </div>
            </div>
		<?php ActiveForm::end(); ?>
        <div class="cats">
            <div class="cats__row">
				<?php foreach($category_main_page as $category): ?>
					<a href="<?= Url::to(['/vacancy/index', 'VacancySearch[category_list]' => $category->id ]) ?>" class="cats__item">
						<div class="cats__img">
							<img src="<?= $category->getImage() ?>" alt="">
						</div>
						<div class="cats__title">
							<?= Yii::t('category', $category->name) ?>
							<span><?= $category->getAdsCount() ?> <?= Yii::t('site', 'ads') ?></span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
			<a href="<?= Url::to(['/vacancy/index']) ?>" class="cats__all">
				<?= Yii::t('site', 'All jobs') ?>
			</a>
        </div>
    </div>
</div>
<div class="container">
	<div class="title-sec">
        <?= Yii::t('site', 'Hot vacancies') ?>
    </div>
    	<?php
			echo ListView::widget([
				'layout' => '
					<div class="vacancy">
						{items}
					</div>
				',
				'dataProvider' => $dataProvider,
				'itemView' => '_list_hot',
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
			]);
		?>
</div>

<div class="history-views">
	<div class="container">
		<div class="title-sec-wrap j-arrows-wrap">
			<div class="title-sec">
		        <?= Yii::t('main', 'Browsing history') ?>
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

<?php echo $this->render('subscribe'); ?>

<?php echo $this->render('seo_index') ?>

<?php $this->beginJs(); ?>
<script>
    $(document).ready(function() {
		// add remove favorite vacancy
        $( ".jqh-vacancy__like" ).click(function(e) {
			e.stopPropagation();
			let request_url_like = "<?= Url::to(['/userpanel/vacancy/add-favorite']) ?>";
			let request_url_unlike = "<?= Url::to(['/userpanel/vacancy/remove-favorite']) ?>";

			let vacancy_id = $(this).data('id');
			let request_type = $(this).data('rtype');
			let request_url = '';
			let el_to_show = {};

			if(request_type == 'add-like') {
				request_url = request_url_like;
				el_to_show = $('#vacancy_remove_like_' + vacancy_id);
			} else { // request_type == 'remove-like'
				request_url = request_url_unlike;
				el_to_show = $('#vacancy_add_like_' + vacancy_id);
			}

			$(this).hide();
			let loader_wait = $('#vacancy_like_loading_' + vacancy_id);
			loader_wait.show();
			$.post( request_url + '?id=' + vacancy_id, [])
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
