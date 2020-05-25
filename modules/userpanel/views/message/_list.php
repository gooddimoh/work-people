<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\MessageRoom;

/* @var $this yii\web\View */
/* @var $model app\models\MessageRoom */
/* @var $form yii\widgets\ActiveForm */

$sender_name = $model->sender->company->company_name;
$last_message = $model->getMessages()->orderBy('id DESC')->one();
$last_message_text = '-';
$last_message_date = '-';
if($last_message !== null) {
    $last_message_text = substr(strip_tags($last_message->message_text), 0, 75);
    if(strlen($last_message->message_text) > 75) {
        $last_message_text .= '...';
    }

    $last_message_date = Yii::$app->formatter->format($last_message->created_at, 'date'); //? time zone
}

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
                            <a href="<?= Url::to(['changefavorite', 'id'=>$model->sender_id]) ?>" class="message-all__star">
                                <input type="checkbox" <?= $model->favorite == MessageRoom::FAVORITE_YES ? 'checked' : ''?>>
                                <span class="message-all__star-title"></span>
                            </a>
                            <!-- <label class="message-all__star">
                                <input type="checkbox">
                                <span class="message-all__star-title"></span>
                            </label> -->
                        </div>
                        <div class="message-all__col">
                            <a href="<?= Url::to(['changearchive', 'id'=>$model->sender_id]) ?>" class="message-all__delete"></a>
                        </div>
                    </div>
                </div>			
            </div>	
        </div>	
    </div>
    <a href="<?= Url::to(['view', 'id' => $model->sender_id])?>" class="message-all__col message-all__col--name">
        <div class="message-all__inner">
            <div>
                <b><?= Html::encode($sender_name) ?></b>
            </div>
        </div>
    </a>
    <a href="<?= Url::to(['view', 'id' => $model->sender_id])?>" class="message-all__col message-all__col--message">
        <div class="message-all__inner">
            <div>
                <?= $last_message_text ?>
            </div>
        </div>
    </a>
    <div class="message-all__col message-all__col--date">
        <div class="message-all__inner">
            <div><?= $last_message_date ?></div>
        </div>
    </div>
</div>
