<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Company;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Yii::t('company', 'Editing company information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

$country_list = Company::getCountryList();

$company_country_name = '';
foreach ($country_list as $country) {
    if ($country['char_code'] == $model->company_country_code) {
        $company_country_name = Yii::t('country', $country['name']);
    }
}
?>
<div class="info-company edit-info" id="appUpdateCompany">
	<div class="container">
		<div class="title-sec">
            <?= Html::encode($this->title) ?>
		</div>
        <div class="row">
            <div class="col">
                <div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
                    <div class="error-message-alert"><?= Yii::t('main', 'Errors')?>:</div>
                    <ul v-for="(error_messages, field_name) of response_errors">
                        <li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
                    </ul>

                    <ul v-if="submit_clicked && errors.all()">
                        <li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
                    <ul>
                </div>
            </div>
        </div>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'row info-company__row j-trigger',
                'v-on:submit.prevent' => "onSubmit",
                'ref' => 'form'
            ],
        ]); ?>
			<div class="col">
				<hr style="margin-top: 0;">
				<div class="j-edit edit-info__bl" id="bl1">
					<div class="info-company__title">
						<?= Yii::t('company', 'Name and type of company') ?>
					</div>
                    <!-- company info view mode -->
					<div class="table" v-if="!company_info_is_edit_mode">
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('company_name') ?>
							</div>
							<div class="table__td table__td--second-view">
                                <?= Html::encode($model->company_name) ?>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('type') ?>
							</div>
							<div class="table__td table__td--second-view">
                                <?php if($model->type == Company::TYPE_EMPLOYER): ?>
                                    <?= Yii::t('company', 'Employer') ?>
                                <?php elseif($model->type == Company::TYPE_HR_AGENCY): ?>
                                    <?= Yii::t('company', 'Agency') ?>
                                <?php endif; ?>
							</div>
						</div>
						<a class="btn btn--small btn--trans-yellow" v-on:click="companyInfoEdit"><?= Yii::t('main', 'Edit') ?></a>
					</div>
                    <!--/ company info view mode -->
                    <!-- company info edit mode -->
                    <div class="table" v-if="company_info_is_edit_mode && !company_info.edit_mode" v-cloak>
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('company_name') ?>
							</div>
							<div class="table__td table__td--second-view">
                                {{company_info.source_data.company_name}}
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('type') ?>
							</div>
							<div class="table__td table__td--second-view">
                                <span v-if="company_info.source_data.type == <?= Company::TYPE_EMPLOYER ?>"><?= Yii::t('company', 'Employer') ?></span>
                                <span v-if="company_info.source_data.type == <?= Company::TYPE_HR_AGENCY ?>"><?= Yii::t('company', 'Agency') ?></span>
							</div>
						</div>
						<a class="btn btn--small btn--trans-yellow" v-on:click="companyInfoEdit"><?= Yii::t('main', 'Edit') ?></a>
					</div>
                    <div class="table" v-if="company_info_is_edit_mode && company_info.edit_mode" v-cloak>
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('company_name') ?>
							</div>
							<div class="table__td">
								<div class="edit-info__input">
                                    <input v-validate="vv.model_company.company_name" name='Company[company_name]' class="j-edit-input edit" type="text" v-model="company_info.edit_data.company_name">
                                    <span v-show="errors.has('Company[company_name]')" class="validation-error-message">
                                        {{ errors.first('Company[company_name]') }}
                                    </span>
								</div>
							</div>
						</div>
						<div class="table__tr">
							<div class="table__td table__td--first">
                                <?= $model->getAttributeLabel('type') ?>
							</div>
							<div class="table__td">
								<div class="edit-info__input">
                                    <input v-validate="vv.model_company.type" name="Company[type]" type="hidden" v-model="company_info.edit_data.type">

                                    <nice-select name="Company[type]" class="select" v-model="company_info.edit_data.type">
                                        <option value="<?= Company::TYPE_EMPLOYER ?>"><?= Yii::t('company', 'Employer') ?></option>
                                        <option value="<?= Company::TYPE_HR_AGENCY ?>"><?= Yii::t('company', 'Agency') ?></option>
                                    </nice-select>

                                    <span v-show="errors.has('Company[type]')" class="validation-error-message">
                                        {{ errors.first('Company[type]') }}
                                    </span>
								</div>
							</div>
						</div>
                        <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="companyInfoEditSave"><?= Yii::t('main', 'Save') ?></button>
                        <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="companyInfoEditCancel"><?= Yii::t('main', 'Cancel') ?></button>
					</div>
                    <!--/ company info edit mode -->
				</div>
				<div class="j-edit edit-info__bl" id="bl2">
					<div class="row edit-info__row">
						<div class="col">
							<div class="info-company__title">
								<?= Yii::t('company', 'Company information') ?>
							</div>
                            <!-- profile info 2 view mode -->
							<div class="table" v-if="!company_info2_is_edit_mode">
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('company_phone') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        <?= Html::encode($model->company_phone) ?>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('company_country_code') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        <?= $company_country_name ?>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('site') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        <?= Html::encode($model->site) ?>
									</div>
								</div>
                                <?php /*
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_name') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        <?= Html::encode($model->contact_name) ?>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_phone') ?>
									</div>
									<div class="table__td">
                                        <div class="table__td table__td--second-view">
                                            <?= Html::encode($model->contact_phone) ?>
                                        </div>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_email') ?>
									</div>
									<div class="table__td">
                                        <div class="table__td table__td--second-view">
                                            <?= Html::encode($model->contact_email) ?>
                                        </div>
									</div>
                                </div>
                                */ ?>
							</div>
                            <!--/ profile info 2 view mode -->
                            <!-- profile info 2 edit mode -->
                            <div class="table" v-if="company_info2_is_edit_mode && !company_info2.edit_mode" v-cloak>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('company_phone') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        {{company_info2.source_data.company_phone}}
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('company_country_code') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        {{getNumberOfEmployeesLabel(company_info2.source_data.company_country_code)}}
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('site') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        {{company_info2.source_data.site}}
									</div>
								</div>
                                <?php /*
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_name') ?>
									</div>
									<div class="table__td table__td--second-view">
                                        {{company_info2.source_data.contact_name}}
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_phone') ?>
									</div>
									<div class="table__td">
                                        <div class="table__td table__td--second-view">
                                            {{company_info2.source_data.contact_phone}}
                                        </div>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('contact_email') ?>
									</div>
									<div class="table__td">
                                        <div class="table__td table__td--second-view">
                                            {{company_info2.source_data.contact_email}}
                                        </div>
									</div>
                                </div>
                                */ ?>
							</div>
                            <div class="table" v-if="company_info2_is_edit_mode && company_info2.edit_mode" v-cloak>
								<div class="table__tr">
									<div class="table__td table__td--first">
                                        <?= $model->getAttributeLabel('company_phone') ?>
									</div>
									<div class="table__td">
                                        <div class="edit-info__input">
                                            <input v-validate="vv.model_company.company_phone" name='Company[company_phone]' class="j-edit-input edit" type="text" v-model="company_info2.edit_data.company_phone">
                                            <span v-show="errors.has('Company[company_phone]')" class="validation-error-message">
                                                {{ errors.first('Company[company_phone]') }}
                                            </span>
                                        </div>
                                    </div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= $model->getAttributeLabel('company_country_code') ?>
									</div>
									<div class="table__td">
										<div class="edit-info__input">
                                            <input v-validate="vv.model_company.company_country_code" name='Company[company_country_code]' type="hidden" v-model="company_info2.edit_data.company_country_code">
                                            <nice-select name="Company[company_country_code]" class="select select--double" v-model="company_info2.edit_data.company_country_code">
                                                <option v-for="item in country_list" :value="item.id">{{item.name}}</option>
                                            </nice-select>
                                            <span v-show="errors.has('Company[company_country_code]')" class="validation-error-message">
                                                {{ errors.first('Company[company_country_code]') }}
                                            </span>
										</div>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= $model->getAttributeLabel('site') ?>
									</div>
									<div class="table__td">
										<div class="edit-info__input">
                                            <input v-validate="vv.model_company.site" name='Company[site]' class="j-edit-input edit" type="text" v-model="company_info2.edit_data.site">
                                            <span v-show="errors.has('Company[site]')" class="validation-error-message">
                                                {{ errors.first('Company[site]') }}
                                            </span>
										</div>
									</div>
								</div>
                                <?php /*
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= $model->getAttributeLabel('contact_name') ?>
									</div>
									<div class="table__td">
										<div class="edit-info__input">
                                            <input v-validate="vv.model_company.contact_name" name='Company[contact_name]' class="j-edit-input edit" type="text" v-model="company_info2.edit_data.contact_name">
                                            <span v-show="errors.has('Company[contact_name]')" class="validation-error-message">
                                                {{ errors.first('Company[contact_name]') }}
                                            </span>
										</div>
									</div>
                                </div>
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= $model->getAttributeLabel('contact_phone') ?>
									</div>
									<div class="table__td">
										<div class="edit-info__input">
                                            <span v-for="(email_item, index) of company_info2.edit_data.contact_phone_list">
                                                <div class="reg-lang__top" style="margin-bottom: 0px;" v-if="index != 0" v-cloak>
                                                    <div class="reg-lang__title">
                                                    </div>
                                                    <div class="reg-lang__edit">
                                                        <a class="pointer" v-on:click="removePhone(index)"><?= Yii::t('main', 'Remove') ?></a>
                                                    </div>
                                                </div>
                                                <input v-validate="vv.model_company.contact_phone" v-bind:name='`Contact[contact_phone_list][${index}]`' class="edit" type="text" v-model="company_info2.edit_data.contact_phone_list[index]" v-on:change="changePhone">
                                                <span v-show="errors.has('Contact[contact_phone_list][' + index + ']')" class="validation-error-message" v-cloak>
                                                    {{ errors.first('Contact[contact_phone_list][' + index + ']') }}
                                                </span>
                                            </span>
                                            <input v-validate="vv.model_company.contact_phone_limit" name='Contact[contact_phone]' class="form-control" type="hidden" v-model="company_info2.edit_data.contact_phone">
                                            <span v-show="errors.has('Contact[contact_phone]')" class="validation-error-message" v-cloak>
                                                {{ errors.first('Contact[contact_phone]') }}
                                            </span>
                                            <div class="registration__add-email" v-on:click="addPhone">
                                                <?= Yii::t('company', 'Add an alternate phone number') ?>
                                            </div>
										</div>
									</div>
								</div>
								<div class="table__tr">
									<div class="table__td table__td--first">
										<?= $model->getAttributeLabel('contact_email') ?>
									</div>
									<div class="table__td">
										<div class="edit-info__input">
                                            <input v-validate="vv.model_company.contact_email" name='Company[contact_email]' class="j-edit-input edit" type="text" v-model="company_info2.edit_data.contact_email">
                                            <span v-show="errors.has('Company[contact_email]')" class="validation-error-message">
                                                {{ errors.first('Company[contact_email]') }}
                                            </span>
										</div>
									</div>
                                </div>
                                */ ?>
							</div>
                            <!--/ profile info 2 edit mode -->
                            <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="companyInfo2EditSave" v-if="company_info2_is_edit_mode && company_info2.edit_mode" v-cloak><?= Yii::t('main', 'Save') ?></button>
                            <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="companyInfo2EditCancel" v-if="company_info2_is_edit_mode && company_info2.edit_mode" v-cloak><?= Yii::t('main', 'Cancel'); ?></button>
							<a class="btn btn--small btn--trans-yellow" v-on:click="companyInfo2Edit" v-if="!company_info2_is_edit_mode || !company_info2.edit_mode"><?= Yii::t('main', 'Edit')?></a>
						</div>
                        <div class="col" v-if="!company_info4_is_edit_mode">
							<div class="info-company__title">
								<?= Yii::t('company', 'Logo') ?>
							</div>
							<div class="edit-info__logo-wrapper">
								<div class="edit-info__logo">
									<img src="<?= empty($model->logo) ? '/img/profile/link4.svg' : Html::encode($model->getLogoWebPath()); ?>" alt="">
								</div>
							</div>
							<div class="edit-info__logo-btns">
								<label class="upload">
									<input type="file" name="logo" ref="logo" v-on:change="changeLogo">
									<div class="btn btn--small btn--trans-yellow">
										<?= Yii::t('main', 'Update') ?>
									</div>
								</label>
                                <?php if(!empty($model->logo)): ?>
                                    <div>
                                        <a class="btn btn--small btn--trans-yellow" v-on:click="removeLogo"><?= Yii::t('main', 'Remove') ?></a>
                                    </div>
                                <?php endif; ?>
							</div>
						</div>
                        <div class="col" v-if="company_info4_is_edit_mode && !company_info4.edit_mode" v-cloak>
							<div class="info-company__title">
								<?= Yii::t('main', 'Logo') ?>
							</div>
							<div class="edit-info__logo-wrapper">
								<div class="edit-info__logo">
									<img :src="getLogoPath(company_info4.source_data.logo_src)" alt="">
								</div>
							</div>
							<div class="edit-info__logo-btns">
								<label class="upload">
									<input type="file" name="logo" ref="logo" v-on:change="changeLogo">
									<div class="btn btn--small btn--trans-yellow">
										<?= Yii::t('main', 'Update') ?>
									</div>
								</label>
								<div>
									<a class="btn btn--small btn--trans-yellow" v-on:click="removeLogo" v-if="company_info4.source_data.logo_src"><?= Yii::t('main', 'Remove') ?></a>
								</div>
							</div>
						</div>
                        <div class="col" v-if="company_info4_is_edit_mode && company_info4.edit_mode" v-cloak>
							<div class="info-company__title">
							    <?= Yii::t('main', 'Logo') ?>
							</div>
							<div class="edit-info__logo-wrapper">
								<div class="edit-info__logo">
									<img :src="getLogoPath(company_info4.edit_data.logo_src)" alt="">
								</div>
							</div>
							<div class="edit-info__logo-btns">
								<label class="upload">
									<input type="file" name="logo" ref="logo" v-on:change="changeLogo">
									<div class="btn btn--small btn--trans-yellow">
										<?= Yii::t('main', 'Update') ?>
									</div>
								</label>
								<div>
									<a class="btn btn--small btn--trans-yellow" v-on:click="removeLogo" v-if="company_info4.edit_data.logo_src"><?= Yii::t('main', 'Remove') ?></a>
								</div>
							</div>
                            <div class="edit-info__logo-btns">
                                <div>
                                    <a class="btn btn--small" v-on:click="saveLogo"><?= Yii::t('main', 'Save')?></a>
                                </div>
                                <div>
                                    <a class="btn btn--small btn--trans-yellow" v-on:click="cancelEditLogo"><?= Yii::t('main', 'Cancel') ?></a>
                                </div>
                            </div>
						</div>
					</div>
				</div>
				<div class="j-edit edit-info__bl" id="bl3">
					<div class="info-company__title">
						<?= Yii::t('company', 'Company description') ?>
					</div>
                    <!-- profile info 2 view mode -->
                    <textarea v-if="!company_info3_is_edit_mode" wrap="hard" name="" id="" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="j-edit-input edit-info__textarea"><?= Html::encode($model->description) ?></textarea>
                    <!--/ profile info 2 view mode -->
                    <!-- profile info 2 edit mode -->
                    <textarea v-if="company_info3_is_edit_mode && !company_info3.edit_mode" wrap="hard" name="" id="" cols="30" rows="5" readonly="readonly" style="width: 100%;" class="j-edit-input edit-info__textarea" v-model="company_info3.source_data.description" v-cloak></textarea>
                    <textarea v-if="company_info3_is_edit_mode && company_info3.edit_mode" wrap="hard" name="" id="" cols="30" rows="5" style="width: 100%;" class="j-edit-input edit-info__textarea" v-model="company_info3.edit_data.description" v-cloak></textarea>
                    <button type="button" class="btn j-accordion-btn active" style="margin-top: 10px;" v-on:click="companyInfo3EditSave" v-if="company_info3_is_edit_mode && company_info3.edit_mode" v-cloak><?= Yii::t('main', 'Save')?></button>
                    <button type="button" class="btn btn--transparent j-accordion-btn active" style="margin-top: 10px; margin-left: 7px;" v-on:click="companyInfo3EditCancel" v-if="company_info3_is_edit_mode && company_info3.edit_mode" v-cloak><?= Yii::t('main', 'Cancel') ?></button>
                    <!--/ profile info 2 edit mode -->
                    <a class="btn btn--small btn--trans-yellow" v-on:click="companyInfo3Edit" v-if="!company_info3_is_edit_mode || !company_info3.edit_mode"><?= Yii::t('main', 'Edit') ?></a>
				</div>
				<div class="j-edit edit-info__bl" id="bl4">
					<div class="info-company__title">
						<?= Yii::t('company', 'Company verification') ?>
					</div>
					<?php if( $model->status == Company::STATUS_VERIFIED ): ?>
                        <div class="edit-info__auth">
                            <div class="authentication authentication--yes">
                                <?= Yii::t('company', 'Verified') ?>
                            </div>
                            <div class="edit-info__auth-title edit-info__auth-title--yes">
                                <?= Yii::t('company', 'Company verified') ?>
                            </div>
                        </div>
                        <a href="<?= Url::to(['/userpanel/company/verify']) ?>" class="btn btn--small btn--trans-yellow"><?= Yii::t('main', 'Edit') ?></a>
                    <?php else: ?>
                        <div class="edit-info__auth">
                            <div class="authentication authentication--no">
                                <?= Yii::t('company', 'Not Verified') ?>
                            </div>
                            <div class="edit-info__auth-title edit-info__auth-title--no">
                                <?= Yii::t('company', 'Company not verified') ?>
                            </div>
                        </div>
                        <a href="<?= Url::to(['/userpanel/company/verify']) ?>" class="btn btn--small btn--trans-yellow"><?= Yii::t('company', 'Submit Data For Verification') ?></a>
                    <?php endif; ?>
				</div>
				<hr style="margin-bottom: 0;">
				<button type="submit" class="btn"><?= Yii::t('company', 'Complete Edit') ?></button>
			</div>
			<div class="col info-company__col j-height-sticky-column">
				<div class="j-sticky">
					<button type="submit" class="edit-info__edit-btn btn" style="width: 100%; margin-bottom: 20px;"><?= Yii::t('company', 'Complete Edit') ?></button>
					<div class="sidebar">
						<ul class="sidebar__list">
							<li>
								<a href="#bl1" class="j-scroll"><?= Yii::t('company', 'Company name and type') ?></a>
							</li>
							<li>
								<a href="#bl2" class="j-scroll"><?= Yii::t('company', 'Company information') ?></a>
							</li>
							<li>
								<a href="#bl3" class="j-scroll"><?= Yii::t('company', 'Company description') ?></a>
							</li>
							<li>
								<a href="#bl4" class="j-scroll"><?= Yii::t('company', 'Company verification') ?></a>
							</li>
						</ul>
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

