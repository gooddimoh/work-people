<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Vacancy;
use app\models\Category;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $data_list array */
/* @var $data_field_list array */

$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/js/app/dist/vue.js');
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
?>

<div id="appReference" class="reference-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => [
            'class' => '',
            'v-on:submit.prevent' => "onSubmit",
            'ref' => 'form'
        ]
    ]); ?>

    <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
        <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
        <ul v-for="(error_messages, field_name) of response_errors">
            <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
        </ul>

        <ul v-if="submit_clicked && errors.all()">
            <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
        <ul>
    </div>

    <div class="form-group">
        <button class="btn btn-success save-files" v-on:click="referenceInfoSave">Сохранить</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="50px;">#</th>
                <th v-for="field_key in data_field_list">{{field_key}}</th>
                <th width="100px;"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(data_item, index) in data_list">
                <th>{{index+1}}</th>
                <td v-for="field_key in data_field_list">
                    <input type="text" class="form-control input-sm" v-model="data_list[index][field_key]">
                </td>
                <td>
                    <div class="input-group-append">
                        <a href="javascript:;" class="text-success pull-right" v-on:click="insertBeforeRow(index)" title="Вставить строку">
                            <span class="glyphicon glyphicon glyphicon-plus delete-param">&nbsp;</span>
                        </a>
                        <a href="javascript:;" class="text-danger pull-right" v-on:click="removeRow(index)">
                            <span class="glyphicon glyphicon glyphicon-remove delete-param">&nbsp;</span>
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="form-group">
        <button class="btn btn-success add-string" v-on:click="insertBeforeRow(data_list.length)">Добавить строку</button>
        <button class="btn btn-success save-files" v-on:click="referenceInfoSave">Сохранить</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php $this->beginJs(); ?>
<script>
Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'validation-error-input',
    },
    events: 'change|blur|keyup'
});

new Vue({
  el: '#appReference',
  data: {
    data_field_list: <?= json_encode($data_field_list) ?>,
    data_list: <?= json_encode($data_list) ?>,
    //-------
    response_errors: void 0,
    submit_clicked: false,
    reference: {
    },
  },
  mounted: function() {
    
  },
  methods: {
    onSubmit () {
		// lock send form on key 'enter'
      	return; // supress submit
    },
    sendFieldsToServer: function(post_data, cb) {
        // find and add '_csrf' to post data
        for (let i = 0; i < this.$refs.form.length; i++) {
            if (this.$refs.form[i].name == '_csrf') {
                post_data._csrf = this.$refs.form[i].value;
            }
        }

        // send data to server via AJAX POST
		let me = this;
        this.loaderShow();
        $.post( window.location.href, post_data )
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                me.loaderHide();
                if (response.success === false && response.errors) {
                    // insert errors
                    me.$data.response_errors = response.errors;
                    // scroll to top
                    me.scrollToErrorblock();
                } else {
                    cb(response);
                }
            });
    },
    referenceInfoSave: function() {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            // -- collect data
            let post_data = {};
            for (let i = 0; i < this.$data.data_list.length; i++) {
                for (let j = 0; j < this.$data.data_field_list.length; j++) {
                    let fileld_name = this.$data.data_field_list[j];
                    post_data['Reference['+i+']['+ fileld_name +']'] = this.$data.data_list[i][fileld_name];
                }
            }

			// find and add '_csrf' to post data
			for (let i = 0; i < this.$refs.form.length; i++) {
				if (this.$refs.form[i].name == '_csrf') {
					post_data._csrf = this.$refs.form[i].value;
				}
			}

            // send data to server via AJAX POST
			let me = this;
			this.sendFieldsToServer(
				post_data,
				function(data) {
                    me.$data.submit_clicked = false;
                    if (data.success) {
                        alert('Success!');
                    } else if(data.responseText) {
                        alert(data.responseText);
                    }
				}
			);
        });
    },
    insertBeforeRow: function(index) {
        let new_item = {};
        for (let i = 0; i < this.$data.data_field_list.length; i++) {
            new_item[this.$data.data_field_list[i]] = '';
        }

        this.$data.data_list.splice(index, 0, new_item);
    },
    removeRow: function(index) {
        this.$data.data_list.splice(index, 1);
    },
    // --------
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
            }, 600);
        });
    },
    loaderShow() {
        let loaderWaitElement = document.getElementById('loaderWait');
        if (!loaderWaitElement.dataset.countShows) {
            loaderWaitElement.dataset.countShows = 0;
        }

        loaderWaitElement.dataset.countShows++
        loaderWaitElement.style.display = "initial";
    },
    loaderHide() {
        let loaderWaitElement = document.getElementById('loaderWait');
        
        loaderWaitElement.dataset.countShows--;
        if ( loaderWaitElement.dataset.countShows <= 0) {
            loaderWaitElement.dataset.countShows = 0;
            loaderWaitElement.style.display = "none";
        }
    },
  }
})
</script>
<?php $this->endJs(); ?>
