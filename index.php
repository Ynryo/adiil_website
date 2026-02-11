<?php
session_start();

define('ROOT_PATH', __DIR__ . '/');

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

function dispatch() {

    $page = $_GET['page'] ?? 'base-home';

    $parts = explode('/', $page);

    $dir = explode('-', $parts[0]);

    $dirName = $dir[0];
    $controllerName = $dir[1]."Controller";
    $className = $dir[1];

    $method = $parts[1] ?? 'show';

    $controllerFile = "src/controller/$dirName/$controllerName.php";

    if (!file_exists($controllerFile)) {
        die("Controller $controllerName not found.");
    }

    require_once $controllerFile;

    $controller = new $className();

    if (!method_exists($controller, $method)) {
        die("Method $method not found in $controllerName.");
    }

    $controller->$method();
}

dispatch();