VeeValidate.Validator.extend('company_contact_phone_item_required', {
    getMessage: function(field) {
        return '<?= Yii::t('company', 'You must fill in the «Contact Phone».') ?>';
    },
    validate: function(value) {
        return value !== void 0 && value.length !== 0;
    }
});

VeeValidate.Validator.extend('company_contact_phone_item_limit', {
    getMessage: function(field) {
        return '<?= Yii::t('company', 'The aggregate value of all «Contact Phone» must contain a maximum of 255 characters.') ?>';
    },
    validate: function(value) {
        return value.trim().length < 255;
    }
});

new Vue({
  el: '#appUpdateCompany',
  data: {
    response_errors: void 0,
    submit_clicked: false,
    default_logo_image: '/img/profile/link4.svg',
    country_list: [
        <?php foreach( $country_list as $country): ?>
        {
            id: '<?= $country['char_code'] ?>',
            name: '<?= Yii::t('country', $country['name']) ?>',
        },
        <?php endforeach; ?>
    ],
    //-------
    company_info_is_edit_mode: false,
    company_info2_is_edit_mode: false,
    company_info3_is_edit_mode: false,
    company_info4_is_edit_mode: false,
    company_info: {
        edit_mode: false,
        source_data: {
            company_name: '<?= Html::encode($model->company_name) ?>',
            type: '<?= Html::encode($model->type) ?>',
        },
        edit_data: {
            company_name: '',
            type: '',
        }
    },
    company_info2: {
        edit_mode: false,
        source_data: {
            company_phone: '<?= Html::encode($model->company_phone) ?>',
            company_country_code: '<?= Html::encode($model->company_country_code) ?>',
            site: '<?= Html::encode($model->site); ?>',
            <?php /*
            contact_name: '<?= Html::encode($model->contact_name); ?>',
            contact_phone: '<?= Html::encode($model->contact_phone); ?>',
            contact_email: '<?= Html::encode($model->contact_email); ?>',
            */ ?>
        },
        edit_data: { // copy object on edit
            company_phone: '',
            company_country_code: '',
            site: '',
            // contact_name: '',
            // contact_phone: '',
            // contact_email: '',
            // contact_phone_limit: '',
            // contact_phone_list: [
                // ''
            // ],
        }
    },
    company_info3: {
        edit_mode: false,
        source_data: {
            description: <?= json_encode(Html::encode($model->description)); ?>,
        },
        edit_data: { // copy object on edit
            description: '',
        }
    },
    company_info4: {
        edit_mode: false,
        source_data: {
            logo: '<?= Html::encode($model->logo); ?>',
            logo_src: '<?= Html::encode($model->getLogoWebPath()); ?>',
        },
        edit_data: { // copy object on edit
            logo: '',
            logo_src: '',
        }
    },
    response_errors: void 0,
    submit_clicked: false,
    vv: {
        model_company: {
            company_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_name') ?>',
            type: '<?= VeeValidateHelper::getVValidateString($this, $model, 'type') ?>',
            company_phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_phone') ?>',
            company_country_code: '<?= VeeValidateHelper::getVValidateString($this, $model, 'company_country_code') ?>',
            site: '<?= VeeValidateHelper::getVValidateString($this, $model, 'site') ?>',
            <?php /*
            contact_name: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_name') ?>',
            contact_phone: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_phone') ?>',
            contact_phone_limit: 'company_contact_phone_item_required|required|company_contact_phone_item_limit',
            contact_email: '<?= VeeValidateHelper::getVValidateString($this, $model, 'contact_email') ?>',
            */ ?>
            description: '<?= VeeValidateHelper::getVValidateString($this, $model, 'description') ?>',
        },
    }
  },
  mounted: function() {
    
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
        $.post( window.location.pathname.slice(0, -5) + '/update', post_data)
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
    //-- Company
    companyInfoEdit: function() {
        this.$data.company_info.edit_data = JSON.parse(JSON.stringify(this.$data.company_info.source_data));
        this.$data.company_info_is_edit_mode = true;
        this.$data.company_info.edit_mode = true;
    },
    companyInfoEditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Company[company_name]': this.$data.company_info.edit_data.company_name,
                'Company[type]': this.$data.company_info.edit_data.type,
            };

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.company_info.source_data = JSON.parse(JSON.stringify(me.$data.company_info.edit_data));
                    me.$data.company_info.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    companyInfoEditCancel: function() {
        // this.$data.company_info_is_edit_mode = false;
        this.$data.company_info.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    companyInfo2Edit: function() {
        let edit_data = JSON.parse(JSON.stringify(this.$data.company_info2.source_data));

        // edit_data.contact_phone_list = edit_data.contact_phone.split(';');

        this.$data.company_info2.edit_data = edit_data;

        this.$data.company_info2_is_edit_mode = true;
        this.$data.company_info2.edit_mode = true;
    },
    companyInfo2EditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Company[company_phone]': this.$data.company_info2.edit_data.company_phone,
                'Company[company_country_code]': this.$data.company_info2.edit_data.company_country_code,
                'Company[site]': this.$data.company_info2.edit_data.site,
                <?php /*
                'Company[contact_name]': this.$data.company_info2.edit_data.contact_name,
                'Company[contact_phone]': this.$data.company_info2.edit_data.contact_phone,
                'Company[contact_email]': this.$data.company_info2.edit_data.contact_email,
                */ ?>
            };

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.company_info2.source_data = JSON.parse(JSON.stringify(me.$data.company_info2.edit_data));
                    me.$data.company_info2.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    companyInfo2EditCancel: function() {
        // this.$data.company_info2_is_edit_mode = false;
        this.$data.company_info2.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    companyInfo3Edit: function() {

        this.$data.company_info3_is_edit_mode = true;
        this.$data.company_info3.edit_mode = true;

        let me = this;
        Vue.nextTick(function () { // nextTick - fix render textarea text
            me.$data.company_info3.edit_data = JSON.parse(JSON.stringify(me.$data.company_info3.source_data));
        });
    },
    companyInfo3EditSave: function() {
        this.$data.submit_clicked = true;
        this.$validator.validate().then(valid => {
            if (!valid) {
                this.scrollToErrorblock();
                return;
            }

            let post_data = {
                'Company[description]': this.$data.company_info3.edit_data.description,
            };

            let me = this;
            this.sendFieldsToServer(
                post_data,
                function(data) {
                    me.$data.company_info3.source_data = JSON.parse(JSON.stringify(me.$data.company_info3.edit_data));
                    me.$data.company_info3.edit_mode = false;
                    me.$data.submit_clicked = false;
                }
            );
        });
    },
    companyInfo3EditCancel: function() {
        // this.$data.company_info3_is_edit_mode = false;
        this.$data.company_info3.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    // -- 4 Company logo
    changeLogo: function() {
        if( this.$refs.logo.files.length == 0 ||
            typeof (FileReader) == "undefined"
        ) {
            return;
        }

        let file = this.$refs.logo.files[0];
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

                me.$data.company_info4.edit_data = JSON.parse(JSON.stringify(me.$data.company_info4.source_data));
                me.$data.company_info4.edit_data.logo_src = img.src;
                me.$data.company_info4.edit_data.logo = file.name.toLowerCase();

                me.$data.company_info4_is_edit_mode = true;
                me.$data.company_info4.edit_mode = true;
            }
        }

        reader.readAsDataURL(file);
    },
    saveLogo: function() {
        this.$data.submit_clicked = true;

        let post_data = {
            'Company[photo_path]': this.$data.company_info4.edit_data.photo_path,
            'Company[src]': this.$data.company_info4.edit_data.logo_src,
        };

        let me = this;
        this.sendFieldsToServer(
            post_data,
            function(data) {
                me.$data.company_info4.source_data = JSON.parse(JSON.stringify(me.$data.company_info4.edit_data));
                me.$data.company_info4.edit_mode = false;
                me.$data.submit_clicked = false;
            }
        );
    },
    cancelEditLogo: function() {
        // this.$data.company_info4_is_edit_mode = false;
        this.$data.company_info4.edit_mode = false;
        this.$data.submit_clicked = false;
    },
    removeLogo: function() {
        this.$data.company_info4.edit_data.photo_path = '';
        this.$data.company_info4.edit_data.logo_src = void 0;

        this.$data.company_info4_is_edit_mode = true;
        this.$data.company_info4.edit_mode = true;
    },
    getLogoPath: function(logo_src) {
        if(logo_src)
            return logo_src;
        
        return this.$data.default_logo_image;
    },
    getNumberOfEmployeesLabel: function(company_country_code) {
        let item = _.find(this.$data.country_list, (p) => {
            return company_country_code == p.id;
        });

        if(item) {
            return item.name;
        }

        return company_country_code;
    },
    <?php /*
    addPhone: function() {
        this.$data.company_info2.edit_data.contact_phone_list.push('');
    },
    changePhone: function() {
        this.$data.company_info2.edit_data.contact_phone = this.$data.company_info2.edit_data.contact_phone_list.join(';');
    },
    removePhone: function(index) {
        this.$data.company_info2.edit_data.contact_phone_list.splice(index, 1);
        this.changePhone();
    },
    */ ?>
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
  }
});
</script>
<?php $this->endJs(); ?>