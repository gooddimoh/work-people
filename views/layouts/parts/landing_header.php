<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\LanguageSwitcher;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>

   <?php echo $this->render('@app/views/layouts/parts/styles'); ?>
   <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="header-relative <?php echo (!Yii::$app->user->isGuest ? 'header-relative--login' : ''); ?>">
        <div class="j-header header-fixed">
            <header class="header">
                <div class="container">
                    <div class="row">
                        <div class="header__col">
                            <a href="<?= Url::home(); ?>" class="header__logo">
                                <img src="/img/global/logo.svg" alt="">
                            </a>
                        </div>
                        <div class="header__col header__col--2 header__center">
                            <a href="candidate.php" class="btn header__login">
                                <?= Yii::t('main', 'For candidates') ?>
                            </a>
                            <a href="employer.php" class="btn header__login">
                                <?= Yii::t('main', 'For employers') ?>
                            </a>
                        </div>
                        <div class="header__col header__col--2">
                            <?= LanguageSwitcher::widget(); ?>
                        </div>
                        <div class="header__mobile-row">
                            <div class="toggle-burger j-toggle">
                                <div>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="toggle-menu j-toggle-menu">
                    <div class="toggle-menu__top">
                        <div class="toggle-menu__row">
                            <?= LanguageSwitcher::widget(); ?>
                            <div class="toggle-menu__close j-toggle-close">
                                 <div>
                                     <?= Yii::t('site', 'Close') ?>
                                    <span></span>
                                 </div>     
                            </div>
                        </div>
                    </div>
                    <ul class="header__menu">
                        <li>
                            <a href="<?= Url::to(['/site/login']); ?>">
                                <div class="avatar">
                                    <div><?= Yii::t('main', 'Login') ?></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <ul class="header__menu header__menu--grey">
                        <li>
                            <a href="#">
                                <?= Yii::t('main', 'For candidates') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <?= Yii::t('main', 'For employers') ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </header>
        </div>
    </div>