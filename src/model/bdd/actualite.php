<?php
require_once "src/model/bdd/database.php";

// function insertActualite($url, $date, $id_membre, $id_evenement) {
//     $sql = 'INSERT INTO EVENEMENT (url_media, date_media, id_membre, id_evenement) 
//             VALUES (?, ?, ?, ?)';
//     $params = [$url, $date, $id_membre, $id_evenement];
//     return insert($sql, $params);
// }

function getActualite($id_actualite)
{
    $bd = DB::getInstance();
    return $bd->select(
        "SELECT * FROM ACTUALITE WHERE id_actualite = ?",
        "i",
        [$id_actualite]
    );
}

function getNextActualite($limit)
{
    $bd = DB::getInstance();
    $sql = "SELECT id_actualite, titre_actualite, date_actualite FROM ACTUALITE WHERE date_actualite <= NOW() ORDER BY date_actualite ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $bd->select(
        $sql
    );
}