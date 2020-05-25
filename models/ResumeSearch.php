<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Resume;
use app\models\CategoryJob;

/**
 * ResumeSearch represents the model behind the search form of `app\models\Resume`.
 */
class ResumeSearch extends Resume
{
    public $category_job_list;
    public $age_min;
    public $age_max;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'use_title', 'use_job_experience', 'use_language', 'relocation_possible', 'use_full_import_description_cleaned', 'country_city_id', 'creation_time', 'upvote_time', 'update_time'], 'integer'],
            [['title', 'job_experience', 'language', 'full_import_description', 'full_import_description_cleaned', 'source_url', 'first_name', 'last_name', 'middle_name', 'email', 'gender_list', 'birth_day', 'country_name', 'desired_salary_currency_code', 'desired_country_of_work', 'photo_path', 'phone', 'custom_country', 'description', 'category_job_list'], 'safe'],
            [['desired_salary', 'desired_salary_per_hour'], 'number'],
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
        $query = Resume::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id',
                    'newest' => [
                        'asc' => ['upvote_time' => SORT_ASC, 'upvote_time' => SORT_ASC],
                        'desc' => ['upvote_time' => SORT_DESC, 'upvote_time' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => Yii::t('resume', 'Date update'),
                    ],
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->category_job_list)) {
            $category_job_list = [];
            if(is_array($this->category_job_list)) {
                foreach($this->category_job_list as $category_id) {
                    $category_job_list[] = intval($category_id);
                }
            } else {
                $category_job_list[] = intval($this->category_job_list);
            }

            $category_job_list_with_childs = $category_job_list;

            // disabled, find by all child id's
            // foreach($category_job_list as $category_job_id) {
            //     $category_job_list_with_childs = array_merge($category_job_list_with_childs, CategoryJob::getChildIds(intval($category_job_id)));
            // }
            
            // or use `resume_category_last_job`
            $query->join('INNER JOIN','resume_category_job','resume_category_job.resume_id = resume.id')
                    ->andFilterWhere(
                        ['in', 'resume_category_job.category_job_id', $category_job_list_with_childs]
                    );
        }

        if (!empty($this->age_min)) {
            $query->andFilterWhere(
                ['<', 'birth_day', date('Y-m-d', strtotime('-' . intval($this->age_min) . ' year'))]
            );
        }

        if (!empty($this->age_max)) {
            $query->andFilterWhere(
                ['>', 'birth_day', date('Y-m-d', strtotime('-' . intval($this->age_max) . ' year'))]
            );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'use_title' => $this->use_title,
            'use_job_experience' => $this->use_job_experience,
            'use_language' => $this->use_language,
            'relocation_possible' => $this->relocation_possible,
            'birth_day' => $this->birth_day,
            'desired_salary' => $this->desired_salary,
            'desired_salary_per_hour' => $this->desired_salary_per_hour,
            'creation_time' => $this->creation_time,
            'upvote_time' => $this->upvote_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'job_experience', $this->job_experience])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'full_import_description', $this->full_import_description])
            ->andFilterWhere(['like', 'full_import_description_cleaned', $this->full_import_description_cleaned])
            ->andFilterWhere(['like', 'source_url', $this->source_url])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'gender_list', $this->gender_list])
            ->andFilterWhere(['like', 'desired_salary_currency_code', $this->desired_salary_currency_code])
            ->andFilterWhere(['like', 'desired_country_of_work', $this->desired_country_of_work])
            ->andFilterWhere(['like', 'photo_path', $this->photo_path])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'custom_country', $this->custom_country])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
