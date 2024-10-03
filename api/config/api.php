<?php

use app\api\modules\api\ApiModule;
use app\api\modules\api\v1\ApiV1Module;
use yii\web\JsonParser;
use yii\web\JsonResponseFormatter;
use yii\web\Response;

$db = require __DIR__ . '/../../config/db.php';

$config = [
    'id' => 'api',
    'basePath' => dirname(__DIR__) . '/../',
    'bootstrap' => ['log'],
    'container' => require __DIR__ . '/../../config/container.php',
    'controllerNamespace' => 'app\api\controllers',
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
            'enableCookieValidation' => false,
        ],
        'response' => [
            'class' => Response::class,
            'format' => Response::FORMAT_JSON,
            'formatters' => [
                Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG,
                    'keepObjectType' => true, // keep object type for zero-indexed objects
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'api/v1/orders',
                    'route' => 'api/v1/orders/create',
                ],
                [
                    'pattern' => 'api/v1/couriers',
                    'route' => 'api/v1/couriers/all',
                    'suffix' => '/',
                ],
                [
                    'pattern' => 'api/v1/orders',
                    'route' => 'api/v1/orders/create',
                    'suffix' => '/',
                ],
            ],
        ],
    ],
    'modules' => [
        'api' => [
            'class' => ApiModule::class,
            'modules' => [
                'v1' => [
                    'class' => ApiV1Module::class,
                ]
            ],
        ]
    ],
    'params' => [],
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
        'allowedIPs' => ['*'],
    ];
}

return $config;
