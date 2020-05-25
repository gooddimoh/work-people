<?php
namespace app\components;

use Yii;

class ReferenceHelper
{
    const FOLDER_HANDBOOK = '@webroot/handbook';

    const COUNTRY_LIST_STATUS_SHOW = "10";
    const COUNTRY_LIST_STATUS_HIDE = "20";

    /**
     * @param $full_list - for admin panel edit
     * @return array
     */
    public static function getCountryList($full_list = false)
    {
        $data = self::loadReference('country_list')['root']['element'];

        // order list by `order`
        usort($data, function($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? 1 : -1;
        });

        if($full_list) {
            return $data;
        }

        // filter by 
        $data = array_filter($data, function($item) {
            return $item['status'] == self::COUNTRY_LIST_STATUS_SHOW;
        });

        return $data;
    }

    /**
     * @param $full_list - for admin panel edit
     * @return array
     */
    public static function getEmployerCountryList()
    {
        $data = self::loadReference('country_list')['root']['element'];

        // order list by `order`
        usort($data, function($a, $b) {
            if ($a['order'] == $b['order']) {
                return 0;
            }
            return ($a['order'] < $b['order']) ? 1 : -1;
        });

        // filter by 
        $data = array_filter($data, function($item) {
            return $item['employer_country'] == self::COUNTRY_LIST_STATUS_SHOW;
        });

        return $data;
    }
    
    /**
     * @param data array
     * @return boolean
     */
    public static function setCountryList($data)
    {
        return self::writeReference('country_list', $data);
    }

    public static function getCurrencyList()
    {
        return self::loadReference('currency_list')['root']['element'];
    }
    
    public static function setCurrencyList($data)
    {
        return self::writeReference('currency_list', $data);
    }

    public static function getLanguageList()
    {
        return self::loadReference('language_list')['root']['element'];
    }
    
    public static function setLanguageList($data)
    {
        return self::writeReference('language_list', $data);
    }

    protected static function loadReference($handbook_name)
    {
        $file_name = Yii::getAlias(self::FOLDER_HANDBOOK). DIRECTORY_SEPARATOR . $handbook_name . '.json';
        if(!is_file($file_name))
            throw new \Exception('Unknown handbook name: ' . $handbook_name);
        
        $hand_book_data = json_decode(file_get_contents($file_name), true);
        // $hand_book_fields = array_keys($hand_book_data['root']['element'][0]);
        return $hand_book_data;
    }
    
    protected static function writeReference($handbook_name, $data)
    {
        $file_name = Yii::getAlias(self::FOLDER_HANDBOOK). DIRECTORY_SEPARATOR . $handbook_name . '.json';
        if(!is_file($file_name))
            throw new \Exception('Unknown handbook name: ' . $handbook_name);
        
        $data_wrapper = ['root' => ['element' => $data]];
        if(file_put_contents($file_name, json_encode($data_wrapper, JSON_PRETTY_PRINT)) === false) {
            return false;
        }

        return true;
    }

}
