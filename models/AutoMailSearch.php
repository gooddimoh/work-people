<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AutoMail;

/**
 * AutoMailSearch represents the model behind the search form of `app\models\AutoMail`.
 */
class AutoMailSearch extends AutoMail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'status', 'use_messenger', 'created_at'], 'integer'],
            [['request', 'country_codes', 'location'], 'safe'],
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
        $query = AutoMail::find();

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
            'category_id' => $this->category_id,
            'status' => $this->status,
            'use_messenger' => $this->use_messenger,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'request', $this->request])
            ->andFilterWhere(['like', 'country_codes', $this->country_codes])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
