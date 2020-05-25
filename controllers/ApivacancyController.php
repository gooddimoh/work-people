<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

// GET param "_format=json"
// OR Accept: application/json; q=1.0, */*; q=0.1
/**
 * search params example /api/vacancy?vacancy_char_code=RU&city_name=mosc
 */
	
class ApivacancyController extends ActiveController
{
    public $modelClass = 'app\models\Vacancy';
    
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    
    public function actions()
    {
        $actions = parent::actions();
        
        // disable the "delete" and "create" actions
		unset(
			// $actions['index'],
			$actions['view'],
			$actions['delete'],
			$actions['create'],
			$actions['update'],
			$actions['options']
        );
        
        $actions['index'] = [
            'class' => 'app\components\rest\IndexAction',
            'modelClass' => $this->modelClass
        ];
        
		return $actions;
    }
	
}
