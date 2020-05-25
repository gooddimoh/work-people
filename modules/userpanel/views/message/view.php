<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Message;
use app\components\VeeValidateHelper;
use yii\widgets\ActiveForm;
use yii\helpers\HtmlPurifier;

/* @var $this yii\web\View */
/* @var $model app\models\MessageRoom */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJsFile('/libs/ckeditor5/ckeditor.js'); //! BUG, need setup asset manager
$this->registerJsFile('/libs/ckeditor5-vue/dist/ckeditor.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager

$sender_name = $model->sender->company->company_name;
$messages = $model->getMessages()->limit(100)->orderBy('id ASC')->all();

$this->title = $sender_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('message', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?php echo $this->render('_header') ?>

<div class="message-all message" id="appMessage">
    <div class="message__top">
        <div class="container">
            <div class="message-all__row message__top-row">
                <div class="message-all__col">
                    <a href="<?= Url::to(['index']) ?>" class="link"><?=Yii::t('message', 'Back')?></a>
                </div>
                <div class="message-all__col">
                    <div class="message-all__row message__top-row">
                        <a href="<?= Url::to(['changefavorite']) ?>" class="message-all__star"><span class="message-all__star-title" style="float: left;"></span> Отметить сообщение</a>
                        <!-- <label class="message-all__star message__flex">
                            <input type="checkbox">
                            <span class="message-all__star-title"></span>
                            <span>Отметить сообщение</span>
                        </label> -->
                        <a href="<?= Url::to(['changearchive']) ?>" class="message__delete message__flex">
                            <span class="message__delete-icon"></span>
                            <span><?= Yii::t('message', 'Move to archive') ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="message-box">
        <div class="container">
            <div class="hr"></div>
            <div class="message-box__inner scrollbar-inner">
                <span v-if="!message_list.length" v-cloak><?=Yii::t('message', 'no messages') ?></span>
                <div v-for="message_item in message_list" v-cloak>
                    <!-- owner messages -->
                    <div class="message-box__item message-box__item--my" v-if="message_item.owner_id == <?= Yii::$app->user->id ?>">
                        <div class="message-box__text">
                            <div class="message-box__top">
                                <div class="message-box__name">
                                    <?= Yii::t('message', 'Your message') ?>
                                    <span class="yellow" v-if="message_item.device_type == <?= Message::DEVICE_TYPE_MOBILE ?>"><?= Yii::t('message', 'Sent from phone') ?></span>
                                    <!-- ??? -->
                                    <div class="icon">
                                        <span></span>
                                    </div>
                                </div>
                                <div class="message-box__date">
                                    {{dateFormat(message_item.created_at)}}
                                </div>
                            </div>
                            <div class="message-box__desc" v-html="message_item.message_text"></div>
                            <div class="upload__preview" v-if="message_item.attachments">
                                <div v-for="(attachment_item, index) of message_item.attachments" v-bind:class="{ 'upload__item--text': !isImage(attachment_item.name)}">
                                    <a :href="'/userpanel/attachment/download/' + attachment_item.id" v-if="isImage(attachment_item.name)" class="single__gallery-img">
                                        <img :src="'/userpanel/attachment/download/' + attachment_item.id" :alt="attachment_item.name">
                                    </a>
                                    <a :href="'/userpanel/attachment/download/' + attachment_item.id" v-if="!isImage(attachment_item.name)" class="single__gallery-img upload__item--text">
                                        <span v-if="!isImage(attachment_item.name)">{{attachment_item.name}} ({{formatFileSize(attachment_item.size)}})</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- sender messages -->
                    <div class="message-box__item" v-if="message_item.owner_id != <?= Yii::$app->user->id ?>">
                        <div class="message-box__img">
                            <img src="/img/rezume/photo.jpg" alt="">
                        </div>
                        <div class="message-box__text">
                            <div class="message-box__top">
                                <div class="message-box__name">
                                    <?= Html::encode($sender_name) ?>
                                </div>
                                <div class="message-box__date">
                                    {{dateFormat(message_item.created_at)}}
                                </div>
                            </div>
                            <div class="message-box__desc" v-html="message_item.message_text"></div>
                            <div class="upload__preview" v-if="message_item.attachments">
                                <div v-for="(attachment_item, index) of message_item.attachments" v-bind:class="{ 'upload__item--text': !isImage(attachment_item.name)}">
                                    <a :href="'/userpanel/attachment/download/' + attachment_item.id" v-if="isImage(attachment_item.name)" class="single__gallery-img">
                                        <img :src="'/userpanel/attachment/download/' + attachment_item.id" :alt="attachment_item.name">
                                    </a>
                                    <a :href="'/userpanel/attachment/download/' + attachment_item.id" v-if="!isImage(attachment_item.name)" class="single__gallery-img upload__item--text">
                                        <span v-if="!isImage(attachment_item.name)">{{attachment_item.name}} ({{formatFileSize(attachment_item.size)}})</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end new messages -->
                <?php  /*
                <div class="message-box__item message-box__item--my" id="last-message">
                    <div class="message-box__text">
                        <div class="message-box__top">
                            <div class="message-box__name">
                                Ваше сообщение
                                <span class="yellow">Отправлено с телефона</span>
                                <div class="icon">
                                    <span></span>
                                </div>	
                            </div>
                            <div class="message-box__date">
                                17.05.2019  12:46
                            </div>
                        </div>
                        <div class="message-box__desc">
                            Задача организации, в особенности же постоянный количественный рост и сфера нашей активности обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Таким образом сложившаяся структура организации обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий.
                        </div>
                    </div>
                </div>
                */ ?>
            </div>
            <div class="container response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
                <ul v-for="(error_messages, field_name) of response_errors">
                    <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                </ul>

                <ul v-if="submit_clicked && errors.all()">
                    <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                <ul>
            </div>
            <?php $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    'options' => [
                        'class' => 'message-form',
                        'v-on:submit.prevent' => "onSubmit",
                        'ref' => 'form'
                    ]
                ]); ?>
                <div class="message-form__textarea">
                    <ckeditor :editor="editor" v-model="message.message_text" :config="editor_config"></ckeditor>
                    <input v-validate="vv.model_message.message_text" name='Message[message_text]' type="hidden" v-model="message.message_text">
                    <span v-show="submit_clicked && errors.has('Message[message_text]')" class="validation-error-message" v-cloak>
                        {{ errors.first('Message[message_text]') }}
                    </span>
                    <div class="message-form__textarea-row j-input">
                        <div class="message-form__col">
                            <div class="upload-files-in-progress" v-if="uploading_count_files_in_progress" v-cloak>uploading...</div>
                            <div class="upload__preview" v-cloak>
                                <div v-for="(attachment_item, index) of message.attachments" class="upload__item" v-bind:class="{ 'upload__item--text': !isImage(attachment_item.name) }" v-on:click="removeAttachment(index)">
                                    <img :src="'/userpanel/attachment/download/' + attachment_item.id" :alt="attachment_item.name" v-if="isImage(attachment_item.name)">
                                    <span v-if="!isImage(attachment_item.name)">{{attachment_item.name}}</span>
                                </div>
                            </div>
                            <label class="message-form__add-file link" for="attachments">
                                <input type="file" ref="attachments" name="attachments" id="attachments" style="display: none;" v-on:change="addAttachment" multiple>
                                <span ><?= Yii::t('message', 'Attach file') ?></span>
                            </label>
                            <div class="validation-error-message" v-if="file_format_error" v-cloak><?= Yii::t('message', 'Wrong file format') ?></div>
                            <div class="message-form__file-desc">
                                <?= Yii::t('message', 'Supported file types') ?>:
                                <b>jpg, jpeg, png, doc, pdf, gif, zip, rar</b>
                            </div>
                        </div>
                        <div class="message-form__col">
                            <input type="submit" value="<?= Yii::t('main', 'Send') ?>" class="btn" v-on:click="sendMessage">
                        </div>
                    </div>
                </div>
                <div class="message-form__help">
                    <div class="message-form__help-title">
                        <?= Yii::t('message', 'For your safety!') ?>
                    </div>
                    <ul class="message-form__help-list">
                        <li>- <?=Yii::t('message', 'Avoid prepayments') ?></li>
                        <li>- <?= Yii::t('message', 'Use at payment Safe deal') ?></li>
                        <li>- <?= Yii::t('message', 'If you need help, contact support.') ?></li>
                    </ul>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php /*
    <!-- deal config -->
    <div class="container">
        <div class="message__title">
            <?=Yii::t('message', 'Deal management') ?>
        </div>

        <div class="message__btns">
			<a href="<?= Url::to(['/userpanel/safe-deal/create', 'id' => $model->sender_id]) ?>" class="btn"> <?= Yii::t('deal', 'Create Safe Deal') ?></a>
        </div>
        
        <hr>

        <div class="message__btns">
            <div class="message__btn-col">
                <div class="btn btn--grey">
                    Сделка <b>выполняется</b>
                </div>
            </div>
            <div class="message__btn-col">
                <div class="btn btn--grey">
                    До завершения <b>5 дней 8 часов</b>
                </div>
            </div>
            <div class="message__btn-col">
                <div class="btn btn--grey">
                    Оплата зарезервирована (<b>$1500</b>)
                </div>
            </div>
        </div>
        <div class="message__time-long">
            <div>
                Продлить сделку на
            </div>
            <div class="btn">1 день</div>
            <div>Или</div>
            <div class="btn">3 дня</div>
        </div>
        <div class="message__btns">
            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                <div class="btn btn--green j-tooltip-btn">
                    Сделка выполнена
                </div>
                <div class="j-tooltip tooltip">
                    Фрилансер успешно завершил сделку: закрыть как выполненный и написать отзыв о сотрудничестве.
                </div>
            </div>
            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                <div class="btn btn--transparent j-tooltip-btn">
                    Сделка не выполнена
                </div>
                <div class="j-tooltip tooltip">
                    Утвержденное вами время завершения сделки (28 апреля в 18:47) еще не истекло
                </div>
            </div>
            <div class="message__btn-col j-tooltip-wrapper tooltip__wrapper">
                <div class="btn j-tooltip-btn">
                    Обратится в арбитраж
                </div>
                <div class="j-tooltip tooltip">
                    В случае возникновения спорной ситуации Вы можете подать жалобу в арбитраж, первый ответ мы предоставим в течении 24 часа в рабочие дни. Так же учтите , что переписка по арбитражу ведется исключительно в Рабочей области и доступна своим участникам сделки.
                </div>
            </div>
        </div>
    </div>
    <!-- end deal config -->
    */ ?>
