<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\MessageRoom;

/* @var $this yii\web\View */
/* @var $model app\models\MessageRoom */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="message-all__row">
    <div class="message-all__col message-all__col--check">		
        <div class="message-all__row">
            <div class="message-all__col">
                <div class="message-all__inner">
                    <label class="checkbox">
                        <input type="checkbox" class="j-check-all-prop">
                        <span class="checkbox__check"></span>
                    </label>
                </div>
            </div>
            <div class="message-all__col message-all__col--check-bl">
                <div class="message-all__inner">
                    <div class="message-all__row">
                        <div class="message-all__col">
                            <!-- need ajax request -->
                              <!-- <label class="message-all__star">
                                <input type="checkbox">
                                <span class="message-all__star-title"></span>
                            </label> -->
                        </div>
                        <div class="message-all__col">
                            <!-- <a href="<?= Url::to(['delete', 'id' => $model->id]) ?>" class="message-all__delete"></a> -->
                            <?= Html::a('', ['delete', 'id' => $model->id], [
                                'class' => 'message-all__delete',
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                            ]) ?>
                        </div>
                    </div>
                </div>			
            </div>	
        </div>	
    </div>
    <a href="<?= Url::to(['view', 'id' => $model->id])?>" class="message-all__col message-all__col--message">
        <div class="message-all__inner">
            <div>
                <?= $model->title ?>
            </div>
        </div>
    </a>
    <div class="message-all__col message-all__col--date">
        <div class="message-all__inner">
            <div><?= Yii::$app->formatter->format($model->created_at, 'date') ?></div>
        </div>
    </div>
</div>
