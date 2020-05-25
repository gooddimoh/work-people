<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('user', 'Create Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form_create', [
    'model' => $model,
]) ?>
