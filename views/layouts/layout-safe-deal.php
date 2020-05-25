<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<?php 
$header = 'employer';
?>

<?php echo $this->render('@app/views/layouts/parts/landing_header'); ?>

<?= Alert::widget([
    'options' => [
        'class' => 'container top-alert-message',
    ]]) ?>

<?= $content ?>

<?php echo $this->render('@app/views/layouts/parts/footer'); ?>

<?php $this->endPage() ?>
