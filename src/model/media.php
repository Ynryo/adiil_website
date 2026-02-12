<?php
require_once "src/model/database.php";

function insertMedia($url, $date, $id_membre, $id_evenement) {
    $sql = 'INSERT INTO EVENEMENT (url_media, date_media, id_membre, id_evenement) 
            VALUES (?, ?, ?, ?)';
    $params = [$url, $date, $id_membre, $id_evenement];
    return insert($sql, $params);
}

function getEvenementMedia($id_evenement, $limit) {
    $sql = "SELECT url_media FROM MEDIA WHERE id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    $params = [$id_evenement];
    return get($sql, $params);
}

function getUserEvenementMedia($id_membre, $id_evenement, $limit) {
    $sql = "SELECT url_media FROM MEDIA WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    $params = [$id_membre, $id_evenement];
    return get($sql, $params);
}