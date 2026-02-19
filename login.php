<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\LoginController;

(new LoginController())->handle();