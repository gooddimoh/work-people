<?php

namespace app\models;

use Yii;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "vacancy".
 *
 * @property int $id
 * @property int $company_id
 * @property int $user_phone_id
 * @property int $category_job_id
 * @property int $status 10 - show; 20 - hide
 * @property int $pin_position
 * @property int $special_status
 * @property int $show_on_main_page
 * @property int $main_page_priority
 * @property string $title for parsed vacancy, use it if not empty
 * @property string $company_name
 * @property string $gender_list variants like: 10;20;30; - 10; - male, 20; - female; 30; - pair
 * @property int $age_min
 * @property int $age_max
 * @property string $employment_type variants like: 10;30; - 10; - full-time, 20; - shift method, 30; - part-time, 40; - shift work
 * @property string $worker_country_codes variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc, if null then for all countries
 * @property int $free_places
 * @property int $regular_places
 * @property string $date_start
 * @property string $date_end
 * @property int $date_free
 * @property string $country_name
 * @property int $country_city_id
 * @property double $salary_per_hour_min
 * @property double $salary_per_hour_max
 * @property double $salary_per_hour_min_src converted to main currency for better ordering and search
 * @property double $salary_per_hour_max_src converted to main currency for better ordering and search
 * @property string $currency_code
 * @property double $hours_per_day_min
 * @property double $hours_per_day_max
 * @property double $days_per_week_min
 * @property double $days_per_week_max
 * @property double $prepaid_expense_min
 * @property double $prepaid_expense_max
 * @property string $type_of_working_shift variants like: 10;30; - 10; - day, 20; - night, 30; - evening
 * @property int $residence_provided 10 - provided, 20 - not provided
 * @property double $residence_amount
 * @property string $residence_amount_currency_code
 * @property int $residence_people_per_room
 * @property string $documents_provided variants like: 10;30; - 10; - wokring visa, 20; - Residence
 * @property string $documents_required variants like: 10;30; - 10; - biometric passport, 20; - wokring visa, 30; - residence, 40; - permanent residence, 50; - EU citizenship
 * @property string $source_url
 * @property string $full_import_description
 * @property string $full_import_description_cleaned
 * @property int $use_full_import_description_cleaned
 * @property string $job_description
 * @property string $job_description_bonus
 * @property string $contact_name
 * @property string $contact_phone additional contact phone numbers spliced by ;
 * @property string $contact_email_list
 * @property string $main_image
 * @property int $agency_accept
 * @property int $agency_paid_document 10 - accept, 20 - reject
 * @property double $agency_paid_document_price
 * @property string $agency_paid_document_currency_code
 * @property int $agency_free_document 10 - accept, 20 - reject
 * @property int $agency_pay_commission 10 - accept, 20 - reject
 * @property double $agency_pay_commission_amount
 * @property string $agency_pay_commission_currency_code
 * @property int $secure_deal 10 - secure pay, 20 - direct pay
 * @property string $meta_keywords
 * @property string $meta_description
 * @property int $creation_time
 * @property int $upvote_time manual up vote - refresh time
 * @property int $update_time
 *
 * @property CategoryVacancy[] $categoryVacancies
 * @property UserFavoriteVacancy[] $userFavoriteVacancies
 * @property User[] $users
 * @property CategoryJob $categoryJob
 * @property Company $company
 * @property CountryCity $countryCity
 * @property VacancyCounter $vacancyCounter
 * @property VacancyImage[] $vacancyImages
 * @property VacancyRespond[] $vacancyResponds
 */
