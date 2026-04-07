<?php

return [
    'db' => [
        'host' => 'db',
        'port' => '3306',
        'database' => 'adiil',
        'username' => 'root',
        'password' => trim(file_get_contents('/run/secrets/db_root_password')),
        'charset' => 'utf8mb4',
    ],
];
