<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "safe_deal_user".
 *
 * @property int $id
 * @property int $safe_deal_id
 * @property int $user_id
 * @property int $archive 10 - default, 20 - archive
 *
 * @property SafeDeal $safeDeal
 * @property User $user
 */
class SafeDealUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'safe_deal_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['safe_deal_id', 'user_id'], 'required'],
            [['safe_deal_id', 'user_id', 'archive'], 'integer'],
            [['safe_deal_id', 'user_id'], 'unique', 'targetAttribute' => ['safe_deal_id', 'user_id']],
            [['safe_deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => SafeDeal::className(), 'targetAttribute' => ['safe_deal_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('deal', 'ID'),
            'safe_deal_id' => Yii::t('deal', 'Safe Deal ID'),
            'user_id' => Yii::t('deal', 'User ID'),
            'archive' => Yii::t('deal', 'Archive'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSafeDeal()
    {
        return $this->hasOne(SafeDeal::className(), ['id' => 'safe_deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
