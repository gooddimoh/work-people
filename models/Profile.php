<?php

namespace app\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;
use app\components\ReferenceHelper;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status 10 - visible, 20 - hidden
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $gender_list 10; - male, 20; - female;
 * @property string $birth_day
 * @property string $country_name
 * @property int $country_city_id
 * @property string $photo_path
 * @property string $phone additional phones siparated by;
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    const STATUS_VISIBLE = 10;
    const STATUS_HIDDEN  = 20;

    const GENDER_MALE   = 10;
    const GENDER_FEMALE = 20;
    const GENDER_PAIR   = 30;

    public $src;
    public $photo_tmp_path; // parser variable

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'first_name', 'last_name', 'email', 'gender_list', 'birth_day', 'country_name'], 'required'],
            [['user_id', 'status', 'country_city_id'], 'integer'],
            [['birth_day'], 'safe'],
            [['src', 'photo_tmp_path'], 'safe'],
            [['first_name', 'last_name', 'middle_name', 'email', 'gender_list', 'country_name', 'photo_path', 'phone'], 'string', 'max' => 255],
            [['country_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryCity::className(), 'targetAttribute' => ['country_city_id' => 'id']], 
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('profile', 'ID'),
            'user_id' => Yii::t('profile', 'User ID'),
            'status' => Yii::t('profile', 'Status'),
            'first_name' => Yii::t('profile', 'First Name'),
            'last_name' => Yii::t('profile', 'Last Name'),
            'middle_name' => Yii::t('profile', 'Middle Name'),
            'email' => Yii::t('profile', 'Email'),
            'gender_list' => Yii::t('profile', 'Gender List'),
            'birth_day' => Yii::t('profile', 'Birth Day'),
            'country_name' => Yii::t('profile', 'Country Name'),
            'country_city_id' => Yii::t('profile', 'Country City ID'),
            'photo_path' => Yii::t('profile', 'Photo Path'),
            'phone' => Yii::t('profile', 'Phone'),
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->photo_tmp_path)) {
            // move tmp file to web dir
            // $saveDirectoryTmp = Yii::getAlias(Company::TMP_IMAGE_DIR_ALIAS);
            $saveDirectory = Yii::getAlias('@webroot/upload/users/' . $this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('photo_path', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $tmp = explode('.', $this->photo_tmp_path);
            $extension = array_pop($tmp);

            $this->photo_path = substr(md5($this->src), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension
            
            copy($this->photo_tmp_path, $saveDirectory . '/' . $this->photo_path);
        }

        if (!empty($this->src)) {
            $saveDirectory = Yii::getAlias('@webroot/upload/users/'.$this->user_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('photo_path', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $data = file_get_contents($this->src);
            $pos  = strpos($this->src, ';');
            $type = explode(':', substr($this->src, 0, $pos))[1];
            
            $extension = 'jpg';
            switch($type) {
                case 'image/png': $extension = 'png'; break;
                // case 'image/jpeg': $extension = 'jpg'; break;
                default: $extension = 'jpg';
            }

            $this->photo_path = substr(md5($this->src), 0, 10) . '_' . $this->user_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension

            $imagine = Imagine::getImagine();
            $image = $imagine->load($data);
            Imagine::resize($image, 800, 600)
                ->save($saveDirectory.'/'.$this->photo_path, ['quality' => 75]);
        }

        return true;
    }

    public function getImageWebPath() {
        if(!empty($this->photo_path)) {
            return '/upload/users/' . $this->user_id . '/' . $this->photo_path;
        }
        return null;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        // photo_path also stored in users table, then upgrade it
        $this->user->photo_path = $this->photo_path;
        $this->user->save(false); // skip validation
    }

    public function getFullName()
    {
        $full_name_arr = [$this->first_name];
        
        if (!empty($this->middle_name)) {
            $full_name_arr[] = $this->middle_name;
        }

        if (!empty($this->last_name)) {
            $full_name_arr[] = $this->last_name;
        }

        return implode(' ', $full_name_arr);
    }

    public function getAge()
    {
        $from = new \DateTime($this->birth_day);
        $to   = new \DateTime('today');
        return $from->diff($to)->y;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCity()
    {
        return $this->hasOne(CountryCity::className(), ['id' => 'country_city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getGenders()
    {
        $gender_list = self::getGenderList();
        $gender_arr = explode(';', $this->gender_list);
        array_pop($gender_arr); // remove last empty
        
        $selected_gender_list = [];
        foreach($gender_list as $key_id => $gender) {
            if(in_array($key_id, $gender_arr)) {
                $selected_gender_list[$key_id] = $gender;
            }
        }

        return $selected_gender_list;
    }

    public static function getGenderList()
    {
        return [
            self::GENDER_MALE   => Yii::t('vacancy', 'Male'),
            self::GENDER_FEMALE => Yii::t('vacancy', 'Female'),
            self::GENDER_PAIR   => Yii::t('vacancy', 'Pair'),
        ];
    }

    public static function getCountryList()
    {
        return ReferenceHelper::getCountryList();
    }
}
