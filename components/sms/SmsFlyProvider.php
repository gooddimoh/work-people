<?php
namespace app\components\sms;

use app\components\sms\ProviderInterface;

class SmsFlyProvider implements ProviderInterface
{
    public $user;
    public $password;
    public $phone;
    public $text;
    public $description;
    public $start_time;
    public $end_time;
    public $rate;
    public $lifetime;
    
    /**
     *  @brief wrap encode and template
     *
     * @params [in] user - account login
     * @params [in] password - account password
     * @params [in] phone - recipient
     * @params [in] text - sms text
     * @params [in] description - sms description (for API)
     * @params [in] start_time - send imediatly or send at date: YYYY-MM-DD HH:MM:SS
     * @params [in] end_time - YYYY-MM-DD HH:MM:SS
     * @params [in] rate 1 = 1 sms per minute
     * @params [in] lifetime sms life time
     */     
    public function __construct(
        $user,
        $password,
        $phone,
        $text,
        $description = 'Site API sms send',
        $start_time = 'AUTO',
        $end_time = 'AUTO',
        $rate = 1,
        $lifetime = 4
    ) {
        $this->user = $user;
        $this->password = $password;
        $this->phone = $phone;
        $this->text = htmlspecialchars($text);
        $this->description = htmlspecialchars($description);
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->rate = $rate;
        $this->lifetime = $lifetime;
    }
    
    public function send()
    {
        $myXML 	 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $myXML 	.= "<request>"."\n";
        $myXML 	.= "<operation>SENDSMS</operation>"."\n";
        $myXML 	.= '		<message start_time="'.$this->start_time.'" end_time="'.$this->end_time.'" lifetime="'.$this->lifetime.'" rate="'.$this->rate.'" desc="'.$this->description.'">'."\n";
        $myXML 	.= "		<body>".$this->text."</body>"."\n";
        $myXML 	.= "		<recipient>".$this->phone."</recipient>"."\n";
        $myXML 	.=  "</message>"."\n";
        $myXML 	.= "</request>";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERPWD , $this->user.':'.$this->password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.noai.php');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // log mail
        return $response;
    }
}
