<?php

$common = require __DIR__ . '/common.php';

return yii\helpers\ArrayHelper::merge($common, [
    'controllerNamespace' => 'app\\controllers',
    'components' => [
        'request' => [
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY') ?: 'loan-api-cookie-validation-key',
            'parsers' => [
                'application/json' => yii\web\JsonParser::class,
            ],
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST requests' => 'requests/create',
                'GET processor' => 'processor/index',
            ],
        ],
        'errorHandler' => [
            'errorAction' => null,
        ],
    ],
]);
