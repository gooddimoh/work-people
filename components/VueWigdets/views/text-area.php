<?php
use yii\helpers\Html;
use \yii\helpers\StringHelper;
$short_class_name = StringHelper::basename(get_class($model));
?>
<?= Html::activeTextarea($model, $attribute, $options); ?>
<div v-show="errors.has('<?= $short_class_name ?>[<?= $attribute ?>]')" class="validation-error-message" v-cloak>
    {{ errors.first('<?= $short_class_name ?>[<?= $attribute ?>]') }}
</div>