<?php
require_once "src/model/bdd/database.php";

function getLogs()
{
    $logFile = __DIR__ . '/../../../logs/app.log';

    if (!file_exists($logFile)) {
        return '';
    }

    return file_get_contents($logFile);
}