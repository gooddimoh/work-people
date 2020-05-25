<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tarif;
/* @var $this yii\web\View */
/* @var $tarifList app\models\Tarif */

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

$this->title = Yii::t('service', 'Services and prices');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('service', 'Services and accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$tarifListGrouped = ArrayHelper::index($tarifList, null, 'tarif_type');
$tarif_type_list = Tarif::getTarifTypeList();
?>
<div id="appTarif" class="info-company">
	<div class="container">
		<div class="title-sec">
			<?= Html::encode($this->title) ?>
		</div>
		<div class="row info-tarif_row">
			<?php foreach($tarifListGrouped as $tarif_type => $tarif) { ?>
				<div class="col">
					<div class="trud-if__bl">
						<div class="trud-if__title">
							<?= Yii::t('tarif', $tarif_type_list[$tarif_type]) ?>
						</div>
						<ul class="trud-if__list">
							<li>
								<span>1</span>
								<div>
									Сделали оплату агентству - агентство заблокировало ваш телефон и пропало.
								</div>
							</li>
							<li>
								<span>2</span>
								<div>
									Сделали предоплату агентству - агентство нарушили сроки и деньги не вернули. 
								</div>
							</li>
							<li>
								<span>3</span>
								<div>
									Посредник пообещал зарплату 1000$, а зарабатываете 600$
								</div>
							</li>
							<li>
								<span>4</span>
								<div>
									Поехал через агентство и доволен работой. Порекомендовал друзьям, в итоге 10 его друзей обманули. 
								</div>
							</li>
							<li>
								<span>5</span>
								<div>
									Едите в никуда, не зная работодателя и отзывов о нем, в результате, плохое отношение к людям и задержки в зарплатах.
								</div>
							</li>
						</ul>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>


<?php $this->beginJs(); ?>
<script>

new Vue({
  el: '#appTarif',
  data: {
    tarif_list: [

	],
	edit_mode: false
  },
  mounted: function() {

  },
  methods: {
    onSubmit () {
      return; // supress submit
    },
    previousStep: function() {
        this.$data.form_current_step = this.$data.form_current_step - 1;
        this.upgradeStepAnchor();
        
        //! BUG, need fix navigate(validate) previous step when invalid data on current step(or send data to server)
        // + need use data-vv-scope to prevent validate next step when user step back
    },
    
    // --
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
  }
})
</script>
<?php $this->endJs(); ?>
