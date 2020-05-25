<?php
namespace app\components\sms;

use Yii;

class SmsSystem
{
    static public function send(
        $phone,
        $text
    ) {
        // select provide via phone number
        // $provider = new TurbosmsProvider(
        //     Yii::$app->params['turbosms']['login'],
        //     Yii::$app->params['turbosms']['password'],
        //     'MobService',
        //     $phone,
        //     $text
        // );

        $provider = new EsputnikProvider(
            Yii::$app->params['esputnik']['login'],
            Yii::$app->params['esputnik']['password'],
            'marketing',
            $phone,
            $text
        );

        return $provider->send();
    }
}
