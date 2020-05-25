<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */

$this->title = Yii::t('resume', 'Create Resume');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'My resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form_create', [
    'model' => $model,
    'modelJob' => $modelJob,
    'modelLang' => $modelLang,
    'modelEducation' => $modelEducation,
    'category_list' => $category_list,
    'is_registration' => $is_registration,
]) ?>
