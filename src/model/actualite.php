<?php
require_once "src/model/database.php";

// function insertActualite($url, $date, $id_membre, $id_evenement) {
//     $sql = 'INSERT INTO EVENEMENT (url_media, date_media, id_membre, id_evenement) 
//             VALUES (?, ?, ?, ?)';
//     $params = [$url, $date, $id_membre, $id_evenement];
//     return insert($sql, $params);
// }

function getActualite($id_actualite) {
    $sql = "SELECT * FROM ACTUALITE WHERE id_actualite = ?";
    $params = [$id_actualite];
    return get($sql, $params)[0];
}

function getNextActualite($limit) {
    $sql = "SELECT id_actualite, titre_actualite, date_actualite FROM ACTUALITE WHERE date_actualite <= NOW() ORDER BY date_actualite ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return get($sql);
}