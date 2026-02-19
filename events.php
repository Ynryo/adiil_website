<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\EventController;

(new EventController())->handle();