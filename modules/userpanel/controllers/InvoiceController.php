<?php

namespace app\modules\userpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\components\payment\PaymentSystem;
use app\components\payment\QiwiProvider;
use app\components\payment\FreeKassaProvider;
use app\components\payment\PayeerProvider;
use app\components\payment\PlatononlineProvider;
use app\components\payment\EmptyProvider;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
                            'index', 'choice-payment-system', 'invoice-view', 'invoice-paid',
                            'create-invoice-platon-online'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         // 'delete' => ['POST'],
            //     ],
            // ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionInvoiceView($id)
    {
        $model = $this->findModel($id);
        
        $paymentSystem = new PaymentSystem($this->buildPayProvider($model));
        
        return $this->render('invoice_view', [
            'model' => $model,
            'paymentSystem' => $paymentSystem
        ]);
    }

    /**
     * Check payment status Invoice model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionInvoicePaid($id)
    {
        $model = $this->findModel($id);

        if($model->status != Invoice::STATUS_WAITING) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // check status
        $paymentSystem = new PaymentSystem($this->buildPayProvider($model));

        if($paymentSystem->getStatus() == 'success') {
            $model->status = Invoice::STATUS_PAYED;
            // incress user balance
            Yii::$app->user->identity->balance = floatval(Yii::$app->user->identity->balance) + floatval($model->price);
            if($model->save() && Yii::$app->user->identity->save()) {
                Yii::$app->session->setFlash('success', Yii::t('invoice', 'Payment was successful, funds are credited to your account balance.'));
            }
        } else { // else 'waiting' || 'bad_request'
            Yii::$app->session->setFlash('success', Yii::t('invoice', 'Waitng for payment.'));
        }

        return $this->redirect(['invoice-view', 'id' => $model->id]);
    }

    public function actionChoicePaymentSystem()
    {
        return $this->render('choice_payment_system');
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateInvoicePlatonOnline()
    {
        $model = new Invoice();
        $model->currency_code = Yii::$app->params['sourceCurrencyCharCode'];

        $post = Yii::$app->request->post();

        if (!empty($post)) {
            // filter data
            $model->user_id = Yii::$app->user->id;
            $model->created_at = time();
            $model->status = Invoice::STATUS_WAITING;
            $model->pay_system = Invoice::PAYMENT_PLATON_ONLINE;
            $model->price = $post['Invoice']['price'];
            $model->price_source = $model->price;

            if ($model->save()) {
                return $this->redirect(['invoice-view', 'id' => $model->id]);
            }
        }

        return $this->render('create_invoice_platon_online', [
            'model' => $model,
        ]);
    }

    protected function buildPayProvider($model)
    {
        $time = time() + Yii::$app->params['expire_pay_time'];
        $description = '#' . $model->id . ' deposit: ' . $model->price . ' ' . $model->currency_code . ' ' . Yii::$app->name;

        $payeerProvider = null;

        if($model->status !== Invoice::STATUS_WAITING) {
            $payeerProvider = new EmptyProvider;
        } elseif($model->pay_system == Invoice::PAYMENT_FREE_KASSA) {
            $payeerProvider = new FreeKassaProvider(
                Yii::$app->params['payment_free_kassa']['merchant_id'],
                Yii::$app->params['payment_free_kassa']['secret_word'],
                Yii::$app->params['payment_free_kassa']['secret_word2'],
                $model->id,
                $model->price,
                $model->pay_system_i
            );
        } else if($model->pay_system == Invoice::PAYMENT_QIWI) {
            $payeerProvider = new QiwiProvider(
                Yii::$app->params['payment_qiwi']['SHOP_ID'],
                Yii::$app->params['payment_qiwi']['REST_ID'],
                Yii::$app->params['payment_qiwi']['PWD'],
                $model->id,
                $model->phone,
                $model->price,
                Yii::$app->params['payment_qiwi']['ccy'],
                $description,
                date('Y-m-d', $time).'T'.date('H:i:s', $time),
                Yii::$app->params['payment_qiwi']['pay_source'],
                Yii::$app->params['project_name']
            );
        } else if($model->pay_system == Invoice::PAYMENT_PAYEER) {
            $payeerProvider = new PayeerProvider(
                Yii::$app->params['payment_payeer']['m_shop'],
                $model->id,
                $model->price,
                Yii::$app->params['payment_payeer']['m_curr'],
                $description,
                Yii::$app->params['payment_payeer']['m_key']
            );
        } else if($model->pay_system == Invoice::PAYMENT_PLATON_ONLINE) {
            $payeerProvider = new PlatononlineProvider(
                Yii::$app->params['payment_platononline']['merchant_id'], // $merchant_id,
                Yii::$app->params['payment_platononline']['password'],
                'CC', // $payment, // CC - Credit Card
                [
                    'amount' => $model->price,
                    'currency' => $model->currency_code,
                    'description' => $description
                ],
                Url::to(['/userpanel/invoice/invoice-paid', 'id' => $model->id], true),
                $model->id,
                []
            );
        } else {
            throw new ServerErrorHttpException('Unsupported payment system');
        }

        return $payeerProvider;
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('invoice', 'The requested page does not exist.'));
    }
}
