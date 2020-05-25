<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\VacancyRespond;

/* @var $this yii\web\View */
/* @var $model app\models\VacancySearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="search-cat__row">
    <div class="search-cat__col1 search-cat__col">
        <?php echo $form->field($model, 'status')->dropDownList(
            VacancyRespond::getStatusList(),
            [
                'prompt' => Yii::t('vacancy', 'All responds'),
                'class' => 'j-select select'
            ]
        )->label(false); ?>
    </div>
    <div class="search-cat__col2 search-cat__col">
    </div>
    <div class="search-cat__col3 search-cat__col">
        <?= Html::submitButton(Yii::t('main', 'Search To'), ['class' => 'btn']) ?>
    </div>
</div>
