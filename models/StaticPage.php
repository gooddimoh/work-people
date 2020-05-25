<?php

namespace app\models;
use yii\helpers\BaseFileHelper;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class StaticPage extends Model
{
    const STATIC_PAGES_PATH = '@app/views/site';

    public $title;
    public $file_name;
    public $body;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['title', 'file_name', 'body'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'file_name' => 'Название файла',
            'body' => 'Текст',
        ];
    }

    /**
     * get all static pages files
     */
    public static function findAll()
    {
        $files_list = BaseFileHelper::findFiles(Yii::getAlias(self::STATIC_PAGES_PATH));

        $exclude_files = [
            'contact_from.php',
            'error.php',
            'index.php',
            'index_employer.php',
            'login.php',
            'subscribe.php',
            '_list_hot.php',
            'seo_index.php',
        ];

        $models = [];
        foreach($files_list as $file) {
            if(in_array(basename($file), $exclude_files)) {
                continue;
            }

            // cut php text
            $file_data = file_get_contents($file);
            $file_body = substr($file_data, strpos($file_data, '?>')+2 );
            $title_start = strpos($file_data, '{title}')+7;
            $title_end = strpos($file_data, '{/title}');
            $page_title = substr($file_data, $title_start, $title_end - $title_start);

            $model = new StaticPage();
            $model->title = $page_title;
            $model->file_name = basename($file);
            $model->body = $file_body;

            $models[] = $model;
        }

        return $models;
    }

    /**
     * get static page file content
     */
    public static function findOne($file_name)
    {
        $file_path = Yii::getAlias(self::STATIC_PAGES_PATH . '/' . $file_name);
        
        $file_data = file_get_contents($file_path);
        if($file_data !== false) {

            // cut php text
            $file_body = substr($file_data, strpos($file_data, '?>')+2 );
            $title_start = strpos($file_data, '{title}')+7;
            $title_end = strpos($file_data, '{/title}');
            $page_title = substr($file_data, $title_start, $title_end - $title_start);

            $model = new StaticPage();
            $model->title = $page_title;
            $model->file_name = basename($file_name);
            $model->body = $file_body;

            return $model;
        }

        return null;
    }

    /**
     * Save text to static page
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function save()
    {
        if ($this->validate()) {
            // write html to data
            $file_path = Yii::getAlias(self::STATIC_PAGES_PATH . '/' . $this->file_name);
            $file_data = '';
            if($this->file_name == 'home_greeting.php') { // remove title
                $file_data .= '<?php
/* {title}'.$this->title.'{/title} */
?>';
            } else {
                $file_data .= '<?php
/* {title}'.$this->title.'{/title} */
$this->title = \''.$this->title.'\';
$this->params[\'breadcrumbs\'][] = $this->title;
?>';
            }
            
            $file_data .= "\r\n".$this->body;
            if(file_put_contents($file_path, $file_data) !== false) {
                return true;
            }
        }
        return false;
    }

    public function delete()
    {
        $file_path = Yii::getAlias(self::STATIC_PAGES_PATH . '/' . $this->file_name);
        BaseFileHelper::unlink($file_path);
    }
}
