<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacancy_respond".
 *
 * @property int $id
 * @property int $user_id worker
 * @property int $vacancy_id
 * @property int $for_user_id vacancy user_id need for optimization join requests
 * @property int $resume_id user can attach resume to respond
 * @property int $status 10 - new respond, 20 - accepted respond, 30 - rejected respond
 * @property string $message
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Resume $resume
 * @property User $user
 * @property Vacancy $vacancy
 */
class VacancyRespond extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 10;
    const STATUS_ACCEPTED = 20;
    const STATUS_REJECTED = 30;
    const STATUS_INVITED = 40;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy_respond';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'vacancy_id', 'for_user_id'], 'required'],
            [['user_id', 'vacancy_id', 'for_user_id', 'resume_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string'],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
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
            'for_user_id' => Yii::t('vacancy', 'For User ID'),
            'resume_id' => Yii::t('vacancy', 'Resume ID'),
            'status' => Yii::t('vacancy', 'Status'),
            'message' => Yii::t('vacancy', 'Message'),
            'created_at' => Yii::t('vacancy', 'Created At'),
            'updated_at' => Yii::t('vacancy', 'Updated At'),
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
                'value' => function() { return date('U'); }, // unix timestamp
            ],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancy()
    {
        return $this->hasOne(Vacancy::className(), ['id' => 'vacancy_id']);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'Отправлена анкета',
            self::STATUS_ACCEPTED => 'Подтвердилили',
            // self::STATUS_REJECTED => 'Отклонена',
            self::STATUS_INVITED => 'Получено приглашение',
        ];
    }
}
