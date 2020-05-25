<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\ProviderInterface;

class FreeKassaProvider extends BaseObject implements ProviderInterface
{
    public $merchant_id;
    public $secret_word;
    public $secret_word2;
    public $order_id;
    public $order_amount;
    public $i; // currency
    
    /**
     *  response example
        <?xml version="1.0" encoding="UTF-8" ?>
        <root>
        <answer>info</answer>
        <desc>Order Info</desc>
        <status>new</status>
        <intid>19847592</intid>
        <id>test0001</id>
        <date>2017-04-27 16:44:53</date>
        <amount>0.01</amount>
        <description></description>
        <email>ppay4@mail.ru</email>
        </root>
     */
    public $paymentSystemResponse;
        
    /**
     *  @brief Brief
     *  
     *  @param [in] $SHOP_ID Идентификатор магазина из вкладки "Данные магазина" https://ishop.qiwi.com/options/http.action
     *  @param [in] $REST_ID API ID из вкладки "Данные магазина" https://ishop.qiwi.com/options/rest.action
     *  @param [in] $PWD API пароль из вкладки "Данные магазина" https://ishop.qiwi.com/options/rest.action
     *  @param [in] $BILL_ID ID счета
     *  @param [in] $PHONE Идентификатор кошелька пользователя, которому выставляется счет. Представляет собой номер телефона пользователя в международном формате с префиксом
     *  @param [in] $amount Сумма счёта
     *  @param [in] $ccy Идентификатор валюты (Alpha-3 ISO 4217 код)
     *  @param [in] $comment Комментарий к счету
     *  @param [in] $lifetime время оплаты (пример '2017-03-30T15:35:00')
     *  @param [in] $pay_source При значении "mobile" оплата счета будет производиться с баланса мобильного телефона пользователя, "qw" – любым способом через интерфейс Visa QIWI Wallet
     *  @param [in] $prv_name Название провайдера. (имя проекта)
     *  
     *  @details Details
     */
    public function __construct(
        $merchant_id,
        $secret_word,
        $secret_word2,
        $order_id,
        $order_amount,
        $i, // currency
        $config = []
    ) {
        $this->merchant_id = $merchant_id;
        $this->secret_word = $secret_word;
        $this->secret_word2 = $secret_word2;
        $this->order_id = $order_id;
        $this->order_amount = $order_amount;
        $this->i = $i; // currency
        
        parent::__construct($config);
    }
    
    public function getHtml()
    {
        $sign = md5($this->merchant_id.':'.$this->order_amount.':'.$this->secret_word.':'.$this->order_id);
        $form = <<<EOD
    <form method="get" action="http://www.free-kassa.ru/merchant/cash.php">
        <input type="hidden" name="m" value="{$this->merchant_id}">
        <input type="hidden" name="oa" value="{$this->order_amount}">
        <input type="hidden" name="o" value="{$this->order_id}">
        <input type="hidden" name="s" value="{$sign}">
        <input type="hidden" name="i" value="{$this->i}">
        <input type="hidden" name="lang" value="ru">
        <input type="submit" name="pay" value="Оплатить">
    </form>
EOD;
// <input type="hidden" name="us_login" value="demo user">
        
        return $form;
    }
    
    /**
     *  Статус заявки, new - новые, paid - оплаченные, completed - выполненные, all - все
     */
    public function getStatus()
    {
        $url = 'http://www.free-kassa.ru/api.php'
            . '?merchant_id=' . $this->merchant_id
            . '&s=' . md5($this->merchant_id . $this->secret_word2)
            . '&action=' . 'check_order_status'
            . '&order_id=' . $this->order_id;
        
        $xml = file_get_contents($url);
        $this->paymentSystemResponse = $xml;
        
        try {
            $xmlObj = new \SimpleXMLElement($xml);
        } catch (\Exception $e) {
            return $xml;
        }
        
        if($xmlObj->answer == 'new')
            return 'waiting';
        
        if(     $xmlObj->answer == 'info' 
            && ($xmlObj->status == 'paid' || $xmlObj->status == 'completed' )
        ) { //? or completed
            return 'success';
        }
        
        return 'waiting';
    }
    
    public function getPaymentSystemResponse()
    {
        return $this->paymentSystemResponse;
    }
}
