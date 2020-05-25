<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company;

/**
 * CompanySearch represents the model behind the search form of `app\models\Company`.
 */
class CompanySearch extends Company
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'country_city_id', 'type', 'number_of_employees'], 'integer'],
            [['company_name', 'company_phone', 'company_email', 'company_country_code', 'logo', 'type_industry', 'site', 'description', 'document_code'], 'safe'],
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
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'country_city_id' => $this->country_city_id,
            'type' => $this->type,
            'number_of_employees' => $this->number_of_employees,
        ]);

        $query->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_phone', $this->company_phone])
            ->andFilterWhere(['like', 'company_email', $this->company_email])
            ->andFilterWhere(['like', 'company_country_code', $this->company_country_code])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'type_industry', $this->type_industry])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'document_code', $this->document_code]);

        return $dataProvider;
    }
}
