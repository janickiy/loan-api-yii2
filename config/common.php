<?php

return [
    'id' => 'loan-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [],
    'aliases' => [
        '@runtime' => dirname(__DIR__) . '/runtime',
        '@webroot' => dirname(__DIR__) . '/web',
        '@web' => '/',
    ],
    'components' => [
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_PORT') ?: '5432',
                getenv('DB_NAME') ?: 'loans'
            ),
            'username' => getenv('DB_USER') ?: 'user',
            'password' => getenv('DB_PASSWORD') ?: 'password',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [],
    ],
];
