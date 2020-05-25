<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('resume', 'Search resume');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-grey-searchworkers">
    <div class="container">
    	<div class="search-workers">
    		<h1 class="search-workers__title">
	    		<?= Html::encode($this->title) ?>
	    	</h1>
	    	<p>
	    		<?= Yii::t('resume', 'top_search_description') ?>
	    	</p>
    	</div>
		<?php $form = ActiveForm::begin([
			'action' => ['search'],
			'method' => 'get',
			'options' => [
				'class' => '',
			],
			'fieldConfig' => ['options' => ['class' => '']],
		]); ?>
			<?php echo $this->render('_search', [
				'form' => $form,
				'model' => $searchModel
			]); ?>
		<?php ActiveForm::end(); ?>
    </div>
</div>
<?= $this->render('description') ?>
