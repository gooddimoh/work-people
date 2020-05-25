<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('user', 'Profile not exists');
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

        <p><?= Yii::t('user', 'Profile not exists') ?></p>
        <p><a href="<?= Url::to(['/userpanel/profile/create'])?>" class="btn"><?= Yii::t('user', 'Create Profile') ?></a></p>

        <br />
        <br />
    </div>
</div>
