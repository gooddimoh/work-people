<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\ProviderInterface;
use yii\helpers\Url;

class MyProvider extends BaseObject implements ProviderInterface
{
    public $invoice_id;
    
    public function __construct(
        $invoice_id,
        $config = []
    ) {
        $this->invoice_id = $invoice_id;
        
        parent::__construct($config);
    }
    
    public function getHtml()
    {
        return '<a href="'. Url::to(['/userpanel/pay-accept-my/' . $this->invoice_id]) .'" class="btn btn-primary"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;&nbsp;Пополнить с Моего Баланса</a>';
        
    }
    
    public function getStatus()
    {
        return 'unsupported, not need status';
    }
}
