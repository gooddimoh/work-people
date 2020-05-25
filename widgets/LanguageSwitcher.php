<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace app\widgets;

use yii\helpers\ArrayHelper;
use Yii;
use yii\bootstrap\Widget;

/**
 * @author Aris Karageorgos <aris@phe.me>
 */
class LanguageSwitcher extends Widget
{
    public function run()
    {
		$langs = [
			'en' => ['flag'=>'american', 'label'=>'English'],
			'ru' => ['flag'=>'russia', 'label'=>'Рус.'],
			'ua' => ['flag'=>'ua', 'label'=>'Укр.'],
		];
		
        $languages = isset(Yii::$app->getUrlManager()->languages) ? Yii::$app->getUrlManager()->languages : [];
		$items = [];
		$current = [];
        if (count($languages) > 1) {
            $currentUrl = preg_replace('/' . Yii::$app->language . '\//', '', Yii::$app->getRequest()->getUrl(), 1);
            $isAssociative = ArrayHelper::isAssociative($languages);
            foreach ($languages as $language => $code) {
                $url = $code . $currentUrl;
                if ($isAssociative) {
                    $item = ['label' => $langs[$language]['label'], 'url' => $url, 'flag'=>$langs[$language]['flag']];
                } else {
                    $item = ['label' => $langs[$code]['label'], 'url' => $url, 'flag'=>$langs[$code]['flag']];
                }
                
				if(Yii::$app->language == $code)
					$current = $item;
                // else
					$items[] = $item;
            }
        }
		
		return $this->render('lang/view', [
            'current' => $current,
            'langs' => $items
			// [
				// ['name'=>'English', 	'code'=>'en-US', 'flag'=>'american'],
				// ['name'=>'Русский язык','code'=>'ru-RU', 'flag'=>'russia'],
				// ['name'=>'中文', 		'code'=>'zh-CN', 'flag'=>'china'],
			// ], // need explode Yii::$app->language
        ]);
		
    }
}
