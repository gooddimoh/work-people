<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\Company;
use app\models\CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\SendMailHelper;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMINISTRATOR],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        $modelUser = new User();
        $model->loadDefaultValues();
        $modelUser->loadDefaultValues();

        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();

            $db = $model->getDb();
            $trans = $db->beginTransaction();
            try {
                if (
                    $model->loadAll($post_data)
                    && $model->user->generatePasswordResetToken() == null //! generatePasswordResetToken method void
                    && $model->user->save()
                    && $model->link('user', $model->user) == null //! link method return null
                    && $model->saveAll()
                ) {
                    // build email message and send to user for registration company

                    // send mail
                    $sendMail = new SendMailHelper(
                        $model->user->email,
                        'Активация компании',
                        $this->renderPartial('registartion_mail_confirm', [
                            'user' => $model->user
                        ])
                    );

                    $trans->commit();
                    $sendMail->send();

                    return $this->redirect(['view', 'id' => $model->id]);
                } else if(Yii::$app->request->isAjax) {
                    $trans->rollback();
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $model->getErrors()
                    ];
                }
            } catch (Exception $exc) {
                $trans->rollBack();
                throw $exc;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelUser' => $modelUser,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModelWithRelations($id);
        $modelUser = $model->user;

        if(Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            
            if ($model->load($post_data) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelUser' => $modelUser,
        ]);
    }

    /**
     * Deletes an existing Company model.
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('company', 'The requested page does not exist.'));
    }

    protected function findModelWithRelations($id)
    {
        $model = Company::find()
                ->where(['id' => $id])
                ->with([
                    'user',
                ])->one();
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }
}
