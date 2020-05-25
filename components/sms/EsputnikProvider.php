<?php
namespace app\components\sms;

use app\components\sms\ProviderInterface;

class EsputnikProvider implements ProviderInterface
{
    public $login;
    public $password;
    public $from;
    public $phone;
    public $text;

    const SEND_SMS_URL = 'https://esputnik.com/api/v1/message/sms';
    
    /**
     *  @brief wrap encode and template
     *
     * @params [in] login - api login
     * @params [in] password - api password
     * @params [in] from - Номер телефона или подпись отправителя
     * @params [in] phone - Номер телефона получателя (в международном формате).
     *                            Также, можно указать несколько номеров через запятую
     * @params [in] text - sms text
     */     
    public function __construct(
        $login,
        $password,
        $from,
        $phone,
        $text
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->from = $from;
        $this->phone = $phone;
        $this->text = $text;
    }
    
    public function send()
    {
        try { 
            $json_value = new \stdClass();
            $json_value->text = $this->text;
            $json_value->from = $this->from;
            $json_value->phoneNumbers = array(intval($this->phone));
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL, self::SEND_SMS_URL);
            curl_setopt($ch,CURLOPT_USERPWD, $this->login.':'.$this->password);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        } catch(Exception $e) { 
            return 'Ошибка: ' . $e->getMessage();
        }
    }
}
