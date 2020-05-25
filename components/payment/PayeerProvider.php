<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\ProviderInterface;

class PayeerProvider extends BaseObject implements ProviderInterface
{
    public $m_shop;
    public $m_orderid;
    public $m_amount;
    public $m_curr;
    public $m_desc;
    public $m_key;
        
    /**
     *  @brief construct payeer provider
     *  
     *  @param [in] $m_shop id мерчанта 
     *  @param [in] $m_orderid номер счета в системе учета мерчанта 
     *  @param [in] $m_amount сумма счета с двумя знаками после точки // number_format(0.01, 2, '.', '')
     *  @param [in] $m_curr валюта счета // RUB
     *  @param [in] $m_desc описание счета, закодированное с помощью алгоритма base64 // base64_encode('Test payment');
     *  @param [in] $m_key ключ
     */
    public function __construct(
        $m_shop,
        $m_orderid,
        $m_amount,
        $m_curr,
        $m_desc,
        $m_key,
        $config = []
    ) {
        $this->m_shop = $m_shop;
        $this->m_orderid = $m_orderid;
        $this->m_amount = $m_amount;
        $this->m_curr = $m_curr;
        $this->m_desc = $m_desc;
        $this->m_key = $m_key;
        
        parent::__construct($config);
    }
    
    
    public function getHtml()
    {
        $arHash = array(
            $this->m_shop,
            $this->m_orderid,
            $this->m_amount,
            $this->m_curr,
            $this->m_desc
        );
        
        $arHash[] = $this->m_key; 
        
        $sign = strtoupper(hash('sha256', implode(':', $arHash)));
        $form = <<<EOD
    <form method="post" action="https://payeer.com/merchant/">
        <input type="hidden" name="m_shop" value="{$this->m_shop}">
        <input type="hidden" name="m_orderid" value="{$this->m_orderid}">
        <input type="hidden" name="m_amount" value="{$this->m_amount}">
        <input type="hidden" name="m_curr" value="{$this->m_curr}">
        <input type="hidden" name="m_desc" value="{$this->m_desc}">
        <input type="hidden" name="m_sign" value="{$sign}">
        <button type="submit" name="m_process" class="btn btn-primary"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;&nbsp;Перейти На Сайт Оплаты</button>
    </form> 
EOD;
        /*
        <input type="hidden" name="form[ps]" value="2609">
        <input type="hidden" name="form[curr[2609]]" value="USD">
        <input type="hidden" name="m_params" value="{$this->m_params}">
        */
        return $form;
    }
    
    public function getStatus()
    {
        return 'unsupported, instead use pull requsts';
    }
}
