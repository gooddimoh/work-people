<?php

return [
    'adminEmail' => 'admin@wws.work',
    'senderEmail' => 'noreply@wws.work',
    'senderName' => 'wws.work mailer',
    'dateFormat' => 'dd.mm.yyyy', // widget date format
    'dateFormatYii' => 'dd.MM.yyyy', // php date format
    'dateInputMask' => '99.99.9999', // widgets date input mask
    'sourceCurrencyCharCode' => 'UAH', //! important, if change need recalculate all cache fields in tables
    'defaultCurrencyCharCode' => 'USD', // default selected in all select lists
    'messangers' => [
        'telegram' => 'https://t.me/workpeople_bot',
        'messenger' => 'https://m.me/103482897711391',
        'viber' => 'https://pipe.bot/viber/5755'
    ],
    'user.passwordResetTokenExpire' => 2 * 7 * 24 * 60 * 60,
    'expire_pay_time' => 60*60*24*7, // in seconds, - default 7 days
    'payment_platononline' => [
        'merchant_id' => 'J4Z41SYZFD',
        'password' => 'GtyeJxwYW38MBjADTJBnLBRVEF0AsQ3L',
    ],
    'sms_fly' => [
        'login' => '380682614470',
        'password' => 'maksim1511'
    ],
    'turbosms' => [
        'login' => 'workpeople',
        'password' => '9BuQ45w00X1jdhMCw7'
    ],
    'esputnik' => [
        'login' => 'andriy.rodiuk@gmail.com',
        'password' => 'hSt8WR97jY!4LhA',
    ],
    'smsSendDelay' => '40', // seconds
];
