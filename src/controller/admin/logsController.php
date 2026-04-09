<?php

require_once 'src/model/bdd/logs.php';

class LogsController
{
    public function show()
    {
        $logs = getLogs();
        include_once 'src/view/admin/panels/logs.php';
    }
}