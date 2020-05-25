<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_favorite_resume".
 *
 * @property int $id
 * @property int $user_id
 * @property int $resume_id
 *
 * @property Resume $resume
 * @property User $user
 */
class UserFavoriteResume extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorite_resume';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'resume_id'], 'required'],
            [['user_id', 'resume_id'], 'integer'],
            [['user_id', 'resume_id'], 'unique', 'targetAttribute' => ['user_id', 'resume_id']],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
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
            'resume_id' => Yii::t('resume', 'Resume ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
