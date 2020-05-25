<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyReview;

/**
 * CompanyReviewSearch represents the model behind the search form of `app\models\CompanyReview`.
 */
class CompanyReviewSearch extends CompanyReview
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'company_id', 'worker_status', 'rating_total', 'rating_salary', 'rating_opportunities', 'rating_bosses', 'worker_recommendation'], 'integer'],
            [['position', 'department', 'date_end', 'general_impression', 'pluses_impression', 'minuses_impression', 'tips_for_bosses'], 'safe'],
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
        $query = CompanyReview::find();

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
            'status' => $this->status,
            'company_id' => $this->company_id,
            'worker_status' => $this->worker_status,
            'date_end' => $this->date_end,
            'rating_total' => $this->rating_total,
            'rating_salary' => $this->rating_salary,
            'rating_opportunities' => $this->rating_opportunities,
            'rating_bosses' => $this->rating_bosses,
            'worker_recommendation' => $this->worker_recommendation,
        ]);

        $query->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'general_impression', $this->general_impression])
            ->andFilterWhere(['like', 'pluses_impression', $this->pluses_impression])
            ->andFilterWhere(['like', 'minuses_impression', $this->minuses_impression])
            ->andFilterWhere(['like', 'tips_for_bosses', $this->tips_for_bosses]);

        return $dataProvider;
    }
}
