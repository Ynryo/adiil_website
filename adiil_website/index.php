<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\HomeController;

(new HomeController())->handle();