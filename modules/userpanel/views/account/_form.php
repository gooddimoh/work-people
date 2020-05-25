<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerJsFile('/js/app/dist/vue.js');
$has_errors = !empty($model->getErrors());

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edit-info" id="appEditAccount">

    <?php $form = ActiveForm::begin(); ?>

    <div class="table">
        <div class="table__tr">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('login') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'login', ['inputOptions' => [
                        'class' => 'form-control',
                        'disabled' => true,
                    ]])->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('email') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'email', ['inputOptions' => [
                        'class' => 'form-control',
                        'disabled' => true,
                    ]])->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr">
            <div class="table__td table__td--first">
                <?= Yii::t('user', 'Current password') ?>
                (<a id="edit-password-link" style="cursor:pointer;" v-on:click="showChangePassword"><?= Yii::t('main', 'Edit') ?></a>)
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'password')->passwordInput([
                        'maxlength' => true
                    ])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr" v-bind:style="{ display: dispaly_edit_password_fields }">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('password_new') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'password_new')->passwordInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr" v-bind:style="{ display: dispaly_edit_password_fields }">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('password_repeat') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('username') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
        <div class="table__tr">
            <div class="table__td table__td--first">
                <?= $model->getAttributeLabel('phone') ?>
            </div>
            <div class="table__td">
                <div class="edit-info__input">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('main', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php $this->beginJs(); ?>
<script>
new Vue({
  el: '#appEditAccount',
  data: {
    dispaly_edit_password_fields: '<?= $has_errors ? 'flex' : 'none' ?>'
  },
  methods: {
    showChangePassword: function() {
        this.$data.dispaly_edit_password_fields = 'flex'
    }
  }
})
</script>
<?php $this->endJs(); ?>