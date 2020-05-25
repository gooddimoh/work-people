<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_category_job".
 *
 * @property int $id
 * @property int $resume_id
 * @property int $category_job_id
 *
 * @property CategoryJob $categoryJob
 * @property Resume $resume
 */
class ResumeCategoryJob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_category_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'category_job_id'], 'required'],
            [['resume_id', 'category_job_id'], 'integer'],
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
            'id' => 'ID',
            'resume_id' => 'Resume ID',
            'category_job_id' => 'Category Job ID',
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
