<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\CategoryJob;
use app\models\CategoryJobSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\User;

/**
 * CategoryJobController implements the CRUD actions for CategoryJob model.
 */
class CategoryJobController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                    'move' => ['POST'],
                ],
            ],
            // ---
            'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [ // add access control by `role`
					'class' => 'app\components\AccessRule'
				],
                'rules' => [
					// [
                        // 'actions' => ['index'],
                        // 'allow' => false,
                        // 'roles' => ['?', '@'],
                    // ],
					[
                        'actions' => ['index', 'create', 'update', 'delete', 'move', 'edit'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMINISTRATOR],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CategoryJob models.
     * @return mixed
     */
    public function actionIndex()
    {
        $result = $this->makeTreeJS();

        return $this->render('index', ["categories" => json_encode($result)]);
    }

    /**
     * Creates a new CategoryJob model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ["success" => false, "response" => "Не удалось добавить категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");

        $parent_node = CategoryJob::findOne(["id" => $node["parent"]]);
        
        $new_node    = new CategoryJob(["name" => $node["text"]]);

        $result["success"] = $new_node->appendTo($parent_node);

        if ($result["success"]) {
            $result["data"]["id"] = $new_node->id;
            $result["response"]   = "Категория успешно добавлена";
        }

        return $result;
    }

    /**
     * Updates an existing CategoryJob model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ["success" => false, "response" => "Не удалось переименовать категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");
		
        $model = CategoryJob::findOne($node["id"]);
		
		if($model === null) {
			return ["success" => false, "response" => "Не удалось найти категорию", "data" => []];
		}
		
        if(!empty($node["original"]["meta_keywords"])) {
            $model->meta_keywords = $node["original"]["meta_keywords"];
        }
        
        if(!empty($node["original"]["meta_description"])) {
            $model->meta_description = $node["original"]["meta_description"];
        }
        
        $model->name       = $node["text"];
        $result["success"] = $model->save();

        if ($result["success"]) {
            $result["response"] = "Категория успешно переименована";
        }
        
        return $result;
    }

    /**
     * Deletes an existing CategoryJob model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $node   = \Yii::$app->request->post("node");
        $result = ["success" => false, "response" => "Категория содержит вложенные элементы", "data" => []];

        $result["success"] = CategoryJob::findOne($node["id"])->deleteWithChildren();

        if ($result["success"]) {
            $result["response"] = "Категория удалена";
        }

        return $result;
    }

    /**
     * Move an existing CategoryJob model.
     * @param integer $id
     * @return json
     */
    public function actionMove()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ["success" => false, "response" => "Не удалось переместить категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");

        $anchor = CategoryJob::findOne(["id" => $node["anchor_id"]]);
        $moving = CategoryJob::findOne(["id" => $node["moving_id"]]);

        switch ($node['action']) {
            case 'before' :
                $result["success"] = $anchor->insertBefore($moving);
                break;
            case 'after' :
                $result["success"] = $anchor->insertAfter($moving);
                break;
            case 'inside' :
                $result["success"] = $anchor->prependTo($moving);
                break;
        }

        if ($result["success"]) {
            $result["response"] = "Категория успешно перемещена";
        }

        return $result;
    }

    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the CategoryJob model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CategoryJob the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CategoryJob::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Get categories tree
     *
     * @param $category
     * @param $max_depth
     * @param $current_depth
     *
     * @return array
     */
    private function makeTreeJS($category = [], $max_depth = 0, $current_depth = 0)
    {
        if (!$category) {
            $category = current(CategoryJob::find()->roots()->all());
        }

        $node = [
			"id" => $category->id,
			"text" => $category->name,
			"meta_keywords" => $category->meta_keywords,
			"meta_description" => $category->meta_description,
			"type" => "category", "children" => []
		];

        if ($current_depth <= $max_depth) {
            if ($max_depth !== 0) {
                $current_depth++;
            }

            $childrens = $category->children(1)->all();

            if ($childrens) {
                foreach ($childrens as $key_children => $children) {
                    $node["children"][] = $this->makeTreeJS($children, $max_depth, $current_depth);
                }
            }
        }

        return $node;
    }
}
