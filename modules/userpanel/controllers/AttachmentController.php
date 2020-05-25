<?php

namespace app\modules\userpanel\controllers;

use Yii;
use app\models\UserAttachment;
use app\models\UserAttachmentSearch;
use app\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AttachmentController implements the CRUD actions for UserAttachment model.
 */
class AttachmentController extends Controller
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
                            'index', 'download', 'upload', 'delete'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all UserAttachment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserAttachmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Check exists file and send to user
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        $filename = $model->getFilePath();
        
        if (file_exists($filename)) {
            //Get file type and set it as Content Type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            header('Content-Type: ' . finfo_file($finfo, $filename));
            finfo_close($finfo);

            //Use Content-Disposition: attachment to specify the filename
            header('Content-Disposition: attachment; filename=' . $model->name);

            //No cache
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            //Define file size
            header('Content-Length: ' . filesize($filename));

            // ob_clean();
            // flush();
            readfile($filename);
            exit;
        }

        throw new NotFoundHttpException(Yii::t('attachment', 'File removed from disk.'));
    }

    /**
     * just upload file into tmp folder.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpload()
    {
        if(!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException('Accept only AJAX POST data');
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new UserAttachment();

        $model->fileData = UploadedFile::getInstance($model, 'fileData');
        $model->user_id = Yii::$app->user->id;
        $model->name = $model->fileData->baseName . '.' . $model->fileData->extension;
        $model->path_name = substr(md5($model->name), 0, 10) . '_' . $model->user_id . '_' 
                . date('Y-m-d') 
                . '.' . $model->fileData->extension;
        $model->size = $model->fileData->size;
        
        if ($model->validate() && $model->save()) {
            $result_data = $model->getAttributes();
            return [
                'success' => true,
                'data' => $result_data,
            ];
        }

        return [
            'success' => false,
            'errors' => $model->getErrors()
        ];
    }

    /**
     * Deletes an existing UserAttachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserAttachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAttachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAttachment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('attachment', 'The requested page does not exist.'));
    }
}
