<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

/**
 * This is the model class for table "user_attachment".
 *
 * @property int $id
 * @property int $status
 * @property int $user_id
 * @property string $name source file name
 * @property string $path_name file name in file system
 * @property double $size file size, to controll user quotas
 * @property int $created_at
 *
 * @property User $user
 */
class UserAttachment extends \yii\db\ActiveRecord
{
    public $fileData;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'user_id', 'created_at'], 'integer'],
            [['user_id', 'name', 'path_name'], 'required'],
            [['size'], 'number'],
            [['fileData'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, jpeg, png, doc, docx, pdf, gif, zip, rar', 'checkExtensionByMimeType' => false],
            [['name', 'path_name'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('attachment', 'ID'),
            'status' => Yii::t('attachment', 'Status'),
            'user_id' => Yii::t('attachment', 'User ID'),
            'name' => Yii::t('attachment', 'Name'),
            'path_name' => Yii::t('attachment', 'Path Name'),
            'size' => Yii::t('attachment', 'Size'),
            'created_at' => Yii::t('attachment', 'Created At'),
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

    public function upload()
    {
        
        if ($this->validate()) {
            
        } else {
            return false;
        }
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $saveDirectory = Yii::getAlias('@webroot/upload/user_attachments/' . $this->user_id);

        if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
            $this->addError('path_name', 'Не могу создать каталог на сервере: ' . $saveDirectory);
            return false;
        }

        $this->fileData->saveAs($saveDirectory . DIRECTORY_SEPARATOR . $this->path_name);
        // $this->fileData = null; // clean
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getFilePath()
    {
        return Yii::getAlias('@webroot/upload/user_attachments/' . $this->user_id) . DIRECTORY_SEPARATOR . $this->path_name;
    }
}
