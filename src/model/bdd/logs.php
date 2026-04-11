<?php
require_once "src/model/bdd/database.php";

function getLogs(): string
{
    $candidates = [
        ini_get('error_log'),
        '/var/log/apache2/error.log',
        '/var/log/apache2/access.log',
        '/var/log/nginx/error.log',
        '/var/log/nginx/access.log',
        __DIR__ . '/../../../logs/app.log',
    ];

    foreach ($candidates as $path) {
        if ($path && file_exists($path) && is_readable($path)) {
            $content = file_get_contents($path);
            if ($content === false) {
                continue;
            }
            $lines = array_filter(explode("\n", $content));
            $lines = array_slice($lines, -200);
            return implode("\n", $lines);
        }
    }

    return 'Aucun fichier de log accessible.';
}