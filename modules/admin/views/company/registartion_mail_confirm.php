<?php
use yii\helpers\Html;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/register-company-by-email', 'token' => $user->password_reset_token]);
?>

<h1>Активация компании</h1>
<p>Здравствуйте.</p>
<p>Для активации компании и создания пароля, пожалуйста пройдите по <?= Html::a(Html::encode('ссылке'), $resetLink) ?>:</p>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

<p></p>
<p></p>
<p><i>Если это письмо попало к вам по ошибке пожалуйста проигнорируйте его.</i></p>