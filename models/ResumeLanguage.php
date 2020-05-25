<?php

namespace app\models;

use Yii;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "resume_language".
 *
 * @property int $id
 * @property int $resume_id
 * @property string $country_code RU - RUSSIAN, CN - CHINA.. etc
 * @property int $level 10 - newbie, 20 - middle, 30 - hight
 * @property int $can_be_interviewed 10 - yes, 20 - no
 *
 * @property Resume $resume
 */
class ResumeLanguage extends \yii\db\ActiveRecord
{
    const CAN_BE_IN_INTERVIEWED_YES = 10;
    const CAN_BE_IN_INTERVIEWED_NO = 20;

    const LEVEL_NEWBIE = 10;
    const LEVEL_MIDDLE = 20;
    const LEVEL_HIGHT  = 30;
    const LEVEL_EXPERT = 40;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resume_language';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['resume_id', 'country_code'], 'required'],
            [['resume_id', 'level', 'can_be_interviewed'], 'integer'],
            [['country_code'], 'string', 'max' => 255],
            [['resume_id'], 'exist', 'skipOnError' => true, 'targetClass' => Resume::className(), 'targetAttribute' => ['resume_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'resume_id' => Yii::t('user', 'Resume ID'),
            'country_code' => Yii::t('user', 'Language'),
            'level' => Yii::t('user', 'Proficiency level'),
            'can_be_interviewed' => Yii::t('user', 'Can Be Interviewed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResume()
    {
        return $this->hasOne(Resume::className(), ['id' => 'resume_id']);
    }

    public static function getLanguageList()
    {
        return ReferenceHelper::getLanguageList();
    }

    public function getLanguageName()
    {
        $lang_list = self::getLanguageList();
        foreach ($lang_list as $lang) {
            if($lang['char_code'] == $this->country_code) {
                return $lang['name'];
            }
        }

        return null;
    }

    public function getLanguageLevelName()
    {
        $level_list = self::getLevelList();
        return $level_list[$this->level];
    }

    public static function getLevelList()
    {
        return [
            self::LEVEL_NEWBIE => Yii::t('user', 'Newbie'), // 'Начальный',
            self::LEVEL_MIDDLE => Yii::t('user', 'Middle'), // 'Средний',
            self::LEVEL_HIGHT  => Yii::t('user', 'Hight'), //  'Выше среднего',
            self::LEVEL_EXPERT => Yii::t('user', 'Expert'), // 'В совершенстве',
        ];
    }
}
