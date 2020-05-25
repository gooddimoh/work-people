<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\AutoMail;

/* @var $this yii\web\View */
/* @var $model app\models\AutoMail */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="vacancy-review__item resumes__item">
    <div class="vacancy-review__main">
        <a href="resume.php" class="vacancy-review__title">
            <?= $model->request ?>, <?= $model->country_codes ?>
        </a>
        <div class="resumes__status">
            Стандартная анкета
        </div>
        <div class="resumes__date">
            Рассылается
        </div>
        <ul class="vacancy-review__nav resumes__nav">
            <li><a href="#">Проверить результаты</a></li>
            <li><a href="<?= Url::to(['view', 'id' => $model->id]) ?>">Редактировать</a></li>
            <li><a href="#">Удалить</a></li>
        </ul>
    </div>
</div>
<div class="buy-services">
    <a href="#" class="btn" style="margin-top: 30px;">Проверить результат</a>
</div>