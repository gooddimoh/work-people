<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message_user_attachment".
 *
 * @property int $id
 * @property int $message_id
 * @property int $user_attachment_id
 *
 * @property Message $message
 * @property UserAttachment $userAttachment
 */
class MessageUserAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message_user_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'user_attachment_id'], 'required'],
            [['message_id', 'user_attachment_id'], 'integer'],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Message::className(), 'targetAttribute' => ['message_id' => 'id']],
            [['user_attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAttachment::className(), 'targetAttribute' => ['user_attachment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('attachment', 'ID'),
            'message_id' => Yii::t('attachment', 'Message ID'),
            'user_attachment_id' => Yii::t('attachment', 'User Attachment ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(Message::className(), ['id' => 'message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttachment()
    {
        return $this->hasOne(UserAttachment::className(), ['id' => 'user_attachment_id']);
    }
}
