<?php

namespace app\models;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\imagine\Image as Imagine;

/**
 * This is the model class for table "vacancy_image".
 *
 * @property int $id
 * @property int $vacancy_id
 * @property string $name
 * @property string $path_name
 *
 * @property Vacancy $vacancy
 */
class VacancyImage extends \yii\db\ActiveRecord
{
    public $src;
    public $photo_tmp_path; // parser variable

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vacancy_id', 'name', 'path_name'], 'required'],
            [['vacancy_id'], 'integer'],
            [['src', 'photo_tmp_path'], 'safe'],
            [['name', 'path_name'], 'string', 'max' => 255],
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
            'name' => Yii::t('vacancy', 'Name'),
            'path_name' => Yii::t('vacancy', 'Path Name'),
        ];
    }

    public function beforeValidate() {
        // or need check file exists?
        $this->path_name = basename($this->path_name); // filter file name

        if(empty($this->path_name)) {
            $this->path_name = $this->name; // tmp to skip validation
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->photo_tmp_path)) {
            // move tmp file to web dir
            // $saveDirectoryTmp = Yii::getAlias(Company::TMP_IMAGE_DIR_ALIAS);
            $saveDirectory = Yii::getAlias('@webroot/upload/vacancy/' . $this->vacancy_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('path_name', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $tmp = explode('.', $this->photo_tmp_path);
            $extension = array_pop($tmp);

            $this->path_name = substr(md5($this->name), 0, 10) . '_' . $this->vacancy_id . '_'
                    . date('Y-m-d')
                    . '.' . $extension;
            
            copy($this->photo_tmp_path, $saveDirectory . DIRECTORY_SEPARATOR . $this->path_name);
        }

        if (!empty($this->src)) {
            $saveDirectory = Yii::getAlias('@webroot/upload/vacancy/' . $this->vacancy_id);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('path_name', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            if (strpos($this->src, 'data:image') !== 0) { //? is need check unsafe path for file_get_contents
                $this->addError('path_name', 'Не валидные данные Base64 изображения.');
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

            $this->path_name = substr(md5($this->name), 0, 10) . '_' . $this->vacancy_id . '_' 
                    . date('Y-m-d') 
                    . '.' . $extension; // $this->file_data->extension

            $imagine = Imagine::getImagine();
            $image = $imagine->load($data);
            Imagine::resize($image, 800, 600)
                ->save($saveDirectory . DIRECTORY_SEPARATOR . $this->path_name, ['quality' => 75]);
            
            $this->src = null;
        }

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacancy()
    {
        return $this->hasOne(Vacancy::className(), ['id' => 'vacancy_id']);
    }

    public function getImageWebPath() {
        return '/upload/vacancy/' . $this->vacancy_id . '/' . $this->path_name;
    }
}
