<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 - readed, 20 - unreaded
 * @property string $title
 * @property string $title_html
 * @property string $text
 * @property int $created_at
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    const STATUS_READED = 10;
    const STATUS_UNREADED = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'title', 'title_html', 'text'], 'required'],
            [['user_id', 'status', 'created_at'], 'integer'],
            [['text'], 'string'],
            [['title', 'title_html'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('message', 'ID'),
            'user_id' => Yii::t('message', 'User ID'),
            'status' => Yii::t('message', 'Status'),
            'title' => Yii::t('message', 'Title'),
            'title_html' => Yii::t('message', 'Title Html'),
            'text' => Yii::t('message', 'Text'),
            'created_at' => Yii::t('message', 'Created At'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
                'value' => function() { return time(); },
            ],
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
     * check is current user is owner this message
     */
    public function isOwner()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->id == $this->user_id;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_READED => 'Прочитано',
            self::STATUS_UNREADED => 'Не прочитано',
        ];
    }
}
