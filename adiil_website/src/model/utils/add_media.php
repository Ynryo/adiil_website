<?php

require_once 'src/model/bdd/media.php';
require_once 'src/model/utils/files_save.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'], $_POST['userid'], $_POST['eventid'])) {
    $fileName = saveImage();
    
    $date = new DateTime();
    $sqlDate = $date->format('Y-m-d H:i:s');

    if ($fileName !== null) {
        // Met à jour la base de données avec le nom du fichier
        insertMedia($fileName, $sqlDate ,$_POST["userid"], $_POST["eventid"]);
    }

    // Recharge la page pour afficher la nouvelle image
    header("Location: /?page=base-my_gallery&eventid=".$_POST["eventid"]);
    exit();

} else{
    header("Location: /?page=base-home");
    exit();
}