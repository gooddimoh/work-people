<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="container">
  <div class="safe-request site-error">

    <div class="title-sec"><?= Html::encode($this->title) ?></div>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        В результате выполнения вашего запроса возникла ошибка.
    </p>
    <p>
        Пожалуйста, свяжитесь с нами, если считаете, что это ошибка сервера. Спасибо.
    </p>

  </div>
</div>
