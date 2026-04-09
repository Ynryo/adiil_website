<?php

require_once 'src/model/bdd/media.php';
require_once 'src/model/utils/files_save.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mediaid'], $_POST['eventid'])) {
    $fileName = getMedia($_POST['mediaid'])[0]['url_media'];

    if (deleteFile($fileName)) {
        // Met à jour la base de données avec le nom du fichier
        deleteMedia($_POST['mediaid']);
    }

    // Recharge la page pour afficher la nouvelle image
    header("Location: /?page=base-myGallery&eventid=" . $_POST["eventid"]);
    exit();
} else {
    header("Location: /?page=base-home");
    exit();
}