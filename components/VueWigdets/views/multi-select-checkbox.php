<?php
use yii\helpers\Html;
use \yii\helpers\StringHelper;
$short_class_name = StringHelper::basename(get_class($model));
?>
<?= Html::activeInput('hidden', $model, $attribute, $options); ?>

<!-- <input v-validate="vv.model_vacancy.gender_list" name='Vacancy[gender_list]' type="hidden" v-model="vacancy_info.edit_data.gender_list"> -->
<span v-show="errors.has('<?= $short_class_name ?>[<?= $attribute ?>]')" class="validation-error-message" v-cloak>
    {{ errors.first('<?= $short_class_name ?>[<?= $attribute ?>]') }}
</span>

<div class="registration__row">
    <label v-for="item in <?= $items ?>" class="checkbox" v-cloak>
        <input type="checkbox" v-model="item.checked" <?= $checkBoxAttributes ?>>
        <span class="checkbox__check"></span>
        <span class="checkbox__title">
            {{item.name}}
        </span>
    </label>
</div>