<?php
require_once __DIR__ . '/bootstrap.php';

use App\Helpers\FileSave;
use App\Database\DB;
use App\Helpers\Session;
use App\Helpers\Csrf;

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'], $_POST['eventid'])) {
    header("Location: /index.php");
    exit();
}

Csrf::check();

// Sécurité : utiliser l'ID utilisateur de la session, pas du formulaire
if (!Session::isLoggedIn()) {
    header("Location: /login.php");
    exit();
}

$userid = Session::getUserId();
$eventid = (int) $_POST["eventid"];

$db = DB::getInstance();
$fileName = FileSave::saveImage();

$date = new DateTime();
$sqlDate = $date->format('Y-m-d H:i:s');

if ($fileName !== null) {
    $db->query(
        "INSERT INTO MEDIA VALUES (NULL, ?, ?, ?, ?);",
        "ssii",
        [$fileName, $sqlDate, $userid, $eventid]
    );
}

header("Location: /my_gallery.php?eventid=" . $eventid);
exit();