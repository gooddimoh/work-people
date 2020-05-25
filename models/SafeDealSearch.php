<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SafeDeal;

/**
 * SafeDealSearch represents the model behind the search form of `app\models\SafeDeal`.
 */
class SafeDealSearch extends SafeDeal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'creator_id', 'status', 'deal_type', 'has_prepaid', 'execution_period', 'execution_range', 'possible_delay_days', 'comission'], 'integer'],
            [['title', 'amount_currency_code', 'amount_prepaid_currency_code', 'condition_prepaid', 'condition_deal', 'started_at', 'finished_at', 'created_at', 'updated_at'], 'safe'],
            [['amount_total', 'amount_total_src', 'amount_prepaid'], 'number'],
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
        $query = SafeDeal::find();

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
            'creator_id' => $this->creator_id,
            'status' => $this->status,
            'deal_type' => $this->deal_type,
            'has_prepaid' => $this->has_prepaid,
            'amount_total' => $this->amount_total,
            'amount_total_src' => $this->amount_total_src,
            'amount_prepaid' => $this->amount_prepaid,
            'execution_period' => $this->execution_period,
            'execution_range' => $this->execution_range,
            'possible_delay_days' => $this->possible_delay_days,
            'comission' => $this->comission,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'amount_currency_code', $this->amount_currency_code])
            ->andFilterWhere(['like', 'amount_prepaid_currency_code', $this->amount_prepaid_currency_code])
            ->andFilterWhere(['like', 'condition_prepaid', $this->condition_prepaid])
            ->andFilterWhere(['like', 'condition_deal', $this->condition_deal]);

        return $dataProvider;
    }
}
