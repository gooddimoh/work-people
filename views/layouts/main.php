<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<?php echo $this->render('parts/header'); ?>
<div class="loading" id="loaderWait" style="display:none;">Loading&#8230;</div>
<div class="breadcrumbs">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'options' => [
                'class' => 'breadcrumbs__list'
            ]
        ]) ?>
    </div>
</div>

<?= Alert::widget([
    'options' => [
        'class' => 'container top-alert-message',
    ]]) ?>

<?= $content ?>

<?php echo $this->render('parts/footer'); ?>
<?php $this->endPage() ?>