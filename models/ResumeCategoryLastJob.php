<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_category_last_job".
 *
 * @property int $id
 * @property int $resume_id
 * @property int $category_last_job_id
 *
 * @property CategoryJob $categoryLastJob
 * @property Resume $resume
 */
class ResumeCategoryLastJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_category_last_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'category_last_job_id'], 'required'],
            [['resume_id', 'category_last_job_id'], 'integer'],
            [['category_last_job_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryJob::className(), 'targetAttribute' => ['category_last_job_id' => 'id']],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'resume_id' => 'Resume ID',
            'category_last_job_id' => 'Category Last Job ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryLastJob()
    {
        return $this->hasOne(CategoryJob::className(), ['id' => 'category_last_job_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }
}
