<?php

namespace app\models;

use Yii;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "country_city".
 *
 * @property int $id
 * @property int $priority sort by priority, higher top
 * @property string $country_char_code Country char code like: UA
 * @property string $city_name City name in english
 */
class CountryCity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priority'], 'integer'],
            [['country_char_code', 'city_name'], 'required'],
            [['country_char_code', 'city_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'priority' => 'Приоритет сортировки',
            'country_char_code' => 'Код страны',
            'city_name' => 'Название горда (EN)',
        ];
    }

    public static function getCountryList()
    {
        return ReferenceHelper::getCountryList();
    }
}
