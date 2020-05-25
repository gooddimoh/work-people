<?php

use yii\helpers\Url;

/* {title}О проекте WWS.WORK{/title} */
$this->title = 'О проекте WWS.WORK';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-block">
    <img src="/img/about/about-bg.png" alt="">
    <div class="about-block__text">
        <h3>О проекте WWS.WORK</h3>
        <p>Онлайн сервис поиска работы за границей</p>
    </div>
<span class="arrow-icon"></span>
</div>

<div class="container">
    <div class="text-block">
        <div class="title-sec">
            Что мы делаем?
        </div>
        <div class="info-text">
            <p class="info-text__content"><strong>WWS.WORK</strong> − это онлайн сервис поиска работы за границей.
                Площадка объединяет работодателей всех размеров, штат
                которых от <strong>3</strong> человек и до <strong>50 000</strong> тыс человек, также, на сайте
                размещают вакансии рекрутинговые агентства и
                есть возможность кандидатам которые ищут работу размещать свою анкету.</p>
        </div>
    </div>
    <div class="text-block">
        <div class="title-sec">
            Зачем мы создали проект?
        </div>
        <div class="info-text">
            <p class="info-text__content"> Наша компания помогала больше <strong>5-ти</strong> лет людям со всего
                <strong>СНГ</strong> выехать на работу в Европу.</p>
            <p class="info-text__content">Мы лично сталкивались каждый день со всеми сложностями, которые человек
                встречает
                на пути к получению хорошей
                работы за границей.</p>
            <p class="info-text__content">Именно по этому, мы собрали весь наш опыт и желание помочь людям и сделали
                максимально удобную площадку, в
                который каждый человек может прочитать реальные отзывы о работодателях, заключить <a href="<?= Url::to(['/site/safe-deal']) ?>"
                                                                                                     class="link">Безопасную
                    сделку</a> и быть
                уверенным, что все условия обещанные работодателем будет правдой, с каждым работодателем можно вести
                переписку и отправлять документы прямо на сайте, каждый человек может посмотреть все вакансии
                работодателя в
                режиме реального времени.</p>
            <p class="info-text__content"> Мы постарались сделать нашу площадку максимально простой и удобной для
                каждого
                человека.</p>
        </div>
    </div>
    <div class="text-block">
        <div class="title-sec">
            Наша миссия
        </div>
        <div class="info-text">
            <p class="info-text__content">Мы хотим помочь каждому человеку получить работу с лучшими условиями и чтобы
                переезд в другую страну на заработки был надежным, чтобы поехав на работу вы были уверены что зарплату
                выплатят и к вам будут относиться по человечески. Мы хотим дать работу миллионам людей со всего СНГ
                которые
                едут на заработки. Это наша главная миссия и мысль, с которой мы живем каждый каждый день. </p>
            <p class="info-text__content">Если вы также разделяете эти ценности и поддерживаете наше стремление -
                присоединяйтесь к нашей дружной семье единомышленников.</p>
        </div>
    </div>
</div>

<div class="bg-grey-front">
    <div class="container">
        <div class="row narrow">
            <p class="title">Выберите себе лучшую работу или найдите лучших работников
            на свои вакансии на WWS.work</p>
            <div class="button-block">
                <a href="<?= Url::to(['/resume/index']) ?>" class="btn">Найти исполнителя</a>
                <a href="<?= Url::to(['/vacancy/index']) ?>" class="btn">Найти работодателя</a></div>
            </div>
    </div>
</div>

<div class="container">
    <div class="partner-block">
        <div class="title-sec">
            Наши партнеры
        </div>
        <div class="row centered">
            <div class="partner">
                <img src="/img/about/partner-1.png" alt="">
            </div>
            <div class="partner">
                <img src="/img/about/partner-2.png" alt="">
            </div>
            <div class="partner">
                <img src="/img/about/partner-3.png" alt="">
            </div>
            <div class="partner">
                <img src="/img/about/partner-4.png" alt="">
            </div>
            <div class="partner">
                <img src="/img/about/partner-5.png" alt="">
            </div>
        </div>
    </div>
</div>
