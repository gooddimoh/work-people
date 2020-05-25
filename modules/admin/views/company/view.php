<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Company;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = Html::encode($model->company_name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('company', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('main', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('main', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            // 'user_id',
            [
                'label' => Yii::t('company', 'User ID'),
                'value' => $model->user->login . ' (' . $model->user->email . ')',
            ],
            // 'status',
            [
                'label' => Yii::t('company', 'Status'),
                'value'=> function($model) {
                    $status_list = Company::getStatusList();
                    return $status_list[$model->status] ;
                },
            ],
            'company_name',
            'company_phone',
            'company_email',
            'company_country_code',
            'logo',
            // 'type',
            [
                'label' => Yii::t('company', 'Type'),
                'value'=> function($model) {
                    $type_list = Company::getTypeList();
                    return $type_list[$model->type] ;
                },
            ],
            'type_industry',
            // 'number_of_employees',
            [
                'label' => Yii::t('company', 'Number Of Employees'),
                'value'=> function($model) {
                    $item_list = Company::getNumberOfEmployeesList();
                    return $item_list[$model->number_of_employees] ;
                },
            ],
            // 'worker_country_codes',
            [
                'label' => Yii::t('company', 'Worker Country Codes'),
                'value'=> function($model) {
                    $worker_countries = ArrayHelper::getColumn($model->getCompanyCountries(), 'name');
                    foreach($worker_countries as &$country) { $country = Yii::t('country', $country); } // translate
                    return implode(', ', $worker_countries);
                },
            ],
            'site:url',
            'contact_name',
            'contact_phone',
            'contact_email:email',
            'description:ntext',
            'document_code',
        ],
    ]) ?>

</div>
