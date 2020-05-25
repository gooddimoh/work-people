<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\CompanyReview;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyReview */
/* @var $model_company app\models\Company */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager

$this->title = Yii::t('company-review', 'Create Review');
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Company Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model_company->company_name, 'url' => ['viewcompany', 'id' => $model_company->id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="registration registration--review">
	<div class="container">
		<div class="registration__number-step">
			<b><?= $this->title ?></b>
		</div>
		<div class="row j-trigger">
			<div class="registration__main col">
				<div class="registration__inner">
					<div class="registration__top">
						<a href="<?= Url::to(['viewcompany', 'id' => $model_company->id]) ?>">
							<svg class="icon"> 
								<use xlink:href="/img/icons-svg/prev.svg#prev" x="0" y="0" />
							</svg>
							<?= Yii::t('main', 'Back') ?>
						</a>
					</div>					
					<!-- <div class="registration__form"> -->
					<?php $form = ActiveForm::begin([
						'id' => 'appAddReview',
						'enableClientValidation' => false,
						'options' => [
							'class' => 'registration__form add-review-form',
							'v-on:submit.prevent' => "onSubmit",
							'ref' => 'form'
						]
					]);?>
						<div class="response-errors-block" v-if="response_errors || (submit_clicked && errors.all())" v-cloak>
							<div class="error-message-alert"><?= Yii::t('main', 'Errors') ?>:</div>
							<ul v-for="(error_messages, field_name) of response_errors">
								<li v-for="error_message of error_messages" class="validation-error-message">{{error_message}}</li>
							</ul>

							<ul v-if="submit_clicked && errors.all()">
								<li v-for="error in errors.all()" class="validation-error-message">{{ error }}</li>
							<ul>
						</div>
						<div id="bl1">
							<div class="review-add__step row">
								<div class="col"><?= Yii::t('company-review', 'about company') ?></div>
								<div class="col"><?= Yii::t('main', 'Step') ?> 1</div>
							</div>
							<div class="registration__input-bl">
								<div class="registration__input-title">
									<?= Yii::t('company-review', 'Company Name') ?>
									<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
								</div>
								<input type="text" value="<?= Html::encode($model_company->company_name) ?>" disabled>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('position') ?>
											<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
										</div>
										<input v-validate="vv.model_company_review.position" name='CompanyReview[position]' class="form-control" type="text">
										<span v-show="errors.has('CompanyReview[position]')" class="validation-error-message" v-cloak>
											{{ errors.first('CompanyReview[position]') }}
										</span>
									</div>
								</div>
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= Yii::t('company-review', 'Your status in the company') ?>
										</div>
										<div class="review-add__radio-wrapper">
											<label class="radio">
												<input type="radio" name="CompanyReview[worker_status]" class="btn" value="<?= CompanyReview::WORKER_STATUS_WORKING ?>" v-model="company_review.worker_status" checked="">
												<span class="btn"><?= Yii::t('company-review', 'Working') ?></span>
											</label>
											<label class="radio">
											<input type="radio" name="CompanyReview[worker_status]" class="btn" value="<?= CompanyReview::WORKER_STATUS_FIRED ?>" v-model="company_review.worker_status">
												<span class="btn btn--transparent"><?= Yii::t('company-review', 'Fired') ?></span>
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('department') ?>
										</div>
										<input v-validate="vv.model_company_review.department" name='CompanyReview[department]' class="form-control" type="text">
										<span v-show="errors.has('CompanyReview[department]')" class="validation-error-message" v-cloak>
											{{ errors.first('CompanyReview[department]') }}
										</span>
									</div>
								</div>
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= Yii::t('company-review', 'Last year of work') ?>
										</div>
										<select name="CompanyReview[date_end]" class="select j-select">
											<?php for($i=0; $i < 5; $i++): ?>
												<option value="<?= date("Y",strtotime('-'.$i.' year')) ?>-01-01"><?= date("Y",strtotime('-'.$i.' year')) ?></option>
											<?php endfor; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div id="bl2">
							<div class="review-add__step row" style="margin-top: 15px;" id="bl2">
								<div class="col"><?= Yii::t('company-review', 'Company valuation') ?></div>
								<div class="col"><?= Yii::t('main', 'Step') ?> 2</div>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('rating_total') ?>
											<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
										</div>
										<div class="review-add__rating j-rating-bl">
											<div class="review-add__stars">
												<select name="CompanyReview[rating_total]" class="j-rating">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4" selected>4</option>
													<option value="5">5</option>
												</select>
											</div>
											<div class="review-add__rating-input">
												<div class="review-add__rating-input-inner">
													<div class="minus j-minus"></div>
													<div class="j-count-input count">4</div>
													<div class="plus j-plus"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('rating_salary') ?>
										</div>
										<div class="review-add__rating j-rating-bl">
											<div class="review-add__stars">
												<select name="CompanyReview[rating_salary]" class="j-rating">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4" selected>4</option>
													<option value="5">5</option>
												</select>
											</div>
											<div class="review-add__rating-input">
												<div class="review-add__rating-input-inner">
													<div class="minus j-minus"></div>
													<div class="j-count-input count">4</div>
													<div class="plus j-plus"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('rating_opportunities') ?>
										</div>
										<div class="review-add__rating j-rating-bl">
											<div class="review-add__stars">
												<select name="CompanyReview[rating_opportunities]" class="j-rating">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4" selected>4</option>
													<option value="5">5</option>
												</select>
											</div>
											<div class="review-add__rating-input">
												<div class="review-add__rating-input-inner">
													<div class="minus j-minus"></div>
													<div class="j-count-input count">4</div>
													<div class="plus j-plus"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('rating_bosses') ?>
										</div>
										<div class="review-add__rating j-rating-bl">
											<div class="review-add__stars">
												<select name="CompanyReview[rating_bosses]" class="j-rating">
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4" selected>4</option>
													<option value="5">5</option>
												</select>
											</div>
											<div class="review-add__rating-input">
												<div class="review-add__rating-input-inner">
													<div class="minus j-minus"></div>
													<div class="j-count-input count">4</div>
													<div class="plus j-plus"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="bl3">
							<div class="review-add__step row" style="margin-top: 15px;" id="bl3">
								<div class="col"><?= Yii::t('company-review', 'Opinion about the company') ?></div>
								<div class="col"><?= Yii::t('main', 'Step') ?> 3</div>
							</div>
							<div class="registration__input-bl">
								<div class="registration__input-title">
									<?= $model->getAttributeLabel('general_impression') ?>
									<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
								</div>
								<input v-validate="vv.model_company_review.general_impression" name='CompanyReview[general_impression]' class="form-control" type="text">
								<span v-show="errors.has('CompanyReview[general_impression]')" class="validation-error-message" v-cloak>
									{{ errors.first('CompanyReview[general_impression]') }}
								</span>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('pluses_impression') ?>
											<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
										</div>
										<textarea v-validate="vv.model_company_review.pluses_impression" name='CompanyReview[pluses_impression]' cols="30" rows="8"></textarea>
										<span v-show="errors.has('CompanyReview[pluses_impression]')" class="validation-error-message" v-cloak>
											{{ errors.first('CompanyReview[pluses_impression]') }}
										</span>
									</div>
								</div>
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('minuses_impression') ?>
											<span class="registration__input-required"><?= Yii::t('company-review', 'Required!') ?></span>
										</div>
										<textarea v-validate="vv.model_company_review.minuses_impression" name='CompanyReview[minuses_impression]' cols="30" rows="8"></textarea>
										<span v-show="errors.has('CompanyReview[minuses_impression]')" class="validation-error-message" v-cloak>
											{{ errors.first('CompanyReview[minuses_impression]') }}
										</span>
									</div>
								</div>
							</div>
						</div>
						<div id="bl4">
							<div class="review-add__step row" id="bl4">
								<div class="col"><?= Yii::t('company-review', 'Management Tips') ?></div>
								<div class="col"><?= Yii::t('main', 'Step') ?> 4</div>
							</div>
							<div class="registration__input-bl">
								<div class="registration__input-title">
									<?= $model->getAttributeLabel('tips_for_bosses') ?>
								</div>
								<input v-validate="vv.model_company_review.tips_for_bosses" name='CompanyReview[tips_for_bosses]' class="form-control" type="text">
								<span v-show="errors.has('CompanyReview[tips_for_bosses]')" class="validation-error-message" v-cloak>
									{{ errors.first('CompanyReview[tips_for_bosses]') }}
								</span>
							</div>
							<div class="review-add__row">
								<div class="review-add__col">
									<div class="registration__input-bl">
										<div class="registration__input-title">
											<?= $model->getAttributeLabel('worker_recommendation') ?>
										</div>
										<div class="review-add__radio-wrapper">
											<label class="radio">
												<input type="radio" name="CompanyReview[worker_recommendation]" class="btn" value="<?= CompanyReview::WORKER_RECOMMENDATION_YES ?>" v-model="company_review.worker_recommendation" checked="">
												<span class="btn"><?= Yii::t('company-review', '') ?><?=Yii::t('company-review', 'Recommend') ?></span>
											</label>
											<label class="radio">
												<input type="radio" name="CompanyReview[worker_recommendation]" class="btn" value="<?= CompanyReview::WORKER_RECOMMENDATION_NO ?>" v-model="company_review.worker_recommendation">
												<span class="btn btn--transparent"><?=Yii::t('company-review', 'Not recommend') ?></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="review-add__submit">
							<input type="submit" class="btn" value="<?=Yii::t('main', 'Send') ?>">
							<div class="review-add__politics">
								<?= Yii::t('company-review', 'In accordance with the privacy policy, we guarantee complete anonymity.') ?>
							</div>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			<div class="registration-sidebar col j-height-sticky-column">
				<div class="j-sticky">
					<div class="registration-sidebar__inner">
						<ul class="sidebar__list" style="margin-top: 10px;">
							<li>
								<a href="#bl1" class="j-scroll active"><?= Yii::t('company-review', 'about company') ?></a>
							</li>
							<li>
								<a href="#bl2" class="j-scroll"><?=Yii::t('company-review', 'Company valuation') ?></a>
							</li>
							<li>
								<a href="#bl3" class="j-scroll"><?=Yii::t('company-review', 'Opinion about the company') ?></a>
							</li>
							<li>
								<a href="#bl4" class="j-scroll"><?= Yii::t('company-review', 'Management Tips') ?></a>
							</li>
						</ul>
					</div>
					<div class="registration-sidebar__inner registration-sidebar__inner--warning">
						<ul class="sidebar__list">
							<li>
								<div class="item active"><?= Yii::t('company-review', 'FORBIDDEN!') ?></div>
							</li>
							<li>
								<div class="item"><?= Yii::t('company-review', 'Math, insults, spam') ?></div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
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
  el: '#appAddReview',
  data: {
    response_errors: void 0,
    submit_clicked: false,
    vv: {
        model_company_review: {
            position: '<?= VeeValidateHelper::getVValidateString($this, $model, 'position') ?>',
            worker_status: '<?= VeeValidateHelper::getVValidateString($this, $model, 'worker_status') ?>',
            department: '<?= VeeValidateHelper::getVValidateString($this, $model, 'department') ?>',
            date_end: '<?= VeeValidateHelper::getVValidateString($this, $model, 'date_end') ?>',
            rating_total: '<?= VeeValidateHelper::getVValidateString($this, $model, 'rating_total') ?>',
            rating_salary: '<?= VeeValidateHelper::getVValidateString($this, $model, 'rating_salary') ?>',
            rating_opportunities: '<?= VeeValidateHelper::getVValidateString($this, $model, 'rating_opportunities') ?>',
            rating_bosses: '<?= VeeValidateHelper::getVValidateString($this, $model, 'rating_bosses') ?>',
            general_impression: '<?= VeeValidateHelper::getVValidateString($this, $model, 'general_impression') ?>',
            pluses_impression: '<?= VeeValidateHelper::getVValidateString($this, $model, 'pluses_impression') ?>',
            minuses_impression: '<?= VeeValidateHelper::getVValidateString($this, $model, 'minuses_impression') ?>',
            tips_for_bosses: '<?= VeeValidateHelper::getVValidateString($this, $model, 'tips_for_bosses') ?>',
            worker_recommendation: '<?= VeeValidateHelper::getVValidateString($this, $model, 'worker_recommendation') ?>',
        },
	},
	company_review: {
		worker_recommendation: '<?= CompanyReview::WORKER_RECOMMENDATION_YES ?>',
		worker_status: '<?= CompanyReview::WORKER_STATUS_WORKING ?>',
	}
  },
  mounted: function() {
	var minus = '.j-minus',
		plus = '.j-plus',
		input = '.j-count-input',
		wrapper = '.j-rating-bl';

	//stars rating
	$('.j-rating').each(function() {
		var _this = $(this),
			_input = _this.closest(wrapper).find(input);

		_this.barrating({
			theme: 'css-stars',
			onSelect: function(value, text, event) {
				_input.text(value);
			}
		});
	});

	$(minus).on('click', function() {
		var _this = $(this),
			val = _this.siblings(input).text();

		if(val > 1) {
			_this.siblings(input).text(val-1);
			changeInput(_this);
		}
	});
	$(plus).on('click', function() {
		var _this = $(this),
			val = _this.siblings(input).text();

		if(val < 5) {
			_this.siblings(input).text(Number(val)+1);
			changeInput(_this);
		}
	});

	function changeInput(element) {
		var _this = $(element),
			rating = _this.closest(wrapper).find('.j-rating');
			value = _this.siblings(input).text();

		$(rating).barrating('set', value);
	}
  },
  methods: {
    onSubmit () {
      this.$validator.validate().then(valid => {
        this.$data.submit_clicked = true;
        if (!valid) {
            this.scrollToErrorblock();
            return;
        }

        // this.$refs.form.submit();

        let post_data = {};
        for(let i = 0; i < this.$refs.form.length; i++) {
            if(!this.$refs.form[i].name) {
                continue;
            }
            
            post_data[this.$refs.form[i].name] = this.$refs.form[i].value;
		}

		// fix radio value
		post_data['CompanyReview[worker_status]'] = this.$data.company_review.worker_status;
		post_data['CompanyReview[worker_recommendation]'] = this.$data.company_review.worker_recommendation;
		
        // send data to server via AJAX POST
        let me = this;
        $.post( window.location.pathname + '?id='+<?= $model_company->id ?>, post_data)
            .always(function ( response ) { // got response http redirrect or got response validation error messages
                if(response.success === false && response.errors) {
                    // insert errors
                    me.$data.response_errors = response.errors;
                    // scroll to top
                    me.scrollToErrorblock();
                } else {
                    if(response.status == 302) { // success, then redirrect
                        return;
                    }
                    
                    if(response && response.status) {
                        alert('Error, code: ' + response.status + ', message: ' + response.statusText);
                    } else {
                        alert('Connection problem, try again later');
                    }
                }
            });
      });
    },
    
    scrollToErrorblock: function() {
        Vue.nextTick(function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".response-errors-block").offset().top - 200
            }, 600);
        });
    },
  }
})
</script>
<?php $this->endJs(); ?>