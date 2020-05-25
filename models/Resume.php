<?php

namespace app\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "resume".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 - visible, 20 - hidden
 * @property string $title job title for imported records
 * @property int $use_title 10 - use job_title, 20 - use relation category job
 * @property string $job_experience worker experience for imported records
 * @property int $use_job_experience 10 - use job_experience, 20 - use relation resume_job
 * @property string $language language for imported records
 * @property int $use_language 10 - use language, 20 - use relation resume_language
 * @property int $relocation_possible 10 - possible, 20 - unknown
 * @property string $full_import_description Full imported description for admin
 * @property string $full_import_description_cleaned Full imported description for admin
 * @property int $use_full_import_description_cleaned
 * @property string $source_url
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $gender_list 10; - male, 20; - female;
 * @property string $birth_day
 * @property string $country_name
 * @property int $country_city_id
 * @property double $desired_salary
 * @property double $desired_salary_per_hour
 * @property string $desired_salary_currency_code
 * @property string $desired_country_of_work variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc
 * @property string $photo_path
 * @property string $phone phone numbers siparated by;
 * @property string $custom_country
 * @property string $description
 * @property int $creation_time
 * @property int $upvote_time manual up vote - refresh time
 * @property int $update_time
 *
 * @property CategoryResume[] $categoryResumes
 * @property User $user
 * @property ResumeCategoryJob[] $resumeCategoryJobs
 * @property ResumeCountryCity[] $resumeCountryCities
 * @property ResumeCounter $vacancyCounter
 * @property ResumeEducation[] $resumeEducations
 * @property ResumeJob[] $resumeJobs
 * @property ResumeLanguage[] $resumeLanguages
 * @property UserFavoriteResume[] $userFavoriteResumes
 * @property User[] $users
 * @property VacancyRespond[] $vacancyResponds
 */
