<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\sms\SmsSystem;

/**
 * LoginPhoneForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginPhoneForm extends Model
{
    const SCENARION_GENERATE = 10;
    const SCENARION_LOGIN = 20;

    public $phone;
    public $sms_code;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone'], 'required', 'on'=>self::SCENARION_GENERATE],
            [['phone', 'sms_code'], 'required', 'on'=>self::SCENARION_LOGIN],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean', 'on'=>self::SCENARION_LOGIN],
            // sms_code is validated by validateSmsCode()
            ['sms_code', 'validateSmsCode', 'on'=>self::SCENARION_LOGIN],
        ];
    }

        /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('main', 'Phone number'),
            'sms_code' => Yii::t('main', 'SMS from code'),
            'rememberMe' => Yii::t('main', 'Remember Me'),
        ];
    }

    /**
     * Validates the sms_code.
     * This method serves as the inline validation for sms_code.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateSmsCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $session = Yii::$app->session;
            $sms_code = $session->get('sms_code');
            
            if ($this->sms_code != $sms_code) {
                $this->addError($attribute, Yii::t('main', 'Incorrect SMS from code.'));
            } else if(!$user) {
                // create new user if user not exists
                $model = new User();
                $modelPhone = new UserPhone();

                $db = $model->getDb();
                $trans = $db->beginTransaction();
                $model->scenario = User::SCENARION_REGISTRATION_BY_PHONE;
                $model->login = $this->phone;
                // $model->phone = $this->phone;
                $model->username = $this->phone;
                $model->email = $this->phone . '@unknown.host';

                try {
                    $model->save();
                    $modelPhone->user_id = $model->id;
                    $modelPhone->verified = UserPhone::VERIFIED_REGISTRED_ACCOUNT_BY_SMS;
                    $modelPhone->phone = '' . intval($this->phone);
                    
                    if($modelPhone->save()) {
                        $trans->commit();
                    } else {
                        throw new Exception('Error. Can\'t create account');
                    }
                } catch (Exception $exc) {
                    $trans->rollBack();
                    throw $exc;
                }


                $this->_user = false; // reset search
            }
        }
    }

    public function generateSmscode()
    {
        if (!$this->hasErrors()) {
            // $user = $this->getUser();

            // if (!$user) {
            //     $this->addError('phone', Yii::t('main', 'Account with this pnone number not registred.'));
            //     return false;
            // }

            $session = Yii::$app->session;
            $last_send_time = $session->get('last_send_time');
            $current_time = time();
            $sms_send_dealy = intval(Yii::$app->params['smsSendDelay']);
            if ( !empty($last_send_time)
                && ($current_time - intval($last_send_time)) < $sms_send_dealy
            ) {
                $this->addError('phone', 'You can send SMS again after: ' . $current_time + $sms_send_dealy - intval($last_send_time) . ' seconds');
                return false; // exit
            }

            $rand_code = rand(100000, 999999);
            $session->set('sms_code', $rand_code);
            
            if ($_SERVER['HTTP_HOST'] == 'wwswork.local') { // debug mode for dev localhost
                $data_txt = $this->phone . ': ' . $rand_code . "\r\n";
                file_put_contents('sms_send.txt', $data_txt, FILE_APPEND);
            } else {
                $response = SmsSystem::send(
                    $this->phone,
                    $rand_code
                );

                //! BUG, need process balance errors
                // $response
                // var_dump($response);
                // die();
            }
        }

        return true;
    }

    /**
     * Logs in a user using the provided phone and smscode.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $login_result = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
            if(!$login_result) {
                return false;
            }

            // remember by what UserPhone `id` user logged in
            Yii::$app->user->identity->setPhoneId($this->phone);

            return true;
        }
        return false;
    }

    /**
     * Finds user by [[phone]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
