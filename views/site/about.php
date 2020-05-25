<?php

use yii\helpers\Url;

/* {title}About WWS.WORK{/title} */
$this->title = 'About WWS.WORK';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-block">
    <img src="/img/about/about-bg.png" alt="">
    <div class="about-block__text">
        <h3>About WWS.WORK</h3>
        <p>Online job search service abroad</p>
    </div>
<span class="arrow-icon"></span>
</div>

<div class="container">
    <div class="text-block">
        <div class="title-sec">
            What are we doing?
        </div>
        <div class="info-text">
            <p class="info-text__content"><strong>WWS.WORK</strong> − it's an online job search service abroad.
                The platform unites employers of all sizes, the staff of
                which from <strong>3</strong> people and up <strong>50 000</strong> thousand people, also on the site
                place vacancies recruiting agencies and
                there is an opportunity for candidates who are looking for work to post their resume.</p>
        </div>
    </div>
    <div class="text-block">
        <div class="title-sec">
            Why did we create a project?
        </div>
        <div class="info-text">
            <p class="info-text__content"> Our company has helped more <strong>5th</strong> years old people from all over
                <strong>CIS</strong> go to work in Europe.</p>
            <p class="info-text__content">We personally faced every day all the difficulties that people meets on the way to getting good work abroad.</p>
            <p class="info-text__content">That’s why we gathered all our experience and desire to help people and did the most convenient platform in
                 which everyone can read real reviews about employers, conclude <a href="<?= Url::to(['/site/safe-deal']) ?>" class="link">Safe deal</a>
                 and be sure that all conditions promised by the employer will be true, with each employer you can keep
                 correspondence and send documents directly on the site, everyone can see all the vacancies employer in real time.</p>
            <p class="info-text__content"> We tried to make our site as simple and convenient as possible. each person.</p>
        </div>
    </div>
    <div class="text-block">
        <div class="title-sec">
            Our mission
        </div>
        <div class="info-text">
            <p class="info-text__content">We want to help everyone get a better job and to
                Relocation to another country for work was reliable, so that when you go to work you are sure that your salary
                pay and you will be treated humanly. We want to give work to millions of people from all over the CIS
                which are
                go to work. This is our main mission and thought with which we live every day. </p>
            <p class="info-text__content">If you also share these values and support our aspiration -
                join our friendly family of like-minded people.</p>
        </div>
    </div>
</div>

<div class="bg-grey-front">
    <div class="container">
        <div class="row narrow">
            <p class="title">Choose your best job or find the best employees
                to your jobs on WWS.work</p>
            <div class="button-block">
                <a href="<?= Url::to(['/resume/index']) ?>" class="btn">Find Worker</a>
                <a href="<?= Url::to(['/vacancy/index']) ?>" class="btn">Find Employer</a></div>
            </div>
    </div>
</div>

<div class="container">
    <div class="partner-block">
        <div class="title-sec">
            Our partners
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
