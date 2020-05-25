<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Vacancy */

$this->title = Yii::t('vacancy', 'Create Vacancy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'My Vacancies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form_create', [
    'model' => $model,
    // 'modelJob' => $modelJob,
    // 'modelLang' => $modelLang,
    // 'modelEducation' => $modelEducation,
    // 'modelEducationAdditional' => $modelEducationAdditional,
    // 'modelAdditional' => $modelAdditional,
    // 'modelForeignJob' => $modelForeignJob,
    // 'category_list' => $category_list,
]) ?>
