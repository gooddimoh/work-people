<?php
namespace app\components;

use Yii;
use yii\helpers\BaseFileHelper;
use yii\web\HttpException;

class CurrencyConverterHelper
{
    /**
     * @param $source_amount number
     * @param $source_currency_code strng - currency char code (countryList['char_code])
     * @param $destination_currency_code strng - currency char code (countryList['char_code])
     * @return number
     * @throws HttpException if the currency can not be founded
     */
    public static function currencyToCurrency($amount, $source_currency, $result_currency, $decimals = 2)
    {
        if ($source_currency == $result_currency) {
            return $amount;
        }
        
        $corses_list = self::getCoursesList();

        // convert currencies into 'RUR'
        if ($source_currency == 'RUR') {
            $source_currency_rur = $amount;
        } else { // try to convert into 'RUR'
            // find currency to convert into 'RUR'
            $is_funded = false;
            foreach ($corses_list as $currency) {
                if ($source_currency == $currency['CharCode']) {
                    $is_funded = true;
                    $source_currency_rur = ( floatval($amount) * $currency['Value'] ) / $currency['Nominal'];
                    break; // exit
                }
            }

            if(!$is_funded) {
                Yii::warning('Unknown or unsupported currency code: ' . $source_currency);
                return 0;
                // throw new HttpException(500, ');
            }
        }

        if($result_currency == 'RUR') {
            return number_format($source_currency_rur, $decimals, '.', '');
        } else {
            // find currency to convert into result_currency
            foreach ($corses_list as $currency) {
                if ($result_currency == $currency['CharCode']) {
                    $result = ($source_currency_rur / $currency['Value']) * floatval($currency['Nominal']);
                    return number_format($result, $decimals, '.', '');
                }
            }

            // if unknown CharCode, throw exception
            throw new HttpException('Unknown or unsupported currency code: ' . $result_currency);
        }
    }

    public static function getCoursesList() {
        $file_path = Yii::getAlias('@app/runtime/courses');
        $file_name = date('Y-m-d') . '.json';
        
        if ( file_exists($file_path . DIRECTORY_SEPARATOR . $file_name) ) {
            return json_decode(file_get_contents($file_path . DIRECTORY_SEPARATOR . $file_name), true);
        } else {
            // load from remote server
            $current_date = date('d-m-Y'); // dd/MM/yyyy 
            $url = 'http://cbr.ru/scripts/XML_daily.asp?date_req=' . $current_date;
            
            $xmlCourses = file_get_contents($url);
            $domObj = new xmlToArrayParser($xmlCourses);
            $domArr = $domObj->array;
            $valutes = $domArr['ValCurs']['Valute'];
            foreach($valutes as &$valute) {
                $valute['Value'] = floatval(str_replace(',', '.', $valute['Value']));
                $valute['Nominal'] = intval($valute['Nominal']);
            }
            
            // chache data to file
            if ( !BaseFileHelper::createDirectory($file_path, $mode = 0775) ) {
                throw new HttpException('Can\'t create cache folder on server: ' . $file_path);
            }

            if ( file_put_contents($file_path . DIRECTORY_SEPARATOR . $file_name, json_encode($valutes)) === false ) {
                throw new HttpException( 'Can\'t write file into file system: ' . ($file_path . DIRECTORY_SEPARATOR . $file_name) );
            }

            return $valutes;
        }
    }
}


class xmlToArrayParser {
    /** The array created by the parser can be assigned to any variable: $anyVarArr = $domObj->array.*/
    public  $array = array();
    public  $parse_error = false;
    private $parser;
    private $pointer;
   
    /** Constructor: $domObj = new xmlToArrayParser($xml); */
    public function __construct($xml) {
        $this->pointer =& $this->array;
        $this->parser = xml_parser_create("UTF-8");
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
        $this->parse_error = xml_parse($this->parser, ltrim($xml))? false : true;
    }
   
    /** Free the parser. */
    public function __destruct() { xml_parser_free($this->parser);}
 
    /** Get the xml error if an an error in the xml file occured during parsing. */
    public function get_xml_error() {
        if($this->parse_error) {
            $errCode = xml_get_error_code ($this->parser);
            $thisError =  "Error Code [". $errCode ."] \"<strong style='color:red;'>" . xml_error_string($errCode)."</strong>\",
                                at char ".xml_get_current_column_number($this->parser) . "
                                on line ".xml_get_current_line_number($this->parser)."";
        } else $thisError = $this->parse_error;
        return $thisError;
    }
   
    private function tag_open($parser, $tag, $attributes) {
        $this->convert_to_array($tag, 'attrib');
        $idx=$this->convert_to_array($tag, 'cdata');
        if(isset($idx)) {
            $this->pointer[$tag][$idx] = Array('@idx' => $idx,'@parent' => &$this->pointer);
            $this->pointer =& $this->pointer[$tag][$idx];
        } else {
            $this->pointer[$tag] = Array('@parent' => &$this->pointer);
            $this->pointer =& $this->pointer[$tag];
        }
        if (!empty($attributes)) { $this->pointer['attrib'] = $attributes; }
    }
 
    /** Adds the current elements content to the current pointer[cdata] array. */
    private function cdata($parser, $cdata){
        if (isset($this->pointer['cdata'])) { $this->pointer['cdata'] .= $cdata;}
        else { $this->pointer['cdata'] = $cdata; }
    }
    
    private function tag_close($parser, $tag) {
        $current = & $this->pointer;
        if(isset($this->pointer['@idx'])) {unset($current['@idx']);}
        $this->pointer = & $this->pointer['@parent'];
        unset($current['@parent']);
        if(isset($current['cdata']) && count($current) == 1) { $current = $current['cdata'];}
        else if(empty($current['cdata'])) {unset($current['cdata']);}
    }
   
    /** Converts a single element item into array(element[0]) if a second element of the same name is encountered. */
    private function convert_to_array($tag, $item) {
        if(isset($this->pointer[$tag]) && !is_array($this->pointer[$tag])){
            $content = $this->pointer[$tag];
            $this->pointer[$tag] = array((0) => $content);
            $idx = 1;
        } else if (isset($this->pointer[$tag])) {
            $idx = count($this->pointer[$tag]);
            if(!isset($this->pointer[$tag][0])) {
                foreach ($this->pointer[$tag] as $key => $value) {
                    unset($this->pointer[$tag][$key]);
                    $this->pointer[$tag][0][$key] = $value;
        }}} else $idx = null;
        return $idx;
    }
}
