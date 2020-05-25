<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\Message;
use app\models\MessageSearch;
use app\models\MessageRoom;
use app\models\MessageRoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\HtmlPurifier;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'actions' => [
                            'index', 'view', 'create', 'changefavorite', 'changearchive',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MessageRoom models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['/userpanel/profile/view']);
        }

        $searchModel = new MessageRoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider->query->andFilterWhere([
            'user_id' => Yii::$app->user->id
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays Message's from MessageRoom.
     * @param integer $id - `sender_id`
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['/userpanel/profile/view']);
        }
        
        $model = $this->findOrCreateModel($id, Yii::$app->user->id);
        $modelMessage = new Message();

        // mark room messages readed
        Yii::$app->db->createCommand()->update(
            Message::tableName(), [
                'status' => Message::STATUS_READED
            ], 'message_room_id = :message_room_id'
        )->bindValue(':message_room_id', $model->id)->execute();

        
        return $this->render('view', [
            'model' => $model,
            'modelMessage' => $modelMessage,
        ]);
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        if(empty(Yii::$app->user->identity->profile)) {
            return $this->redirect(['/userpanel/profile/view']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model_room = $this->findOrCreateModel($id, Yii::$app->user->id);
        $model = new Message();

        if (Yii::$app->request->isPost) {
            // -- create current user message
            //! BUG, can be vulnerability need filter post data
            $post_data = Yii::$app->request->post();
            $post_data['Message']['owner_id'] = $model_room->user_id;
            $post_data['Message']['message_room_id'] = $model_room->id;
            $post_data['Message']['for_user_id'] = $model_room->user_id; //? skip, message already readed
            $post_data['Message']['status'] = Message::STATUS_READED;
            $post_data['Message']['message_text'] = HtmlPurifier::process($post_data['Message']['message_text']);
            // $post_data['Message']['created_at'] = time(); // behaviors update it
            
            if ($model->loadAll($post_data) && $model->saveAll()) {
                $model_other = new Message();
                // -- create message for other user
                $model_room_other = $this->findOrCreateModel($model_room->user_id, $id);
                $post_data_other = $post_data;
                // $post_data['Message']['owner_id'] = $model_room->user_id;
                $post_data_other['Message']['message_room_id'] = $model_room_other->id;
                $post_data_other['Message']['for_user_id'] = $id;
                $post_data_other['Message']['status'] = Message::STATUS_UNREADED;
                // $post_data_other['Message']['message_text'] = HtmlPurifier::process($post_data_other['Message']['message_text']);

                if($model_other->loadAll($post_data_other) && $model_other->saveAll()) {
                    $result_data = $model->getAttributesWithRelatedAsPost();
                    return [
                        'success' => true,
                        'data' => $result_data,
                    ];
                }

                return [
                    'success' => false,
                    'errors' => $model_other->getErrors()
                ];
            }
        }

        return [
            'success' => false,
            'errors' => $model->getErrors()
        ];
    }

    /**
     * $id MessageRoom sender_id
     */
    public function actionChangefavorite($id)
    {
        $model = $this->findOrCreateModel($id, Yii::$app->user->id);
        
        // check access for this user
        if (!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        if ($model->favorite == MessageRoom::FAVORITE_YES) {
            $model->favorite = MessageRoom::FAVORITE_NO;
        } else {
            $model->favorite = MessageRoom::FAVORITE_YES;
        }

        $model->save(); //
        // var_dump($model->getErrors());
        // die();

        if (!empty(Yii::$app->request->referrer)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    public function actionChangearchive($id)
    {
        $model = $this->findOrCreateModel($id, Yii::$app->user->id);
        
        // check access for this user
        if (!$model->isOwner()) {
            throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
        }

        if ($model->status == MessageRoom::STATUS_ACTIVE) {
            $model->status = MessageRoom::STATUS_ARCHIVE;
        } else {
            $model->status = MessageRoom::STATUS_ACTIVE;
        }

        $model->save(false); //

        if (!empty(Yii::$app->request->referrer)) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('message', 'The requested page does not exist.'));
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOrCreateModel($sender_id, $user_id)
    {
        if($sender_id == $user_id) {
            throw new ForbiddenHttpException(Yii::t('message', 'You try to send messages to yourself'));    
        }

        // try to find room with `sender_id` = $id
        $model = MessageRoom::find()->where([
            'user_id' => $user_id,
            'sender_id' => $sender_id,
        ])->one();

        if($model === null) { // if not exists create it
            $model = new MessageRoom;
            $model->user_id = $user_id;
            $model->sender_id = $sender_id;
            $model->save();
        }

        return $model;
    }
}