class Vacancy extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    const STATUS_SHOW = 10;
    const STATUS_HIDE = 20;

    const GENDER_MALE   = 10;
    const GENDER_FEMALE = 20;
    const GENDER_PAIR   = 30;

    const EMPLOYMENT_TYPE_FULL_TIME    = 10;
    const EMPLOYMENT_TYPE_SHIFT_METHOD = 20;
    const EMPLOYMENT_TYPE_PART_TIME    = 30;
    const EMPLOYMENT_TYPE_SHIFT_WORK   = 40;

    const REGULAR_PLACES_YES = 10;
    const REGULAR_PLACES_NO  = 20;

    const DATE_FREE_YES = 10;
    const DATE_FREE_NO  = 20;

    const TYPE_OF_WORKING_SHIFT_DAY     = 10;
    const TYPE_OF_WORKING_SHIFT_NIGHT   = 20;
    const TYPE_OF_WORKING_SHIFT_EVENING = 30;

    const RESIDENCE_PROVIDED_YES = 10;
    const RESIDENCE_PROVIDED_NO  = 20;

    const DOCUMENTS_PROVIDED_VISA = 10;
    const DOCUMENTS_PROVIDED_RESIDENCE_PERMIT = 20;

    const DOCUMENTS_REQUIRED_BIOMETRIC_PASSPORT  = 10;
    const DOCUMENTS_REQUIRED_WORK_VISA           = 20;
    const DOCUMENTS_REQUIRED_RESIDENCE_PERMIT    = 30;
    const DOCUMENTS_REQUIRED_PERMANENT_RESIDENCE = 40;
    const DOCUMENTS_REQUIRED_EU_CITIZENSHIP      = 50;

    const AGENCY_ACCEPT_YES = 10;
    const AGENCY_ACCEPT_NO = 20;

    const AGENCY_PAID_DOCUMENT_YES = 10;
    const AGENCY_PAID_DOCUMENT_NO  = 20;

    const AGENCY_FREE_DOCUMENT_YES = 10;
    const AGENCY_FREE_DOCUMENT_NO  = 20;

    const AGENCY_PAY_COMMISSION_YES = 10;
    const AGENCY_PAY_COMMISSION_NO  = 20;

    const SECURE_DEAL_YES = 10;
    const SECURE_DEAL_NO = 20;

    const SHOW_ON_MAIN_PAGE_YES = 10;
    const SHOW_ON_MAIN_PAGE_NO  = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'user_phone_id', 'category_job_id', 'title', 'gender_list', 'country_name', 'salary_per_hour_min', 'salary_per_hour_min_src', 'currency_code', 'hours_per_day_min', 'days_per_week_min', 'prepaid_expense_min', 'type_of_working_shift', 'job_description', 'contact_phone', 'contact_email_list', 'creation_time', 'update_time'], 'required'],
            [['company_id', 'user_phone_id', 'category_job_id', 'status', 'pin_position', 'special_status', 'show_on_main_page', 'main_page_priority', 'age_min', 'age_max', 'free_places', 'regular_places', 'date_free', 'country_city_id', 'residence_provided', 'residence_people_per_room', 'use_full_import_description_cleaned', 'agency_accept', 'agency_paid_document', 'agency_free_document', 'agency_pay_commission', 'secure_deal', 'creation_time', 'upvote_time', 'update_time'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['date_start', 'date_end'], 'date', 'format' => 'php:Y-m-d'],
            [['salary_per_hour_min', 'salary_per_hour_max', 'salary_per_hour_min_src', 'salary_per_hour_max_src', 'hours_per_day_min', 'hours_per_day_max', 'days_per_week_min', 'days_per_week_max', 'prepaid_expense_min', 'prepaid_expense_max', 'residence_amount', 'agency_paid_document_price', 'agency_pay_commission_amount'], 'number'],
            [['hours_per_day_min', 'hours_per_day_max'], 'integer', 'min'=>1, 'max'=>24],
            [['days_per_week_min', 'days_per_week_max'], 'integer', 'min'=>1, 'max'=>7],
            [['full_import_description', 'full_import_description_cleaned', 'job_description', 'job_description_bonus', 'meta_keywords', 'meta_description'], 'string'],
            [['title', 'company_name', 'gender_list', 'worker_country_codes', 'country_name', 'currency_code', 'type_of_working_shift', 'residence_amount_currency_code', 'documents_provided', 'documents_required', 'contact_name', 'contact_phone', 'contact_email_list', 'main_image', 'agency_paid_document_currency_code', 'agency_pay_commission_currency_code'], 'string', 'max' => 255],
            [['source_url'], 'string', 'max' => 1000],
            [['category_job_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryJob::className(), 'targetAttribute' => ['category_job_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['user_phone_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPhone::className(), 'targetAttribute' => ['user_phone_id' => 'id']],
            [['country_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryCity::className(), 'targetAttribute' => ['country_city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vacancy', 'ID'),
            'company_id' => Yii::t('vacancy', 'Company'),
            'user_phone_id' => Yii::t('vacancy', 'User Phone ID'),
            'category_job_id' => Yii::t('vacancy', 'Category Job ID'),
            'status' => Yii::t('vacancy', 'Status'),
            'pin_position' => Yii::t('vacancy', 'Pin Position'),
            'special_status' => Yii::t('vacancy', 'Special Status'),
            'show_on_main_page' => Yii::t('vacancy', 'Show On Main Page'),
            'main_page_priority' => Yii::t('vacancy', 'Main Page Priority'),
            'title' => Yii::t('vacancy', 'Title'),
            'company_name' => Yii::t('vacancy', 'Company Name'),
            'gender_list' => Yii::t('vacancy', 'Gender List'),
            'age_min' => Yii::t('vacancy', 'Age Min'),
            'age_max' => Yii::t('vacancy', 'Age Max'),
            'employment_type' => Yii::t('vacancy', 'Employment Type'),
            'worker_country_codes' => Yii::t('vacancy', 'Worker Country Codes'),
            'free_places' => Yii::t('vacancy', 'Free Places'),
            'regular_places' => Yii::t('vacancy', 'Regular Places'),
            'date_start' => Yii::t('vacancy', 'Date Start'),
            'date_end' => Yii::t('vacancy', 'Date End'),
            'date_free' => Yii::t('vacancy', 'Date Free'),
            'country_name' => Yii::t('vacancy', 'Country Name'),
            'country_city_id' => Yii::t('vacancy', 'Country City ID'),
            'salary_per_hour_min' => Yii::t('vacancy', 'Salary Per Hour Min'),
            'salary_per_hour_max' => Yii::t('vacancy', 'Salary Per Hour Max'),
            'salary_per_hour_min_src' => Yii::t('vacancy', 'Salary Per Hour Min Src'),
            'salary_per_hour_max_src' => Yii::t('vacancy', 'Salary Per Hour Max Src'),
            'currency_code' => Yii::t('vacancy', 'Currency Code'),
            'hours_per_day_min' => Yii::t('vacancy', 'Hours Per Day Min'),
            'hours_per_day_max' => Yii::t('vacancy', 'Hours Per Day Max'),
            'days_per_week_min' => Yii::t('vacancy', 'Days Per Week Min'),
            'days_per_week_max' => Yii::t('vacancy', 'Days Per Week Max'),
            'prepaid_expense_min' => Yii::t('vacancy', 'Prepaid Expense Min'),
            'prepaid_expense_max' => Yii::t('vacancy', 'Prepaid Expense Max'),
            'type_of_working_shift' => Yii::t('vacancy', 'Type Of Working Shift'),
            'residence_provided' => Yii::t('vacancy', 'Residence Provided'),
            'residence_amount' => Yii::t('vacancy', 'Residence Amount'),
            'residence_amount_currency_code' => Yii::t('vacancy', 'Residence Amount Currency Code'),
            'residence_people_per_room' => Yii::t('vacancy', 'Residence People Per Room'),
            'documents_provided' => Yii::t('vacancy', 'Documents Provided'),
            'documents_required' => Yii::t('vacancy', 'Documents Required'),
            'source_url' => Yii::t('vacancy', 'Source URL'),
            'full_import_description' => Yii::t('vacancy', 'Full Import Description'),
            'full_import_description_cleaned' => Yii::t('vacancy', 'Full Import Description Cleaned'),
            'job_description' => Yii::t('vacancy', 'Job Description'),
            'job_description_bonus' => Yii::t('vacancy', 'Job Description Bonus'),
            'contact_name' => Yii::t('vacancy', 'Contact Name'),
            'contact_phone' => Yii::t('vacancy', 'Contact Phone'),
            'contact_email_list' => Yii::t('vacancy', 'Contact Email List'),
            'main_image' => Yii::t('vacancy', 'Main Image'),
            'agency_accept' => Yii::t('vacancy', 'Agency Accept'),
            'agency_paid_document' => Yii::t('vacancy', 'Agency Paid Document'),
            'agency_paid_document_price' => Yii::t('vacancy', 'Agency Paid Document Price'),
            'agency_paid_document_currency_code' => Yii::t('vacancy', 'Agency Paid Document Currency Code'),
            'agency_free_document' => Yii::t('vacancy', 'Agency Free Document'),
            'agency_pay_commission' => Yii::t('vacancy', 'Agency Pay Commission'),
            'agency_pay_commission_amount' => Yii::t('vacancy', 'Agency Pay Commission Amount'),
            'agency_pay_commission_currency_code' => Yii::t('vacancy', 'Agency Pay Commission Currency Code'),
            'secure_deal' => Yii::t('vacancy', 'Secure Deal'),
            'meta_keywords' => Yii::t('vacancy', 'Meta Keywords'),
            'meta_description' => Yii::t('vacancy', 'Meta Description'),
            'creation_time' => Yii::t('vacancy', 'Creation Time'),
            'upvote_time' => Yii::t('resume', 'Upvote Time'),
            'update_time' => Yii::t('vacancy', 'Update Time'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'creation_time',
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'upvote_time',
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'update_time',
                ],
                'value' => function() { return date('U'); }, // unix timestamp
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryVacancies()
    {
        return $this->hasMany(CategoryVacancy::className(), ['vacancy_id' => 'id']);
    }

    public function getCategories()
    {
        // return $this->hasMany(Category::className(), ['vacancy_id' => 'id']);
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
                    ->viaTable('category_vacancy', ['vacancy_id' => 'id']);
    }

    public function getVacancyCountry()
    {
        $country_list = self::getCountryList();
        foreach($country_list as $country) {
            if($country['char_code'] == $this->country_name) {
                return $country;
            }
        }

        return [ // imposible code
            'char_code' => '',
            'name' => 'unknown',
        ];
    }

    public function getWorkerCountries()
    {
        if ($this->worker_country_codes === null) {
            return [];
        }

        $country_list = self::getCountryList();
        $country_code_list = explode(';', $this->worker_country_codes);
        array_pop($country_code_list); // remove last empty
        
        $selected_country_list = [];
        foreach($country_list as $country) {
            if(in_array($country['char_code'], $country_code_list)) {
                array_push($selected_country_list, $country);
            }
        }

        return $selected_country_list;
    }

    public function getEmploymentTypes()
    {
        $employment_list = self::getEmploymentTypeList();
        $employment_type_arr = explode(';', $this->employment_type);
        array_pop($employment_type_arr); // remove last empty
        
        $selected_employment_type_list = [];
        foreach($employment_list as $key_id => $employment) {
            if(in_array($key_id, $employment_type_arr)) {
                $selected_employment_type_list[$key_id] = $employment;
            }
        }

        return $selected_employment_type_list;
    }

    public function getWorkingShifts()
    {
        $working_shift_list = self::getTypeOfWorkingShiftList();
        
        foreach($working_shift_list as $key => $value) {
            $working_shift_list[$key] = Yii::t('vacancy', $value);
        }

        $working_shift_arr = explode(';', $this->type_of_working_shift);
        array_pop($working_shift_arr); // remove last empty
        
        $selected_working_shift_list = [];
        foreach($working_shift_list as $key_id => $working_shift) {
            if(in_array($key_id, $working_shift_arr)) {
                $selected_working_shift_list[$key_id] = $working_shift;
            }
        }

        return $selected_working_shift_list;
    }

    public function getGenders()
    {
        $gender_list = self::getGenderList();
        $gender_arr = explode(';', $this->gender_list);
        array_pop($gender_arr); // remove last empty
        
        $selected_gender_list = [];
        foreach($gender_list as $key_id => $gender) {
            if(in_array($key_id, $gender_arr)) {
                $selected_gender_list[$key_id] = $gender;
            }
        }

        return $selected_gender_list;
    }

    public function getDocumentsProvided()
    {
        $documents_provided_list = self::getDocumentsProvidedList();
        $documents_provided_arr = explode(';', $this->documents_provided);
        array_pop($documents_provided_arr); // remove last empty
        
        $selected_documents_provided_list = [];
        foreach($documents_provided_list as $key_id => $documents_provided) {
            if(in_array($key_id, $documents_provided_arr)) {
                $selected_documents_provided_list[$key_id] = $documents_provided;
            }
        }

        return $selected_documents_provided_list;
    }

    public function getDocumentsRequired()
    {
        $documents_required_list = self::getDocumentsRequiredList();
        $documents_required_arr = explode(';', $this->documents_required);
        array_pop($documents_required_arr); // remove last empty
        
        $selected_documents_required_list = [];
        foreach($documents_required_list as $key_id => $documents_required) {
            if(in_array($key_id, $documents_required_arr)) {
                $selected_documents_required_list[$key_id] = $documents_required;
            }
        }

        return $selected_documents_required_list;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPhone()
    {
        return $this->hasOne(UserPhone::className(), ['id' => 'user_phone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCity()
    {
        return $this->hasOne(CountryCity::className(), ['id' => 'country_city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancyCounter()
    {
        return $this->hasOne(VacancyCounter::className(), ['vacancy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancyImages()
    {
        return $this->hasMany(VacancyImage::className(), ['vacancy_id' => 'id']);
    }

    /**
    * @return \yii\db\ActiveQuery
    */
   public function getUserFavoriteVacancies()
   {
       return $this->hasMany(UserFavoriteVacancy::className(), ['vacancy_id' => 'id']);
   }

   /**
    * @return \yii\db\ActiveQuery
    */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_favorite_vacancy', ['vacancy_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryJob()
    {
        return $this->hasOne(CategoryJob::className(), ['id' => 'category_job_id']);
    }

    public function isOwner()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->id == $this->company->user_id;
    }

    public static function getStatusList()
    {
        return [
            Vacancy::STATUS_SHOW => 'Показать',
            Vacancy::STATUS_HIDE => 'Скрыть',
        ];
    }
    
    public static function getRegularPlacesList()
    {
        return [
            self::REGULAR_PLACES_YES => 'Да',
            self::REGULAR_PLACES_NO  => 'Нет',
        ];
    }
    
    public static function getDateFreeList()
    {
        return [
            self::DATE_FREE_YES => 'Да',
            self::DATE_FREE_NO  => 'Нет',
        ];
    }

    public static function getResidenceProvidedList()
    {
        return [
            self::RESIDENCE_PROVIDED_YES => 'Да',
            self::RESIDENCE_PROVIDED_NO  => 'Нет',
        ];
    }
    
    public static function getAgencyAcceptList()
    {
        return [
            self::AGENCY_ACCEPT_YES => 'Да',
            self::AGENCY_ACCEPT_NO  => 'Нет',
        ];
    }
    
    public static function getAgencyPaidDocumentList()
    {
        return [
            self::AGENCY_PAID_DOCUMENT_YES => 'Да',
            self::AGENCY_PAID_DOCUMENT_NO  => 'Нет',
        ];
    }

    public static function getAgencyFreeDocumentList()
    {
        return [
            self::AGENCY_FREE_DOCUMENT_YES => 'Да',
            self::AGENCY_FREE_DOCUMENT_NO  => 'Нет',
        ];
    }

    public static function getAgencyPayCommissionList()
    {
        return [
            self::AGENCY_PAY_COMMISSION_YES => 'Да',
            self::AGENCY_PAY_COMMISSION_NO  => 'Нет',
        ];
    }
    
    public static function getSecureDealList()
    {
        return [
            self::SECURE_DEAL_YES => 'Да',
            self::SECURE_DEAL_NO  => 'Нет',
        ];
    }

    public static function getShowOnMainPageStatusList()
    {
        return [
            Vacancy::SHOW_ON_MAIN_PAGE_YES => 'Показать на главной',
            Vacancy::SHOW_ON_MAIN_PAGE_NO => 'Скрыть с главной',
        ];
    }

    public static function getGenderList()
    {
        return [
            self::GENDER_MALE   => Yii::t('vacancy', 'Male'),
            self::GENDER_FEMALE => Yii::t('vacancy', 'Female'),
            self::GENDER_PAIR   => Yii::t('vacancy', 'Pair'),
        ];
    }

    public static function getEmploymentTypeList()
    {
        return [
            self::EMPLOYMENT_TYPE_FULL_TIME    => Yii::t('vacancy', 'Full-time'),
            self::EMPLOYMENT_TYPE_SHIFT_METHOD => Yii::t('vacancy', 'Shift method'),
            self::EMPLOYMENT_TYPE_PART_TIME    => Yii::t('vacancy', 'Part time'),
            // self::EMPLOYMENT_TYPE_SHIFT_WORK   => Yii::t('vacancy', 'Shift work'),
        ];
    }

    public static function getCountryList()
    {
        return ReferenceHelper::getEmployerCountryList();
    }

    public static function getCurrencyList()
    {
        return ReferenceHelper::getCurrencyList();
    }

    public static function getTypeOfWorkingShiftList()
    {
        return [
            self::TYPE_OF_WORKING_SHIFT_DAY   => 'Day',
            self::TYPE_OF_WORKING_SHIFT_NIGHT => 'Night',
            self::TYPE_OF_WORKING_SHIFT_EVENING   => 'Evening',
        ];
    }

    public static function getDocumentsProvidedList()
    {
        return [
            self::DOCUMENTS_PROVIDED_VISA => 'Documents for VISA',
            self::DOCUMENTS_PROVIDED_RESIDENCE_PERMIT => 'Documents for residence permit',
        ];
    }

    public static function getDocumentsRequiredList()
    {
        return [
            self::DOCUMENTS_REQUIRED_BIOMETRIC_PASSPORT  => 'Biometric passport',
            self::DOCUMENTS_REQUIRED_WORK_VISA           => 'Work Visa',
            self::DOCUMENTS_REQUIRED_RESIDENCE_PERMIT    => 'Residence permit',
            self::DOCUMENTS_REQUIRED_PERMANENT_RESIDENCE => 'Permanent residence',
            self::DOCUMENTS_REQUIRED_EU_CITIZENSHIP      => 'EU citizenship',
        ];
    }

    public function getImageWebPath() {
        if(!empty($this->main_image)) {
            return '/upload/vacancy/' . $this->id . '/' . $this->main_image;
        }
        
        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancyResponds()
    {
        return $this->hasMany(VacancyRespond::className(), ['vacancy_id' => 'id']);
    }
}
