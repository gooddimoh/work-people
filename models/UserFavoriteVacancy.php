<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_favorite_vacancy".
 *
 * @property int $id
 * @property int $user_id
 * @property int $vacancy_id
 *
 * @property User $user
 * @property Vacancy $vacancy
 */
class UserFavoriteVacancy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorite_vacancy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'vacancy_id'], 'required'],
            [['user_id', 'vacancy_id'], 'integer'],
            [['user_id', 'vacancy_id'], 'unique', 'targetAttribute' => ['user_id', 'vacancy_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['vacancy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacancy::className(), 'targetAttribute' => ['vacancy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vacancy', 'ID'),
            'user_id' => Yii::t('vacancy', 'User ID'),
            'vacancy_id' => Yii::t('vacancy', 'Vacancy ID'),
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
    public function getVacancy()
    {
        return $this->hasOne(Vacancy::className(), ['id' => 'vacancy_id']);
    }
}
