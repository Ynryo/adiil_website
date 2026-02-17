<?php
require_once "src/model/bdd/database.php";

// function insertMedia($url, $date, $id_membre, $id_evenement) {
//     $bd = DB::getInstance();
//     $sql = 'INSERT INTO EVENEMENT (url_media, date_media, id_membre, id_evenement) 
//             VALUES (?, ?, ?, ?)';
//     $params = [$url, $date, $id_membre, $id_evenement];
//     return $bd->query($sql, $params);
// }

function getEvenementMedia($id_evenement, $limit) {
    $bd = DB::getInstance();
    $sql = "SELECT url_media FROM MEDIA WHERE id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $bd->select(
        $sql, 
        "i", 
        [$id_evenement]
        );
}

function getUserEvenementMedia($id_membre, $id_evenement, $limit) {
    $bd = DB::getInstance();
    $sql = "SELECT url_media FROM MEDIA WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $bd->select(
        $sql, 
        "ii", 
        [$id_membre, $id_evenement]
    );
}