</div>

<?php $this->beginJs(); ?>
<script>
var CATEGORIES_MAX_SELECTED_LIMIT = 5;
Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'validation-error-input',
    },
    events: 'change|blur|keyup'
});

Vue.use( CKEditor );

new Vue({
  el: '#appMessage',
  data: {
    editor: ClassicEditor,
    editor_config: {
        toolbar: [ 'heading', '|', 
                    'bold', 'italic', 'alignment', '|', 
                    'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'undo', 'redo' ],
        language: '<?= Yii::$app->language ?>'
    },
    uploading_count_files_in_progress: 0,
    //-------
	file_format_error: false,
	response_errors: void 0,
    submit_clicked: false,
    message: {
		message_text: void 0,
        attachments: [],
    },
    // list new messages:
    message_list: [
        <?php foreach($messages as $message): ?>
        {
            // 'id': '<?= $message->id ?>',
            // 'message_room_id': '<?= $message->message_room_id ?>',
            'owner_id': '<?= $message->owner_id ?>',
            'status': '<?= $message->status ?>',
            'device_type': '<?= $message->device_type ?>',
            'message_text': <?= json_encode(HtmlPurifier::process($message->message_text)) ?>,
            'created_at': '<?= $message->created_at ?>',
            'attachments': [
                <?php foreach($message->messageUserAttachments as $attachment): ?>
                {
                    'id': '<?= $attachment->userAttachment->id ?>',
                    'status': '<?= $attachment->userAttachment->status ?>',
                    'user_id': '<?= $attachment->userAttachment->user_id ?>',
                    'name': '<?= $attachment->userAttachment->name ?>',
                    'path_name': '<?= $attachment->userAttachment->path_name ?>',
                    'size': '<?= $attachment->userAttachment->size ?>',
                    'created_at': '<?= $attachment->userAttachment->created_at ?>',
                },
                <?php endforeach; ?>
            ]
        },
        <?php endforeach; ?>
    ],
    vv: {
        model_message: {
            message_text: '<?= VeeValidateHelper::getVValidateString($this, $modelMessage, 'message_text') ?>',
        },
    }
  },
  mounted: function() {
    // --
    $('.scrollbar-inner').scrollbar();

    this.scrollDownChat();
  },
  methods: {
    onSubmit () {
		// lock send form on key 'enter'
      	return; // supress submit
    },
	sendFieldsToServer: function(post_data, cb) {
        // find and add '_csrf' to post data
        for(let i = 0; i < this.$refs.form.length; i++) {
            if (this.$refs.form[i].name == '_csrf') {
                post_data._csrf = this.$refs.form[i].value;
            }
        }

        // send data to server via AJAX POST
		let me = this;
        this.loaderShow();
        $.post( '<?= Url::to(['/userpanel/message/create/' . $model->sender_id]) ?>', post_data)
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                me.loaderHide();
                if(response.success === false && response.errors) {
                    // insert errors
                    me.$data.response_errors = response.errors;
                    // scroll to top
                    me.scrollToErrorblock();
                } else {
                    if(response.success !== true) {
                        alert(response.responseText ? response.responseText : response.statusText);
                        return; // exit
                    }

                    cb(response);
                }
            });
    },
    sendMessage: function() {
        this.$data.submit_clicked = true;

        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

			// this.$refs.form.submit();
			let post_data = {
				'Message[message_text]': this.$data.message.message_text
			};

            // photos_list (messageUserAttachments)
            for (let i = 0; i < this.$data.message.attachments.length; i++) {
                post_data['Message[messageUserAttachments]['+ i +'][user_attachment_id]'] = this.$data.message.attachments[i].id;
            }

			// find and add '_csrf' to post data
			for(let i = 0; i < this.$refs.form.length; i++) {
				if (this.$refs.form[i].name == '_csrf') {
					post_data._csrf = this.$refs.form[i].value;
				}
            }

			// send data to server via AJAX POST
            let me = this;
			this.sendFieldsToServer(
				post_data,
				function(data) {
                    let attachments = me.$data.message.attachments; // or got from server?
                    let message = {
                        // 'id' => '',
                        // 'message_room_id' => '',
                        'owner_id': '<?= Yii::$app->user->id ?>',
                        'status': '<?= Message::STATUS_READED ?>',
                        'device_type': '<?= Message::DEVICE_TYPE_DEFAULT ?>',
                        'message_text': data.data.Message.message_text,
                        'created_at': data.data.Message.created_at,
                        'attachments': attachments,
                    };

					me.$data.message_list.push(message);
                    me.$data.submit_clicked = false;
                    me.$data.message.message_text = '';
                    me.$data.message.attachments = [];

                    me.scrollDownChat();
				}
			);
        });
    },
	addAttachment: function() {
        if( this.$refs.attachments.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let me = this;
        let img_supported_types = ['image/png', 'image/jpeg'];
        let regex = new RegExp("(.*?)\.(jpg|jpeg|png|doc(x)|pdf|gif|zip|rar)$");
        me.$data.file_format_error = false;

        for (let i = 0; i < this.$refs.attachments.files.length; i++) {
            let file = this.$refs.attachments.files[i];
            if ( !(regex.test(file.name.toLowerCase()))) {
                me.$data.file_format_error = true;
                return;
            }

            let reader = new FileReader();

            me.$data.uploading_count_files_in_progress++;

            reader.onload = function (e) {
                //? use promise
                if(img_supported_types.indexOf(file.type) !== -1) {
                    // clientside resize image
                    let img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        img = me.compressImage(img, 75, 1024);

                        var fd = new FormData();
                        var blob = me.dataURItoBlob(img.src);
                        fd.append('UserAttachment[fileData]', blob, file.name);
                        $.ajax({
                            url: '/userpanel/attachment/upload',
                            data: fd,
                            processData: false,
                            contentType: false,
                            cache: false,
                            type: 'POST',
                            complete: function (data) {
                                let response = JSON.parse(data.responseText);
                                if(response.success === false && response.errors) {
                                    // insert errors
                                    me.$data.response_errors = response.errors;
                                    // scroll to top
                                    me.scrollToErrorblock();
                                } else {
                                    if(response.success !== true) {
                                        alert(data.responseText ? data.responseText : data.statusText);
                                        return; // exit
                                    }

                                    let attachment = {
                                        id: response.data.id,
                                        status: response.data.status,
                                        user_id: response.data.user_id,
                                        name: response.data.name,
                                        path_name: response.data.path_name,
                                        size: response.data.size,
                                        created_at: response.data.created_at,
                                    };

                                    me.$data.message.attachments.push(attachment);
                                    me.$data.uploading_count_files_in_progress--;
                                }
                            }
                        });
                        
                    }
                } else {
                    var fd = new FormData();
                    fd.append('UserAttachment[fileData]', file);

                    $.ajax({
                        url: '/userpanel/attachment/upload',
                        data: fd,
                        processData: false,
                        contentType: false,
                        cache: false,
                        type: 'POST',
                        complete: function (data) {
                            let response = JSON.parse(data.responseText);
                            if(response.success === false && response.errors) {
                                // insert errors
                                me.$data.response_errors = response.errors;
                                // scroll to top
                                me.scrollToErrorblock();
                            } else {
                                if(response.success !== true) {
                                    alert(data.responseText ? data.responseText : data.statusText);
                                    return; // exit
                                }

                                let attachment = {
                                    id: response.data.id,
                                    status: response.data.status,
                                    user_id: response.data.user_id,
                                    name: response.data.name,
                                    path_name: response.data.path_name,
                                    size: response.data.size,
                                    created_at: response.data.created_at,
                                };

                                me.$data.message.attachments.push(attachment);
                                me.$data.uploading_count_files_in_progress--;
                            }
                        }
                    });
                }
            }

            reader.readAsDataURL(file);
        }
    },
    removeAttachment: function(index) {
        this.$data.message.attachments.splice(index, 1);
    },
    // --
    dateFormat: function(date) {
        return moment(date*1000).format('DD.MM.YYYY H:m:ss');
    },
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
    scrollDownChat: function() {
        Vue.nextTick(function () {
            $('.scrollbar-inner').animate({
                // scrollTop: $(".message-box__item").last().offset().top
                scrollTop: 50000
            }, 600);
        });
    },
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
            }, 600);
        });
    },
	compressImage: function (source_img_obj, quality, maxWidth, output_format){
        var mime_type = "image/jpeg";
        if(typeof output_format !== "undefined" && output_format=="png"){
            mime_type = "image/png";
        }

        maxWidth = maxWidth || 1000;
        var natW = source_img_obj.naturalWidth;
        var natH = source_img_obj.naturalHeight;
        var ratio = natH / natW;
        if (natW > maxWidth) {
            natW = maxWidth;
            natH = ratio * maxWidth;
        }

        var cvs = document.createElement('canvas');
        cvs.width = natW;
        cvs.height = natH;

        var ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
        var newImageData = cvs.toDataURL(mime_type, quality/100);
        var result_image_obj = new Image();
        result_image_obj.src = newImageData;
        return result_image_obj;
    },
    dataURItoBlob: function(dataURI) {
        // convert base64/URLEncoded data component to raw binary data held in a string
        var byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0)
            byteString = atob(dataURI.split(',')[1]);
        else
            byteString = unescape(dataURI.split(',')[1]);

        // separate out the mime component
        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        // write the bytes of the string to a typed array
        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], {type:mimeString});
    },
    isImage: function(file_name) {
        let regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        return regex.test(file_name.toLowerCase());
    },
    formatFileSize: function(size) {
        return (size/1024).toFixed(2) + ' kb';
    }
  },
})
</script>
<?php $this->endJs(); ?>
