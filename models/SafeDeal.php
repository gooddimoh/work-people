<?php

namespace app\models;

use Yii;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "safe_deal".
 *
 * @property int $id
 * @property int $creator_id user-initiator of safe deal
 * @property int $status 10 - new, 20 - paid, 30 - complete, 40 - rejected, 50 - arbitration
 * @property int $deal_type 10 - vacancy deal, 20 - agent deal
 * @property int $has_prepaid 10 - yes, 20 - no
 * @property string $title
 * @property double $amount_total total deal amount, amount locked by initiator
 * @property double $amount_total_src amount in source currency, need for sorting
 * @property string $amount_currency_code
 * @property double $amount_prepaid guarantee deal prepaid amount
 * @property string $amount_prepaid_currency_code
 * @property string $condition_prepaid
 * @property string $condition_deal
 * @property int $execution_period days, weeks, month before complete deal
 * @property int $execution_range mesure of execution_period: 10 - days, 20 - weeks,  30 - month;
 * @property int $possible_delay_days 10 - 1-10 days; 20 - 10-30 days; 30 - 30-90 days
 * @property int $comission 10 - type1, 20 - type2, 30 - type3
 * @property string $started_at
 * @property string $finished_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $creator
 * @property SafeDealUser[] $safeDealUsers
 */
class SafeDeal extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;
    
    const HAS_PREPAID_YES = 10;
    const HAS_PREPAID_NO = 20;

    const EXECUTION_RANGE_DAYS = 10;
    const EXECUTION_RANGE_WEEKS = 20;
    const EXECUTION_RANGE_MONTHS = 30;

    const POSSIBLE_DELAY_DAYS_1_10 = 10;
    const POSSIBLE_DELAY_DAYS_10_30 = 20;
    const POSSIBLE_DELAY_DAYS_30_90 = 30;

    const COMISSION_TYPE_1 = 10;
    const COMISSION_TYPE_2 = 20;
    const COMISSION_TYPE_3 = 30;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'safe_deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['creator_id', 'title', 'amount_total', 'amount_total_src', 'amount_currency_code', 'condition_deal', 'execution_period', 'execution_range'], 'required'],
            [['creator_id', 'status', 'deal_type', 'has_prepaid', 'execution_period', 'execution_range', 'possible_delay_days', 'comission'], 'integer'],
            [['amount_total', 'amount_total_src', 'amount_prepaid'], 'number'],
            [['condition_prepaid', 'condition_deal'], 'string'],
            [['started_at', 'finished_at', 'created_at', 'updated_at'], 'safe'],
            [['title', 'amount_currency_code', 'amount_prepaid_currency_code'], 'string', 'max' => 255],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('Y-m-d h:i:s'); },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('deal', 'ID'),
            'creator_id' => Yii::t('deal', 'Creator ID'),
            'status' => Yii::t('deal', 'Status'),
            'deal_type' => Yii::t('deal', 'Deal Type'),
            'has_prepaid' => Yii::t('deal', 'Has Prepaid'),
            'title' => Yii::t('deal', 'Title'),
            'amount_total' => Yii::t('deal', 'Amount Total'),
            'amount_total_src' => Yii::t('deal', 'Amount Total Src'),
            'amount_currency_code' => Yii::t('deal', 'Amount Currency Code'),
            'amount_prepaid' => Yii::t('deal', 'Amount Prepaid'),
            'amount_prepaid_currency_code' => Yii::t('deal', 'Amount Prepaid Currency Code'),
            'condition_prepaid' => Yii::t('deal', 'Condition Prepaid'),
            'condition_deal' => Yii::t('deal', 'Condition Deal'),
            'execution_period' => Yii::t('deal', 'Execution Period'),
            'execution_range' => Yii::t('deal', 'Execution Range'),
            'possible_delay_days' => Yii::t('deal', 'Possible Delay Days'),
            'comission' => Yii::t('deal', 'Comission'),
            'started_at' => Yii::t('deal', 'Started At'),
            'finished_at' => Yii::t('deal', 'Finished At'),
            'created_at' => Yii::t('deal', 'Created At'),
            'updated_at' => Yii::t('deal', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedSafeDealUser()
    {
        return $this->hasOne(SafeDealUser::className(), ['safe_deal_id' => 'id'])
                    ->where(['!=', 'safe_deal_id', Yii::$app->user->id]);
    }

    public function hasAccess()
    {
        return SafeDealUser::find()->where([
            'safe_deal_id' =>  $this->id,
            'user_id' => Yii::$app->user->id,
        ])->exists();
    }

    public function isCreator()
    {
        return $this->creator_id === Yii::$app->user->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSafeDealUsers()
    {
        return $this->hasMany(SafeDealUser::className(), ['safe_deal_id' => 'id']);
    }

    public static function getCurrencyList()
    {
        return ReferenceHelper::getCurrencyList();
    }

    public static function getHasPrepaidList()
    {
        return [
            self::HAS_PREPAID_YES => 'Есть предоплата',
            self::HAS_PREPAID_NO => 'Нет предоплаты',
        ];
    }

    public static function getExecutionRangeList()
    {
        return [
            self::EXECUTION_RANGE_DAYS => Yii::t('deal', 'Days'),
            self::EXECUTION_RANGE_WEEKS => Yii::t('deal', 'Weeks'),
            self::EXECUTION_RANGE_MONTHS => Yii::t('deal', 'Months'),
        ];
    }
}
