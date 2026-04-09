<?php
require_once "src/model/bdd/database.php";

function insertMedia($url, $date, $id_membre, $id_evenement) {
    $db = DB::getInstance();
    $db->query(
        "INSERT INTO MEDIA VALUES (?, ?, ?, ?);",
        "ssii",
        [$url, $date, $id_membre, $id_evenement]
    );
}

function deleteMedia($id) {
    $db = DB::getInstance();
    return $db->select(
        "DELETE FROM MEDIA WHERE id_media = ?",
        "i",
        [$id]
    );
}

function getMedia($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM MEDIA WHERE id_media = ?",
        "i",
        [$id]
    );
}

function getEvenementMedia($id_evenement, $limit) {
    $db = DB::getInstance();
    $sql = "SELECT url_media FROM MEDIA WHERE id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $db->select(
        $sql, 
        "i", 
        [$id_evenement]
    );
}

function getUserEvenementMedia($id_membre, $id_evenement, $limit) {
    $db = DB::getInstance();
    $sql = "SELECT url_media FROM MEDIA WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $db->select(
        $sql, 
        "ii", 
        [$id_membre, $id_evenement]
    );
}