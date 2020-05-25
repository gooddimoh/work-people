<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "resume_counter".
 *
 * @property int $id
 * @property int $resume_id
 * @property int $view_count
 * @property int $open_count
 * @property int $vip_count
 * @property int $top_count
 * @property int $main_page_count
 * @property int $favorite_count
 *
 * @property Resume $resume
 */
class ResumeCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'view_count'], 'required'],
            [['resume_id', 'view_count', 'open_count', 'vip_count', 'top_count', 'main_page_count', 'favorite_count'], 'integer'],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vacancy', 'ID'),
            'resume_id' => Yii::t('vacancy', 'Resume ID'),
            'view_count' => Yii::t('vacancy', 'View Count'),
            'open_count' => Yii::t('vacancy', 'Open Count'),
            'vip_count' => Yii::t('vacancy', 'Vip Count'),
            'top_count' => Yii::t('vacancy', 'Top Count'),
            'main_page_count' => Yii::t('vacancy', 'Main Page Count'),
            'favorite_count' => Yii::t('vacancy', 'Favorite Count'),
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
