<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_phone".
 *
 * @property int $id
 * @property int $user_id
 * @property int $verified 10 - registred account by sms, 20 - just inserted by user, 30 - inserted by parser, 40 - login user
 * @property string $phone
 * @property string $contact_phone_for_admin
 * @property string $phone_messangers variants like: viber;whatsapp;telegram;
 * @property string $company_role access level for company for this phone number
 * @property string $company_worker_name
 * @property string $company_worker_email
 *
 * @property User $user
 * @property Vacancy[] $vacancies
 */
class UserPhone extends \yii\db\ActiveRecord
{
    const VERIFIED_REGISTRED_ACCOUNT_BY_SMS = 10;
    const VERIFIED_JUST_INSERTED_BY_USER = 20;
    const VERIFIED_INSERTED_BY_PARSER = 30;
    const VERIFIED_LOGIN_USER = 40;

    const ROLE_USER = 'user';
    const ROLE_COMPANY_OWNER = 'owner';

    const SCENARION_CREATE_COMPANY = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_phone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'phone'], 'required'],
            [['user_id', 'verified'], 'integer'],
            [['phone'], 'integer', 'on' => self::SCENARION_CREATE_COMPANY],
            [['phone', 'contact_phone_for_admin', 'phone_messangers', 'company_role', 'company_worker_name', 'company_worker_email'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'user_id' => Yii::t('user', 'User ID'),
            'verified' => Yii::t('user', 'Verified'),
            'phone' => Yii::t('user', 'Phone'),
            'contact_phone_for_admin' => Yii::t('company', 'Contact Phone'),
            'phone_messangers' => Yii::t('user', 'Phone Messangers'),
            'company_role' => Yii::t('company', 'Company Role'),
            'company_worker_name' => Yii::t('company', 'Contact Name'),
            'company_worker_email' => Yii::t('company', 'Contact Email'),
        ];
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
    public function getVacancies()
    {
        return $this->hasMany(Vacancy::className(), ['user_phone_id' => 'id']);
    }
}
