<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Profile;
use app\models\ProfileJob;
use app\models\ProfileLanguage;
use app\models\Category;
use app\models\CategoryJob;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */

$this->title = Yii::t('user', 'Profile') . ' ' . Html::encode($model->getFullName());
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vue-multiselect.min.js'); //! BUG, need setup asset manager
$this->registerCssFile('/js/app/dist/vue-multiselect.min.css'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/moment.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/air-datepicker.wrapper.js'); //! BUG, need setup asset manager

$country_list = Profile::getCountryList();
$gender_list = Profile::getGenderList();

$genders = $model->getGenders();
$gender_names = implode(', ', $genders);

// get profile country name label
$country_name = $model->country_name;
foreach($country_list as $country) {
	if($country['char_code'] == $country_name) {
		$country_name = Yii::t('country', $country['name']);
		break;
	}
}

// get profile city name
$country_city_name = '';
if(!empty($model->countryCity)) {
	$country_city_name = Yii::t('city', $model->countryCity->city_name);
}

?>
<div class="info-company edit-info">
	<div class="container">
		<div class="title-sec">
            <?= Html::encode($this->title) ?>
		</div>
		        
        <?php $form = ActiveForm::begin([
            'id' => 'appUpdateProfile',
            'options' => [
                'class' => 'row info-company__row',
                'v-on:submit.prevent' => "onSubmit",
                'ref' => 'form'
            ],
        ]); ?>
            <div class="col">
                <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                    <div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
                    <ul v-for="(error_messages, field_name) of response_errors">
                        <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                    </ul>

                    <ul v-if="submit_clicked && errors.all()">
                        <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                    <ul>
                </div>

                <hr style="margin-top: 0;margin-bottom: 30px;">
                <div class="edit-info__bl" id="bl1">
                    <div class="row edit-info__row">
                        <!-- profile info view mode -->
                        <div class="col" v-if="!profile_info_is_edit_mode">
                            <div class="info-company__title">
                                <?= Yii::t('user', 'Personal data') ?>
                            </div>
                            <div class="table">
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'Full Name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($model->getFullName()) ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Yii::$app->formatter->asDate($model->birth_day); ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= $gender_names ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($country_name) ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_city_id') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($country_city_name) ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($model->email); ?>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'phone') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        <?= Html::encode($model->phone); ?>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn--small btn--trans-yellow" v-on:click="profileInfoEdit"><?= Yii::t('main', 'Edit') ?></a>
                        </div>
                        <!--/ profile info view mode -->
                        <!-- profile info edit mode -->
                        <div class="col" v-if="profile_info_is_edit_mode" v-cloak>
                            <div class="info-company__title">
                                <?= Yii::t('user', 'Personal data') ?>
                            </div>
                            <div class="table" v-if="!profile_info.edit_mode">
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'Full Name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getFullName(profile_info.source_data)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{formatDate(profile_info.source_data.birth_day)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getGenderListLabel(profile_info.source_data.gender_list)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getCountryNameLabel(profile_info.source_data.country_name)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_city_id') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{getCountryCityLabel(profile_info.source_data.country_city_id)}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{profile_info.source_data.email}}
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'phone') ?>
                                    </div>
                                    <div class="table__td table__td--second-view">
                                        {{profile_info.source_data.phone}}
                                    </div>
                                </div>
                            </div>
                            <div class="table" v-if="profile_info.edit_mode">
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('first_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_profile.first_name" name='Profile[first_name]' class="j-edit-input edit" type="text" v-model="profile_info.edit_data.first_name">
                                            <span v-show="errors.has('Profile[first_name]')" class="validation-error-message">
                                                {{ errors.first('Profile[first_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('last_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_profile.last_name" name='Profile[last_name]' class="j-edit-input edit" type="text" v-model="profile_info.edit_data.last_name">
                                            <span v-show="errors.has('Profile[last_name]')" class="validation-error-message">
                                                {{ errors.first('Profile[last_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('middle_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_profile.middle_name" name='Profile[middle_name]' class="j-edit-input edit" type="text" v-model="profile_info.edit_data.middle_name">
                                            <span v-show="errors.has('Profile[middle_name]')" class="validation-error-message">
                                                {{ errors.first('Profile[middle_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('birth_day') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <air-datepicker v-validate="vv.model_profile.birth_day" name='Profile[birth_day]' date-format="<?=Yii::$app->params['dateFormat'] ?>" date-input-mask="<?=Yii::$app->params['dateInputMask'] ?>" v-model="profile_info.edit_data.birth_day" class="j-edit-input edit"></air-datepicker>
                                            <!-- <input v-validate="vv.model_profile.birth_day" name='Profile[birth_day]' type="text" id="profile-birth_day" v-model="profile_info.edit_data.birth_day" class="j-edit-input edit"> -->
                                            <span v-show="errors.has('Profile[birth_day]')" class="validation-error-message">
                                                {{ errors.first('Profile[birth_day]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('gender_list') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <div class="registration__row">
                                                <label v-for="gender_item in gender_list" class="checkbox" v-cloak>
                                                    <input type="checkbox" v-model="gender_item.checked" v-on:change="genderChange">
                                                    <span class="checkbox__check"></span>
                                                    <span class="checkbox__title">
                                                        {{gender_item.name}}
                                                    </span>
                                                </label>
                                            </div>

                                            <input v-validate="vv.model_profile.gender_list" name='Profile[gender_list]' type="hidden" v-model="profile_info.edit_data.gender_list">
                                            <span v-show="errors.has('Profile[gender_list]')" class="validation-error-message">
                                                {{ errors.first('Profile[gender_list]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_name') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <nice-select :options="country_list" name="Profile[country_name]`" class="select select--double" v-model="profile_info.edit_data.country_name" v-on:input="countryChanged">
                                                <option v-for="item in country_list" :value="item.char_code" >
                                                    {{ item.name }}
                                                </option>
                                            </nice-select>
                                            <input v-validate="vv.model_profile.country_name" name='Profile[country_name]' class="j-edit-input edit" type="hidden" v-model="profile_info.edit_data.country_name">
                                            <span v-show="errors.has('Profile[country_name]')" class="validation-error-message">
                                                {{ errors.first('Profile[country_name]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('country_city_id') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <nice-select :options="country_city_list" name="Profile[country_city_id]`" class="select" v-model="profile_info.edit_data.country_city_id" v-if="country_city_refresh_flag">
                                                <option v-for="item in country_city_list" :value="item.id">
                                                    {{ item.city_name }}
                                                </option>
                                            </nice-select>
                                            <input v-validate="vv.model_profile.country_city_id" name='Profile[country_city_id]' class="j-edit-input edit" type="hidden" v-model="profile_info.edit_data.country_city_id">
                                            <span v-show="errors.has('Profile[country_city_id]')" class="validation-error-message">
                                                {{ errors.first('Profile[country_city_id]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('email') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_profile.email" name='Profile[email]' class="j-edit-input edit" type="text" v-model="profile_info.edit_data.email">
                                            <span v-show="errors.has('Profile[email]')" class="validation-error-message">
                                                {{ errors.first('Profile[email]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table__tr">
                                    <div class="table__td table__td--first">
                                        <?= Yii::t('user', 'phone') ?>
                                    </div>
                                    <div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_profile.phone" name='Profile[phone]' class="j-edit-input edit" type="text" v-model="profile_info.edit_data.phone">
                                            <span v-show="errors.has('Profile[phone]')" class="validation-error-message">
                                                {{ errors.first('Profile[phone]') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="profileInfoEditSave" v-if="profile_info.edit_mode"><?= Yii::t('main', 'Save') ?></button>
                            <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="profileInfoEditCancel" v-if="profile_info.edit_mode"><?= Yii::t('main', 'Cancel') ?></button>
                            <a class="btn btn--small btn--trans-yellow" v-on:click="profileInfoEdit" v-if="!profile_info.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
                        </div>
                        <!--/ profile info edit mode -->
                        <div class="col" v-if="!profile_info3_is_edit_mode">
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img src="<?= empty($model->photo_path) ? '/img/icons-svg/user.svg' : Html::encode($model->getImageWebPath()); ?>" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <?php if(!empty($model->photo_path)): ?>
                                    <div>
                                        <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto"><?= Yii::t('main', 'Remove') ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col" v-if="profile_info3_is_edit_mode && !profile_info3.edit_mode" v-cloak>
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img :src="getImagePath(profile_info3.source_data.photo_src)" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" id="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto" v-if="profile_info3.source_data.photo_src"><?= Yii::t('main', 'Remove') ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col" v-if="profile_info3_is_edit_mode && profile_info3.edit_mode" v-cloak>
                            <div class="edit-info__logo-wrapper">
                                <div class="edit-info__img">
                                    <img :src="getImagePath(profile_info3.edit_data.photo_src)" alt="">
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <label class="upload">
                                    <input type="file" name="photo" id="photo" ref="photo" v-on:change="changePhoto">
                                    <div class="btn btn--small btn--trans-yellow">
                                        <?= Yii::t('main', 'Update') ?>
                                    </div>
                                </label>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="removePhoto" v-if="profile_info3.edit_data.photo_src"><?= Yii::t('main', 'Remove') ?></a>
                                </div>
                            </div>
                            <div class="edit-info__logo-btns">
                                <div>
                                    <a class="btn btn--small" v-on:click="savePhoto"><?= Yii::t('main', 'Save') ?></a>
                                </div>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="cancelEditPhoto"><?= Yii::t('main', 'Cancel') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col info-company__col">
                <div>
                    <button type="submit" class="edit-info__edit-btn btn" style="width: 100%; margin-top:0;margin-bottom: 20px;"><?= Yii::t('user', 'Complete Edit') ?></button>
                    <div class="sidebar">
                        <div class="sidebar__title">
                            <?= Yii::t('user', 'Fill out a resume and increase the chances of quickly finding work') ?> <br>
                            <a href="#"><?=Yii::t('main', 'More') ?></a>
                        </div>
                        <ul class="sidebar__list-add">
                            <li>
                                <a href="#bl1" class="j-scroll">
                                    <span><?= Yii::t('main', 'Edit') ?></span>
                                    <?= Yii::t('user', 'Personal data') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl2" class="j-scroll">
                                    <span><?= Yii::t('main', 'Edit') ?></span>
                                    <?= Yii::t('user', 'General information') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl3" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Job') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl4" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Place of study') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl5" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Course or training') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl6" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Language') ?>
                                </a>
                            </li>
                            <li>
                                <a href="#bl7" class="j-scroll">
                                    <span><?= Yii::t('main', 'Add') ?></span>
                                    <?= Yii::t('user', 'Additional Information') ?>
                                </a>
                            </li>
                        </ul>
                        <a href="<?= Url::to(['/userpanel']) ?>" class="edit-info__edit-btn btn" style="width: 100%; margin-bottom: -20px;"><?= Yii::t('user', 'Go to personal account') ?></a>
                    </div>
                </div>
            </div>
        <?php ActiveForm::end(); ?>

	</div>
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
  el: '#appUpdateProfile',
  components: {
    'multiselect' : window.VueMultiselect.default
  },
  data: {
    default_profile_image: '/img/icons-svg/user.svg',
    country_list: [
        <?php foreach( $country_list as $country): ?>
        {
            char_code: '<?= $country['char_code'] ?>',
            name: '<?= Yii::t('country', $country['name']) ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    country_city_list: [],
    gender_list: [
        <?php foreach( $gender_list as $key_id => $gender): ?>
        {
            id: '<?= $key_id ?>',
            name: '<?= $gender ?>',
            checked: false
        },
        <?php endforeach; ?>
    ],
    //-------
    response_errors: void 0,
    submit_clicked: false,
    country_char_code_last: '',
    country_city_refresh_flag: true,
    select_category_job_items: [], // fix
    profile_info_is_edit_mode: false,
    profile_info3_is_edit_mode: false,
    profile_info: {
        edit_mode: false,
        source_data: {
            first_name: '<?= Html::encode($model->first_name) ?>',
            last_name: '<?= Html::encode($model->last_name) ?>',
            middle_name: '<?= Html::encode($model->middle_name) ?>',
            email: '<?= Html::encode($model->email) ?>',
            gender_list: '<?= Html::encode($model->gender_list) ?>',
            birth_day: '<?= Yii::$app->formatter->asDate($model->birth_day) ?>',
            country_name: '<?= Html::encode($model->country_name) ?>',
            country_city_id: '<?= Html::encode($model->country_city_id) ?>',
            phone: '<?= Html::encode($model->phone); ?>',
        },
        edit_data: {
            first_name: '',
            last_name: '',
            middle_name: '',
            birth_day: '',
            email: '',
            gender_list: '',
            country_name: '',
            country_city_id: '',
            phone: '',
        }
    },
    profile_info3: {
        edit_mode: false,
        source_data: {
            photo_path: '<?= Html::encode($model->photo_path); ?>',
            photo_src: '<?= Html::encode($model->getImageWebPath()); ?>',
        },
        edit_data: { // copy object on edit
            photo_path: '',
            photo_src: '',
        }
    },
    response_errors: void 0,
    submit_clicked: false,
    vv: {
        model_profile: {
            first_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'first_name') ?>',
            last_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'last_name') ?>',
            middle_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'middle_name') ?>',
            birth_day: '<?= VeeValidateHelper::getVValidateString($this, $model, 'birth_day') ?>',
            email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'email') ?>',
            gender_list: '<?= VeeValidateHelper::getVValidateString($this, $model, 'gender_list') ?>',
            country_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_name') ?>',
            country_city_id: '<?= VeeValidateHelper::getVValidateString($this, $model, 'country_city_id') ?>',
            phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'phone') ?>',
        },
    }
  },
  mounted: function() {
    // --
  },
  methods: {
    onSubmit () {
        // lock send form on key 'enter'
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
        $.post( '<?= Url::to(['/userpanel/profile/update']) ?>', post_data)
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
    //-- Profile
    profileInfoEdit: function() {
        let edit_data = JSON.parse(JSON.stringify(this.$data.profile_info.source_data));

		// gender_list
		for (let i = 0; i < this.$data.gender_list.length; i++) {
			this.$data.gender_list[i].checked = false;
            if ( edit_data.gender_list.indexOf(this.$data.gender_list[i].id + ';') !== -1) {
				this.$data.gender_list[i].checked = true;
            }
        }

        this.$data.profile_info.edit_data = edit_data;
        this.$data.profile_info_is_edit_mode = true;
        this.$data.profile_info.edit_mode = true;

        this.countryChanged();
    },
    profileInfoEditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Profile[first_name]': this.$data.profile_info.edit_data.first_name,
                'Profile[last_name]': this.$data.profile_info.edit_data.last_name,
                'Profile[middle_name]': this.$data.profile_info.edit_data.middle_name,
                'Profile[birth_day]': moment(this.$data.profile_info.edit_data.birth_day, "<?=Yii::$app->params['dateFormat'] ?>".toUpperCase()).format('Y-MM-DD'),
                'Profile[email]': this.$data.profile_info.edit_data.email,
                'Profile[gender_list]': this.$data.profile_info.edit_data.gender_list,
                'Profile[country_name]': this.$data.profile_info.edit_data.country_name,
                'Profile[country_city_id]': this.$data.profile_info.edit_data.country_city_id,
                'Profile[phone]': this.$data.profile_info.edit_data.phone,
            };

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.profile_info.source_data = JSON.parse(JSON.stringify(me.$data.profile_info.edit_data));
                    // me.$data.profile_info2.source_data.country_name = me.$data.profile_info2.edit_data.country_name;
                    // me.$data.profile_info2.source_data.country_city_id = me.$data.profile_info2.edit_data.country_city_id;
                    me.$data.profile_info.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    profileInfoEditCancel: function() {
        // this.$data.profile_info_is_edit_mode = false;
        this.$data.profile_info.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    getFullName: function(profile_info) {
        let full_name_arr = [profile_info.first_name];
        
        if (profile_info.middle_name) {
            full_name_arr.push(profile_info.middle_name);
        }

        if (profile_info.last_name) {
            full_name_arr.push(profile_info.last_name);
        }

        return full_name_arr.join(' ');
    },
    genderChange: function() {
        let selected_genders = '';
        for(let i = 0; i < this.$data.gender_list.length; i++) {
            if(this.$data.gender_list[i].checked) {
                selected_genders += this.$data.gender_list[i].id + ';';
            }
        }

        this.$data.profile_info.edit_data.gender_list = selected_genders;
    },
    getGenderListLabel: function(gender_list_codes) {
		let selected_list = _.map(
			_.filter(this.$data.gender_list, function(p) {
				return gender_list_codes.indexOf(p.id + ';') !== -1;
			}),
			function(p) {
				return p.name;
			}
		);

		return selected_list.join(', ');
	},
    countryChanged: function() {
        let country_char_code = this.$data.profile_info.edit_data.country_name;
        
        // fix double request
        if(country_char_code == this.$data.country_char_code_last) {
            return;
        }

        this.$data.country_char_code_last = country_char_code;
        this.$data.country_city_refresh_flag = false;

        let me = this;
        this.loaderShow();
        $.get( '/api/country?country_char_code=' + country_char_code, function( data ) {
            me.loaderHide();
            me.$data.country_city_refresh_flag = true;
            me.$data.country_city_list = data.items
        });
    },
    getCountryNameLabel: function(char_code) {
		let item = _.find(this.$data.country_list, function(p) {
			return p.char_code == char_code;
		});

		if(item)
			return item.name;
		
		return '-'; // impossible value
	},
	getCountryCityLabel: function(country_city_id) {
		let item = _.find(this.$data.country_city_list, function(p) {
			return p.id == country_city_id;
		});

		if(item)
			return item.city_name;
		
		return '-'; // impossible value
	},
    // --
    formatDate: function(date_obj) {
        return date_obj;
    },
    changePhoto: function() {
        if( this.$refs.photo.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let file = this.$refs.photo.files[0];
        let img_supported_types = ['image/png', 'image/jpeg'];

        let regex = new RegExp("(.*?)\.(jpg|jpeg|png)$");
        if ( !(regex.test(file.name.toLowerCase()))
            || img_supported_types.indexOf(file.type) == -1) {
            alert('Unsupported file type');
            return;
        }

        let reader = new FileReader();

        let me = this;
        reader.onload = function (e) {
            // clientside resize image
            let img = new Image();
            img.src = e.target.result;
            img.onload = function() {
                img = me.compressImage(img, 75, 1024);

                me.$data.profile_info3.edit_data = JSON.parse(JSON.stringify(me.$data.profile_info3.source_data));
                me.$data.profile_info3.edit_data.photo_src = img.src;
                me.$data.profile_info3.edit_data.photo_path = file.name.toLowerCase();

                me.$data.profile_info3_is_edit_mode = true;
                me.$data.profile_info3.edit_mode = true;
            }
        }

        reader.readAsDataURL(file);
    },
    savePhoto: function() {
        this.$data.submit_clicked = true;

        let post_data = {
            'Profile[photo_path]': this.$data.profile_info3.edit_data.photo_path,
            'Profile[src]': this.$data.profile_info3.edit_data.photo_src,
        };

        let me = this;
        this.sendFieldsToServer(
            post_data,
            function(data) {
                me.$data.profile_info3.source_data = JSON.parse(JSON.stringify(me.$data.profile_info3.edit_data));
                me.$data.profile_info3.edit_mode = false;
                me.$data.submit_clicked = false;
            }
        );
    },
    cancelEditPhoto: function() {
        // this.$data.profile_info3_is_edit_mode = false;
        this.$data.profile_info3.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    removePhoto: function() {
        this.$data.profile_info3.edit_data.photo_path = '';
        this.$data.profile_info3.edit_data.photo_src = void 0;

        this.$data.profile_info3_is_edit_mode = true;
        this.$data.profile_info3.edit_mode = true;
    },
    getImagePath: function(photo_src) {
        if(photo_src)
            return photo_src;
        
        return this.$data.default_profile_image;
    },
    // --
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
            }, 600);
        });
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
    }
  }
});
</script>
<?php $this->endJs(); ?>