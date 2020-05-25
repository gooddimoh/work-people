<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_education".
 *
 * @property int $id
 * @property int $resume_id
 * @property string $description
 *
 * @property Resume $resume
 */
class ResumeEducation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_education';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'description'], 'required'],
            [['resume_id'], 'integer'],
            [['description'], 'string'],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('resume', 'ID'),
            'resume_id' => Yii::t('resume', 'Resume ID'),
            'description' => Yii::t('resume', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }
}
