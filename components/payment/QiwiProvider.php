<?php
namespace app\components\payment;

use yii\base\BaseObject;
use app\components\payment\ProviderInterface;

class QiwiProvider extends BaseObject implements ProviderInterface
{
    public $SHOP_ID;
    public $REST_ID;
    public $PWD;
    public $BILL_ID;
    public $PHONE;
    public $amount;
    public $ccy;
    public $comment;
    public $lifetime;
    public $pay_source;
    public $prv_name;
    
    /**
     *  response example
     *  {"response": { "result_code": 0, "bill": { "bill_id": "99111-ABCD-0001", "amount": "1.00", "ccy": "RUB", "status": "waiting", "error": 0, "user": "tel:+79089877423", "comment": "тестируем оплату" } }}
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
        $SHOP_ID,
        $REST_ID,
        $PWD,
        $BILL_ID,
        $PHONE,
        $amount,
        $ccy,
        $comment,
        $lifetime,
        $pay_source,
        $prv_name,
        $config = []
    ) {
        $this->SHOP_ID = $SHOP_ID;
        $this->REST_ID = $REST_ID;
        $this->PWD = $PWD;
        $this->BILL_ID = $BILL_ID;
        $this->PHONE = $PHONE;
        $this->amount = $amount;
        $this->ccy = $ccy;
        $this->comment = $comment;
        $this->lifetime = $lifetime;
        $this->pay_source = $pay_source;
        $this->prv_name = $prv_name;
        
        parent::__construct($config);
    }
    
    public function init()
    {
        $data = array(
            "user" => "tel:+" . $this->PHONE,
            "amount" => $this->amount,
            "ccy" => $this->ccy,
            "comment" => $this->comment,
            "lifetime" => $this->lifetime,
            "pay_source" => $this->pay_source,
            "prv_name" => $this->prv_name
        ); 

        $ch = curl_init('https://api.qiwi.com/api/v2/prv/'.$this->SHOP_ID.'/bills/'.$this->BILL_ID);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->REST_ID.":".$this->PWD);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array (
            "Accept: application/json"
        ));
        
        $this->paymentSystemResponse = curl_exec($ch) or die(curl_error($ch));
        // echo $results;
        // echo curl_error($ch);
        curl_close ($ch); //Необязательный редирект пользователя
    }
    
    public function getHtml()
    {
        $url = 'https://qiwi.com/order/external/main.action'
            .'?shop='.$this->SHOP_ID
            .'&transaction='.$this->BILL_ID
            // .'&successUrl=http%3A%2F%2Fieast.ru%2Findex.php%3Froute%3Dpayment%2Fqiwi%2Fsuccess'
            // .'&failUrl=http%3A%2F%2Fieast.ru%2Findex.php%3Froute%3D payment%2Fqiwi%2Ffail'
            .'&qiwi_phone='.$this->PHONE;

        return '<a href="'.$url.'" class="btn btn-primary"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;&nbsp;Перейти На Сайт Оплаты</a>';
        
    }
    
    public function getStatus()
    {
        $decode = json_decode($this->paymentSystemResponse, true);
        // $test = '{"response": { "result_code": 0, "bill": { "bill_id": "99111-ABCD-0001", "amount": "1.00", "ccy": "RUB", "status": "waiting", "error": 0, "user": "tel:+79089877423", "comment": "тестируем оплату" } }}';
        // $decode = json_decode($test, true);
        
        $status = 'bad_request';
        if(!empty($decode['response']) && !empty($decode['response']['bill']) && !empty($decode['response']['bill']['status']) ) {
            $status = $decode['response']['bill']['status'];
        }
        if($status == 'paid')
            return 'success';
        
        return 'waiting';
    }
    
    public function getPaymentSystemResponse()
    {
        return $this->paymentSystemResponse;
    }
}
