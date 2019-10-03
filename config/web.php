<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Teratai Ordering System',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'OyBRlR2M3HY3y8kTb-l2DAWclRh87pl4',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],/*
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],*/
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mailtrap.io',
                'username' => 'b1c19d603df392',
                'password' => 'c89cd929097f53',
                'port' => '2525',
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','trace'],
                ],
            ],
        ],
        'db' => $db,
        
        'urlManager' => [
			'class' => 'yii\web\UrlManager',
		   // Disable index.php
		   'showScriptName' => false,
		   // Disable r= routes
		   'enablePrettyUrl' => true,
		   'rules' => [
			  '<controller:\w+>/<id:\d+>' => '<controller>/view',
			  '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
              '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
              [
                  'class' => 'yii\rest\UrlRule',
                'controller' => 'order'
                ]
           ],
		],
		'view' => [
			'theme' => [
				'pathMap' => [
					'@Da/User/resources/views' => '@app/views/user'
				],
			],
		
        ],
        'config' => [
            'class'         => 'app\components\Config', // Class (Required)
            'db'            => 'db',                                 // Database Connection ID (Optional)
            'tableName'     => '{{%config}}',                        // Table Name (Optioanl)
            'cacheId'       => 'cache',                              // Cache Id. Defaults to NULL (Optional)
            'cacheKey'      => 'config.cache',                       // Key identifying the cache value (Required only if cacheId is set)
            'cacheDuration' => 0                                   // Cache Expiration time in seconds. 0 means never expire. Defaults to 0 (Optional)
        ],
            /*
		'authManager' => [
		'class' => 'yii\rbac\DbManager',
		],
            */
            
            
        
    ],
    'modules' => [
		/*'user' => [
			'class' => 'dektrium\user\Module',
			'admins' => ['matle'],
			 'modelMap' => [
				'Profile' => 'app\models\Profile',
			],
			
		],*/
		'user' => [
			 'class' => Da\User\Module::class,
			 'administrators' => ['matle'],
			 'classMap' => [
				'Profile' => app\models\Profile::class,
			],
		],
		   'gridview' =>  [
        'class' => '\kartik\grid\Module'
        ],
		 
	
    ],
    /*
    'as access' => [

        'class' => \yii\filters\AccessControl::className(),//AccessControl::className(),

        'rules' => [

            [

                'actions' => ['login', 'error'],

                'allow' => true,

            ],

            [

                'actions' => ['logout', 'index'], // add all actions to take guest to login page

                'allow' => true,

                'roles' => ['@'],

            ],

        ],

    ],*/
	
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
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
