<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('vacancy', 'You cannot apply to your own vacancy.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="profile">
	<div class="container">

        <div class="profile-top">
			<div class="profile-top__col">
                <?= Html::encode($this->title) ?>
            </div>
        </div>

        <p><?= Yii::t('vacancy', 'You cannot apply to your own vacancy.') ?></p>

        <br />
        <br />
    </div>
</div>
