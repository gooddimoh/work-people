<?php
namespace app\components;

class SendMailHelper
{
    public $subject;
    public $body;
    public $email;
    
    /**
     *  @brief wrap encode and template
     *
     *  @param [in] $email send mail to this email
     *  @param [in] $subject mail title
     *  @param [in] $body mail text
     *  @return bool result of mail() function
     */     
    public function __construct(
        $email,
        $subject,
        $body
    ) {
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
    }
    
    public function send()
    {
        $subject='=?UTF-8?B?'.base64_encode($this->subject).'?=';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
            // "MIME-Version: 1.0\r\n".
            // "Content-type: text/html; charset=UTF-8";
        $body = $this->body;
        
        // log mail
        return mail( $this->email, $subject, $body, $headers );
    }
}
