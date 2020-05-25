<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Vacancy;
use app\models\VacancyImage;
use app\models\User;
use app\models\VacancySearch;
use app\models\Category;
use app\models\UploadExcelForm;
use app\models\CategoryJob;
use app\models\Company;
use app\models\CountryCity;
use app\models\UserPhone;
use app\models\CategoryVacancy;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\components\CurrencyConverterHelper;
use app\components\ReferenceHelper;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;

/**
 * VacancyController implements the CRUD actions for Vacancy model.
 */
class VacancyController extends Controller
{
    // cache attributes
    protected $country_list;
    protected $city_list;
    protected $category_list;
    protected $job_list;

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'export', 'import'],
                        'allow' => true,
                        'roles' => [User::ROLE_ADMINISTRATOR, User::ROLE_MANAGER],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Vacancy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VacancySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vacancy model.
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
     * Creates a new Vacancy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vacancy();
        $model->loadDefaultValues();

        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            // $post_data['Vacancy']['company_id'] = Yii::$app->user->identity->company->id;
            $post_data['Vacancy']['creation_time'] = time();
            $post_data['Vacancy']['update_time'] = time();
                
            $post_data['Vacancy']['salary_per_hour_min_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_min'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );
            $post_data['Vacancy']['salary_per_hour_max_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_max'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );

            // unlink deleted relations
            if(!empty($post_data['relations'])) {
                foreach($post_data['relations'] as $relation_name) {
                    //! BUG of optimization need save `id` for unmodifed relations
                    $model->unlinkAll($relation_name, true);
                }
            }

            if ($model->loadAll($post_data) && $model->saveAll()) {
                // set main_image, just get first
                // if(!empty($model->vacancyImages)) {
                //     $model->main_image = $model->vacancyImages[0]->path_name;
                //     $model->save(false);
                // }

                return $this->redirect(['view', 'id' => $model->id]);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }

        // default values
        $model->currency_code = Yii::$app->params['defaultCurrencyCharCode'];
        $model->residence_amount_currency_code = Yii::$app->params['defaultCurrencyCharCode'];
        $model->agency_paid_document_currency_code = Yii::$app->params['defaultCurrencyCharCode'];
        $model->agency_pay_commission_currency_code = Yii::$app->params['defaultCurrencyCharCode'];

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Vacancy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            $post_data['Vacancy']['company_id'] = Yii::$app->user->identity->company->id;
            $post_data['Vacancy']['update_time'] = time();
                
            $post_data['Vacancy']['salary_per_hour_min_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_min'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );
            $post_data['Vacancy']['salary_per_hour_max_src'] = CurrencyConverterHelper::currencyToCurrency(
                $post_data['Vacancy']['salary_per_hour_max'],
                $post_data['Vacancy']['currency_code'],
                Yii::$app->params['sourceCurrencyCharCode']
            );

            // fix insert null into DB
            if (empty($post_data['Vacancy']['worker_country_codes'])) {
                $post_data['Vacancy']['worker_country_codes'] = null;
            }

            // unlink deleted relations
            if(!empty($post_data['relations'])) {
                foreach($post_data['relations'] as $relation_name) {
                    //! BUG of optimization need save `id` for unmodifed relations
                    $model->unlinkAll($relation_name, true);
                }
            }

            if ($model->loadAll($post_data) && $model->saveAll()) {
                // set main_image, just get first
                // if(!empty($model->vacancyImages)) {
                //     $model->main_image = $model->vacancyImages[0]->path_name;
                //     $model->save(false);
                // }

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
        ]);
    }

    /**
     * Deletes an existing Vacancy model.
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
     * Export Vacancy search result into Excel(*.xls).
     * @return file|mixed
     */
    public function actionExport()
    {
        // export data to excel file
        return $this->render('export');
    }

    /**
     * Import Vacancy Excel(*.xls) into DB
     * @return file|mixed
     */
    public function actionImport()
    {
        $model = new UploadExcelForm();

        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();

            if (!empty($post_data['file_path'])) {
                $model->file_path = $post_data['file_path']; //! unsafe data need filter
                
                // save uploaded file
                $data_arr = $model->processExcel();
                $parse_result = $this->processData($data_arr, true);
            } else {
                $model->excel_file = UploadedFile::getInstance($model, 'excel_file');

                if (!$model->upload()) {
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => false,
                        'errors' => $model->getErrors()
                    ];
                }

                // process uploaded file
                $data_arr = $model->processExcel();
                $parse_result = $this->processData($data_arr, false);
            }

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'success' => true,
                'data' => [
                    'file_path' => $model->file_path,
                    'parse_result' => $parse_result,
                ]
            ];
        }

        // import data from excel file
        return $this->render('import', [
            'model' => $model
        ]);
    }

    protected function processData($data_arr, $save_data)
    {
        $parse_result = [];

        if (!empty($data_arr[0])) {
            $headers = $data_arr[0];
            $model_attribute_index = [];

            // compare header names with model data
            foreach($headers as $index => $header) {
                $attribute_name = $this->getAttributeNameByLabel($header);
                if($attribute_name !== null) {
                    $model_attribute_index[$index] = $attribute_name;
                }
            }
        
            $model_data_list = [];

            $saveDirectoryTmp = Yii::getAlias(Company::TMP_IMAGE_DIR_ALIAS);
            if (!BaseFileHelper::createDirectory($saveDirectoryTmp, $mode = 0775)) {
                throw new HttpException(500, Yii::t('vacancy', 'Не могу создать каталог на сервере: ' . $saveDirectoryTmp));
            }
            
            foreach ($data_arr as $i => $item) {
                if ($i == 0) continue; // skip headers
                $model_data = [];

                foreach($model_attribute_index as $index => $attribute) {
                    if (!isset($item[$index])) { // skip empty fields
                        continue;
                    }
                    
                    switch($attribute) {
                        case 'full_import_description_cleaned':
                            $model_data['Vacancy'][$attribute] = $item[$index];
                        case 'employment_type':
                            $model_data['Vacancy'][$attribute] = $this->getEmploymentTypeByLabel($item[$index]);
                            break;
                        case 'creation_time':
                            $model_data['Vacancy'][$attribute] = Yii::$app->formatter->asTimestamp($item[$index]);
                            break;
                        // case 'salary_per_hour_min':
                        case 'gender_list':
                            $model_data['Vacancy'][$attribute] = $this->getGendersByLabel($item[$index]);
                            break;
                        case 'vacancy_company_name':
                            $model_data['Vacancy']['company_name'] = $item[$index];
                            break;
                        case 'date_free':
                            $model_data['Vacancy']['date_free'] = $this->getDateFreeByLabel($item[$index]);
                            break;
                        case 'date_start':
                            if (!empty($item[$index])) {
                                $model_data['Vacancy']['date_start'] = date('Y-m-d', Yii::$app->formatter->asTimestamp($item[$index]));
                            }
                            break;
                        case 'date_end':
                            if (!empty($item[$index])) {
                                $model_data['Vacancy']['date_end'] = date('Y-m-d', Yii::$app->formatter->asTimestamp($item[$index]));
                            }
                            break;
                        case 'worker_country_codes':
                            $country_labels = $this->getCountryCodesByLabel($item[$index]);
                            if (!empty($country_labels)) {
                                $model_data['Vacancy']['worker_country_codes'] = $country_labels;
                            }
                            break;
                        case 'residence_provided':
                            $model_data['Vacancy']['residence_provided'] = $this->getResidenceProvidedByLabel($item[$index]);
                            break;
                        case 'documents_provided':
                            $model_data['Vacancy']['documents_provided'] = $this->getDocumentsProvidedByLabel($item[$index]);
                            break;
                        case 'documents_required':
                            $model_data['Vacancy']['documents_required'] = $this->getDocumentsRequiredByLabel($item[$index]);
                            break;
                        case 'contact_email_list':
                            $contact_email_list = explode(',', $item[$index]);
                            foreach($contact_email_list as &$email) {
                                $email = trim($email);
                            }
                            unset($email);

                            if(!empty($contact_email_list)) {
                                $model_data['Vacancy']['contact_email_list'] = implode($contact_email_list, ';');
                            }
                            break;
                        case 'contact_phone':
                            $contact_phone = explode(',', $item[$index]);
                            foreach ($contact_phone as &$phone) {
                                $phone = trim($phone);
                            }
                            unset($phone);

                            if(!empty($contact_phone)) {
                                $model_data['Vacancy']['contact_phone'] = implode($contact_phone, ';');
                            }
                            break;
                        // relations:
                        // -- company
                        case 'company_phone':
                            $model_data['Company']['company_phone'] = '' . $item[$index];
                            break;
                        case 'company_name':
                            $model_data['Company']['company_name'] = $item[$index];
                            break;
                        case 'company_email':
                            $model_data['Company']['company_email'] = $item[$index];
                            break;
                        case 'company_site':
                            $model_data['Company']['site'] = $item[$index];
                            break;
                        case 'company_description':
                            $model_data['Company']['description'] = $item[$index];
                            break;
                        case 'company_verification':
                            $model_data['Company']['status'] = $this->getCompanyStatusByLabel($item[$index]);
                            break;
                        case 'company_worker_name':
                            $model_data['UserPhone']['company_worker_name'] = $item[$index];
                            break;
                        case 'company_worker_email':
                            $model_data['UserPhone']['company_worker_email'] = $item[$index];
                            break;
                        case 'contact_phone_for_admin':
                            $model_data['UserPhone']['contact_phone_for_admin'] = '' . $item[$index];
                            break;
                        case 'country_name':
                            $model_data['Vacancy'][$attribute] = $this->getCountryCodeByLabel($item[$index]);
                            break;
                        case 'company_country_code':
                            $model_data['Company']['company_country_code'] = $this->getCountryCodeByLabel($item[$index]);
                            break;
                        case 'country_city_id':
                            $model_data['Vacancy'][$attribute] = $this->getCityIdByLabel($item[$index]);
                            break;
                        case 'company_country_city_id':
                            $model_data['Company']['country_city_id'] = $this->getCityIdByLabel($item[$index]);
                            break;
                        case 'type':
                            $model_data['Company']['type'] = $this->getCompanyTypeByLabel($item[$index]);
                            break;
                        case 'logo':
                            $model_data['Company']['logo_src'] = $item[$index]; // will load from remote server
                            break;
                        case 'source_url':
                            $model_data['Vacancy']['source_url'] = substr($item[$index], 0, 1000);
                            break;
                        // -- other relations
                        case 'categoryVacancies':
                            $category_id = $this->getCategoryByLabel($item[$index]);
                            if($category_id !== null) {
                                $model_data['Vacancy']['categoryVacancies'][] = ['category_id' => $category_id];
                            }
                            break;
                        case 'category_job_id':
                            $model_data['Vacancy'][$attribute] = $this->getCategoryJobByLabel($item[$index]);
                            break;
                        // --
                        default:
                            if(!empty($item[$index])) {
                                $model_data['Vacancy'][$attribute] = $item[$index];
                            }
                            break;
                    }
                }

                // if `gender_list` not filled by default setup 'male' and 'female'
                if (empty($model_data['Vacancy']['gender_list'])) {
                    $model_data['Vacancy']['gender_list'] = Vacancy::GENDER_MALE . ';'. Vacancy::GENDER_FEMALE .';';
                }

                // if `company_country_code` not filled use Vacancy country_name
                if (empty($model_data['Company']['company_country_code']) && !empty($model_data['Vacancy']['country_name'])) {
                    $model_data['Company']['company_country_code'] = $model_data['Vacancy']['country_name'];
                }

                // if `country_name` not filled use Company company_country_code
                if (empty($model_data['Vacancy']['country_name']) && !empty($model_data['Company']['company_country_code'])) {
                    $model_data['Vacancy']['country_name'] = $model_data['Company']['company_country_code'];
                }

                // if empty company phone, use vacancy `contact_phone`
                if (empty($model_data['Company']['company_phone'])) {
                    $model_data['Company']['company_phone'] = '' . $model_data['Vacancy']['contact_phone'];
                }

                // if empty company type, default employer
                if (empty($model_data['Company']['type'])) {
                    $model_data['Company']['type'] = Company::TYPE_EMPLOYER;
                }

                // if `worker_country_codes` empty setup current cantry code
                if ( empty($model_data['Vacancy']['worker_country_codes']) && !empty($model_data['Vacancy']['country_name'])) {
                    $model_data['Vacancy']['worker_country_codes'] = $model_data['Vacancy']['country_name'] . ';';
                }
                
                // setup `creation_time`
                if (empty($model_data['Vacancy']['creation_time'])) {
                    $model_data['Vacancy']['creation_time'] = time();
                }

                $model_data['Vacancy']['upvote_time'] = $model_data['Vacancy']['creation_time'];
                $model_data['Vacancy']['update_time'] = $model_data['Vacancy']['creation_time'];

                // convert currency to search
                if (!empty($model_data['Vacancy']['salary_per_hour_min']) && !empty($model_data['Vacancy']['currency_code'])) {
                    $model_data['Vacancy']['salary_per_hour_min_src'] = CurrencyConverterHelper::currencyToCurrency(
                        $model_data['Vacancy']['salary_per_hour_min'],
                        $model_data['Vacancy']['currency_code'],
                        Yii::$app->params['sourceCurrencyCharCode']
                    );
                }

                // convert currency to search
                if (!empty($model_data['Vacancy']['salary_per_hour_max']) && !empty($model_data['Vacancy']['currency_code'])) {
                    $model_data['Vacancy']['salary_per_hour_max_src'] = CurrencyConverterHelper::currencyToCurrency(
                        $model_data['Vacancy']['salary_per_hour_max'],
                        $model_data['Vacancy']['currency_code'],
                        Yii::$app->params['sourceCurrencyCharCode']
                    );
                }

                // if `hours_per_day_min` setup default by field `employment_type`
                if ( empty($model_data['Vacancy']['hours_per_day_min'])
                  && !empty($model_data['Vacancy']['employment_type'])
                  && $model_data['Vacancy']['employment_type'] == Vacancy::EMPLOYMENT_TYPE_FULL_TIME
                ) {
                    $model_data['Vacancy']['hours_per_day_min'] = '8'; // hours
                }

                // if `days_per_week_min` setup default by field `employment_type`
                if (empty($model_data['Vacancy']['days_per_week_min']) && !empty($model_data['Vacancy']['employment_type'])) {
                    $model_data['Vacancy']['days_per_week_min'] = '5'; // days
                }

                if (empty($model_data['Vacancy']['type_of_working_shift'])) {
                    $model_data['Vacancy']['type_of_working_shift'] = Vacancy::TYPE_OF_WORKING_SHIFT_DAY. ';' . Vacancy::TYPE_OF_WORKING_SHIFT_EVENING . ';';
                }

                // clean description
                if (empty($model_data['Vacancy']['full_import_description_cleaned']) && !empty($model_data['Vacancy']['full_import_description'])) {
                    //! BUG, need sanitize html code
                    $model_data['Vacancy']['full_import_description_cleaned'] = $model_data['Vacancy']['full_import_description'];
                }

                if (empty($model_data['Vacancy']['job_description']) && !empty($model_data['Vacancy']['full_import_description_cleaned'])) {
                    $model_data['Vacancy']['job_description'] = trim(preg_replace('/\s+/', ' ', strip_tags($model_data['Vacancy']['full_import_description_cleaned'])));
                }

                // !BUG, empty data
                if (empty($model_data['Vacancy']['contact_email_list'])) {
                    $model_data['Vacancy']['contact_email_list'] = '-';
                }

                // !BUG, empty data
                if (empty($model_data['Vacancy']['residence_provided'])) {
                    $model_data['Vacancy']['residence_provided'] = Vacancy::RESIDENCE_PROVIDED_NO;
                }

                // clear error: SQLSTATE[HY000]: General error: 1366 Incorrect string value
                // if (!empty($model_data['Vacancy']['full_import_description_cleaned'])) {
                //     $model_data['Vacancy']['full_import_description_cleaned'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $model_data['Vacancy']['full_import_description_cleaned']);
                // }
                // if (!empty($model_data['Vacancy']['full_import_description'])) {
                //     $model_data['Vacancy']['full_import_description'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $model_data['Vacancy']['full_import_description']);
                // }
                // if (!empty($model_data['Vacancy']['job_description'])) {
                //     $model_data['Vacancy']['job_description'] = preg_replace('/[\x00-\x1F\x7F]/u', '', $model_data['Vacancy']['job_description']);
                // }

                $model_data_list[] = $model_data;
            }

            // upload images from remove server
            //? setup timeout and memory limit
            if ($save_data) {
                $chs = array();
                $cmh = curl_multi_init();
                for ($t = 0; $t < count($model_data_list); $t++) {
                    if (empty($model_data_list[$t]['Company']['logo_src'])) {
                        continue;
                    }

                    // setup tmp file path
                    $tmp = explode('.', $model_data_list[$t]['Company']['logo_src']);
                    $file_ext = array_pop($tmp);
                    $tmp = explode('/', implode('.', $tmp));
                    $file_name = array_pop($tmp);
                    $model_data_list[$t]['Company']['logo_tmp_path'] = $saveDirectoryTmp . DIRECTORY_SEPARATOR . md5($model_data_list[$t]['Company']['logo_src']) . '.' . $file_ext;
                    $model_data_list[$t]['Vacancy']['vacancyImages'] = [
                    [
                        'photo_tmp_path' => $model_data_list[$t]['Company']['logo_tmp_path'],
                        'name' => $file_name,
                    ]
                ];
                
                    // check is file already uploaded
                    if (file_exists($model_data_list[$t]['Company']['logo_tmp_path'])) {
                        continue; // file already uploaded
                    }

                    $chs[$t] = curl_init();
                    curl_setopt($chs[$t], CURLOPT_URL, $model_data_list[$t]['Company']['logo_src']);
                    curl_setopt($chs[$t], CURLOPT_RETURNTRANSFER, 1);
                    curl_multi_add_handle($cmh, $chs[$t]);
                }

                $running = null;
                do {
                    curl_multi_exec($cmh, $running);
                } while ($running > 0);

                for ($t = 0; $t < count($model_data_list); $t++) {
                    if (empty($chs[$t])) {
                        continue;
                    }

                    try { // supress file 404 errors
                        $tmp_file_name = tempnam(sys_get_temp_dir(), 'VC_IMG');
                        file_put_contents($tmp_file_name, curl_multi_getcontent($chs[$t]));
                        $data = file_get_contents($tmp_file_name);
                        $imagine = Imagine::getImagine();
                        $image = $imagine->load($data);
                        Imagine::resize($image, 800, 600)
                        ->save($model_data_list[$t]['Company']['logo_tmp_path'], ['quality' => 90]);
                    
                        curl_multi_remove_handle($cmh, $chs[$t]);
                        curl_close($chs[$t]);
                    } catch (\Throwable $e) {
                        unset($model_data_list[$t]['Company']['logo_src']);
                        unset($model_data_list[$t]['Company']['logo_tmp_path']);
                        unset($model_data_list[$t]['Vacancy']['vacancyImages']);
                    }
                }
                curl_multi_close($cmh);
            }
            // --
            
            // process data to build models
            foreach($model_data_list as $row_index => $model_data) {
                //! BUG, need register all phone numbers, - get only first phone number,
                $contact_phone = null;
                if (!empty($model_data['Vacancy']['contact_phone'])) {
                    $contact_phone_list = explode(';', $model_data['Vacancy']['contact_phone']);
                    $contact_phone = array_shift($contact_phone_list);
                }

                // try to find company id by phone number
                if (empty($contact_phone)) {
                    $parse_result[$row_index] = [
                        'success' => false,
                        'errors' => [[
                            'Can\'t create vacancy without contact phone'
                        ]],
                        'data' => $model_data
                    ];
                    continue; // go next row
                }

                $user_phone_model = UserPhone::find()->where(['phone' => $contact_phone])->one();
                if (empty($user_phone_model)) { // account not exists
                    // create new account and company
                    $model_data['User'] = [
                        'login' => $contact_phone,
                        'username' => $contact_phone,
                        'email' => $contact_phone . '@unknown.host',
                    ];

                    $model_data['UserPhone'] = [
                        'verified' => UserPhone::VERIFIED_INSERTED_BY_PARSER,
                        'phone' => $contact_phone,
                        'company_worker_name' => empty($model_data['UserPhone']['company_worker_name']) ? null : $model_data['UserPhone']['company_worker_name'],
                        'company_worker_email' => empty($model_data['UserPhone']['company_worker_email']) ? null : $model_data['UserPhone']['company_worker_email'],
                        'contact_phone_for_admin' => empty($model_data['UserPhone']['contact_phone_for_admin']) ? null : $model_data['UserPhone']['contact_phone_for_admin'],
                    ];
                } else if(!empty($user_phone_model->user->profile)) { // this is worker's account
                    $parse_result[$row_index] = [
                        'success' => false,
                        'errors' => [[
                            'Not acceptable. Phone number ' . $contact_phone . ' already registred as worker and can\'t create Vacancy'
                        ]],
                        'data' => $model_data
                    ];
                    continue; // go next row
                } else { // company founded and exists
                    $company_model = $user_phone_model->user->company;
                    if(empty($company_model)) { // user do not complete registration, just create company for this account
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => [[
                                'Not acceptable. Phone number ' . $contact_phone . ' already registred user do not complete registration and can\'t create Vacancy'
                            ]],
                            'data' => $model_data
                        ];
                        continue; // go next row
                    }

                    $model_data['Vacancy']['company_id'] = $company_model->id;
                    $model_data['Vacancy']['user_phone_id'] = $user_phone_model->id;
                }

                $model_item = new Vacancy();
                
                // save all data into DB
                $db = $model_item->getDb();
                $trans = $db->beginTransaction();
                
                if (empty($model_data['Vacancy']['user_phone_id'])) { // || empty($model_data['Vacancy']['company_id'])
                    // lets create new account
                    $model_company = new Company();
                    $model_user = new User();
                    $model_user_phone = new UserPhone();

                    $model_company->loadDefaultValues();
                    $model_user->loadDefaultValues();
                    $model_user_phone->loadDefaultValues();

                    $model_company->load($model_data);
                    $model_user->load($model_data);
                    $model_user_phone->load($model_data);
                    
                    if (!$model_user->save()) {
                        $trans->rollBack();
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => $model_user->getErrors(),
                            'data' => $model_user->getAttributes()
                        ];
                        continue; // go next row
                    }

                    $model_company->user_id = $model_user->id;
                    if (!$model_company->save()) {
                        $trans->rollBack();
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => $model_company->getErrors(),
                            'data' => $model_company->getAttributes()
                        ];
                        continue; // go next row
                    }

                    $model_user_phone->user_id = $model_user->id;
                    if (!$model_user_phone->save()) {
                        $trans->rollBack();
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => $model_user_phone->getErrors(),
                            'data' => $model_user_phone->getAttributes()
                        ];
                        continue; // go next row
                    }

                    $model_data['Vacancy']['company_id'] = $model_company->id;
                    $model_data['Vacancy']['user_phone_id'] = $model_user_phone->id;
                }

                // laod all data
                $model_item->loadDefaultValues();
                $model_item->load($model_data);

                // validate model
                if ($model_item->save()) {
                    if(!empty($model_data['Vacancy']['categoryVacancies'])) {
                        foreach ($model_data['Vacancy']['categoryVacancies'] as $categoryVacancy) {
                            $model_category_vacancy = new CategoryVacancy();
                            $model_category_vacancy->vacancy_id = $model_item->id;
                            $model_category_vacancy->category_id = $categoryVacancy['category_id'];
                            $model_category_vacancy->save();
                        }
                    }

                    if($save_data) {
                        if(!empty($model_data['Vacancy']['vacancyImages'])) {
                            foreach($model_data['Vacancy']['vacancyImages'] as $vacancy_image_data) {
                                $vacancy_image_data['vacancy_id'] = $model_item->id;
                                $model_vacancy_image = new VacancyImage();
                                $model_vacancy_image->load(['VacancyImage' => $vacancy_image_data]);
                                $model_vacancy_image->save();
                            }
                        }
                        $trans->commit();
                    } else {
                        $trans->rollBack();
                    }

                    $parse_result[$row_index] = [
                        'success' => true,
                        'data' => $model_item->getAttributes(), // $model_item->getAttributesWithRelatedAsPost()
                    ];
                } else {
                    $trans->rollBack();
                    $parse_result[$row_index] = [
                        'success' => false,
                        'errors' => $model_item->getErrors(),
                        'data' => $model_item->getAttributes()
                    ];
                }
            }
        }
        
        return $parse_result;
    }

    protected function getAttributeNameByLabel($name)
    {
        $name = trim(mb_strtolower($name));
        switch($name) {
            case 'вакансия':            return 'title';
            case 'зарплата мин в час':  return 'salary_per_hour_min';
            case 'зарплата макс в час': return 'salary_per_hour_max';
            case 'зарплата мин':
            case 'зарплата, от':        return 'prepaid_expense_min';
            case 'зарплата макс':
            case 'зарплата, до':        return 'prepaid_expense_max';
            case 'валюта':              return 'currency_code';
            case 'текст':               return 'full_import_description';
            case 'ред. текст':          return 'full_import_description_cleaned';
            case 'описание вакансии':   return 'job_description';
            case 'сфера':               return 'categoryVacancies';
            case 'категория':           return 'category_job_id';
            case 'телефон':             return 'contact_phone';
            case 'дата':                return 'creation_time';
            case 'занятость':           return 'employment_type';
            case 'работодатель':        return 'company_name';
            case 'предприятие':         return 'vacancy_company_name';
            case 'email компании':      return 'company_email';
            case 'телефон компании':    return 'company_phone';
            case 'страна компании':     return 'company_country_code';
            case 'компания подтверждена':return 'company_verification';
            case 'имя сотрудника':      return 'company_worker_name';
            case 'email сотрудника':    return 'company_worker_email';
            case 'сайт компании':       return 'company_site';
            case 'описание компании':   return 'company_description';
            case 'телефон администратора': return 'contact_phone_for_admin';
            case 'для граждан таких стран': return 'worker_country_codes';
            case 'страна работы':       return 'country_name';
            case 'город работы':               return 'country_city_id';
            case 'город местонахождения компании': return 'country_city_id';
            case 'тип':                 return 'type';
            case 'логотип':             return 'logo';
            case 'ссылка':              return 'source_url';
            case 'пол':                 return 'gender_list';
            case 'возраст от':          return 'age_min';
            case 'возраст до':          return 'age_max';
            case 'свободных мест':      return 'free_places';
            case 'свободный заезд':     return 'date_free';
            case 'дата заезда от':      return 'date_start';
            case 'дата заезда до':      return 'date_end';
            case 'занятость в день от': return 'hours_per_day_min';
            case 'занятость в день до': return 'hours_per_day_max';
            case 'Занятость дней в неделю от': return 'days_per_week_min';
            case 'Занятость дней в неделю до': return 'days_per_week_max';
            case 'проживание предоставляется': return 'residence_provided';
            case 'проживание в месяц':  return 'residence_amount';
            case 'проживание валюта':   return 'residence_amount_currency_code';
            case 'проживание человек в комнате': return 'residence_people_per_room';
            case 'документы прелоставляются': return 'documents_provided';
            case 'требуемые документы от кандидата': return 'documents_required';
            case 'email адреса': return 'contact_email_list';
            case 'контактные телефоны': return 'contact_phone';
            case 'контактное лицо': return 'contact_name';
            default:
                return null;
        }
    }

    protected function getCountryCodeByLabel($country_name)
    {
        if(empty($this->country_list)) {
            $this->country_list = ReferenceHelper::getCountryList(true);
        }

        $country_name = mb_strtolower($country_name);
        foreach ($this->country_list as $country) {
            // search by all locale labels
            foreach (Yii::$app->components['urlManager']['languages'] as $lang) {
                $country_tr = mb_strtolower(Yii::t('country', $country['name'], [], $lang));
                if ($country_tr == $country_name) {
                    return $country['char_code'];
                }
            }
        }

        return null;
    }

    protected function getCountryCodesByLabel($country_names)
    {
        if(empty($this->country_list)) {
            $this->country_list = ReferenceHelper::getCountryList(true);
        }

        $country_name_list = explode(',', $country_names);
        foreach($country_name_list as &$val) {
            $val = trim(mb_strtolower($val));
        }

        $selected_list = [];
        foreach ($this->country_list as $country) {
            // search by all locale labels
            foreach($country_name_list as $country_name) {
                foreach (Yii::$app->components['urlManager']['languages'] as $lang) {
                    $country_tr = mb_strtolower(Yii::t('country', $country['name'], [], $lang));
                    if ($country_tr == $country_name) {
                        $selected_list[] = $country['char_code'];
                    }
                }
            }
        }

        $selected_list = array_unique($selected_list);

        if (empty($selected_employment_type_list))
            return null;
        
        return implode($selected_list, ';') . ';';
    }

    protected function getCityIdByLabel($city_name)
    {
        if(empty($this->city_list)) {
            $this->city_list = CountryCity::find()->all(); //! BUG of optimization, need redis cache
        }

        $city_name = mb_strtolower($city_name);
        foreach ($this->city_list as $city) {
            // search by all locale labels
            foreach (Yii::$app->components['urlManager']['languages'] as $lang) {
                $city_tr = mb_strtolower(Yii::t('city', $city->city_name, [], $lang));
                if ($city_tr == $city_name) {
                    return $city->id;
                }
            }
        }

        return null;
    }

    protected function getCategoryByLabel($category_name)
    {
        if(empty($this->category_list)) {
            $this->category_list = Category::getUserSelectList();
        }
        $category_name = mb_strtolower($category_name);
        foreach ($this->category_list as $category) {
            foreach (Yii::$app->components['urlManager']['languages'] as $lang) {
                if (mb_strtolower(Yii::t('category', $category->name, [], $lang)) == $category_name) {
                    return $category->id;
                }
            }
        }

        return null;
    }

    protected function getCategoryJobByLabel($job_name)
    {
        if(empty($this->job_list)) {
            $this->job_list = CategoryJob::getUserMultiSelectList();
        }
        $job_name = trim(mb_strtolower($job_name));
        foreach ($this->job_list as $job_group) {
            foreach ($job_group['jobs'] as $job_item) {
                // search by all locale labels
                foreach (Yii::$app->components['urlManager']['languages'] as $lang) {
                    if (mb_strtolower(Yii::t('category-job', $job_item['name'], [], $lang)) == $job_name) {
                        return $job_item['id'];
                    }
                }
            }
        }

        return null;
    }

    protected function getEmploymentTypeByLabel($employment_type)
    {
        $employment_list_aliases = [
            Vacancy::EMPLOYMENT_TYPE_FULL_TIME    => [
                'полная занятость'
            ],
            Vacancy::EMPLOYMENT_TYPE_SHIFT_METHOD => [

            ],
            Vacancy::EMPLOYMENT_TYPE_PART_TIME    => [
                'временная занятость',
                'частичная занятость'
            ],
            // Vacancy::EMPLOYMENT_TYPE_SHIFT_WORK   => Yii::t('vacancy', 'Shift work'),
        ];

        $employment_type_arr = explode(',', $employment_type);
        
        // remove spaces
        foreach ($employment_type_arr as &$val) {
            $val = trim(mb_strtolower($val));
        }

        $selected_employment_type_list = [];
        foreach ($employment_type_arr as $employment_type_label) {
            foreach ($employment_list_aliases as $ukey => $aliases) {
                foreach ($aliases as $alias) {
                    if ($alias == $employment_type_label) {
                        $selected_employment_type_list[] = $ukey;
                    }
                }
            }
        }

        // clear duplicates
        $selected_employment_type_list = array_unique($selected_employment_type_list);

        if (empty($selected_employment_type_list))
            return null;
        
        return implode($selected_employment_type_list, ';') . ';';
    }

    protected function getGendersByLabel($gender_list)
    {
        $gender_list_aliases = [
            Vacancy::GENDER_MALE    => [
                'м',
                'муж',
                'мужчина'
            ],
            Vacancy::GENDER_FEMALE => [
                'ж',
                'жен',
                'женщина',
            ],
            Vacancy::GENDER_PAIR    => [
                'п',
                'пара'
            ]
        ];

        $gender_list_arr = explode(',', $gender_list);
        
        // remove spaces
        foreach ($gender_list_arr as &$val) {
            $val = trim(mb_strtolower($val));
        }

        $selected_gender_list = [];
        foreach ($gender_list_arr as $gender_list_label) {
            foreach ($gender_list_aliases as $ukey => $aliases) {
                foreach ($aliases as $alias) {
                    if ($alias == $gender_list_label) {
                        $selected_gender_list[] = $ukey;
                    }
                }
            }
        }

        // clear duplicates
        $selected_gender_list = array_unique($selected_gender_list);

        if (empty($selected_gender_list))
            return null;
        
        return implode($selected_gender_list, ';') . ';';
    }

    protected function getCompanyStatusByLabel($status)
    {
        if(mb_strtolower($status) == 'да') {
            return Company::STATUS_VERIFIED;
        }

        return Company::STATUS_NOT_VERIFIED;
    }

    protected function getDateFreeByLabel($status)
    {
        if(mb_strtolower($status) == 'да') {
            return Vacancy::DATE_FREE_YES;
        }

        return Vacancy::DATE_FREE_NO;
    }

    protected function getResidenceProvidedByLabel($status)
    {
        if(mb_strtolower($status) == 'да') {
            return Vacancy::RESIDENCE_PROVIDED_YES;
        }

        return Vacancy::RESIDENCE_PROVIDED_NO;
    }

    protected function getDocumentsProvidedByLabel($documents_provided)
    {
        $documents_provided_arr = explode(',', $documents_provided);
        
        // remove spaces
        foreach ($documents_provided_arr as &$val) {
            $val = trim(mb_strtolower($val));
        }
        
        $documents_provided_list = Vacancy::getDocumentsProvidedList();
        $selected_documents_provided = [];
        foreach ($documents_provided_arr as $documents_provided_label) {
            foreach ($documents_provided_list as $ukey => $alias) {
                if (mb_strtolower(Yii::t('vacancy', $alias, 'ru')) == $documents_provided_label) {
                    $selected_documents_provided[] = $ukey;
                }
            }
        }

        // clear duplicates
        $selected_documents_provided = array_unique($selected_documents_provided);

        if (empty($selected_documents_provided))
            return null;
        
        return implode($selected_documents_provided, ';') . ';';
    }

    protected function getDocumentsRequiredByLabel($documents_required)
    {
        $documents_required_arr = explode(',', $documents_required);
        
        // remove spaces
        foreach ($documents_required_arr as &$val) {
            $val = trim(mb_strtolower($val));
        }
        
        $documents_required_list = Vacancy::getDocumentsRequiredList();
        $selected_documents_required = [];
        foreach ($documents_required_arr as $documents_required_label) {
            foreach ($documents_required_list as $ukey => $alias) {
                if (mb_strtolower(Yii::t('vacancy', $alias, 'ru')) == $documents_required_label) {
                    $selected_documents_required[] = $ukey;
                }
            }
        }

        // clear duplicates
        $selected_documents_required = array_unique($selected_documents_required);

        if (empty($selected_documents_required))
            return null;
        
        return implode($selected_documents_required, ';') . ';';
    }

    protected function getCompanyTypeByLabel($type)
    {
        $type_list_aliases = [
            Company::TYPE_EMPLOYER  => [
                'прямой работодатель'
            ],
            Company::TYPE_HR_AGENCY => [
                'кадровое агентство'
            ],
        ];

        foreach ($type_list_aliases as $ukey => $aliases) {
            foreach ($aliases as $aliase) {
                if ($aliase == $type)
                    return $ukey;
            }
        }

        return null;
    }

    /**
     * Finds the Vacancy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vacancy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vacancy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('vacancy', 'The requested page does not exist.'));
    }
}
