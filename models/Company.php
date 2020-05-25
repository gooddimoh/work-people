<?php

namespace app\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 - verified; 20 - not verified
 * @property string $company_name
 * @property string $company_phone company primary phone number
 * @property string $company_email
 * @property string $company_country_code variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc
 * @property int $country_city_id
 * @property string $logo
 * @property int $type 10 - employer; 20 - HR agency
 * @property string $type_industry
 * @property int $number_of_employees select list: 10 - 1-19; 20 - 20-39; 40 - 40-59; 60 - 60-99
 * @property string $site
 * @property string $description
 * @property string $document_code
 *
 * @property User $user
 * @property CompanyReview[] $companyReviews
 * @property UserCompany[] $userCompanies
 * @property Vacancy[] $vacancies
 */
class Company extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    
    const STATUS_VERIFIED = 10;
    const STATUS_NOT_VERIFIED = 20;

    const TYPE_EMPLOYER = 10;
    const TYPE_HR_AGENCY = 20;

    const NUMBER_OF_EMPLOYEES_1_19 = 10;
    const NUMBER_OF_EMPLOYEES_20_39 = 20;
    const NUMBER_OF_EMPLOYEES_40_59 = 40;
    const NUMBER_OF_EMPLOYEES_60_99 = 60;
    const NUMBER_OF_EMPLOYEES_100_plus = 90;

    const TMP_IMAGE_DIR_ALIAS = '@webroot/upload/tmp_vacancy_import';

    public $src;
    public $logo_tmp_path; // parser variable

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_name', 'company_country_code'], 'required'],
            [['user_id', 'status', 'country_city_id', 'type', 'number_of_employees'], 'integer'],
            [['description'], 'string'],
            [['src', 'logo_tmp_path'], 'safe'],
            [['company_name', 'company_phone', 'company_email', 'company_country_code', 'logo', 'type_industry', 'site', 'document_code'], 'string', 'max' => 255],
            [['country_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryCity::className(), 'targetAttribute' => ['country_city_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('company', 'ID'),
            'user_id' => Yii::t('company', 'User ID'),
            'status' => Yii::t('company', 'Status'),
            'company_name' => Yii::t('company', 'Company Name'),
            'company_phone' => Yii::t('company', 'Company Phone'),
            'company_email' => Yii::t('company', 'Company Email'),
            'company_country_code' => Yii::t('company', 'Company Country Code'),
            'country_city_id' => Yii::t('company', 'Country City ID'),
            'logo' => Yii::t('company', 'Logo'),
            'type' => Yii::t('company', 'Type'),
            'type_industry' => Yii::t('company', 'Type Industry'),
            'number_of_employees' => Yii::t('company', 'Number Of Employees'),
            'site' => Yii::t('company', 'Site'),
            'description' => Yii::t('company', 'Description'),
            'document_code' => Yii::t('company', 'Document Code'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->logo_tmp_path)) {
            // move tmp file to web dir
            // $saveDirectoryTmp = Yii::getAlias(Company::TMP_IMAGE_DIR_ALIAS);
            $saveDirectory = Yii::getAlias('@webroot/upload/users/' . $this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('logo', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $tmp = explode('.', $this->logo_tmp_path);
            $extension = array_pop($tmp);

            $this->logo = substr(md5($this->company_name), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension
            
            copy($this->logo_tmp_path, $saveDirectory.'/'.$this->logo);
        }

        if (!empty($this->src)) {
            $saveDirectory = Yii::getAlias('@webroot/upload/users/'.$this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('logo', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $data = file_get_contents($this->src);
            $pos  = strpos($this->src, ';');
            $type = explode(':', substr($this->src, 0, $pos))[1];
            
            $extension = 'jpg';
            switch($type) {
                case 'image/png': $extension = 'png'; break;
                // case 'image/jpeg': $extension = 'jpg'; break;
                default: $extension = 'jpg';
            }

            $this->logo = substr(md5($this->company_name), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension

            $imagine = Imagine::getImagine();
            $image = $imagine->load($data);
            Imagine::resize($image, 800, 600)
                ->save($saveDirectory.'/'.$this->logo, ['quality' => 75]);
        }

        return true;
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyReviews()
    {
        return $this->hasMany(CompanyReview::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyReviewsPositive()
    {
        return $this->hasMany(CompanyReview::className(), ['company_id' => 'id'])
            ->where(['=', 'worker_recommendation', CompanyReview::WORKER_RECOMMENDATION_YES]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyReviewsNegative()
    {
        return $this->hasMany(CompanyReview::className(), ['company_id' => 'id'])
            ->where(['=', 'worker_recommendation', CompanyReview::WORKER_RECOMMENDATION_NO]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyReviewsTotalRating()
    {
        return '-';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['company_id' => 'id']);
    }

    /**
     * check is current user is owner this company
     */
    public function isOwner()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->id == $this->user_id;
    }

    public function getLogoWebPath()
    {
        if(!empty($this->logo)) {
            return '/upload/users/' . $this->user_id . '/' . $this->logo;
        }
        return null;
    }

    public static function getNumberOfEmployeesList()
    {
        return [
            self::NUMBER_OF_EMPLOYEES_1_19 => '1-19',
            self::NUMBER_OF_EMPLOYEES_20_39 => '20-39',
            self::NUMBER_OF_EMPLOYEES_40_59 => '40-59',
            self::NUMBER_OF_EMPLOYEES_60_99 => '60-99',
            self::NUMBER_OF_EMPLOYEES_100_plus => '100+',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_VERIFIED => 'Проверено',
            self::STATUS_NOT_VERIFIED => 'Не проверено',
        ];
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_EMPLOYER => Yii::t('company', 'Employer'),
            self::TYPE_HR_AGENCY => Yii::t('company', 'Agency'),
        ];
    }

    public static function getCountryList()
    {
        return ReferenceHelper::getCountryList();
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCompanies()
    {
        return $this->hasMany(UserCompany::className(), ['company_id' => 'id']);
    }
}
