<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_review".
 *
 * @property int $id
 * @property int $status 10 - show; 20 - hide
 * @property int $company_id
 * @property string $position
 * @property int $worker_status 10 - working; 20 - fired
 * @property string $department
 * @property string $date_end
 * @property int $rating_total
 * @property int $rating_salary
 * @property int $rating_opportunities
 * @property int $rating_bosses
 * @property string $general_impression
 * @property string $pluses_impression
 * @property string $minuses_impression
 * @property string $tips_for_bosses
 * @property int $worker_recommendation 10 - recomended; 20 - not recomended
 *
 * @property Company $company
 */
class CompanyReview extends \yii\db\ActiveRecord
{
    const STATUS_SHOW = 10;
    const STATUS_HIDE = 20;

    const WORKER_STATUS_WORKING = 10;
    const WORKER_STATUS_FIRED = 20;

    const WORKER_RECOMMENDATION_YES = 10;
    const WORKER_RECOMMENDATION_NO = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'company_id', 'worker_status', 'rating_total', 'rating_salary', 'rating_opportunities', 'rating_bosses', 'worker_recommendation'], 'integer'],
            [['company_id', 'position', 'rating_total', 'rating_salary', 'rating_opportunities', 'rating_bosses', 'general_impression', 'pluses_impression', 'minuses_impression', 'tips_for_bosses'], 'required'],
            [['date_end'], 'safe'],
            [['position', 'department', 'general_impression', 'pluses_impression', 'minuses_impression', 'tips_for_bosses'], 'string', 'max' => 255],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('company-review', 'ID'),
            'status' => Yii::t('company-review', 'Status'),
            'company_id' => Yii::t('company-review', 'Company ID'),
            'position' => Yii::t('company-review', 'Position'),
            'worker_status' => Yii::t('company-review', 'Worker Status'),
            'department' => Yii::t('company-review', 'Department'),
            'date_end' => Yii::t('company-review', 'Date End'),
            'rating_total' => Yii::t('company-review', 'Rating Total'),
            'rating_salary' => Yii::t('company-review', 'Rating Salary'),
            'rating_opportunities' => Yii::t('company-review', 'Rating Opportunities'),
            'rating_bosses' => Yii::t('company-review', 'Rating Bosses'),
            'general_impression' => Yii::t('company-review', 'General Impression'),
            'pluses_impression' => Yii::t('company-review', 'Pluses Impression'),
            'minuses_impression' => Yii::t('company-review', 'Minuses Impression'),
            'tips_for_bosses' => Yii::t('company-review', 'Tips For Bosses'),
            'worker_recommendation' => Yii::t('company-review', 'Worker Recommendation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_SHOW => 'Показать',
            self::STATUS_HIDE => 'Скрыть',
        ];
    }

    public static function getWorkerStatusList()
    {
        return [
            self::WORKER_STATUS_WORKING => 'Работает',
            self::WORKER_STATUS_FIRED => 'Не работает',
        ];
    }

    public static function getWorkerRecommendationList()
    {
        return [
            self::WORKER_RECOMMENDATION_YES => 'Рекомендует',
            self::WORKER_RECOMMENDATION_NO => 'Не рекомендует',
        ];
    }
}
