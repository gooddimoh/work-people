<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vacancy;

/**
 * VacancySearch represents the model behind the search form of `app\models\Vacancy`.
 */
class VacancySearch extends Vacancy
{
    public $category_list;
    public $category_job_list;
    public $salary_currency;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'category_job_id', 'user_phone_id', 'status', 'pin_position', 'special_status', 'show_on_main_page', 'main_page_priority', 'age_min', 'age_max', 'free_places', 'regular_places', 'date_free', 'country_city_id', 'residence_provided', 'residence_people_per_room', 'use_full_import_description_cleaned', 'agency_accept', 'agency_paid_document', 'agency_free_document', 'agency_pay_commission', 'secure_deal', 'creation_time', 'upvote_time', 'update_time'], 'integer'],
            [['title', 'company_name', 'gender_list', 'worker_country_codes', 'date_start', 'date_end', 'country_name', 'currency_code', 'type_of_working_shift', 'residence_amount_currency_code', 'documents_provided', 'documents_required', 'full_import_description', 'full_import_description_cleaned', 'source_url', 'job_description', 'job_description_bonus', 'contact_name', 'contact_phone', 'contact_email_list', 'main_image', 'agency_paid_document_currency_code', 'agency_pay_commission_currency_code', 'meta_keywords', 'meta_description', 'category_list', 'category_job_list'], 'safe'],
            [['salary_per_hour_min', 'salary_per_hour_max', 'salary_per_hour_min_src', 'salary_per_hour_max_src', 'hours_per_day_min', 'hours_per_day_max', 'days_per_week_min', 'days_per_week_max', 'prepaid_expense_min', 'prepaid_expense_max', 'residence_amount', 'agency_paid_document_price', 'agency_pay_commission_amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Vacancy::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => [ 'pageSize' => 1 ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'newest' => [
                        'asc' => ['creation_time' => SORT_ASC, 'creation_time' => SORT_ASC],
                        'desc' => ['creation_time' => SORT_DESC, 'creation_time' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('vacancy', 'The newest'),
                    ],
                    'salary' => [
                        'asc' => ['salary_per_hour_min' => SORT_ASC, 'salary_per_hour_max' => SORT_ASC],
                        'desc' => ['salary_per_hour_min' => SORT_DESC, 'salary_per_hour_max' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('vacancy', 'Salary per hour'),
                    ],
                    'salary_month' => [
                        'asc' => ['prepaid_expense_min' => SORT_ASC, 'prepaid_expense_max' => SORT_ASC],
                        'desc' => ['prepaid_expense_min' => SORT_DESC, 'prepaid_expense_max' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('vacancy', 'Highest monthly salary per month'),
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->category_list)) {
            $query->join('INNER JOIN','category_vacancy','category_vacancy.vacancy_id = vacancy.id AND category_vacancy.category_id = :category_id')
                    ->addParams([':category_id' => (int)$this->category_list]);
        }

        // var_dump($this->category_job_list);
        // die();
        if(!empty($this->category_job_list)) {
            $query->andFilterWhere(
               ['in', 'category_job_id', $this->category_job_list]
            );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'category_job_id' => $this->category_job_id,
            'user_phone_id' => $this->user_phone_id,
            'status' => $this->status,
            'pin_position' => $this->pin_position,
            'special_status' => $this->special_status,
            'show_on_main_page' => $this->show_on_main_page,
            'main_page_priority' => $this->main_page_priority,
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'free_places' => $this->free_places,
            'regular_places' => $this->regular_places,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'date_free' => $this->date_free,
            'country_city_id' => $this->country_city_id,
            'salary_per_hour_min' => $this->salary_per_hour_min,
            'salary_per_hour_max' => $this->salary_per_hour_max,
            'salary_per_hour_min_src' => $this->salary_per_hour_min_src,
            'salary_per_hour_max_src' => $this->salary_per_hour_max_src,
            'hours_per_day_min' => $this->hours_per_day_min,
            'hours_per_day_max' => $this->hours_per_day_max,
            'days_per_week_min' => $this->days_per_week_min,
            'days_per_week_max' => $this->days_per_week_max,
            'prepaid_expense_min' => $this->prepaid_expense_min,
            'prepaid_expense_max' => $this->prepaid_expense_max,
            'residence_provided' => $this->residence_provided,
            'residence_amount' => $this->residence_amount,
            'residence_people_per_room' => $this->residence_people_per_room,
            'use_full_import_description_cleaned' => $this->use_full_import_description_cleaned,
            'agency_accept' => $this->agency_accept,
            'agency_paid_document' => $this->agency_paid_document,
            'agency_paid_document_price' => $this->agency_paid_document_price,
            'agency_free_document' => $this->agency_free_document,
            'agency_pay_commission' => $this->agency_pay_commission,
            'agency_pay_commission_amount' => $this->agency_pay_commission_amount,
            'secure_deal' => $this->secure_deal,
            'creation_time' => $this->creation_time,
            'upvote_time' => $this->upvote_time,
            'update_time' => $this->update_time,
        ]);

        if (!empty($this->worker_country_codes)) {
            $query->andFilterWhere(['like', 'worker_country_codes', $this->worker_country_codes])
                ->orWhere(['is', 'worker_country_codes', null]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'gender_list', $this->gender_list])
            ->andFilterWhere(['like', 'country_name', $this->country_name])
            ->andFilterWhere(['like', 'currency_code', $this->currency_code])
            ->andFilterWhere(['like', 'type_of_working_shift', $this->type_of_working_shift])
            ->andFilterWhere(['like', 'residence_amount_currency_code', $this->residence_amount_currency_code])
            ->andFilterWhere(['like', 'documents_provided', $this->documents_provided])
            ->andFilterWhere(['like', 'documents_required', $this->documents_required])
            ->andFilterWhere(['like', 'full_import_description', $this->full_import_description])
            ->andFilterWhere(['like', 'full_import_description_cleaned', $this->full_import_description_cleaned])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'job_description', $this->job_description])
            ->andFilterWhere(['like', 'job_description_bonus', $this->job_description_bonus])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'contact_email_list', $this->contact_email_list])
            ->andFilterWhere(['like', 'main_image', $this->main_image])
            ->andFilterWhere(['like', 'agency_paid_document_currency_code', $this->agency_paid_document_currency_code])
            ->andFilterWhere(['like', 'agency_pay_commission_currency_code', $this->agency_pay_commission_currency_code])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        //! BUG, `salary_currency` not working need convert to source currency

        return $dataProvider;
    }
}
