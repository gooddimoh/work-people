<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\User;
use app\models\Resume;
use app\models\ResumeJob;
use app\models\ResumeLanguage;
use app\models\ResumeEducation;
use app\models\ResumeSearch;
use app\models\CountryCity;
use app\models\UserPhone;
use app\models\CategoryJob;
use app\models\UploadExcelForm;
use app\models\Category;
use app\models\Profile;
use yii\web\UploadedFile;
use app\components\ReferenceHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ResumeController implements the CRUD actions for Resume model.
 */
class ResumeController extends Controller
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
     * Lists all Resume models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResumeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Resume model.
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
     * Creates a new Resume model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Resume();
        $model->loadDefaultValues();

        $modelJob = new ResumeJob();
        $modelLang = new ResumeLanguage();
        $modelEducation = new ResumeEducation();

        if (Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            // $post_data['Resume']['user_id'] = Yii::$app->user->identity->id;
            $post_data['Resume']['creation_time'] = time();
            $post_data['Resume']['update_time'] = time();

            if ($model->loadAll($post_data) && $model->saveAll()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else if(Yii::$app->request->isAjax) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'errors' => $model->getErrors()
                ];
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelJob' => $modelJob,
            'modelLang' => $modelLang,
            'modelEducation' => $modelEducation,
        ]);
    }

    /**
     * Updates an existing Resume model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModelWithRelations($id);

        $modelJob = new ResumeJob();
        $modelLang = new ResumeLanguage();
        $modelEducation = new ResumeEducation();

        if(Yii::$app->request->isPost) {
            $post_data = Yii::$app->request->post();
            $post_data['Resume']['update_time'] = time();
            
            // unlink deleted relations
            if(!empty($post_data['relations'])) {
                foreach($post_data['relations'] as $relation_name) {
                    //! BUG of optimization need save `id` for unmodifed relations
                    $model->unlinkAll($relation_name, true);
                }
            }

            if ($model->loadAll($post_data) && $model->saveAll()) {
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
            'modelJob' => $modelJob,
            'modelLang' => $modelLang,
            'modelEducation' => $modelEducation,
        ]);
    }

    /**
     * Deletes an existing Resume model.
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
     * Export Resume search result into Excel(*.xls).
     * @return file|mixed
     */
    public function actionExport()
    {
        // export data to excel file
        return $this->render('export');
    }

    /**
     * Import Resume Excel(*.xls) into DB
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

            $saveDirectoryTmp = Yii::getAlias(Resume::TMP_IMAGE_DIR_ALIAS);
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
                        case 'creation_time':
                            $model_data['Resume'][$attribute] = Yii::$app->formatter->asTimestamp($item[$index]);
                            break;
                        case 'update_time':
                            $model_data['Resume'][$attribute] = Yii::$app->formatter->asTimestamp($item[$index]);
                            break;
                        case 'birth_day':
                            $model_data['Resume'][$attribute] = date('Y-m-d', Yii::$app->formatter->asTimestamp($item[$index]));
                            break;
                        case 'country_city_id':
                            $model_data['Resume'][$attribute] = $this->getCityIdByLabel($item[$index]);
                            break;
                        case 'country_name':
                            $model_data['Resume'][$attribute] = $this->getCountryCodeByLabel($item[$index]);
                            break;
                        case 'desired_country_of_work':
                            $country_list = $this->getCountryCodesByLabel($item[$index]);
                            if(!empty($country_list)) {
                                $model_data['Resume'][$attribute] = $country_list;
                            }
                            break;
                        case 'phone':
                            $contact_phone = explode(',', $item[$index]);
                            foreach ($contact_phone as &$phone) {
                                $phone = trim($phone);
                            }
                            unset($phone);

                            if(!empty($contact_phone)) {
                                $model_data['Resume'][$attribute] = implode($contact_phone, ';');
                            }
                            break;
                        case 'relocation_possible':
                            if ($item[$index] == 'возможен переезд') {
                                $model_data['Resume'][$attribute] = Resume::RELOCATION_POSSIBLE_YES;
                            } // else default value 20
                            break;
                        case 'photo_path':
                            $model_data['Resume']['photo_path_src'] = $item[$index]; // will load from remote server
                            break;
                        case 'gender_list':
                            $model_data['Resume'][$attribute] = $this->getGendersByLabel($item[$index]);
                            break;
                        // -- relations:
                        case 'resumeLanguages':
                            $model_data['Resume']['language'] = $item[$index];
                            $model_data['Resume']['use_language'] = Resume::USE_LANGUAGE_YES;
                            $model_data['Resume']['resumeLanguages'] = $this->getResumeLanguagesByLabel($item[$index]);
                            
                            // if data recognized then use relation
                            if (!empty($model_data['Resume']['resumeLanguages'])) {
                                $model_data['Resume']['use_language'] = Resume::USE_LANGUAGE_REALTION;
                            }
                            break;
                        case 'resumeEducations':
                            // filer html
                            $model_data['Resume']['resumeEducations'][] = [
                                'description' => $this->htmlToString($item[$index])
                            ];
                            break;
                        case 'resumeJobs':
                            $model_data['Resume']['job_experience'] = $item[$index];
                            // not parsable
                            $model_data['Resume']['use_job_experience'] = Resume::USE_JOB_EXPERIENCE_YES;
                            break;
                        case 'title':
                            $model_data['Resume']['title'] = $item[$index];
                            if (empty($model_data['Resume']['use_title'])) { // first cycle
                                $model_data['Resume']['use_title'] = Resume::USE_TITLE_YES;
                            }
                            //!!! not break;
                        case 'resumeCategoryJobs':
                            $job_list = explode(',', $item[$index]);
                            foreach($job_list as $job_label) {
                                $model_data['Resume']['resumeCategoryJobs'][] = [
                                    'category_job_id' => $this->getCategoryJobByLabel($job_label)
                                ];
                            }

                            // if data recognized then use relation
                            if (!empty($model_data['Resume']['resumeCategoryJobs'])) {
                                $model_data['Resume']['use_title'] = Resume::USE_TITLE_REALTION;
                            }
                            break;
                        case 'categoryResumes':
                            $category_id = $this->getCategoryByLabel($item[$index]);
                            if($category_id !== null) {
                                $model_data['Resume']['categoryVacancies'] = ['category_id' => $category_id];
                            }
                            break;
                        default:
                            $model_data['Resume'][$attribute] = $item[$index];
                            break;
                    }
                }

                // clean description
                if (empty($model_data['Resume']['full_import_description_cleaned']) && !empty($model_data['Resume']['full_import_description'])) {
                    //! BUG, need sanitize html code
                    $model_data['Resume']['full_import_description_cleaned'] = $model_data['Resume']['full_import_description'];
                }

                // clear duplicates resumeCategoryJobs
                if (!empty($model_data['Resume']['resumeCategoryJobs'])) {
                    $resumeCategoryJobs = [];
                    foreach ($model_data['Resume']['resumeCategoryJobs'] as $item) {
                        $funded = false;
                        foreach ($resumeCategoryJobs as $selected_item) {
                            if ($selected_item['category_job_id'] == $item['category_job_id']) {
                                $funded = true;
                            }
                        }

                        if (!$funded) {
                            $resumeCategoryJobs[] = $item;
                        }
                    }
                    $model_data['Resume']['resumeCategoryJobs'] = $resumeCategoryJobs;
                }

                // !BUG, empty data
                if (empty($model_data['Resume']['email'])) {
                    $model_data['Resume']['email'] = '-';
                }

                $model_data_list[] = $model_data;
            }

            // upload images from remove server
            //? setup timeout and memory limit
            if ($save_data) {
                $chs = array();
                $cmh = curl_multi_init();
                for ($t = 0; $t < count($model_data_list); $t++) {
                    if (empty($model_data_list[$t]['Resume']['photo_path_src'])) {
                        continue;
                    }

                    // setup tmp file path
                    $tmp = explode('.', $model_data_list[$t]['Resume']['photo_path_src']);
                    $file_ext = array_pop($tmp);
                    $tmp = explode('/', implode('.', $tmp));
                    $file_name = array_pop($tmp);
                    $model_data_list[$t]['Resume']['photo_tmp_path'] = $saveDirectoryTmp . DIRECTORY_SEPARATOR . md5($model_data_list[$t]['Resume']['photo_path_src']) . '.' . $file_ext;
                
                    // check is file already uploaded
                    if (file_exists($model_data_list[$t]['Resume']['photo_tmp_path'])) {
                        continue; // file already uploaded
                    }

                    $chs[$t] = curl_init();
                    curl_setopt($chs[$t], CURLOPT_URL, $model_data_list[$t]['Resume']['photo_path_src']);
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
                            ->save($model_data_list[$t]['Resume']['photo_tmp_path'], ['quality' => 90]);
                    
                        curl_multi_remove_handle($cmh, $chs[$t]);
                        curl_close($chs[$t]);
                    } catch (\Throwable $e) {
                        unset($model_data_list[$t]['Resume']['photo_path_src']);
                        unset($model_data_list[$t]['Resume']['photo_tmp_path']);
                    }
                }
                curl_multi_close($cmh);
            }
            // --

            // --- process data to build models
            foreach ($model_data_list as $row_index => $model_data) {
                //! BUG, need register all phone numbers, - get only first phone number,
                $phone = null;
                if (!empty($model_data['Resume']['phone'])) {
                    $phone_list = explode(';', $model_data['Resume']['phone']);
                    $phone = array_shift($phone_list);
                }

                // try to find Profile id by phone number
                if (empty($phone)) {
                    $parse_result[$row_index] = [
                        'success' => false,
                        'errors' => [[
                            'Can\'t create resume without contact phone'
                        ]],
                        'data' => $model_data
                    ];
                    continue; // go next row
                }

                $user_phone_model = UserPhone::find()->where(['phone' => $phone])->one();
                if (empty($user_phone_model)) { // account not exists
                    // create new account and profile
                    $model_data['User'] = [
                        'login' => $phone,
                        'username' => $phone,
                        'email' => $phone . '@unknown.host',
                    ];

                    $model_data['UserPhone'] = [
                        'verified' => UserPhone::VERIFIED_INSERTED_BY_PARSER,
                        'phone' => $phone
                    ];

                    $model_data['Profile'] = [
                        'first_name' => empty($model_data['Resume']['first_name']) ? null : $model_data['Resume']['first_name'],
                        'last_name' => empty($model_data['Resume']['last_name']) ? null : $model_data['Resume']['last_name'],
                        'middle_name' => empty($model_data['Resume']['middle_name']) ? null : $model_data['Resume']['middle_name'],
                        'email' => $model_data['User']['email'], //! unknown email
                        'birth_day' => empty($model_data['Resume']['birth_day']) ? null : $model_data['Resume']['birth_day'],
                        'gender_list' => empty($model_data['Resume']['gender_list']) ? null : $model_data['Resume']['gender_list'],
                        'country_name' => empty($model_data['Resume']['country_name']) ? null : $model_data['Resume']['country_name'],
                        'country_city_id' => empty($model_data['Resume']['country_city_id']) ? null : $model_data['Resume']['country_city_id'],
                        'photo_tmp_path' => empty($model_data['Resume']['photo_tmp_path']) ? null : $model_data['Resume']['photo_tmp_path'],
                        'phone' => empty($model_data['Resume']['phone']) ? null : $model_data['Resume']['phone'],
                    ];
                } else if(!empty($user_phone_model->user->company)) { // this is worker's account
                    $parse_result[$row_index] = [
                        'success' => false,
                        'errors' => [[
                            'Not acceptable. Phone number ' . $phone . ' already registred as company and can\'t create Resume'
                        ]],
                        'data' => $model_data
                    ];
                    continue; // go next row
                } else { // profile founded and exists
                    $profile_model = $user_phone_model->user->profile;
                    if(empty($profile_model)) { // user do not complete registration, just create profile for this account
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => [[
                                'Not acceptable. Phone number ' . $phone . ' already registred user do not complete registration and can\'t create Resume'
                            ]],
                            'data' => $model_data
                        ];
                        continue; // go next row
                    }

                    $model_data['Resume']['user_id'] = $profile_model->user_id;
                }

                $model_item = new Resume();
                
                // save all data into DB
                $db = $model_item->getDb();
                $trans = $db->beginTransaction();
                
                if (empty($model_data['Resume']['user_id'])) {
                    // lets create new account
                    $model_profile = new Profile();
                    $model_user = new User();
                    $model_user_phone = new UserPhone();

                    $model_profile->loadDefaultValues();
                    $model_user->loadDefaultValues();
                    $model_user_phone->loadDefaultValues();

                    $model_profile->load($model_data);
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

                    $model_profile->user_id = $model_user->id;
                    if (!$model_profile->save()) {
                        $trans->rollBack();
                        $parse_result[$row_index] = [
                            'success' => false,
                            'errors' => $model_profile->getErrors(),
                            'data' => $model_profile->getAttributes()
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

                    $model_data['Resume']['user_id'] = $model_user->id;
                }

                // laod all data
                $model_item->loadDefaultValues();
                $model_item->loadAll([
                    'Resume' => $model_data['Resume']
                ]);

                // validate model
                if ($model_item->saveAll()) {
                    if($save_data) {
                        //! BUG real save not working as emulated, imported just one from 11 records
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
            case 'добавлено':           return 'creation_time';
            case 'изменено':            return 'update_time';
            case 'фамилия':             return 'last_name';
            case 'имя':                 return 'first_name';
            case 'отчество':            return 'middle_name';
            case 'дата рождения':       return 'birth_day';
            case 'город':               return 'country_city_id';
            case 'страна':              return 'country_name';
            case 'образование':         return 'resumeEducations'; // mutiplie columns support
            case 'телефон':             return 'phone';
            case 'специализация':       return 'title';
            case 'категория':           return 'resumeCategoryJobs';
            // case 'место работы':        return ''; // ???
            case 'переезд':             return 'relocation_possible';
            case 'оплата за час':       return 'desired_salary_per_hour'; // duplicate header
            case 'оплата за месяц':     return 'desired_salary';
            case 'валюта':              return 'desired_salary_currency_code';
            // case 'занятость':        return '';
            case 'полный текст':        return 'full_import_description';
            case 'текст':               return 'full_import_description_cleaned';
            case 'опыт работы':         return 'resumeJobs';
            case 'знание языков':       return 'resumeLanguages';
            // case 'дополнительно':         return '';
            case 'ссылка на фото':      return 'photo_path';
            case 'cсылка на обьявление':return 'source_url';
            case 'сфера':               return 'categoryResumes';
            // +
            case 'пол':                 return 'gender_list';
            case 'желаемая страна работы': return 'desired_country_of_work';
            default:
                return null;
        }
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

    /**
     * parse string to data
     * example: Польский - средний, Чешский - базовый, Английский, Русский - свободно, Украинский - свободно
     */
    protected function getResumeLanguagesByLabel($lang_label)
    {
        $level_aliases = [
            ResumeLanguage::LEVEL_NEWBIE => [
                'базовый',
            ],
            ResumeLanguage::LEVEL_MIDDLE => [
                'средний',
            ],
            ResumeLanguage::LEVEL_HIGHT  => [
                'продвинутый',
            ],
            ResumeLanguage::LEVEL_EXPERT => [
                'свободно',
            ],
        ];

        $language_list = ResumeLanguage::getLanguageList();

        $label_list = explode(',', $lang_label);
        foreach($label_list as &$val) {
            $val = trim(mb_strtolower($val));
        }
        unset($val);

        $selected_langs = [];
        foreach ($label_list as $label_item) {
            // detect language name
            $language_code = '';
            foreach (Yii::$app->components['urlManager']['languages'] as $locale) {
                if (!empty($language_code)) break; // stop cycle

                foreach ($language_list as $language) {
                    $lang_tr = mb_strtolower(Yii::t('lang', $language['name'], [], $locale));
                    if (mb_strpos($label_item, $lang_tr) !== false) {
                        $language_code = $language['char_code'];
                        break;
                    }
                }
            }

            if(empty($language_code)) continue; // language not supported or unknown

            // detect language level
            $level = ResumeLanguage::LEVEL_MIDDLE; // default middle
            foreach ($level_aliases as $ukey => $aliases) {
                foreach ($aliases as $alias) {
                    if (mb_strpos($label_item, $alias) !== false) {
                        $level = $ukey;
                        break;
                    }
                }
            }

            $lang_item = [
                'country_code' => $language_code,
                'level' => $level
            ];

            if ($level == ResumeLanguage::LEVEL_EXPERT) {
                $lang_item['can_be_interviewed'] = ResumeLanguage::CAN_BE_IN_INTERVIEWED_YES;
            }

            $selected_langs[] = $lang_item;

        }

        return $selected_langs;
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

    protected function getGendersByLabel($gender_list)
    {
        $gender_list_aliases = [
            Resume::GENDER_MALE    => [
                'м',
                'муж',
                'мужчина'
            ],
            Resume::GENDER_FEMALE => [
                'ж',
                'жен',
                'женщина',
            ],
            Resume::GENDER_PAIR    => [
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

    protected function htmlToString($text)
    {
        return str_replace(' ,', ',', trim(preg_replace('/\s+/', ' ', strip_tags($text))));
    }

    /**
     * Finds the Resume model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Resume the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resume::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModelWithRelations($id)
    {
        $model = Resume::find()
                ->where(['id' => $id])
                ->with([
                    'categoryResumes',
                    'resumeCategoryJobs',
                    'resumeCountryCities',
                    'resumeEducations',
                    'resumeJobs',
                    'resumeLanguages'
                ])->one();
        
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }
}
