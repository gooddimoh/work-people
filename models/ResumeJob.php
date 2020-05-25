<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_job".
 *
 * @property int $id
 * @property int $resume_id
 * @property int $category_job_id
 * @property string $company_name
 * @property int $month
 * @property int $years
 * @property string $date_start
 * @property int $for_now 10 - yes, 20 - no
 * @property int $foreign_job 10 - yes, 20 - no
 *
 * @property CategoryJob $categoryJob
 * @property Resume $resume
 */
class ResumeJob extends \yii\db\ActiveRecord
{
    const STATUS_FOR_NOW_YES = 10;
    const STATUS_FOR_NOW_NO = 20;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'category_job_id', 'company_name', 'years'], 'required'],
            [['resume_id', 'category_job_id', 'month', 'years', 'for_now', 'foreign_job'], 'integer'],
            [['date_start'], 'safe'],
            [['company_name'], 'string', 'max' => 255],
            [['category_job_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryJob::className(), 'targetAttribute' => ['category_job_id' => 'id']],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'resume_id' => Yii::t('user', 'Resume ID'),
            'category_job_id' => Yii::t('user', 'Category Job ID'),
            'company_name' => Yii::t('user', 'Company Name'),
            'month' => Yii::t('user', 'Month'),
            'years' => Yii::t('user', 'Years'),
            'date_start' => Yii::t('user', 'Date Start'),
            'for_now' => Yii::t('user', 'For Now'),
            'foreign_job' => Yii::t('user', 'Foreign Job'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryJob()
    {
        return $this->hasOne(CategoryJob::className(), ['id' => 'category_job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }
}
