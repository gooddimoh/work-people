<?php

namespace app\models;

use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;

/**
 * This is the model class for table "category_job".
 *
 * @property int $id
 * @property int $status 10 - default , 20 - hidden
 * @property string $name
 * @property string $description
 * @property string $background_color
 * @property string $image_path
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $meta_keywords
 * @property string $meta_description
 * @property int $status
 * @property int $show_on_main_page
 * @property int $main_page_order
 * @property int $created_at
 * @property int $updated_at
 */
class CategoryJob extends \yii\db\ActiveRecord
{
    public $file;

    const IMAGE_FOLDER_ALIAS = '@webroot/upload/category-job';
    const IMAGE_FOLDER_WEB = '/upload/category-job';

    const STATUS_SHOW = 10;
    const STATUS_HIDE = 20;

    const STATUS_MAIN_PAGE_SHOW = 10;
    const STATUS_MAIN_PAGE_HIDE = 20;

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');

        if (!empty($this->file)) {
            $saveDirectory = Yii::getAlias(self::IMAGE_FOLDER_ALIAS);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('name', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $this->image_path =  $this->file->baseName . '.' . $this->file->extension;
            $this->file->saveAs($saveDirectory.'/'. $this->image_path);

            // $data = file_get_contents($this->file);
            // $imagine = Imagine::getImagine();
            // $image = $imagine->load($data);
            // Imagine::thumbnail($image, 800, 600)
            //     ->save($saveDirectory.'/'.$this->name, ['quality' => 75]);
        }
    
        return parent::beforeValidate();
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                // 'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function() { return date('U'); }, // unix timestamp
            ],
        ];
    }
    

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['tree', 'lft', 'rgt', 'depth', 'status', 'show_on_main_page', 'main_page_order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'background_color', 'image_path'], 'string', 'max' => 255],
            [['file'], 'file'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('category', 'ID'),
            'status' => Yii::t('category', 'Status'),
            'name' => Yii::t('category', 'Name'),
            'description' => Yii::t('category', 'Description'),
            'background_color' => Yii::t('category', 'Background Color'),
            'image_path' => Yii::t('category', 'Image Path'),
            'tree' => Yii::t('category', 'Tree'),
            'lft' => Yii::t('category', 'Lft'),
            'rgt' => Yii::t('category', 'Rgt'),
            'depth' => Yii::t('category', 'Depth'),
            'meta_keywords' => Yii::t('category', 'Meta Keywords'),
            'meta_description' => Yii::t('category', 'Meta Description'),
            'show_on_main_page' => Yii::t('category', 'Show On Main Page'),
            'main_page_order' => Yii::t('category', 'Main Page Order'),
            'created_at' => Yii::t('category', 'Created At'),
            'updated_at' => Yii::t('category', 'Updated At'),
        ];
    }
    
    
    public function getParentId()
    {
        $parent = $this->parent;
        return $parent ? $parent->id : null;
    }

    public function getParent()
    {
        return $this->parents(1)->one();
    }

    public static function getTree($node_id = 0)
    {
        $children = [];
        if (!empty($node_id)) {
            $children = array_merge(self::findOne($node_id)->children()->column(), [$node_id]);
        }

        $rows = self::find()
            ->select(['name', 'id'])
            ->where(['NOT IN', 'id', $children])
            ->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC])
            ->all();
        $data = [];
        foreach ($rows as $row) {
            $data[$row->id] = $row->name;
        }
        return $data;
    }

    /**
     * get category child id's
     * important! Not recursive.
     */
    public static function getChildIds($node_id = 0)
    {
        return self::findOne($node_id)->children()->column();
    }

    public static function getMainPageList()
    {
        $rows = self::find()
            ->select(['name', 'id', 'image_path'])
            ->where(['=', 'show_on_main_page', self::STATUS_MAIN_PAGE_SHOW])
            // ->andWhere(['=', 'status', '1'])
            // ->andWhere(['=', 'depth', '1'])
            ->orderBy(['main_page_order' => SORT_ASC])
            ->all();
        
        return $rows;
    }

    /**
     * return total active resume for this category
     * @return int
     */
    public function getAdsCount() {
        //! BUG need use cache
        $child_ids = self::getChildIds($this->id);
        $query = ResumeCategoryJob::find()
                    ->where(['in', 'category_job_id', $child_ids]);

        return $query->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryResumes()
    {
        return $this->hasMany(Vacancy::className(), ['id' => 'resume_id'])
            ->viaTable('resume_category_job', ['category_job_id' => 'id']);
    }

    public function getImage() {
        if(empty($this->image_path)) {
            return '/img/cats/all.png';
        }

        return self::IMAGE_FOLDER_WEB . '/' . $this->image_path;
    }

    /**
     * @inheritdoc
     * @return CategoryJobQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryJobQuery(get_called_class());
    }

    /**
     * category `status` variants list
     */
    static public function getStatusList() {
        return array(
            self::STATUS_SHOW => Yii::t('main', 'Show'),
            self::STATUS_HIDE => Yii::t('main', 'Hide'),
        );
    }

    /**
     * build only two level list
     */
    static public function getCategoryTree() {
        $rows = self::find()
            ->where(['!=', 'id', 1]) // exclude root category
            ->andWhere(['=', 'status', self::STATUS_SHOW]) // exclude hidden
            ->orderBy(['tree' => SORT_ASC, 'lft' => SORT_ASC])
            ->all();
        
        // build tree
        $result = [];
        $last_parent_id = null;
        foreach($rows as $row) {
            if($row['depth'] == 1) { // parent
                $last_parent_id = $row['id'];
                $result[$last_parent_id] = [
                    'parent' => $row,
                    'childs' => []
                ];

                continue;
            }

            // childs
            $result[$last_parent_id]['childs'][$row->id] = $row;
        }
        return $result;
    }

    static public function getUserMultiSelectList() {
        $tree_list = self::getCategoryTree();
        $result = [];
        foreach ($tree_list as $parent_row) {
            // collect childs
            $jobs = [];
            foreach($parent_row['childs'] as $child_row) {
                $jobs[] = [
                    'id' => $child_row->id,
                    'name' => $child_row->name,
                    'parent_id' => $parent_row['parent']->id,
                ];
            }

            // collect parent
            $result[] = [
                'id' => $parent_row['parent']->id,
                'group_name' => $parent_row['parent']->name,
                'jobs' => $jobs,
            ];
        }
        
        return $result;
    }

    /**
     * category `show_on_main_page` list
     */
    static public function getShowOnMainPageList() {
        return array(
            self::STATUS_MAIN_PAGE_SHOW => Yii::t('main', 'Show'),
            self::STATUS_MAIN_PAGE_HIDE => Yii::t('main', 'Hide')
        );
    }

}
