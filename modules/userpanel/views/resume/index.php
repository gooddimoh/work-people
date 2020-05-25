<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ResumeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('resume', 'My resumes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="vacancy-review resumes">
		<div class="vacancy-review__top">
			<div class="title-sec">
				<?= Html::encode($this->title) ?>
			</div>
		</div>
		<hr style="margin-bottom: 0;">
        <?php Pjax::begin(); ?>
        <?php
            echo ListView::widget([
                'layout' => '
                    <!-- {sorter} -->
                    {items}
                    <div class="pagination">{pager}</div>
                ',
                'dataProvider' => $dataProvider,
                'itemView' => '_list',
                'itemOptions' => [
                    'class' => 'block normal-block'
                ],
                'sorter' => [
                    'options' => [
                        'class' => 'list-view-sorter'
                    ],
                    // 'linkOptions' => [
                    //     'class' => ''
                    // ]
                ],
                'pager' => [
                    'options' => [
                        'class' => ''
                    ],
                ]
            ]);
        ?>
        <?php Pjax::end(); ?>
        <? /*
		<div class="vacancy-review__item resumes__item">
			<div class="vacancy-review__main">
				<a href="resume.php" class="vacancy-review__title">
					Разнорабочие на автомобильный завод
				</a>
				<div class="resumes__status">
					Стандартная анкета
				</div>
				<div class="resumes__date">
					Анкета размещена на сайте с 18 апреля 2019
				</div>
				<ul class="vacancy-review__nav resumes__nav">
					<li>
						Обновить дату можно будет через 7 дней
					</li>
					<li><a href="#">Подобрать вакансии</a></li>
					<li>
						<div class="dropdown j-zvon-wrapper">
							<a href="#" class="dropdown__btn j-zvon">Еще</a>
							<div class="dropdown__menu j-zvon-menu">
								<a href="register-candidate-end.php" class="dropdown__item">Редактировать</a>
								<a href="#" class="dropdown__item">Скопировать</a>
								<a href="#" class="dropdown__item">Удалить</a>
								<a href="#" class="dropdown__item">Разместить</a>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="vacancy-review__count">
				<div class="vacancy-review__number resumes__number">
					<div>
						<b>70</b>
						показов
					</div>
					<div class="view">
						<b>10</b>
						просмотров
					</div>
				</div>
				<div class="dropdown j-zvon-wrapper">
					<a href="#" class="vacancy-review__share j-zvon dropdown__btn btn btn--trans-yellow">Поделиться</a>
					<div class="dropdown__menu j-zvon-menu">
						<a href="#" class="dropdown__item">
							Скопировать ссылку
						</a>
						<a href="#" class="dropdown__item">
							Поделиться на Facebook
							<svg class="icon"> 
							    <use xlink:href="img/icons-svg/facebook.svg#icon" x="0" y="0"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="vacancy-review__item resumes__item">
			<div class="vacancy-review__main">
				<a href="resume.php" class="vacancy-review__title">
					Разнорабочие на автомобильный завод
				</a>
				<div class="resumes__status">
					Стандартная анкета
				</div>
				<div class="resumes__date">
					Анкета размещена на сайте с 18 апреля 2019
				</div>
				<ul class="vacancy-review__nav resumes__nav">
					<li>
						Обновить дату можно будет через 7 дней
					</li>
					<li><a href="#">Подобрать вакансии</a></li>
					<li>
						<div class="dropdown j-zvon-wrapper">
							<a href="#" class="dropdown__btn j-zvon">Еще</a>
							<div class="dropdown__menu j-zvon-menu">
								<a href="register-candidate-end.php" class="dropdown__item">Редактировать</a>
								<a href="#" class="dropdown__item">Скопировать</a>
								<a href="#" class="dropdown__item">Удалить</a>
								<a href="#" class="dropdown__item">Разместить</a>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="vacancy-review__count">
				<div class="vacancy-review__number resumes__number">
					<div>
						<b>70</b>
						показов
					</div>
					<div class="view">
						<b>10</b>
						просмотров
					</div>
				</div>
				<div class="dropdown j-zvon-wrapper">
					<a href="#" class="vacancy-review__share j-zvon dropdown__btn btn btn--trans-yellow">Поделиться</a>
					<div class="dropdown__menu j-zvon-menu">
						<a href="#" class="dropdown__item">
							Скопировать ссылку
						</a>
						<a href="#" class="dropdown__item">
							Поделиться на Facebook
							<svg class="icon"> 
							    <use xlink:href="img/icons-svg/facebook.svg#icon" x="0" y="0"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
        </div>
        */ ?>
		<div class="buy-services">
			<a href="<?= Url::to(['/userpanel/resume/create']) ?>" class="btn" style="margin-top: 30px;"> <?= Yii::t('resume', 'Create Resume') ?></a>
		</div>
	</div>

</div>
