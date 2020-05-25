<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageRoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('message', 'Messages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_header') ?>

<div class="message-all">
    <div class="message-all__top">
        <div class="container">
            <div class="message-all__row">
                <div class="message-all__col message-all__col--check">
                    <div class="message-all__inner">
                        <label class="checkbox">
                            <input type="checkbox" class="j-check-all">
                            <span class="checkbox__check"></span>
                        </label>
                    </div>					
                </div>
                <div class="message-all__col message-all__col--name">
                    <div class="message-all__inner">
                        <div><?=Yii::t('message', 'User') ?></div>
                    </div>
                </div>
                <div class="message-all__col message-all__col--message">
                    <div class="message-all__inner">
                        <div><?= Yii::t('message', 'Message') ?></div>
                    </div>
                </div>
                <div class="message-all__col message-all__col--date">
                    <div class="message-all__inner">
                        <div><?= Yii::t('message', 'Date') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="message-all__bottom">
        <div class="container">
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
                        'class' => 'message-all__item'
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
        </div>
    </div>
</div>

