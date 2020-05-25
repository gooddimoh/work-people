<?php
namespace app\components\rest;

use Yii;
use yii\rest\Action;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\components\rest\ActionHelpers;
use yii\web\BadRequestHttpException;

class IndexActionCountry extends Action
{
    public $prepareDataProvider;
	
    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

		/* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;
		$query = $modelClass::find();

        $params = Yii::$app->request->queryParams;

        if(isset($params['id'])) {
            $query->andFilterWhere([
                'id' => $params['id'],
            ]);
        }

        if(isset($params['country_char_code'])) {
            $query->andFilterWhere([
                'country_char_code' => $params['country_char_code'],
            ]);
        }

        if(isset($params['city_name'])) {
            $query->andFilterWhere(['like', 'city_name', $params['city_name']]);
        }
		
        // return new ActiveDataProvider([
        //     'query' => $query,
        //     'pagination' => [
        //         'pageSize' => 10
        //     ],
        //     'sort'=> ['defaultOrder' => ['priority' => SORT_DESC, 'id' => SORT_ASC]]
        // ]);
        $sort = ActionHelpers::getSort();

        $page = empty($_GET['page']) ? 0 : (int)$_GET['page'];
        $data = $query
            ->limit(ActionHelpers::getLimit())
            ->offset($page * ActionHelpers::getLimit())
            ->orderBy($sort)
            ->asArray()->all();
        
        // translate
        foreach($data as $key => &$item) {
            $item['city_name'] = Yii::t('city', $item['city_name']);
        }

        return new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => ActionHelpers::getLimit()
            ],
            'totalCount' => $query->count(),
            'sort'=> $sort
            // 'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }
}