class Resume extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait {
        \mootensai\relation\RelationTrait::saveAll as protected traitSaveAll;
    }

    const STATUS_SHOW = 10;
    const STATUS_HIDE = 20;

    const GENDER_MALE   = 10;
    const GENDER_FEMALE = 20;
    const GENDER_PAIR   = 30;

    const USE_TITLE_YES = 10;
    const USE_TITLE_REALTION = 20;

    const RELOCATION_POSSIBLE_YES = 10;
    const RELOCATION_POSSIBLE_UNKNOWN = 20;

    const USE_LANGUAGE_YES = 10;
    const USE_LANGUAGE_REALTION = 20;

    const USE_JOB_EXPERIENCE_YES = 10;
    const USE_JOB_EXPERIENCE_REALTION = 20;

    const TMP_IMAGE_DIR_ALIAS = '@webroot/upload/tmp_resume_import';

    public $src;
    public $photo_tmp_path; // parser variable
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name', 'email', 'gender_list', 'country_name', 'country_city_id', 'creation_time', 'update_time'], 'required'],
            [['user_id', 'status', 'use_title', 'use_job_experience', 'use_language', 'relocation_possible', 'use_full_import_description_cleaned', 'country_city_id', 'creation_time', 'upvote_time', 'update_time'], 'integer'],
            [['job_experience', 'full_import_description', 'full_import_description_cleaned', 'description'], 'string'],
            [['birth_day'], 'safe'],
            [['src', 'photo_tmp_path'], 'safe'],
            [['desired_salary', 'desired_salary_per_hour'], 'number'],
            [['title', 'language', 'source_url', 'first_name', 'last_name', 'middle_name', 'email', 'gender_list', 'country_name', 'desired_salary_currency_code', 'desired_country_of_work', 'photo_path', 'phone', 'custom_country'], 'string', 'max' => 255],
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
            'id' => Yii::t('resume', 'ID'),
            'user_id' => Yii::t('resume', 'User ID'),
            'status' => Yii::t('resume', 'Status'),
            'title' => Yii::t('resume', 'Title'),
            'use_title' => Yii::t('resume', 'Use Title'),
            'job_experience' => Yii::t('resume', 'Job Experience'),
            'use_job_experience' => Yii::t('resume', 'Use Job Experience'),
            'language' => Yii::t('resume', 'Language'),
            'use_language' => Yii::t('resume', 'Use Language'),
            'relocation_possible' => Yii::t('resume', 'Relocation Possible'),
            'full_import_description' => Yii::t('resume', 'Full Import Description'),
            'full_import_description_cleaned' => Yii::t('resume', 'Full Import Description Cleaned'),
            'use_full_import_description_cleaned' => Yii::t('resume', 'Use Full Import Description Cleaned'),
            'source_url' => Yii::t('resume', 'Source Url'),
            'first_name' => Yii::t('user', 'First Name'),
            'last_name' => Yii::t('user', 'Last Name'),
            'middle_name' => Yii::t('user', 'Middle Name'),
            'email' => Yii::t('resume', 'Email'),
            'gender_list' => Yii::t('resume', 'Gender List'),
            'birth_day' => Yii::t('resume', 'Birth Day'),
            'country_name' => Yii::t('profile', 'Country Name'),
            'country_city_id' => Yii::t('profile', 'Country City ID'),
            'desired_salary' => Yii::t('resume', 'Desired Salary'),
            'desired_salary_per_hour' => Yii::t('resume', 'Desired Salary Per Hour'),
            'desired_salary_currency_code' => Yii::t('resume', 'Desired Salary Currency Code'),
            'desired_country_of_work' => Yii::t('resume', 'Desired Country Of Work'),
            'photo_path' => Yii::t('resume', 'Photo Path'),
            'phone' => Yii::t('resume', 'Phone'),
            'custom_country' => Yii::t('resume', 'Custom Country'),
            'description' => Yii::t('resume', 'Description'),
            'creation_time' => Yii::t('resume', 'Creation Time'),
            'upvote_time' => Yii::t('resume', 'Upvote Time'),
            'update_time' => Yii::t('resume', 'Update Time'),
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
     * validate, relation resumeCategoryJobs - required
     */
    public function saveAll($skippedRelations = [])
    {
        if(empty($this->relatedRecords['resumeCategoryJobs'])) {
            $this->addError('resumeCategoryJobs', Yii::t('resume', 'You must fill out the «Preferred job».'));
            return false;
        }

        return $this->traitSaveAll($skippedRelations); // parent::saveAll($skippedRelations)
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->photo_tmp_path)) {
            // move tmp file to web dir
            // $saveDirectoryTmp = Yii::getAlias(Company::TMP_IMAGE_DIR_ALIAS);
            $saveDirectory = Yii::getAlias('@webroot/upload/users/' . $this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('photo_path', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $tmp = explode('.', $this->photo_tmp_path);
            $extension = array_pop($tmp);

            $this->photo_path = substr(md5($this->src), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension
            
            copy($this->photo_tmp_path, $saveDirectory.'/'.$this->photo_path);
        }

        if (!empty($this->src)) {
            $saveDirectory = Yii::getAlias('@webroot/upload/users/'.$this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('photo_path', 'Не могу создать каталог на сервере: ' . $saveDirectory);
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

            $this->photo_path = substr(md5($this->src), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension

            $imagine = Imagine::getImagine();
            $image = $imagine->load($data);
            Imagine::resize($image, 800, 600)
                ->save($saveDirectory.'/'.$this->photo_path, ['quality' => 75]);
        }

        return true;
    }

    public function getImageWebPath() {
        if(!empty($this->photo_path)) {
            return '/upload/users/' . $this->user_id . '/' . $this->photo_path;
        }
        return null;
    }

    public function getFullName()
    {
        $full_name_arr = [$this->first_name];
        
        if (!empty($this->middle_name)) {
            $full_name_arr[] = $this->middle_name;
        }

        if (!empty($this->last_name)) {
            $full_name_arr[] = $this->last_name;
        }

        return implode(' ', $full_name_arr);
    }

    public function getAge()
    {
        $from = new \DateTime($this->birth_day);
        $to   = new \DateTime('today');
        return $from->diff($to)->y;
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
    public function getResumeCounter()
    {
        return $this->hasOne(ResumeCounter::className(), ['resume_id' => 'id']);
    }

    public function getDesiredCountryOfWork()
    {
        $country_list = self::getCountryList();
        $country_code_list = explode(';', $this->desired_country_of_work);
        array_pop($country_code_list); // remove last empty
        
        $selected_country_list = [];
        foreach($country_list as $country) {
            if(in_array($country['char_code'], $country_code_list)) {
                array_push($selected_country_list, $country);
            }
        }

        return $selected_country_list;
    }

    /**
     * need for post create
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryResumes()
    {
        return $this->hasMany(CategoryResume::className(), ['resume_id' => 'id']);
    }

    public function getCategories()
    {
        // return $this->hasMany(Category::className(), ['resume_id' => 'id']);
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
                    ->viaTable('category_resume', ['resume_id' => 'id']);
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
    public function getResumeCategoryJobs()
    {
        return $this->hasMany(ResumeCategoryJob::className(), ['resume_id' => 'id']);
    }

    public function getCategoryJobs()
    {
        return $this->hasMany(CategoryJob::className(), ['id' => 'category_job_id'])
                    ->viaTable('resume_category_job', ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumeCountryCities()
    {
        return $this->hasMany(ResumeCountryCity::className(), ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumeEducations()
    {
        return $this->hasMany(ResumeEducation::className(), ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumeJobs()
    {
        return $this->hasMany(ResumeJob::className(), ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumeLanguages()
    {
        return $this->hasMany(ResumeLanguage::className(), ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavoriteResumes()
    {
        return $this->hasMany(UserFavoriteResume::className(), ['resume_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_favorite_resume', ['resume_id' => 'id']);
    }

    public function isOwner()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->id == $this->user_id;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_SHOW => Yii::t('main', 'Show'),
            self::STATUS_HIDE => Yii::t('main', 'Hide'),
        ];
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

    public static function getGenderList()
    {
        return [
            self::GENDER_MALE   => Yii::t('vacancy', 'Male'),
            self::GENDER_FEMALE => Yii::t('vacancy', 'Female'),
            self::GENDER_PAIR   => Yii::t('vacancy', 'Pair'),
        ];
    }

    public static function getCurrencyList()
    {
        return ReferenceHelper::getCurrencyList();
    }

    public static function getCountryList()
    {
        return ReferenceHelper::getCountryList();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancyResponds()
    {
        return $this->hasMany(VacancyRespond::className(), ['resume_id' => 'id']);
    }
}
