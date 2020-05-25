<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/confirm-email', 'token' => $user->email_confirm_token]);
?>
<h1>Подтверждение регистрации пользователя</h1>
<p>Здравствуйте.</p>
<p>Для завершения регистрации, пожалуйста пройдите по <?= Html::a(Html::encode('ссылке'), $resetLink) ?>:</p>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
<p></p>
<p></p>
<p><i>Если это письмо попало к вам по ошибке пожалуйста проигнорируйте его.</i></p>