<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Category;
use app\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use app\models\User;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $result = $this->makeTreeJS();

        return $this->render('index', ["categories" => json_encode($result)]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ["success" => false, "response" => "Не удалось добавить категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");

        $parent_node = Category::findOne(["id" => $node["parent"]]);
        
        $new_node    = new Category(["name" => $node["text"]]);

        $result["success"] = $new_node->appendTo($parent_node);

        if ($result["success"]) {
            $result["data"]["id"] = $new_node->id;
            $result["response"]   = "Категория успешно добавлена";
        }

        return $result;
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ["success" => false, "response" => "Не удалось переименовать категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");
		
        $model = Category::findOne($node["id"]);
		
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
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $node   = \Yii::$app->request->post("node");
        $result = ["success" => false, "response" => "Категория содержит вложенные элементы", "data" => []];

        $result["success"] = Category::findOne($node["id"])->deleteWithChildren();

        if ($result["success"]) {
            $result["response"] = "Категория удалена";
        }

        return $result;
    }

    /**
     * Move an existing Category model.
     * @param integer $id
     * @return json
     */
    public function actionMove()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ["success" => false, "response" => "Не удалось переместить категорию", "data" => []];
        $node   = \Yii::$app->request->post("node");

        $anchor = Category::findOne(["id" => $node["anchor_id"]]);
        $moving = Category::findOne(["id" => $node["moving_id"]]);

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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
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
            $category = current(Category::find()->roots()->all());
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
