<?php
namespace app\components\rest;

use Yii;
use yii\rest\Action;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\components\rest\ActionHelpers;
use yii\web\BadRequestHttpException;

class IndexAction extends Action
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


        $sort = ActionHelpers::getSort();

        $filters = ActionHelpers::getFilter();
        foreach($filters as $filter) {
            $query->andFilterWhere($filter);
        }

        $page = empty($_GET['page']) ? 0 : (int)$_GET['page'];
        $data = $query
            ->limit(ActionHelpers::getLimit())
            ->offset($page * ActionHelpers::getLimit())
            ->orderBy($sort)
            ->asArray()->all();

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
