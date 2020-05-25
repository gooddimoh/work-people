<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\LanguageSwitcher;
use app\models\Message;
use app\models\Notification;
use app\models\VacancyRespond;
use app\models\User;

$message_count = 0;
if(!Yii::$app->user->isGuest) {
    $message_count = Message::find()->where([
        'for_user_id' => Yii::$app->user->id,
        'status' => Message::STATUS_UNREADED,
    ])->count();
}

if(!Yii::$app->user->isGuest) {
    $notifications = Yii::$app->user->identity->getNotifications()->limit(5)->orderBy('id DESC')->all();
    $new_notification_count = Yii::$app->user->identity->getNotifications()->where(['status' => Notification::STATUS_UNREADED])->count();
}

$session = Yii::$app->session;
$user_type = $session->get('interface_for');
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
	<title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <?php if ($_SERVER['HTTP_HOST'] != 'wwswork.local'): // debug mode for dev localhost ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TCXN336');</script>
        <!-- End Google Tag Manager -->
    <?php endif ?>

    <?php echo $this->render('styles'); ?>
    <?php $this->head() ?>
</head>
<body>
<?php if ($_SERVER['HTTP_HOST'] != 'wwswork.local'): // debug mode for dev localhost ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TCXN336"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif ?>
<?php $this->beginBody() ?>
    <div class="header-relative <?php echo (!Yii::$app->user->isGuest ? 'header-relative--login' : ''); ?>">
        <div class="j-header header-fixed">
            <header class="header<?php if($user_type == 'employer') { ?> header--employer<?php } ?>">
                <div class="container">
                    <div class="row">
                        <div class="header__col">
                            <a href="<?= Url::to(['/']); ?>" class="header__logo">
                                <img src="/img/global/work_people_logo_003.png" alt="">
                            </a>
                            <ul class="header__menu">
                                <?php if(Yii::$app->user->isGuest) { ?>
                                    <?php /*
                                    <li>
                                        <a href="<?= Url::to(['/company-review/index']) ?>" class="active"><?= Yii::t('site', 'Company reviews') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/site/safe-deal']) ?>" class="header__safe">
                                            <span></span>
                                            <div><?= Yii::t('main', 'Safe deal') ?></div>
                                        </a>
                                    </li>
                                    */ ?>
                                <?php } else { ?>
                                    <?php if($user_type == 'employer') { ?>
                                    <li>
                                        <a href="<?= Url::to(['/userpanel/likes']); ?>" class="header__like">
                                            <span></span> <div><?= Yii::t('main', 'Favorite') ?></div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/resume/search']) ?>" class="header__search">
                                            <span></span> <div><?= Yii::t('main', 'Find resume') ?></div>
                                        </a>
                                    </li>
                                    <?php } else { ?>
                                        <li>
                                            <a href="<?= Url::to(['/userpanel/likes-candidate']); ?>" class="header__like">
                                                <span></span> <div><?= Yii::t('main', 'Favorite') ?></div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?= Url::to(['/vacancy/index']) ?>" class="header__search">
                                                <span></span> <div><?= Yii::t('site', 'Find a job') ?></div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="header__border"></div>
                        <div class="header__col header__col--2">
                            <?php if(!Yii::$app->user->isGuest) { ?>
                                <a href="<?= Url::to(['/userpanel']); ?>" class="header__profile">
                                    <span></span> <div><?= Yii::t('main', 'My Profile') ?></div>
                                </a>
                            <?php } ?>
                            <?php if($user_type == 'employer') { ?>
                                <a href="<?= Url::to(['/userpanel/vacancy/create']) ?>" class="header__add">
                                    <span></span> <div><?= Yii::t('main', 'Post Vacancy') ?></div>
                                </a>
                                <?php if(Yii::$app->user->isGuest || Yii::$app->user->identity->role === User::ROLE_ADMINISTRATOR) { ?>
                                    <a href="<?= Url::to(['/site/for-candidate']) ?>" class="header__login">
                                        <?= Yii::t('main', 'For candidates') ?>
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <a href="<?= Url::to(['/userpanel/resume/create']) ?>" class="header__add">
                                    <span></span> <div><?= Yii::t('site', 'Post resume') ?></div>
                                </a>
                                <?php if(Yii::$app->user->isGuest || Yii::$app->user->identity->role === User::ROLE_ADMINISTRATOR) { ?>
                                    <a href="<?= Url::to(['/site/for-employer']) ?>" class="header__login">
                                        <?= Yii::t('main', 'For employers') ?>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                            <?php if(!Yii::$app->user->isGuest) { ?>
                                <?php /*
                                    echo Html::beginForm(['/site/logout'], 'post')
                                    . Html::submitButton(
                                        Yii::t('main', 'Logout'),
                                        ['class' => 'header__login header-logout-btn', 'title'=>Yii::$app->user->identity->username]
                                    )
                                    . Html::endForm()
                                    */
                                ?>
                            <?php } else { ?>
                                <a href="<?= Url::to(['/site/login']); ?>" class="header__login">
                                    <?= Yii::t('main', 'Login') ?>
                                </a>
                            <?php } ?>
                            <?= LanguageSwitcher::widget(); ?>
                        </div>
                        <div class="header__mobile-row">
                            <?php if(!Yii::$app->user->isGuest) { ?>
                                <div class="info__menu">
                                    <div class="info__zvon-wrap j-zvon-wrapper">
                                        <div class="info__zvon info__link j-zvon">
                                            <span><?= $new_notification_count ?></span>
                                            <div><?= Yii::t('site', 'Alerts') ?></div>
                                        </div>
                                        <div class="info__zvon-menu j-zvon-menu">
                                            <?php foreach ($notifications as $notification): ?>
                                                <div class="info__zvon-item">
                                                    <div class="info__zvon-title">
                                                        <a href="<?= Url::to(['/userpanel/notification/view', 'id' => $notification->id]) ?>"><?= Html::encode($notification->title) ?></a>
                                                    </div>
                                                    <!-- <a href="#" class="info__zvon-img">
                                                        <img src="/img/header/person.png" alt="">
                                                    </a>
                                                    <div class="info__zvon-title">
                                                        <a href="#">Nickname</a> откликнулся на вакансию <a href="#">Руководитель отдела дизайна и упаковки</a>
                                                    </div> -->
                                                </div>
                                            <?php endforeach; ?>
                                            <div class="info__zvon-item">
                                                <div class="info__zvon-title">
                                                    <a href="<?= Url::to(['/userpanel/notification']) ?>"><?= Yii::t('message', 'See all notifications') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= Url::to(['/userpanel/message']) ?>" class="info__message info__link">
                                        <span><?= $message_count ?></span>
                                        <div><?= Yii::t('site', 'Messages') ?></div>
                                    </a>
                                    <a href="#" class="info__like info__link">
                                        
                                    </a>
                                </div>
                            <?php } else { ?>
                                <div class="info__menu">
                                    <div class="info__zvon-wrap j-zvon-wrapper">
                                        <?php if($user_type == 'employer') { ?>
                                            <a href="<?= Url::to(['/userpanel/vacancy/create']) ?>" class="header__add header-add-mobile-guest">
                                                <span></span> <div><?= Yii::t('main', 'Post Vacancy') ?></div>
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= Url::to(['/userpanel/resume/create']) ?>" class="header__add header-add-mobile-guest">
                                                <span></span> <div><?= Yii::t('site', 'Post resume') ?></div>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
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
                        <?php if(Yii::$app->user->isGuest) { ?>
                            <li>
                                <a href="<?= Url::to(['/site/login']); ?>">
                                    <div class="avatar">
                                        <div><?= Yii::t('main', 'Login') ?></div>
                                    </div>
                                </a>
                            </li>
                        <?php } else { ?>
                            <li>
                                <a href="<?= Url::to(['/userpanel']); ?>">
                                    <div class="avatar">
                                        <div class="img">
                                            <img src="/img/header/person.png" alt="">
                                        </div>
                                        <div><?= Yii::t('main', 'My Profile') ?></div>
                                    </div>
                                </a>
                            </li>
                            <?php if($user_type == 'employer') { ?>
                                <li>
                                    <a href="<?= Url::to(['/userpanel/vacancy-respond']) ?>">
                                        <div><?= Yii::t('site', 'Sent invitations') ?></div>
                                        <span class="number"><?= Yii::$app->user->identity->getVacancyRespondCount() ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/userpanel/vacancy-respond']) ?>">
                                        <div><?= Yii::t('site', 'Confirmed') ?></div>
                                        <span class="number"><?= Yii::$app->user->identity->getVacancyRespondAcceptedCount() ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Url::to(['/userpanel/vacancy']) ?>">
                                        <div><?= Yii::t('site', 'Wants to work') ?></div>
                                        <span class="number"><?= Yii::$app->user->identity->getWantToWorkCount() ?></span>
                                    </a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <a href="">
                                        <div><?= Yii::t('site', 'Resume sent') ?></div>
                                        <span class="number">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div><?= Yii::t('site', 'Confirmed') ?></div>
                                        <span class="number">0</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <div><?= Yii::t('site', 'Invitations Received') ?></div>
                                        <span class="number">0</span>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                    <ul class="header__menu header__menu--grey">
                        <?php if($user_type == 'employer') { ?>
                            <li>
                                <a href="<?= Url::to(['/resume/search']) ?>"><?= Yii::t('main', 'Find resume') ?></a>
                            </li>
                            <?php /*
                            <li>
                                <a href="<?= Url::to(['/site/safe-deal']) ?>">
                                    <?= Yii::t('main', 'Safe deal') ?>
                                </a>
                            </li>
                            */ ?>
                            <?php if(Yii::$app->user->isGuest || Yii::$app->user->identity->role === User::ROLE_ADMINISTRATOR) { ?>
                                <li>
                                    <a href="<?= Url::to(['/site/for-candidate']) ?>">
                                        <?= Yii::t('main', 'For candidates') ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li>
                                <a href="<?= Url::to(['/vacancy/index']) ?>"><?= Yii::t('site', 'Find a job') ?></a>
                            </li>
                            <?php /*
                            <li>
                                <a href="<?= Url::to(['/site/safe-deal']) ?>">
                                    <?= Yii::t('main', 'Safe deal') ?>
                                </a>
                            </li>
                            */ ?>
                            <?php if(Yii::$app->user->isGuest || Yii::$app->user->identity->role === User::ROLE_ADMINISTRATOR) { ?>
                                <li>
                                    <a href="<?= Url::to(['/site/for-employer']) ?>">
                                        <?= Yii::t('main', 'For employers') ?>
                                    </a>
                                </li>
                            <?php } ?>
                        <?php } ?>
                        <?php if(!Yii::$app->user->isGuest) { ?>
                            <!-- <li><a href="#" class="exit">Выход</a></li> -->
                            <li>
                                <?= Html::beginForm(['/site/logout'], 'post')
                                    . Html::submitButton(
                                        Yii::t('main', 'Logout'),
                                        ['class' => 'header__login header-logout-btn', 'title'=>Yii::$app->user->identity->username]
                                    )
                                    . Html::endForm()
                                ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </header>
            <?php if(!Yii::$app->user->isGuest) { ?>
                <div class="info">
                    <div class="container">
                        <div class="row">
                            <div class="info__menu col">
                                <div class="info__zvon-wrap j-zvon-wrapper">
                                    <div class="info__zvon info__link j-zvon">
                                        <span><?= $new_notification_count ?></span>
                                        <div><?= Yii::t('site', 'Alerts') ?></div>
                                    </div>
                                    <div class="info__zvon-menu j-zvon-menu">
                                        <?php foreach($notifications as $notification): ?>
                                            <div class="info__zvon-item">
                                                <div class="info__zvon-title">
                                                    <a href="<?= Url::to(['/userpanel/notification/view', 'id' => $notification->id]) ?>"><?= Html::encode($notification->title) ?></a>
                                                </div>
                                                <!-- <a href="#" class="info__zvon-img">
                                                    <img src="/img/header/person.png" alt="">
                                                </a>
                                                <div class="info__zvon-title">
                                                    <a href="#">Nickname</a> откликнулся на вакансию <a href="#">Руководитель отдела дизайна и упаковки</a>
                                                </div> -->
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="info__zvon-item">
                                            <div class="info__zvon-title">
                                                <a href="<?= Url::to(['/userpanel/notification']) ?>"><?= Yii::t('message', 'See all notifications') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= Url::to(['/userpanel/message']) ?>" class="info__message info__link">
                                    <span><?= $message_count ?></span>
                                    <div><?= Yii::t('site', 'Messages') ?></div>
                                </a>
                            </div>
                            <div class="info__border"></div>
                            <div class="come-work col">
                                <?php if($user_type == 'employer') { ?>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                        <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><b><?= Yii::t('site', 'Sent invitations') ?></b></a>
                                        </span>
                                        <div class="come-work__number">
                                            <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><?= Yii::$app->user->identity->getVacancyRespondCount() ?></a>
                                        </div>
                                    </div>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                            <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><?= Yii::t('site', 'Confirmed') ?></a>
                                        </span>
                                        <div class="come-work__number">
                                            <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><?= Yii::$app->user->identity->getVacancyRespondAcceptedCount() ?></a>
                                        </div>
                                    </div>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                            <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><?= Yii::t('site', 'Wants to work') ?></a>
                                        </span>
                                        <div class="come-work__number">
                                            <a href="<?= Url::to(['/userpanel/vacancy']) ?>"><?= Yii::$app->user->identity->getWantToWorkCount() ?></a>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                            <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_NEW]) ?>"><b><?= Yii::t('site', 'Resume sent') ?></b></a>
                                        </span>
                                        <div class="come-work__number">
                                            <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_NEW]) ?>"><?= Yii::$app->user->identity->getResumeSentCount() ?></a>
                                        </div>
                                    </div>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                            <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_ACCEPTED]) ?>"><?= Yii::t('site', 'Confirmed') ?></a>
                                        </span>
                                        <div class="come-work__number">
                                            <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_ACCEPTED]) ?>"><?= Yii::$app->user->identity->getResumeConfirmedCount() ?></a>
                                        </div>
                                    </div>
                                    <div class="come-work__td">
                                        <span class="come-work__title">
                                            <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_INVITED]) ?>"><?= Yii::t('site', 'Invitations Received') ?></a>
                                        </span>
                                        <div class="come-work__number">
                                           <a href="<?= Url::to(['/userpanel/vacancy-respond', 'VacancyRespondSearch[status]' => VacancyRespond::STATUS_INVITED]) ?>"><?= Yii::$app->user->identity->getResumeInvitedCount() ?></a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php if(
    ($user_type != 'employer' && Yii::$app->controller->id == 'site' && Yii::$app->controller->action->id == 'index')
    || (Yii::$app->controller->id == 'vacancy' && Yii::$app->controller->action->id == 'index')
): ?>
<a href="<?= Url::to(['/userpanel/auto-mail']) ?>" class="btn fixed-subscribe">
    <?= Yii::t('site', 'Receive vacancies first') ?>
</a>
<?php endif ?>