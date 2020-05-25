<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_country_city".
 *
 * @property int $id
 * @property int $resume_id
 * @property int $country_city_id
 *
 * @property CountryCity $countryCity
 * @property Resume $resume
 */
class ResumeCountryCity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_country_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'country_city_id'], 'required'],
            [['resume_id', 'country_city_id'], 'integer'],
            [['country_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryCity::className(), 'targetAttribute' => ['country_city_id' => 'id']],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resume_id' => 'Resume ID',
            'country_city_id' => 'Country City ID',
        ];
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
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }
}
