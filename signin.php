<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controllers\SigninController;

(new SigninController())->handle();