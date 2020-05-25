<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\LoginPhoneForm;
use app\models\ContactForm;
use app\models\User;
use rmrevin\yii\ulogin\AuthAction;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use app\models\Category;
use app\models\CategoryJob;
use app\models\VacancySearch;
use app\models\ResumeSearch;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'ulogin' => ['post'],
                    'send-sms-code' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'ulogin' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'uloginSuccessCallback'],
                'errorCallback' => function($data){
                    \Yii::error($data['error']);
                },
            ]
        ];
    }

    public function beforeAction($action) {
        if($action->id == 'ulogin') {
            Yii::$app->request->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $user_type = $session->get('interface_for');

        if ($user_type == 'employer') {
            $category_job_main_page = CategoryJob::getMainPageList();

            $searchModel = new ResumeSearch();
            // $searchModel->salary_currency = Yii::$app->params['defaultCurrencyCharCode'];
            $dataProvider = $searchModel->search([]); // Yii::$app->request->queryParams
            $dataProvider->pagination->pageSize = 10;
            $dataProvider->query->orderBy([
                // 'main_page_priority' => SORT_DESC,
                // 'show_on_main_page' => SORT_DESC,
                'id' => SORT_DESC,
            ]); //?

            // current favorite resume id's
            $favorite_ids = [];
            if (!Yii::$app->user->isGuest) {
                $favorite_ids = Yii::$app->user->identity->getUserFavoriteResumes()->limit(100)->select('resume_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
            }

            return $this->render('index_employer', [
                'category_job_main_page' => $category_job_main_page,
                'dataProvider' => $dataProvider,
                'favorite_ids' => $favorite_ids,
            ]);
        } else { // worker
            $category_main_page = Category::getMainPageList();

            $searchModel = new VacancySearch();
            $searchModel->salary_currency = Yii::$app->params['defaultCurrencyCharCode'];
            $dataProvider = $searchModel->search([]); // Yii::$app->request->queryParams
            $dataProvider->pagination->pageSize = 10;
            $dataProvider->query->orderBy([
                'main_page_priority' => SORT_DESC,
                'show_on_main_page' => SORT_DESC,
                'id' => SORT_DESC,
            ]); //?

            // current favorite resume id's
            $favorite_ids = [];
            if (!Yii::$app->user->isGuest) {
                $favorite_ids = Yii::$app->user->identity->getUserFavoriteVacancies()->limit(100)->select('vacancy_id')->orderBy(['id'=> SORT_DESC])->asArray()->column();
            }

            return $this->render('index', [
                'category_main_page' => $category_main_page,
                'dataProvider' => $dataProvider,
                'favorite_ids' => $favorite_ids,
            ]);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginPhoneForm(['scenario' => LoginPhoneForm::SCENARION_LOGIN]);
        // $model->load(Yii::$app->request->post())
        // $model->getUser();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // if user has company then redirrect him resume search
            if (!empty(Yii::$app->user->identity->company)) {
                // check is user complete registration
                if(!Yii::$app->user->identity->isUserHasVacancy()) {
                    return $this->redirect(['/userpanel/vacancy/create']);
                }

                // return $this->redirect(['/resume/search']);
                return $this->goBack();
            }

            // if user has profile then redirrect him vacancy search
            if (!empty(Yii::$app->user->identity->profile)) {
                // check is user complete registration
                if(!Yii::$app->user->identity->isUserHasResume()) {
                    return $this->redirect(['/userpanel/resume/create']);
                }

                // return $this->redirect(['/vacancy/index']);
                return $this->goBack();
            }

            /**
             * if user has not profile or company
             * then he is not registred
             * then send him to fill role data (finish redistration)
             **/  
            $session = Yii::$app->session;
            $user_type = $session->get('interface_for');
            if ($user_type == 'employer') {
                return $this->redirect(['/userpanel/company/create']);
            }
            // else { // candidate
                return $this->redirect(['/userpanel/profile/create']);
            // }

            // return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLoginUser()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login_user', [
            'model' => $model,
        ]);
    }

    public function actionSendSmsCode()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = new LoginPhoneForm(['scenario' => LoginPhoneForm::SCENARION_GENERATE]);
        if($model->load(Yii::$app->request->post()) && $model->validate() && $model->generateSmscode()) {
            return [
                'success' => true,
                'message' => 'SMS just sent'
            ];
        }

        return [
            'success' => false,
            'errors' => $model->getErrors()
        ];
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContactForm()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact_from', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionContacts()
    {
        return $this->render('contacts');
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays faq page.
     *
     * @return string
     */
    public function actionFaq()
    {
        return $this->render('faq');
    }

    /**
     * Displays public-offer page.
     *
     * @return string
     */
    public function actionPublicOffer()
    {
        return $this->render('public_offer');
    }

    /**
     * Displays terms-of-use page.
     *
     * @return string
     */
    public function actionTermsOfUse()
    {
        return $this->render('terms_of_use');
    }

    /**
     * Displays paid-services page.
     *
     * @return string
     */
    public function actionPaidServices()
    {
        return $this->render('paid_services');
    }

    /**
     * Displays map page.
     *
     * @return string
     */
    public function actionMap()
    {
        return $this->render('site_map');
    }

    /**
     * Displays safe deal page.
     *
     * @return string
     */
    public function actionSafeDeal()
    {
        // page now hidden:
        throw new NotFoundHttpException(
            Yii::t('main', 'This page is under construction.')
            . ' ' . Yii::t('main', 'Subscribe to our portal news and be the first to know about the changes.')
        );

        $this->layout = 'layout-safe-deal';
        return $this->render('safe-deal');
    }

    /**
     * switch session flaf to candidate
     * return @return redirrect back page
     */
    public function actionForCandidate()
    {
        $session = Yii::$app->session;
        $session->set('interface_for', 'candidate');
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    public function actionForEmployer()
    {
        $session = Yii::$app->session;
        $session->set('interface_for', 'employer');
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }

    /**
     * login user via Ulogin
     */
    public function uloginSuccessCallback($attributes)
    {
        if(empty($attributes)) {
            throw new BadRequestHttpException('Data empty');
        }

        $user = User::findByUsername($attributes['uid']);

        if(!empty($attributes)) {
            // chek user exists if not exists register
            if(empty($user)) {
                $user = new User();
                $user->scenario = User::SCENARION_ULOGIN;
                // filter data
                $user->login = trim($attributes['uid']);
                $user->username = trim($attributes['first_name']) . ' ' . $attributes['last_name'];
                $user->email = trim($attributes['email']);
                // $user->verified_email = trim($attributes['verified_email']);
                
                if(!$user->save()) {
                    throw new BadRequestHttpException('Invalid login data');
                }
            }

            // login user
            Yii::$app->user->login($user, 3600*24*30);
            return $this->goBack();
        }

        return $this->goBack();
    }
}
