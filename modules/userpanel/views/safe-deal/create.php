<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SafeDeal */

$this->title = Yii::t('deal', 'Create Safe Deal');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('deal', 'Safe Deals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form_create', [
    'model' => $model,
    'modelDealUser' => $modelDealUser,
]) ?>
