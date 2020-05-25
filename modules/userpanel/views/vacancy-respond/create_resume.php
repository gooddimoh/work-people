<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\VeeValidateHelper;

/* @var $this yii\web\View */
/* @var $model app\models\VacancyRespond */

$this->title = Yii::t('vacancy', 'Apply for worker');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'User Panel'), 'url' => ['/userpanel/default/index']];
// $this->params['breadcrumbs'][] = ['label' => Yii::t('vacancy', 'Response History'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerJsFile('/js/app/dist/vue.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/dist/vee-validate.js'); //! BUG, need setup asset manager
$this->registerJsFile('/js/app/component/nice-select.wrapper.js'); //! BUG, need setup asset manager

?>

<?= $this->render('_form_create_resume', [
    'model' => $model,
    'model_resume' => $model_resume,
    'my_vacancy_list' => $my_vacancy_list,
]) ?>

