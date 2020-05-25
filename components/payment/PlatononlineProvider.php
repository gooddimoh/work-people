<?php
namespace app\components\payment;

use Yii;
use yii\base\BaseObject;
use app\components\payment\ProviderInterface;

class PlatononlineProvider extends BaseObject implements ProviderInterface
{
    const API_URL = 'https://secure.platononline.com/payment/auth';

    public $merchant_id;
    public $password;
    public $payment;
    public $data;
    public $url;
    public $order;
    public $sign;
    public $custom_params;
    
    /**
     *  response example
     *  {"response": { "result_code": 0, "bill": { "bill_id": "99111-ABCD-0001", "amount": "1.00", "ccy": "RUB", "status": "waiting", "error": 0, "user": "tel:+79089877423", "comment": "тестируем оплату" } }}
     */
    public $paymentSystemResponse;
        
    /**
     *  @brief Brief
     *  
     *  @param [in] $merchant_id - Ключ идентификации мерчанта
     *  @param [in] $password
     *  @param [in] $payment - Код платежного метода, СС — для платежных карт
     *  @param [in] $data (array) - Свойства заказа (цена, наименование, валюта)  
     *     Пример:
     *      amount — 12 00 (два знака после точки)
     *      currency — UAH
     *      description — строка до 255 символов
     *  @param [in] $url - URL на который покупатель будет перенаправлен после
     *  @param [in] $sign - md5 (strtoupper(strrev(key)strrev(poyment)strrev(doto)strrev(url)strrev(PASSWORD)))
     *  @param [in] $order - Параметр для передачи номера Строка до 255 символов
     *  @param [in] $custom_params:
     *     lang	 Параметр яызка для отображения платежной формы	Доступны — UK, RU, EN.
     *           В противном случае будет определяться в зависимости от языка браузера. (ISO 639-1)
     *     email	 Параметр для передачи e-mail плательщика	Строка длиной до 255 символов	
     *     first_name	Параметр для передачи Имени клиента	Строка до 32 символов	
     *     last_name	Параметр для передачи Фамилии клиента	Строка до 32 символов	
     *     phone	Параметр для передачи телефона плательщика	Строка до 32 символов	
     *     order	Параметр для передачи номера	Строка до 255 символов	
     *     error_url	URL, на который покупатель будет перенаправлен после не успешного платежа	В случае отсутствия ошибка будет отображаться на платежной форме.	
     *     formid	Параметр для отображения показываемой формы плательщику		
     *     ext1	Клиентский параметр 1	Строка до 32 символов	
     *     ext2	Клиентский параметр 2	Строка до 32 символов	
     *     ext3	Клиентский параметр 3	Строка до 32 символов	
     *     ext4	Клиентский параметр 4	Строка до 32 символов
     *  @details Details
     */
    public function __construct(
        $merchant_id,
        $password,
        $payment /* = 'CC'*/ ,
        $data,
        $url,
        $order,
        $custom_params = [],
        $config = []
    ) {
        $this->merchant_id = $merchant_id;
        $this->password = $password;
        $this->payment = $payment; // default 'CC'
        $this->data = $data;
        $this->url = $url;
        $this->order = $order;
        $this->custom_params = $custom_params;
        
        parent::__construct($config);
    }
    
    public function getHtml()
    {
        $data = base64_encode(json_encode($this->data));

        $sign = md5(strtoupper(
                strrev($this->merchant_id).
                strrev($this->payment).
                strrev($data).
                strrev($this->url).
                strrev($this->password)
            ));
        
        $btn_label = Yii::t('invoice', 'Go To Payment Site');
        $api_url = self::API_URL;
        $form = <<<HTML
            <form action="{$api_url}" method="post">
                <input type="hidden" name="payment" value="{$this->payment}" />
                <input type="hidden" name="key" value="{$this->merchant_id}" />
                <input type="hidden" name="url" value="{$this->url}" />
                <input type="hidden" name="order" value="{$this->order}" />
                <input type="hidden" name="data" value="{$data}" />
                <input type="hidden" name="sign" value="{$sign}" />
                <button type="submit" class="btn">{$btn_label}</button>
            </form>
HTML;
        /*
        <input type="hidden" name="form[ps]" value="2609">
        <input type="hidden" name="form[curr[2609]]" value="USD">
        <input type="hidden" name="m_params" value="{$this->m_params}">
        */
        return $form;
    }
    
    public function getStatus()
    {
        $hash =  md5(strtoupper($this->order . $this->password));

        $post = [
            'action' => 'GET_TRANS_STATUS_BY_ORDER',
            'CLIENT_KEY' => $this->merchant_id,
            'order_id'   => '' . $this->order,
            'hash' => $hash,
        ];

        $api_url = self::API_URL;
        $form = <<<HTML
            <form action="{$api_url}" method="post">
                <input type="hidden" name="action" value="GET_TRANS_STATUS_BY_ORDER" />
                <input type="hidden" name="CLIENT_KEY" value="{$this->merchant_id}" />
                <input type="hidden" name="order_id" value="{$this->order}" />
                <input type="hidden" name="hash" value="{$hash}" />
                <button type="submit" class="btn">test</button>
            </form>
HTML;

        // echo $form;
        // die();

        $ch = curl_init(self::API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);
        curl_close($ch);

        // echo $response;
        // die();

        return 'success'; //! BUG, need check success from payment system
    }
    
    public function getPaymentSystemResponse()
    {
        return $this->paymentSystemResponse;
    }
}
