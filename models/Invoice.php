<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 - waiting, 20 - paid, 30 - rejected, 40 - unpaid(error), 50 - expired
 * @property int $pay_system
 * @property string $price
 * @property string $price_source
 * @property string $currency_code
 * @property string $phone
 * @property string $pay_system_response
 * @property int $pay_system_i
 * @property int $pay_date
 * @property int $created_at
 *
 * @property User $user
 */
class Invoice extends \yii\db\ActiveRecord
{
    const STATUS_WAITING    = 10;
    const STATUS_PAYED      = 20;
    const STATUS_REJECTED   = 30;

    const PAYMENT_QIWI   = 10;
    const PAYMENT_PAYEER = 20;
    const PAYMENT_MY = 30;
    const PAYMENT_FREE_KASSA = 40;
    const PAYMENT_PLATON_ONLINE = 50;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pay_system', 'price', 'price_source', 'currency_code'], 'required'],
            [['user_id', 'status', 'pay_system', 'pay_system_i', 'pay_date', 'created_at'], 'integer'],
            [['price', 'price_source'], 'number'],
            [['currency_code', 'phone', 'pay_system_response'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('invoice', 'ID'),
            'user_id' => Yii::t('invoice', 'User ID'),
            'status' => Yii::t('invoice', 'Status'),
            'pay_system' => Yii::t('invoice', 'Pay System'),
            'price' => Yii::t('invoice', 'Price'),
            'price_source' => Yii::t('invoice', 'Price Source'),
            'currency_code' => Yii::t('invoice', 'Currency Code'),
            'phone' => Yii::t('invoice', 'Phone'),
            'pay_system_response' => Yii::t('invoice', 'Pay System Response'),
            'pay_system_i' => Yii::t('invoice', 'Pay System I'),
            'pay_date' => Yii::t('invoice', 'Pay Date'),
            'created_at' => Yii::t('invoice', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
