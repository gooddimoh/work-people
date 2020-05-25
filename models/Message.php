<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $message_room_id
 * @property int $owner_id message creator ID
 * @property int $for_user_id field need for optimization join request to count new messages
 * @property int $status 10 - readed, 20 - unreaded
 * @property int $device_type 10 - default, 20 - send from mobile
 * @property string $message_text
 * @property int $created_at
 *
 * @property MessageRoom $messageRoom
 * @property User $owner
 * @property MessageUserAttachment[] $messageUserAttachments
 */
class Message extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    
    const STATUS_READED = 10;
    const STATUS_UNREADED = 20;

    const DEVICE_TYPE_DEFAULT = 10;
    const DEVICE_TYPE_MOBILE = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_room_id', 'owner_id', 'for_user_id', 'message_text'], 'required'],
            [['message_room_id', 'owner_id', 'for_user_id', 'status', 'device_type', 'created_at'], 'integer'],
            [['message_text'], 'string'],
            [['message_room_id'], 'exist', 'skipOnError' => true, 'targetClass' => MessageRoom::className(), 'targetAttribute' => ['message_room_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('message', 'ID'),
            'message_room_id' => Yii::t('message', 'Message Room ID'),
            'owner_id' => Yii::t('message', 'Owner ID'),
            'for_user_id' => Yii::t('message', 'For User ID'),
            'status' => Yii::t('message', 'Status'),
            'device_type' => Yii::t('message', 'Device Type'),
            'message_text' => Yii::t('message', 'Message Text'),
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
    public function getMessageRoom()
    {
        return $this->hasOne(MessageRoom::className(), ['id' => 'message_room_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForUser()
    {
        return $this->hasOne(User::className(), ['id' => 'for_user_id']);
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getMessageUserAttachments() 
    { 
        return $this->hasMany(MessageUserAttachment::className(), ['message_id' => 'id']); 
    } 

    public static function getStatusList()
    {
        return [
            self::STATUS_READED => 'Прочитано',
            self::STATUS_UNREADED => 'Не прочитано',
        ];
    }

    public static function getDeviceTypeList()
    {
        return [
            self::DEVICE_TYPE_DEFAULT => 'Desktop',
            self::DEVICE_TYPE_MOBILE => 'Mobile',
        ];
    }
}
