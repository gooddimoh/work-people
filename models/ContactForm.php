<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    // public $verifyCode;
    public $reCaptcha;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            // ['verifyCode', 'captcha'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'uncheckedMessage' => 'Пожалуйста подтвердите что вы не робот.']
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'subject' => 'Тема',
            'body' => 'Текст',
            'reCaptcha' => '',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            // Yii::$app->mailer->compose()
                // ->setTo($email)
                // ->setFrom([$this->email => $this->name])
                // ->setSubject($this->subject)
                // ->setTextBody($this->body)
                // ->send();

            $from_server = 'Contact_From@' . $_SERVER['SERVER_NAME'];
            
            $headers = "From: Contact_Form <{$from_server}>\r\n".
              "Reply-To: {$this->email}\r\n".
              "MIME-Version: 1.0\r\n".
              "Content-type: text/plain; charset=UTF-8";
          
            mail($email, $this->subject, $this->body, $headers);
            
            return true;
        }
        return false;
    }
}
