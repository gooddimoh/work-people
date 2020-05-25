<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\LanguageSwitcher;

$this->registerJsFile('/libs/jquery-nice-select/js/jquery.nice-select.min.js');
$this->registerCssFile('/libs/jquery-nice-select/css/nice-select.css');

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php $this->registerCsrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>

    <link href="/css/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/css/admin/css/animate.css" rel="stylesheet">
    <link href="/css/admin/css/style.css?v=0.1" rel="stylesheet">
    <link href="/css/admin/css/upload-images.css" rel="stylesheet">
    <link href="/css/admin/css/site.css" rel="stylesheet">
    <?php $this->head() ?>

</head>

<body>
<?php $this->beginBody() ?>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <img src="/img/global/logo.svg" alt="" style="background: #fff;">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="block m-t-xs font-bold"><?=Yii::$app->user->identity->username?></span>
                            <span class="text-muted text-xs block"><?=Yii::$app->user->identity->role?> <b class="caret"></b></span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="dropdown-item" href="<?=Url::toRoute('/userpanel')?>">Личный кабинет</a></li>
                            <li class="dropdown-divider"></li>
                            <!-- <li><a class="dropdown-item" href="login.html">Logout</a></li> -->
                            <li>
                                <?= Html::beginForm(['/site/logout'], 'post') ?>
                                <?= Html::submitButton(
                                    '<i class="fa fa-sign-out"></i>&nbsp;Выйти',
                                    ['class' => 'btn btn-link logout', 'title' => Yii::$app->user->identity->username]
                                )
                                . Html::endForm() ?>
                            </li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        WWS
                    </div>
                </li>
                <li class="active">
                    <a href="<?=Url::toRoute('/admin')?>"><i class="fa fa-th-large"></i> <span class="nav-label">Главная</span> <span class="fa arrow"></span></a>
                    <?php echo Nav::widget([
                            'options' => ['class' => 'nav nav-second-level'],
                            'items' => [
                                ['label' => 'Категории', 'url' => ['/admin/category']],
                                ['label' => 'Список профессий', 'url' => ['/admin/category-job']],
                                ['label' => 'Вакансии', 'url' => ['/admin/vacancy']],
                                ['label' => 'Анкеты', 'url' => ['/admin/resume']],
                                ['label' => 'Компании', 'url' => ['/admin/company']],
                                ['label' => 'Отзывы о компаниях', 'url' => ['/admin/company-review']],
                                // ['label' => 'Безопасные сделки', 'url' => ['/admin/safe-deal']],
                            ],
                        ]);
                    ?>
                </li>
                <li class="active">
                    <a href="<?=Url::toRoute('/admin')?>"><i class="fa fa-user"></i> <span class="nav-label">Пользователи</span> <span class="fa arrow"></span></a>
                    <?php echo Nav::widget([
                            'options' => ['class' => 'nav nav-second-level'],
                            'items' => [
                                ['label' => 'Аккаунты', 'url' => ['/admin/user']],
                                ['label' => 'Профили', 'url' => ['/admin/profile']],
                                ['label' => 'Сообщения', 'url' => ['/admin/message']],
                                ['label' => 'Уведомления', 'url' => ['/admin/notification']],
                                ['label' => 'Автоматическая рассылка', 'url' => ['/admin/auto-mail']],
                            ],
                        ]);
                    ?>
                </li>
                <li>
                    <a href="<?=Url::toRoute('/admin')?>"><i class="fa fa-book"></i> <span class="nav-label">Справочники</span> <span class="fa arrow"></span></a>
                    <?php echo Nav::widget([
                            'options' => ['class' => 'nav nav-second-level'],
                            'items' => [
                                ['label' => 'Переводы', 'url' => ['/admin/localization']],
                                ['label' => 'Страны', 'url' => ['/admin/reference?file=country_list']],
                                ['label' => 'Валюты', 'url' => ['/admin/reference?file=currency_list']],
                                ['label' => 'Языки', 'url' => ['/admin/reference?file=language_list']],
                                ['label' => 'Города', 'url' => ['/admin/country-city']],
                            ],
                        ]);
                    ?>
                </li>
                <li>
                    <a href="<?=Url::toRoute('/admin')?>"><i class="fa fa-cubes"></i> <span class="nav-label">Прочее</span> <span class="fa arrow"></span></a>
                    <?php echo Nav::widget([
                            'options' => ['class' => 'nav nav-second-level'],
                            'items' => [
                                ['label' => 'Статические страницы', 'url' => ['/admin/staticpage']],
                            ],
                        ]);
                    ?>
                </li>
                
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">Панель управления</span>
                    </li>
                    <li>
                        <?= LanguageSwitcher::widget(); ?>
                        <?php $this->beginJs(); ?>
                            <script>
                                $(document).ready(function() {
                                    $('.header__select').niceSelect();
                                });
                            </script>
                        <?php $this->endJs(); ?>
                    </li>
                    <li>
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <?= Html::submitButton(
                            '<i class="fa fa-sign-out"></i>&nbsp;Выйти',
                            ['class' => 'btn btn-link logout', 'title' => Yii::$app->user->identity->username]
                        )
                        . Html::endForm() ?>
                    </li>
                    <li>
                        <a class="right-sidebar-toggle">
                            <i class="fa fa-tasks"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5><?= Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?></h5>
                        </div>
                        <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                            <?= Alert::widget() ?>
                            <?= $content ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="float-right">
                    Developer <strong>haiflive</strong>.
                </div>
                <div>
                    <strong>Copyright</strong> developed by <a href="http://2cubes.ru">2cubes</a> &copy; 2019, <?= Yii::powered() ?>
                </div>
            </div>
        </div>
        
    </div>
    <div class="loading" id="loaderWait" style="display:none;">Loading&#8230;</div>

    <!-- Mainly scripts -->
    <script src="/css/admin/js/jquery-3.1.1.min.js"></script>
    <script src="/css/admin/js/popper.min.js"></script>
    <script src="/css/admin/js/bootstrap.js"></script>
    <script src="/css/admin/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/css/admin/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    
    <!-- Custom and plugin javascript -->
    <script src="/css/admin/js/inspinia.js"></script>
    <script src="/css/admin/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="/css/admin/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <script src="/css/admin/js/imageLoader.js"></script>
    
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
