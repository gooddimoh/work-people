<?php

namespace app\modules\admin\controllers;

use app\models\GoodsTitle;
use Composer\Command\SelfUpdateCommand;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;

class LocalizationController extends \yii\web\Controller
{
    public $enableCsrfValidation = false; //!BUG, warning Csrf Validation disabled
    const app_Yii = 'Yii';
    const app_Angular = 'Angular';
    const app_Handbook = 'Handbook';
    const dir_Yii = '@app/messages';
    const dir_Angular = '@webroot/js/localization';
    const dir_Handbook = '@webroot/handbook';
    const pageSize = 10;
    private $aGarbage = ['.php', '.json', DIRECTORY_SEPARATOR];
	private $handbookLangList =['ru','en','zh_cn'];

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
				'ruleConfig' => [
					'class' => 'app\components\AccessRule'
				],
                'rules' => [
                    [
                        'actions' => ['index' /*, 'handbook', 'goods' */],
                        'allow' => true,
                        'roles' => ['administrator'],
                    ]
                ],
            ],
            // 'verbs' => [
                // 'class' => VerbFilter::className(),
                // 'actions' => [
                    // 'logout' => ['post'],
                // ],
            // ],
        ];
    }

    public function actionHandbook()
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (Yii::$app->request->isGet){
                $file = Yii::$app->request->get('file');
                if ($file != null){
                    return  $this->getHandbookData($file);
                }
            } elseif (Yii::$app->request->isPost) {
                $app = Yii::$app->request->post('app');
                if ($app == 'handbook'){
                    $fdata = Yii::$app->request->post('fdata');
                    $fname = Yii::$app->request->post('fname');
                    if (($fdata != null)&&($fname != null)){
                        $this->setHanbookData(json_decode($fdata), $fname);
                    }
                   return ['success' => true];
                }
            }
        } else
            return $this->render('handbook', ['files' => $this->getCommonNames(self::app_Handbook)]);
    }

    
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->isGet) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $appName = Yii::$app->request->get('app');
                $fileName = Yii::$app->request->get('file');
                if ($fileName !== null) {
                    return $this->getFileData($appName, $fileName);
                } else {
                    return $this->getCommonNames($appName);
                }
            }
            else {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                $appName = Yii::$app->request->post('app');
                $data = json_decode(Yii::$app->request->post('fdata'));
                $file = Yii::$app->request->post('file');
                if ($appName == self::app_Yii){
                    $this->setYiiData($data, $file);
                } else {
                    $this->setAngularData($data, $file);
                }
                return ["success"=>true];
            }
        } else {
            return $this->render('index', ['apps' => [self::app_Yii/*, self::app_Angular*/]]);
        }
    }

    private function setYiiData($newData, $cFileName){
        $appPath = $this->getPath(self::app_Yii);
        foreach ($newData as $lang => $data){
            $tmpFileName = $appPath . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $cFileName .".php";
            $content = "<?php \n\nreturn [\n";

            $template = "\t\"%key%\"=>\"%value%\",\n";
            foreach ($data as $key => $value){
                $value = str_replace('"', '\"', $value);
                $value = str_replace('\\"', '\"', $value);

                $tmp = $template;
                $tmp = str_replace('%key%', $key, $tmp);
                $tmp = str_replace('%value%', $value, $tmp);
                $content .= $tmp;
            }
            $content .= ("];\n");
            if ($file = fopen($tmpFileName,"w+")){

                fwrite($file, $content);

            }

        }
    }

    private function setAngularData($newData, $cFileName){
        $appPath = $this->getPath(self::app_Angular);
        foreach ($newData as $lang => $data){
            $tmpFileName = $appPath . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $cFileName . "_".$lang .".json";
            if ($file = fopen($tmpFileName, "w+")){
                fwrite($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
    }

    private function setHanbookData($newData, $cFileName){
        $appPath = $this->getPath(self::app_Handbook);

        foreach ($newData as $lang => $data){
            $tmpData = ["root"=>["element"=>$data]];
            $langSuffix = "";
            if ($lang != "ru"){
                $langSuffix = "_".$lang;
            }
            $tmpFileName = $appPath . DIRECTORY_SEPARATOR . $cFileName . $langSuffix . ".json";
            if ($file = fopen($tmpFileName, "w+")){
                fwrite($file, json_encode($tmpData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
        }
    }
  
    private function dirList($path, $directory = false) {
        $result = [];
        $tmp = scandir($path);
        $tmp = array_diff($tmp, ['.','..']);
        foreach ($tmp as $value) {
            if (is_dir($path .DIRECTORY_SEPARATOR. $value) === $directory){
                array_push($result, $value);
            }
        }
        return $result;
    }

    private function getPath($appName) {
        switch ($appName){
            case self::app_Yii:
                $path = self::dir_Yii;
                break;
            case self::app_Angular:
                $path = self::dir_Angular;
                break;
            case self::app_Handbook:
                $path = self::dir_Handbook;
                break;
        }
        return Yii::getAlias($path);
    }
    
//  Getting data from files   
    private function getFileData($appName, $commonFileName){
        $result = [];
        $appPath = $this->getPath($appName);
        $langList = $this->dirList($appPath,true);
        foreach ($langList as $lang) {
            $fullNames = FileHelper::findFiles($appPath . DIRECTORY_SEPARATOR . $lang);
            foreach ($fullNames as $fileName) {
                if ($this->getCommonName($appName, $fileName) === $commonFileName){
                    if($appName === self::app_Yii){
                        $result[$lang] = $this->getYiiData($fileName);
                    }else{
                        $result[$lang] = $this->getAngularData($fileName);
                    }
                    break;
                }
            }
        }
        return $result;
    }
 
    private function getYiiData($fileName) {
        return include($fileName);
    }
    
    private function getAngularData($fileName) {
         return json_decode(file_get_contents($fileName),true);
    }

    private function getHandbookData($commonFileName){
        $result = [];
        foreach($this->handbookLangList as $lang){
            $result[$lang] = [];
        }
        $appPath = $this->getPath(self::app_Handbook);
        $fileList = FileHelper::findFiles($appPath);
        foreach ($fileList as $file){
            if ($this->getCommonName(self::app_Handbook, $file) == $commonFileName){
                if ($appPath. DIRECTORY_SEPARATOR . $commonFileName . '.json' == $file){
                    $result['ru'] = json_decode(file_get_contents($file), true)['root']['element'];
                } else {
                    foreach ($this->handbookLangList as $lang){
                        if ($appPath. DIRECTORY_SEPARATOR . $commonFileName . '_' .$lang .'.json' == $file) {
                            $result[$lang] = json_decode(file_get_contents($file), true)['root']['element'];
                            break;
                        }
                    }
                }
            }
        }
        return $result;
    }
    
//  Getting common file names
    
    private function getCommonName($appName, $fileName){
        $appPath = $this->getPath($appName);
        if ($appName != self::app_Handbook)
            $langList = $this->dirList($appPath,true);
        else
            $langList = $this->handbookLangList;
        $aTmpLang = [];
        foreach ($langList as $lang) {
            $aTmpLang[] = '_' . $lang;            
        }
        return str_replace(array_merge($aTmpLang, $langList, [$appPath],
                                         $this->aGarbage), '', basename($fileName));
    }
    
    private function getCommonNames($appName){        
        $appPath = $this->getPath($appName);
        if ($appName != self::app_Handbook) {
            $langList = $this->dirList($appPath, true);
        } else {
            $langList = $this->handbookLangList;
        }
        $fullNames = [];
        $nameList = [];
        if ($appName != self::app_Handbook)
            foreach ($langList as $lang) {
                $tmpPath = $appPath . DIRECTORY_SEPARATOR . $lang;
                $tmpArr = FileHelper::findFiles($tmpPath);
                $fullNames = array_merge($fullNames, $tmpArr);
            }
        else
        {
            $fullNames = FileHelper::findFiles($appPath);
        }
        foreach ($fullNames as $fileName){
            $commonName = $this->getCommonName($appName, $fileName);
            $nameList[$commonName] = NULL;
        }
        return array_keys($nameList);
    }
}