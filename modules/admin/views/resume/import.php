<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Resume;
use app\models\Category;
use app\models\CategoryJob;
use yii\helpers\ArrayHelper;
use app\components\VeeValidateHelper;
use app\components\VueWigdets\VueTextInputWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Resume */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('resume', 'Import Excel (*.xls) file');
$this->params['breadcrumbs'][] = ['label' => Yii::t('resume', 'Resumes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/libs/underscore/underscore-min.js');
$this->registerJsFile('/js/app/dist/vue.js');
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager

?>

<div id="appImportExcel" class="resume-form">

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

    <div style="margin-top: 30px;">
        <input type="file" id="excel_file" name="excel_file" ref="excel_file" style="display: none;" v-on:change="selectFile">
        <label class="upload" for="excel_file">
            <div class="btn btn-primary">
                <?= Yii::t('main', 'Select Excel (*.xls) file...') ?>
            </div>
        </label>
        <div class="validation-error-message" v-if="file_format_error" v-cloak><?= Yii::t('resume', 'Invalid file format') ?></div>
        <div class="row" v-cloak>
            <div class="col">
                <button class="btn btn-success" v-if="getSuccessCount > 0 && !import_complete" v-on:click="completeImport">Import into DB [{{getSuccessCount}}] Resume</button>
            </div>
        </div>
        <div class="row" v-if="import_complete" v-cloak>
            <div class="col">
                <span class="text-info">{{getSuccessCount}} <?= Yii::t('resume', 'Resume import complete!') ?></span>&nbsp;
                <a href="<?= Url::toRoute(['index']) ?>"><?= Yii::t('resume', 'Go to resume list') ?></a>
            </div>
        </div>
        <div class="upload__preview">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= Yii::t('resume', 'Stataus') ?></th>
                        <th><?= Yii::t('resume', 'Errors') ?></th>
                        <th><?= Yii::t('resume', 'Data') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) of parse_result">
                        <td>{{index+2}}</td>
                        <td>{{item.success ? 'success' : 'invalid'}}</td>
                        <td>
                            <ul v-for="(error_message_list, field_name) of item.errors">
                                <li v-for="error_message_item of error_message_list" class="validation-error-message">
                                    {{field_name}}: {{error_message_item}}
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <li v-for="(value, field_name) of item.data">
                                    {{field_name}}: {{value}}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php $this->beginJs(); ?>
<script>
new Vue({
  el: '#appImportExcel',
  data: {
    //-------
    response_errors: void 0,
    submit_clicked: false,
    file_format_error: false,
    file_path: '',
    parse_result: [],
    import_complete: false,
  },
  mounted: function() {
    // --
  },
  methods: {
    onSubmit () {
		// lock send form on key 'enter'
      	return; // supress submit
    },
    selectFile() {
        if( this.$refs.excel_file.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let me = this;
        let supported_types = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'/*, 'image/jpeg'*/];
        let regex = new RegExp("(.*?)\.(xls|xlsx)$");
        me.$data.file_format_error = false;

        for (let i = 0; i < this.$refs.excel_file.files.length; i++) {
            let file = this.$refs.excel_file.files[i];
            if ( !(regex.test(file.name.toLowerCase()))
                || supported_types.indexOf(file.type) == -1) {
                me.$data.file_format_error = true;
                return;
            }

            var formData = new FormData();
            formData.append("UploadExcelForm[excel_file]", this.$refs.excel_file.files[0]);

            // find and add '_csrf' to post data
            for(let i = 0; i < this.$refs.form.length; i++) {
                if (this.$refs.form[i].name == '_csrf') {
                    formData.append("_csrf", this.$refs.form[i].value);
                }
            }

            var request = new XMLHttpRequest();
            request.open("POST", window.location.pathname, true);
            request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            request.send(formData);

            me.loaderShow();
            request.onload = function(e) {
                me.loaderHide();
                if (request.status != 200) {
                    me.$data.response_errors = [
                        ["Error " + request.status + " occurred when trying to upload file."],
                        [request.response]
                    ];
                    return; // exit
                }

                let result = JSON.parse(request.response);
                if(!result.success) {
                    me.$data.response_errors = result.errors;
                    return; // exit
                }

                me.$data.import_complete = false; // reset
                me.$data.file_path = result.data.file_path;
                me.$data.parse_result = result.data.parse_result;
            };
        }
    },
    completeImport() {
        let post_data = {
            'file_path': this.$data.file_path,
        };

        // find and add '_csrf' to post data
        for(let i = 0; i < this.$refs.form.length; i++) {
            if (this.$refs.form[i].name == '_csrf') {
                post_data._csrf = this.$refs.form[i].value;
            }
        }

        // send data to server via AJAX POST
		let me = this;
        this.loaderShow();
        $.post( window.location.pathname, post_data)
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                me.loaderHide();

                if(!response.success) {
                    me.$data.response_errors = response.errors;
                    return; // exit
                }

                me.$data.file_path = response.data.file_path;
                me.$data.parse_result = response.data.parse_result;
                me.$data.import_complete = true;
            });
    },
    // --------
    loaderShow() {
        let loaderWaitElement = document.getElementById('loaderWait');
        if(!loaderWaitElement.dataset.countShows) {
            loaderWaitElement.dataset.countShows = 0;
        }

        loaderWaitElement.dataset.countShows++
        loaderWaitElement.style.display = "initial";
    },
    loaderHide() {
        let loaderWaitElement = document.getElementById('loaderWait');
        
        loaderWaitElement.dataset.countShows--;
        if( loaderWaitElement.dataset.countShows <= 0) {
            loaderWaitElement.dataset.countShows = 0;
            loaderWaitElement.style.display = "none";
        }
    },
  },
  computed: {
    getSuccessCount: function() {
        // get count of success records
        if(this.$data.parse_result.length == 0) {
            return 0;
        }

        let success_list = _.filter(this.$data.parse_result, function(p) {
            return p.success;
        })

        return success_list.length;
    },
  }
})
</script>
<?php $this->endJs(); ?>
