<?php
namespace app\components;

use Yii;
use yii\base\Widget;
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;

class MenuWidget extends Widget
{

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $cache = Yii::$app->cache;
        $tree = $cache->getOrSet('MenuWidget', function () {
            return $this->makeTree();
        });
        
        $html = '<div id="mm" class="panel panel-default"><div class="panel-heading">
                    <h4 class="panel-title">
                    <a data-toggle="collapse" href="#collapseCategory" class="collapseWill active">Темы</a>
                    </h4>
                </div><ul class="panel-body panel-collapse collapse in" id="collapseCategory">';
        $html .= $this->makeMegaMenu($tree['children']);
        $html .= '</div></ul>';
        return $html;
    }

    public function makeTree($category = [], $max_depth = 0, $current_depth = 0)
    {
        if (!$category) {
            $category = current(Category::find()->roots()->all());
        }

        $node = [
            "id" => $category->id,
            "text" => $category->name,
            "depth" => $category->depth,
            "meta_keywords" => $category->meta_keywords,
            "meta_description" => $category->meta_description,
            "type" => "category", "children" => []
        ];

        if ($current_depth <= $max_depth) {
            if ($max_depth !== 0) {
                $current_depth++;
            }

            $childrens = $category->children(1)->all();

            if ($childrens) {
                foreach ($childrens as $key_children => $children) {
                    $node["children"][] = $this->makeTree($children, $max_depth, $current_depth);
                }
            }
        }

        return $node;
    }

    public function makeTreeHtml($categories)
    {
        $html = '';
        foreach ($categories as $category) {
            $open = null;
            $caret = null;
            $main_category = null;
            
            if ($category['depth'] < 2 && $category['children']) {
                $open = 'open-tree';
            }
            
            if ($category['children'] && $category['depth'] != 1) {
                $caret = '<i class="fa fa-caret-right" aria-hidden="true"></i> ';
            }
            
            if($category['depth'] == 1) {
                $open .= ' open-tree-main';
            }
            
            $html .= '<li class="dropdown-tree text-uppercase '. $open .'">';
            
            $html .= Html::a(
                    $caret.$category['text'],
                    Url::to('/theme/view/'.$category['id'], true), [
                        'class' => 'dropdown-tree-a'
                    ]
                );
            
            // $html .= ' <a class="dropdown-tree-a">'..'</a>';
            
            if ($category['children']) {
                $html .= '<ul class="category-level-2 dropdown-menu-tree">';
                $html .= $this->makeTreeHtml($category['children']);
                $html .= '</ul>';
            }
            $html .= '</li>';

        }
        return $html;
    }

    public function makeMegaMenu($categories)
    {
        $cssFirstA = null;
        $cssFirstLi = null;
        $html = '';

        foreach ($categories as $category) {

            $cssFirstA = $category['depth'] == 1 ? 'mm_a' : null;
            $cssFirstLi = $category['depth'] == 1 ? 'mm_li' : null;
            $cssFirstUl = $category['depth'] == 1 ? 'mm_open-ul' : null;

            $html .= '<li class="'.$cssFirstLi.'"><a class="'.$cssFirstA.'" href="'.Url::to(['/theme/view', 'id' => $category['id']]).'">'.$category['text'].'</a>';

            if ($category['depth'] == 1) {
                $html .= '<div class="mm_open">';
            }

            if ($category['children']) {
                $html .= '<ul class="'.$cssFirstUl.'">';
                $html .= $this->makeMegaMenu($category['children']);
                $html .= '</ul>';
            }

            if ($category['depth'] == 1) {
                $html .= '</div>';
            }
            $html .= '</li>';

        }
        return $html;
    }


    public static function clearCache()
    {
        $cache = Yii::$app->cache;
        $cache->delete('MenuWidget');
    }
}