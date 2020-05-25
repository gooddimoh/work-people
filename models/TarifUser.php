<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_user".
 *
 * @property int $id
 * @property int $user_id
 * @property int $tarif_id
 * @property int $publication_count
 * @property int $upvote_count
 * @property int $vip_count
 * @property int $created_at
 *
 * @property Tarif $tarif
 * @property User $user
 */
class TarifUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarif_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tarif_id', 'publication_count'], 'required'],
            [['user_id', 'tarif_id', 'publication_count', 'upvote_count', 'vip_count', 'created_at'], 'integer'],
            [['tarif_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tarif::className(), 'targetAttribute' => ['tarif_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'tarif_id' => 'Tarif ID',
            'publication_count' => 'Publication Count',
            'upvote_count' => 'Upvote Count',
            'vip_count' => 'Vip Count',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarif()
    {
        return $this->hasOne(Tarif::className(), ['id' => 'tarif_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
