<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VacancySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vacancy-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'company_id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'pin_position') ?>

    <?= $form->field($model, 'special_status') ?>

    <?php // echo $form->field($model, 'show_on_main_page') ?>

    <?php // echo $form->field($model, 'main_page_priority') ?>

    <?php // echo $form->field($model, 'company_name') ?>

    <?php // echo $form->field($model, 'job_title') ?>

    <?php // echo $form->field($model, 'gender_list') ?>

    <?php // echo $form->field($model, 'age_min') ?>

    <?php // echo $form->field($model, 'age_max') ?>

    <?php // echo $form->field($model, 'employment_type') ?>

    <?php // echo $form->field($model, 'worker_country_codes') ?>

    <?php // echo $form->field($model, 'free_places') ?>

    <?php // echo $form->field($model, 'regular_places') ?>

    <?php // echo $form->field($model, 'date_start') ?>

    <?php // echo $form->field($model, 'date_end') ?>

    <?php // echo $form->field($model, 'date_free') ?>

    <?php // echo $form->field($model, 'country_name') ?>

    <?php // echo $form->field($model, 'salary_per_hour_min') ?>

    <?php // echo $form->field($model, 'salary_per_hour_max') ?>

    <?php // echo $form->field($model, 'salary_per_hour_min_src') ?>

    <?php // echo $form->field($model, 'salary_per_hour_max_src') ?>

    <?php // echo $form->field($model, 'currency_code') ?>

    <?php // echo $form->field($model, 'hours_per_day_min') ?>

    <?php // echo $form->field($model, 'hours_per_day_max') ?>

    <?php // echo $form->field($model, 'days_per_week_min') ?>

    <?php // echo $form->field($model, 'days_per_week_max') ?>

    <?php // echo $form->field($model, 'prepaid_expense_min') ?>

    <?php // echo $form->field($model, 'prepaid_expense_max') ?>

    <?php // echo $form->field($model, 'type_of_working_shift') ?>

    <?php // echo $form->field($model, 'residence_provided') ?>

    <?php // echo $form->field($model, 'residence_amount') ?>

    <?php // echo $form->field($model, 'residence_amount_currency_code') ?>

    <?php // echo $form->field($model, 'residence_people_per_room') ?>

    <?php // echo $form->field($model, 'documents_provided') ?>

    <?php // echo $form->field($model, 'documents_required') ?>

    <?php // echo $form->field($model, 'job_description') ?>

    <?php // echo $form->field($model, 'job_description_bonus') ?>

    <?php // echo $form->field($model, 'contact_email_list') ?>

    <?php // echo $form->field($model, 'main_image') ?>

    <?php // echo $form->field($model, 'agency_accept') ?>

    <?php // echo $form->field($model, 'agency_paid_document') ?>

    <?php // echo $form->field($model, 'agency_paid_document_price') ?>

    <?php // echo $form->field($model, 'agency_paid_document_currency_code') ?>

    <?php // echo $form->field($model, 'agency_free_document') ?>

    <?php // echo $form->field($model, 'agency_pay_commission') ?>

    <?php // echo $form->field($model, 'agency_pay_commission_amount') ?>

    <?php // echo $form->field($model, 'agency_pay_commission_currency_code') ?>

    <?php // echo $form->field($model, 'secure_deal') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'meta_description') ?>

    <?php // echo $form->field($model, 'creation_time') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
