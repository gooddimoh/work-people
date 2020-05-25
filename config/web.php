<?php
use kartik\datecontrol\Module;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru', // unused, default language get form user agent
    'name' => 'WWS.Work',
    'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'registration' => [
            'class' => 'app\modules\registration\Module',
        ],
        'userpanel' => [
            'class' => 'app\modules\userpanel\Module',
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
     
            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'dd-MM-yyyy',
                Module::FORMAT_TIME => 'hh:mm:ss a',
                Module::FORMAT_DATETIME => 'dd-MM-yyyy hh:mm:ss a', 
            ],
            
            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],
     
            // set your display timezone
            'displayTimezone' => 'Europe/Moscow',
     
            // set your timezone for date saved to db
            'saveTimezone' => 'UTC',
            
            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,
     
            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type'=>2, 'pluginOptions'=>['autoclose'=>true]], // example
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],
            
            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                Module::FORMAT_DATE => [
                    'class' => 'yii\jui\DatePicker', // example
                    'options' => [
                        'dateFormat' => 'php:d-M-Y',
                        'options' => ['class'=>'form-control'],
                    ]
                ]
            ]
            // other settings
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'vWwdsSgeEVdZViuHBzpkPrswxp0LllsE',
            'parsers' => [
				'application/json' => 'yii\web\JsonParser',
            ],
            'csrfCookie' => ['httpOnly' => true],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_MailTransport',
            ],
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'class' => 'app\components\I18nUrlManager',
            'languages' => ['en', 'ru', 'ua'],
            'rules' => [
                [ // RESTful
					'class' => 'yii\rest\UrlRule',
					'controller' => [
                        'country' => 'apicountry',
                        'vacancy' => 'apivacancy',
					],
					'prefix' => 'api/',
                ],

                // '<controller:\w+>'=>'<controller>/index', // 'enableStrictParsing' => false,
                '/'=>'site/index', // 'enableStrictParsing' => true,
                // 'site/pages/<view:\w+>' => 'site/pages',
                'vacancy/<id:\d+>' => '/vacancy/view',
                'vacancy/add-to-cart/<id:\d+>' => '/vacancy/add-to-cart',
                'jobs' => '/vacancy/index',
                'workers' => '/resume/index',
                'companies' => '/company/index',
                'company-reviews' => '/company-review/index',
                'review-company' => '/company-review/viewcompany',
                'safe-deal' => '/site/safe-deal',
                'registration'     => 'registration/default/index',
                'registration/confirm-mail'     => 'registration/default/confirm-mail',
                'reset-password'     => 'registration/default/reset-password',
                'password-reset-token'     => 'registration/default/password-reset-token',
                'register-company-by-email'=> 'registration/default/register-company-by-email',
                'userpanel'     => 'userpanel/default/index',
                // 'userpanel/account-edit'     => 'userpanel/profile/accountedit',
                // 'profile/create'     => 'userpanel/profile/create',
                'userpanel/likes-candidate' => 'userpanel/resume/favorite',
                'userpanel/likes' => 'userpanel/vacancy/favorite',
                'userpanel/resume/add-favorite' => 'userpanel/resume/addfavorite',
                'userpanel/resume/remove-favorite' => 'userpanel/resume/removefavorite',
                'userpanel/vacancy/add-favorite' => 'userpanel/vacancy/addfavorite',
                'userpanel/vacancy/remove-favorite' => 'userpanel/vacancy/removefavorite',
                'admin'     => 'admin/default/index',

                // '<action>'=>'site/<action>',
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<controller>/<action>' => '<controller>/<action>',
                
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>' => '<module>/<controller>/index',

                // fix safe deal module controller:
                '<module:\w+>/safe-deal/<action:\w+>/<id:\d+>' => '<module>/safe-deal/<action>',
                '<module:\w+>/safe-deal/<action:\w+>' => '<module>/safe-deal/<action>',
                '<module:\w+>/safe-deal' => '<module>/safe-deal/index',

                // fix vacancy-respond module controller:
                '<module:\w+>/vacancy-respond/<action:\w+>/<id:\d+>' => '<module>/vacancy-respond/<action>',
                '<module:\w+>/vacancy-respond/<action:\w+>' => '<module>/vacancy-respond/<action>',
                '<module:\w+>/vacancy-respond' => '<module>/vacancy-respond/index',
                '<module:\w+>/vacancy-respond/create-resume/<id:\d+>' => '<module>/vacancy-respond/create-resume',
                '<module:\w+>/vacancy-respond/view-resume/<id:\d+>' => '<module>/vacancy-respond/view-resume',

                // fix auto-mail module controller:
                '<module:\w+>/auto-mail/<action:\w+>/<id:\d+>' => '<module>/auto-mail/<action>',
                '<module:\w+>/auto-mail/<action:\w+>' => '<module>/auto-mail/<action>',
                '<module:\w+>/auto-mail' => '<module>/auto-mail/index',

                // fix company-review module controller:
                '<module:\w+>/company-review/<action:\w+>/<id:\d+>' => '<module>/company-review/<action>',
                '<module:\w+>/company-review/<action:\w+>' => '<module>/company-review/<action>',
                '<module:\w+>/company-review' => '<module>/company-review/index',
                
                // fix country-city module controller:
                '<module:\w+>/country-city/<action:\w+>/<id:\d+>' => '<module>/country-city/<action>',
                '<module:\w+>/country-city/<action:\w+>' => '<module>/country-city/<action>',
                '<module:\w+>/country-city' => '<module>/country-city/index',

                // fix category-job module controller:
                '<module:\w+>/category-job/<action:\w+>/<id:\d+>' => '<module>/category-job/<action>',
                '<module:\w+>/category-job/<action:\w+>' => '<module>/category-job/<action>',
                '<module:\w+>/category-job' => '<module>/category-job/index',

                '<module:\w+>/invoice/choice-payment-system' => '<module>/invoice/choice-payment-system',
                '<module:\w+>/invoice/create-invoice-platon-online' => '<module>/invoice/create-invoice-platon-online',
                '<module:\w+>/invoice/invoice-view' => '<module>/invoice/invoice-view',
                '<module:\w+>/invoice/invoice-paid' => '<module>/invoice/invoice-paid',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => $params['dateFormatYii'],
            'datetimeFormat' => 'php:d.m.Y H:i:s',
            'timeFormat' => 'php:H:i:s',
        ],
        'assetManager' => [ // disable all standard libs
			'bundles' => [
				'yii\web\JqueryAsset' => [
					// 'sourcePath' => 'js/libs',
					'js' => []
				],
				'yii\bootstrap\BootstrapAsset' => [ 'css' => [] ],
                'yii\bootstrap\BootstrapPluginAsset' => [ 'js'=>[] ],
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
			],
        ],
        'i18n' => [
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					'sourceLanguage' => 'en-US',
					'fileMap' => [
						// 'vacancy' => 'vacancy.php',
						'main' => 'main.php',
						// 'app' => 'app.php',
						// 'app/error' => 'error.php',
					],
				],
			],
		],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '6LfXM6wUAAAAANwEetL9kmhhmnlqALzTMa8weqs4', // generate your own key in google services
            'secret' => '6LfXM6wUAAAAAAvX5-khJJbUuq84oZOxJswdHL7x',
        ],
        'view' => [
            'class'=>'app\components\ClientScriptView',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.*'],
    ];
}

return $config;
