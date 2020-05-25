<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "auto_mail".
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $status 10 - active, 20 - not active
 * @property int $use_messenger 10 - send to Telegram or Viber, 20 - not active
 * @property string $request
 * @property string $country_codes variants like: RU;CN;UZ; - RU; - RUSSIAN, CN; - CHINA.. etc
 * @property string $location
 * @property int $created_at
 *
 * @property Category $category
 * @property User $user
 */
class AutoMail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auto_mail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'use_messenger'], 'required'],
            [['user_id', 'category_id', 'status', 'use_messenger', 'created_at'], 'integer'],
            [['request', 'country_codes', 'location'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('automail', 'ID'),
            'user_id' => Yii::t('automail', 'User ID'),
            'category_id' => Yii::t('automail', 'Category ID'),
            'status' => Yii::t('automail', 'Status'),
            'use_messenger' => Yii::t('automail', 'Use Messenger'),
            'request' => Yii::t('automail', 'Request'),
            'country_codes' => Yii::t('automail', 'Country Codes'),
            'location' => Yii::t('automail', 'Location'),
            'created_at' => Yii::t('automail', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
