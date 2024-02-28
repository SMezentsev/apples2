<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE') . ';port=' . env('DB_PORT'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'enableSchemaCache' => true,
        'charset' => 'utf8mb4',
        'enableQueryCache' => true,
        'queryCacheDuration' => 60 * 60,
        'attributes'=>[
            PDO::ATTR_PERSISTENT => false
        ]
    ],
];
