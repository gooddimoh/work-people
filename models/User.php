<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property int $status
 * @property string $username
 * @property string $email
 * @property string $role
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $balance
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    
    // const ROLE_BANNED = 0;
    const ROLE_USER = 'user';
    // const ROLE_REDACTOR = 20;
    const ROLE_ADMINISTRATOR = 'administrator';
    const ROLE_MANAGER = 'manager';
    
    const SCENARION_REGISTRATION = 10;
    const SCENARION_RESET_PASSWORD = 20;
    const SCENARIO_EDIT_PROFILE = 30;
    const SCENARIO_ADMIN_EDIT_PROFILE = 40;
    const SCENARION_ULOGIN = self::SCENARIO_DEFAULT;
    const SCENARION_REGISTRATION_BY_PHONE = self::SCENARIO_DEFAULT;

    const EMAIL_VERIFIED = 10;
    const EMAIL_NOT_VERIFIED = 20;
    
    public $password;
    public $password_new;
    public $password_repeat;
    
    public $reCaptcha;

    private $_user_phone = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'email'], 'required'],
            [['balance'], 'number'],
            [['status', 'verified_email'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['login', 'username', 'email', 'photo_path', 'role', 'password_hash', 'password_reset_token', 'email_confirm_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['login'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            // registration
            [['password', 'password_repeat'], 'required', 'on'=>self::SCENARION_REGISTRATION],
            [['password', 'password_repeat'], 'string', 'min'=>8, 'max'=>72, 'on'=>self::SCENARION_REGISTRATION],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"«Пароль» и «Повтор пароля» должны совпадать", 'on' => self::SCENARION_REGISTRATION],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'uncheckedMessage' => 'Пожалуйста подтвердите что вы не робот.', 'on' => self::SCENARION_REGISTRATION],
            // reset password
            [['password', 'password_repeat'], 'required', 'on'=>self::SCENARION_RESET_PASSWORD],
            [['password', 'password_repeat'], 'string', 'min'=>8, 'max'=>72, 'on'=>self::SCENARION_RESET_PASSWORD],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"«Пароль» и «Повтор пароля» должны совпадать", 'on' => self::SCENARION_RESET_PASSWORD],// reset password
            // edit profile
            [['password'], 'required', 'on'=>self::SCENARIO_EDIT_PROFILE],
            ['password', function ($attribute, $params) {
                if(!$this->validatePassword(trim($this->$attribute)))
                    $this->addError($attribute, 'Не верный "Текущий пароль"');
            }, 'on'=>self::SCENARIO_EDIT_PROFILE],
            [['password_new', 'password_repeat'], 'string', 'min'=>8, 'max'=>72, 'on'=>self::SCENARIO_EDIT_PROFILE],
            ['password_repeat', 'compare', 'compareAttribute'=>'password_new', 'message'=>"«Пароль» и «Повтор пароля» должны совпадать", 'on' => self::SCENARIO_EDIT_PROFILE],
            [['password_new'], 'string', 'on'=>self::SCENARIO_ADMIN_EDIT_PROFILE],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('Y-m-d h:i:s'); },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'id'),
            'login' => Yii::t('user', 'login'),
            'status' => Yii::t('user', 'status'),
            'username' => Yii::t('user', 'username'),
            'email' => Yii::t('user', 'email'),
            'role' => Yii::t('user', 'role'),
            'auth_key' => Yii::t('user', 'auth_key'),
            'password_hash' => Yii::t('user', 'password_hash'),
            'password_reset_token' => Yii::t('user', 'password_reset_token'),
            'email_confirm_token' => Yii::t('user', 'email_confirm_token'),
            'created_at' => Yii::t('user', 'created_at'),
            'updated_at' => Yii::t('user', 'updated_at'),
            'balance' => Yii::t('user', 'Balance'),
            'password' => Yii::t('user', 'password'),
            'password_new' => Yii::t('user', 'password_new'),
            'password_repeat' => Yii::t('user', 'password_repeat'),
            'reCaptcha' => '',
        ];
    }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by username
     *
     * @param  string      $username or email
     * @return static|null
     */
    public static function findByUsernameOrEmail($username)
    {
        return static::find()
                        ->where(['login' => $username, 'status' => self::STATUS_ACTIVE])
                        ->orWhere(['email' => $username, 'status' => self::STATUS_ACTIVE])
                        ->one();
    }

    /**
     * Finds user by username
     *
     * @param  string      $username or email
     * @return static|null
     */
    public static function findByPhone($phone)
    {
        return static::find()
                        ->where(['`user_phone`.`phone`' => intval($phone), '`user`.`status`' => self::STATUS_ACTIVE])
                        ->innerJoin('user_phone', '`user_phone`.`user_id` = `user`.`id`')
                        ->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @param [in] $phone_number unique `phone` from UserPhone
     */
    public function setPhoneId($phone_number)
    {
        Yii::$app->session->set('phone_number', $phone_number);
    }

    /**
     * get phone identity if user loged via phone number
     * @return \yii\db\ActiveQuery
     */
    public function getPhoneIdentity()
    {
        $phone_number = Yii::$app->session->get('phone_number');
        if(empty($phone_number)) { // if user not login by phone number
            // return false;
            //! BUG, lets find first
            $this->_user_phone = UserPhone::find()->where(['user_id' => $this->id])->one();
        }

        if ($this->_user_phone === false) {
            $this->_user_phone = UserPhone::find()->where(['phone' => intval($phone_number)])->one();
        }

        return $this->_user_phone;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return self::validate_pass($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = self::crypt_pass($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     *  Generates new email confirm token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString() . '_' . time();;
        // $this->date_confirm = null;
    }
    /** EXTENSION MOVIE **/

    public function getVacancyRespondCount()
    {
        return VacancyRespond::find()->where([
            'user_id' => $this->id,
            'status' => VacancyRespond::STATUS_NEW,
        ])->count();
    }

    public function getVacancyRespondAcceptedCount()
    {
        return VacancyRespond::find()->where([
            'user_id' => $this->id,
            'status' => VacancyRespond::STATUS_ACCEPTED,
        ])->count();
    }

    public function getWantToWorkCount()
    {
        return VacancyRespond::find()->where([
            'for_user_id' => $this->id,
            'status' => VacancyRespond::STATUS_NEW,
        ])->count();
    }
    
    public function getResumeSentCount()
    {
        return VacancyRespond::find()->where([
            'for_user_id' => $this->id,
            'status' => VacancyRespond::STATUS_NEW,
        ])->count();
    }

    public function getResumeConfirmedCount()
    {
        return VacancyRespond::find()->where([
            'for_user_id' => $this->id,
            'status' => VacancyRespond::STATUS_ACCEPTED,
        ])->count();
    }

    public function getResumeInvitedCount()
    {
        return VacancyRespond::find()->where([
            'for_user_id' => $this->id,
            'status' => VacancyRespond::STATUS_INVITED,
        ])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavoriteResumes()
    {
        return $this->hasMany(UserFavoriteResume::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavoriteVacancies()
    {
        return $this->hasMany(UserFavoriteVacancy::className(), ['user_id' => 'id']);
    }

    /**
     *  @brief calculate user balance
     *  
     *  @return number current user balance
     *      
     */
    public function getBalance()
    {
        return number_format($this->balance, 2, '.', '');
    }

    /**
     * check is current user has any resume
     */
    public function isUserHasVacancy() {
        return Vacancy::find()->where([ 'company_id' => $this->company->id])->one() !== null;
    }

    /**
     * check is current user has any resume
     */
    public function isUserHasResume() {
        return Resume::find()->where([ 'user_id' => $this->id])->one() !== null;
    }

    /**
     *  @brief generate password
     *  
     *  @param [in] $pass Parameter_Description
     *  @return string(60)
     */
    public static function crypt_pass($pass, $salt='haiflive_222')
    {
        return Yii::$app->getSecurity()->generatePasswordHash($pass . $salt);
    }
    
    /**
     *  @brief validate password
     *  
     *  @param [in] $pass password
     *  @param [in] $hash password hash
     *  @return bool
     */
    public static function validate_pass($pass, $hash, $salt='haiflive_222')
    {
        try {
            return Yii::$app->security->validatePassword($pass . $salt, $hash);
        }
        catch( \yii\base\InvalidParamException $e ){
            return false;
        }
    }
}
