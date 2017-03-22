<?php

return [
    'db' => [
        'driver' => 'Pdo_Mysql',
        'database'  => 'docker',
        'username'  => 'docker',
        'password'  => 'docker',
        'hostname'  => '172.20.0.2',
        'port'      => '3306',
        'driver_options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ]
    ],
];
