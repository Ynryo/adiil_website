<?php
require_once __DIR__ . '/bootstrap.php';

use App\Helpers\FileSave;
use App\Database\DB;
use App\Helpers\Session;
use App\Helpers\Csrf;

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['mediaid'], $_POST['eventid'])) {
    header("Location: /index.php");
    exit();
}

Csrf::check();

if (!Session::isLoggedIn()) {
    header("Location: /login.php");
    exit();
}

$mediaid = (int) $_POST['mediaid'];
$eventid = (int) $_POST['eventid'];

$db = DB::getInstance();

// Vérifier que le média appartient à l'utilisateur courant
$media = $db->select(
    "SELECT url_media FROM MEDIA WHERE id_media = ? AND id_evenement = ? AND id_membre = ?",
    "iii",
    [$mediaid, $eventid, Session::getUserId()]
);

if (!empty($media)) {
    $fileName = $media[0]['url_media'];
    if (deleteFile($fileName)) {
        $db->query(
            "DELETE FROM MEDIA WHERE id_media = ? AND id_evenement = ?",
            "ii",
            [$mediaid, $eventid]
        );
    }
}

header("Location: /my_gallery.php?eventid=" . $eventid);
exit();