<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif".
 *
 * @property int $id
 * @property int $tarif_type 10 - tarif number 1, 20 - number 2, 30 - number 3
 * @property int $publication_count
 * @property int $top_days
 * @property int $upvote_count
 * @property int $upvote_period
 * @property int $vip_count
 * @property int $vip_period
 * @property int $price
 * @property int $discount_size
 *
 * @property TarifUser[] $tarifUsers
 */
class Tarif extends \yii\db\ActiveRecord
{
    const TARIF_TYPE_STANDARD = 10;
    const TARIF_TYPE_EXTENDED = 20;
    const TARIF_TYPE_VIP = 30;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarif_type', 'publication_count', 'top_days', 'upvote_count', 'upvote_period', 'vip_count', 'vip_period', 'price', 'discount_size'], 'integer'],
            [['publication_count', 'top_days', 'upvote_count', 'upvote_period', 'vip_count', 'vip_period', 'price', 'discount_size'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tarif_type' => 'Tarif Type',
            'publication_count' => 'Publication Count',
            'top_days' => 'Top Days',
            'upvote_count' => 'Upvote Count',
            'upvote_period' => 'Upvote Period',
            'vip_count' => 'Vip Count',
            'vip_period' => 'Vip Period',
            'price' => 'Price',
            'discount_size' => 'Discount Size',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifUsers()
    {
        return $this->hasMany(TarifUser::className(), ['tarif_id' => 'id']);
    }

    public function getPaidPrice($decimals = 2)
    {
        if($this->discount_size == 0)
            return $this->price;
        
        $result = $this->price - (($this->price / 100) * $this->discount_size);
        return number_format($result, $decimals, '.', '');
    }

    public static function getTarifTypeList()
    {
        return [
            self::TARIF_TYPE_STANDARD => 'Tarif number 1',
            self::TARIF_TYPE_EXTENDED => 'Tarif number 2',
            self::TARIF_TYPE_VIP => 'Tarif number 3',
        ];
    }

    public static function getTarifTypeDescriptionList()
    {
        return [
            self::TARIF_TYPE_STANDARD => [
                'sub_title' => 'suitable for small sets',
            ],
            self::TARIF_TYPE_EXTENDED => [
                'sub_title' =>  '13 x more views',
            ],
            self::TARIF_TYPE_VIP => [
                'sub_title' =>  '30 x more views',
            ],
        ];
    }
}
