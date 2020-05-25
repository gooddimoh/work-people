<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_room".
 *
 * @property int $id
 * @property int $user_id room owner ID
 * @property int $sender_id
 * @property int $status 10 - active, 20 - archive
 * @property int $favorite 10 - favorite, 20 - default
 *
 * @property Message[] $messages
 * @property User $sender
 * @property User $user
 */
class MessageRoom extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_ARCHIVE = 20;

    const FAVORITE_YES = 10;
    const FAVORITE_NO = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_room';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sender_id'], 'required'],
            [['user_id', 'sender_id', 'status', 'favorite'], 'integer'],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
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
            'sender_id' => Yii::t('message', 'Sender ID'),
            'status' => Yii::t('message', 'Status'),
            'favorite' => Yii::t('message', 'Favorite'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['message_room_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * check is current user is owner this company
     */
    public function isOwner()
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->id == $this->user_id;
    }
}
