<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\ProviderInterface;

class EmptyProvider extends BaseObject implements ProviderInterface
{
    public function __construct(
        $config = []
    ) { 
        parent::__construct($config);
    }
    
    
    public function getHtml()
    {
        return '';
    }
    
    public function getStatus()
    {
        return '';
    }
}
