<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;

class UploadExcelForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file_path;
    public $excel_file;

    const IMPORT_FOLDER_ALIAS = '@runtime/import_xls';

    public function rules()
    {
        return [
            [['excel_file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $saveDirectory = Yii::getAlias(self::IMPORT_FOLDER_ALIAS);
            if (!BaseFileHelper::createDirectory($saveDirectory, $mode = 0775)) {
                $this->addError('name', 'Не могу создать каталог на сервере: ' . $saveDirectory);
                return false;
            }
            
            $this->file_path = $this->excel_file->baseName . '_' . substr(md5($this->excel_file->baseName), 0, 5) . '_' . date('Y-m-d') . '.' . $this->excel_file->extension;
            $this->excel_file->saveAs($saveDirectory . '/' . $this->file_path);

            // $this->excel_file->saveAs('uploads/' . $this->excel_file->baseName . '.' . $this->excel_file->extension);
            return true;
        } else {
            return false;
        }
    }

    /**
     * parse uploaded file
     * return data array
     */
    public function processExcel()
    {
        $saveDirectory = Yii::getAlias(self::IMPORT_FOLDER_ALIAS);

        // $this->file_path
        // $PHPReader = \PHPExcel_IOFactory::load();
        $workbook = SpreadsheetParser::open($saveDirectory . '/' . $this->file_path);

        $myWorksheetIndex = $workbook->getWorksheetIndex('myworksheet');
        
        $result = [];
        foreach ($workbook->createRowIterator($myWorksheetIndex) as $rowIndex => $values) {
            $result[] = $values;
        }
        
        return $result;
    }
}
