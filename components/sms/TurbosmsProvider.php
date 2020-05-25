<?php
namespace app\components\sms;

use app\components\sms\ProviderInterface;

class TurbosmsProvider implements ProviderInterface
{
    public $login;
    public $password;
    public $sender;
    public $destination;
    public $text;
    // public $wappush;
    
    /**
     *  @brief wrap encode and template
     *
     * @params [in] login - api login
     * @params [in] password - api password
     * @params [in] sender - Номер телефона или подпись отправителя
     * @params [in] destination - Номер телефона получателя (в международном формате).
     *                            Также, можно указать несколько номеров через запятую
     * @params [in] text - sms text
     */     
    public function __construct(
        $login,
        $password,
        $sender,
        $destination,
        $text
    ) {
        $this->login = $login;
        $this->password = $password;
        $this->sender = $sender;
        $this->destination = $destination;
        $this->text = htmlspecialchars($text);
    }
    
    public function send()
    {
        try { 
            // Подключаемся к серверу
            $client = new \SoapClient('http://turbosms.in.ua/api/wsdl.html');
        
            // Можно просмотреть список доступных методов сервера
            // print_r($client->__getFunctions());
            // [0] => AuthResponse Auth(Auth $parameters)
            // [1] => GetCreditBalanceResponse GetCreditBalance(GetCreditBalance $parameters)
            // [2] => SendSMSResponse SendSMS(SendSMS $parameters)
            // [3] => GetNewMessagesResponse GetNewMessages(GetNewMessages $parameters)
            // [4] => GetMessageStatusResponse GetMessageStatus(GetMessageStatus $parameters)
            // [5] => AuthResponse Auth(Auth $parameters)
            // [6] => GetCreditBalanceResponse GetCreditBalance(GetCreditBalance $parameters)
            // [7] => SendSMSResponse SendSMS(SendSMS $parameters)
            // [8] => GetNewMessagesResponse GetNewMessages(GetNewMessages $parameters)
            // [9] => GetMessageStatusResponse GetMessageStatus(GetMessageStatus $parameters)
        
            // Данные авторизации
            $auth = [
                'login' => $this->login,
                'password' => $this->password,
            ];
        
            // Авторизируемся на сервере
            $result = $client->Auth($auth);
        
            // Результат авторизации
            // echo $result->AuthResult . PHP_EOL;
        
            // Получаем количество доступных кредитов
            // $result = $client->GetCreditBalance();
            // echo $result->GetCreditBalanceResult . PHP_EOL;
        
            // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8  
            // Отправляем сообщение на один номер.  
            // Подпись отправителя может содержать английские буквы и цифры. Максимальная длина - 11 символов.  
            // Номер указывается в полном формате, включая плюс и код страны  
            $sms = [
                'sender' => $this->sender,
                'destination' => $this->destination,
                'text' => $this->text
            ];
            $result = $client->SendSMS($sms);
        
            // Отправляем сообщение на несколько номеров.
            // Номера разделены запятыми без пробелов.
            // $sms = [
            //     'sender' => 'Rassilka',
            //     'destination' => '+380XXXXXXXX1,+380XXXXXXXX2,+380XXXXXXXX3',
            //     'text' => $text
            // ];
            // $result = $client->SendSMS($sms);
        
            // Выводим результат отправки.
            return $result->SendSMSResult->ResultArray;
        
            // ID второго сообщения
            // echo $result->SendSMSResult->ResultArray[2] . PHP_EOL;
        
            // Отправляем сообщение с WAPPush ссылкой
            // Ссылка должна включать http://
            // $sms = [
            //     'sender' => 'Rassilka',
            //     'destination' => '+380XXXXXXXXX',
            //     'text' => $text,
            //     'wappush' => 'http://super-site.com'
            // ];
            // $result = $client->SendSMS($sms);
        
            // Запрашиваем статус конкретного сообщения по ID
            // $sms = ['MessageId' => 'c9482a41-27d1-44f8-bd5c-d34104ca5ba9']
            // $status = $client->GetMessageStatus($sms);
            // echo $status->GetMessageStatusResult . PHP_EOL;
        
        } catch(Exception $e) { 
            return 'Ошибка: ' . $e->getMessage();
        }
    }
}
