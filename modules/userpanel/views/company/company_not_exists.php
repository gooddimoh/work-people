<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::t('company', 'Company not exists');
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

        <p><?= Yii::t('company', 'You have not yet added information about the company.') ?></p>
        <p><a href="<?= Url::to(['/userpanel/company/create'])?>" class="btn"><?= Yii::t('company', 'Add Company Information') ?></a></p>

        <br />
        <br />
    </div>
</div>
