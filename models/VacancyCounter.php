<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacancy_counter".
 *
 * @property int $id
 * @property int $vacancy_id
 * @property int $view_count
 * @property int $open_count
 * @property int $vip_count
 * @property int $top_count
 * @property int $main_page_count
 * @property int $favorite_count
 *
 * @property Vacancy $vacancy
 */
class VacancyCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vacancy_id', 'view_count', 'open_count', 'vip_count', 'top_count', 'main_page_count', 'favorite_count'], 'integer'],
            [['vacancy_id'], 'unique'],
            [['vacancy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacancy::className(), 'targetAttribute' => ['vacancy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vacancy', 'ID'),
            'vacancy_id' => Yii::t('vacancy', 'Vacancy ID'),
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
    public function getVacancy()
    {
        return $this->hasOne(Vacancy::className(), ['id' => 'vacancy_id']);
    }
}
