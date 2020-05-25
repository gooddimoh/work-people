<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Vacancy;
use app\models\Category;
use app\models\CountryCity;
use app\models\CategoryJob;
use kartik\widgets\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ResumeSearch */
/* @var $form yii\widgets\ActiveForm */

$gender_list = Vacancy::getGenderList();
$country_list_full = Vacancy::getCountryList();
$country_list = [];
// translate
foreach($country_list_full as $country) {
    $country_list[$country['char_code']] = Yii::t('country', $country['name']);
}

$documents_required_list = Vacancy::getDocumentsRequiredList();
foreach($documents_required_list as $key => $value) {
    $documents_required_list[$key] = Yii::t('vacancy', $value);
}
$currency_list_full = ArrayHelper::map(Vacancy::getCurrencyList(), 'char_code', 'name');
$currency_list = [];
foreach($currency_list_full as $key => $val) {
    $currency_list[$key] = Yii::t('curr', $val);
}

$query_city = CountryCity::find()->asArray()->orderBy(['priority' => SORT_DESC, 'id' => SORT_ASC])->limit(50);
if(!empty($model->getAttribute('country_name'))) {
    $query_city->andWhere(['country_char_code' => $model->getAttribute('country_name')]);
}
$country_city_full = ArrayHelper::map($query_city->all(), 'id', 'city_name');
$country_city_list = [];
// translate
foreach($country_city_full as $key => $city_name) {
    $country_city_list[$key] = Yii::t('city', $city_name);
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

<div class="search-cat__row resume-page-search">
    <div class="search-cat__col1 search-cat__col">
        <div class="input-group">
        <?php
            echo $form->field($model, 'category_job_list')->widget(Select2::classname(), [
                'data' => $job_list,
                'size' => Select2::LARGE,
                'options' => [
                    'multiple' => true,
                    'placeholder' => Yii::t('main', 'Enter your request ...'),
                ],
                'showToggleAll' => false,
                'pluginOptions' => [
                    // 'allowClear' => true,
                    // 'tags' => true,
                    // 'maximumSelectionLength' => 1
                ],
            ])->label(false);
        ?>
        </div>
    </div>
    <div class="search-cat__col2 search-cat__col">
        <div class="input-group">
            <div class="input-group__col">
                <input type="number" name="ResumeSearch[age_min]" value="<?= Html::encode($model->age_min); ?>" placeholder="<?= Yii::t('resume', 'Age Min') ?>">
            </div>
            <div class="input-group__col">
                <input type="number" name="ResumeSearch[age_max]" value="<?= Html::encode($model->age_max); ?>" placeholder="<?= Yii::t('resume', 'Age Max') ?>">
            </div>
        </div>
    </div>
    <!-- <div class="search-cat__col3 search-cat__col">
    </div> -->
    <!-- <div class="search-cat__col4 search-cat__col">
    </div>
    <div class="search-cat__col5 search-cat__col">
    </div> -->
    <div class="search-cat__col6 search-cat__col">
        <?php /* echo $form->field($model, 'resume_country_codes')->dropDownList(
            $country_list,
            [
                'prompt' => Yii::t('resume', 'Сitizenship of candidates'),
                'class' => 'j-select select select--double'
            ]
        )->label(false); */ ?>
    </div>
    <!-- <div class="search-cat__col7 search-cat__col">
    </div> -->
    <div class="search-cat__col8 search-cat__col">
        <?php echo $form->field($model, 'desired_country_of_work')->dropDownList(
            $country_list,
            [
                'prompt' => Yii::t('resume', 'Looking for work in'),
                'class' => 'j-select select select--double'
            ]
        )->label(false); ?>
    </div>
    <!-- <div class="search-cat__col9 search-cat__col">
        
    </div> -->
    <div class="search-cat__col10 search-cat__col">
        <?= Html::submitButton(Yii::t('main', 'Search To'), ['class' => 'btn btn-search']) ?>
        <a href="<?= Url::to(['']) ?>" class="btn btn-reset-filters"><?= Yii::t('main', 'Reset filters') ?></a>
        <?php /* echo Html::resetButton(Yii::t('main', 'Reset filters'), ['class' => 'btn btn-outline-secondary']) */ ?>
    </div>
</div>

<?php $this->beginJs(); ?>
<script>
$(document).ready(function() {
    $('.j-multi-select-docs').multiselect({
        texts: {
            placeholder: '<?= Yii::t('vacancy', 'Your documents') ?>', 
            search         : 'Search',        
            selectedOptions: ' <?=Yii::t('vacancy', 'selected') ?>',     
            selectAll      : 'Select all',   
            unselectAll    : 'Unselect all',  
            noneSelected   : 'None Selected' 
        },
        maxHeight: 500,
    });

    
    var country_list_select = $('#vacancysearch-country_name');
    var country_city_list_select = $('#vacancysearch-country_city_id');
    var laoder_wait = $('.country-city-loader-wait');

    country_list_select.niceSelect();
    country_city_list_select.niceSelect();
    
    // if (country_list_select.val()) {
    //     updateCityList();
    // }

    country_list_select.on('change', updateCityList);

    function updateCityList() {
        var country_char_code = country_list_select.val();
        laoder_wait.show();
        country_city_list_select.niceSelect('destroy');
        country_city_list_select.hide();
        
        $.get( '/<?= \Yii::$app->language ?>/api/country?country_char_code=' + country_char_code, function( data ) {
            country_city_list_select.empty();
            country_city_list_select.append($("<option></option>").text("<?= Yii::t('vacancy', 'City') ?>"));
            $.each(data.items, function(key,value) {
                country_city_list_select.append(
                    $("<option></option>").attr("value", value.id).text(value.city_name)
                );
            });
            laoder_wait.hide();
            // country_city_list_select.show();
            country_city_list_select.niceSelect();
        });
    }
});
</script>

<?php $this->endJs(); ?>