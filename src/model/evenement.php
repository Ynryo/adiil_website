<?php
require_once "src/model/database.php";

function insertEvenement($nom, $xp, $places, $prix, $reductions, $lieu, $date) {
    $sql = 'INSERT INTO EVENEMENT (nom,_evenement xp, _evenementplac_evenementes, prix_evenement, redu_evenementctions, lieu_evenement, date_evenement) 
            VALUES (?, ?, ?, ?, ?, ?, ?)';
    $params = [$nom, $xp, $places, $prix, $reductions, $lieu, $date];
    return insert($sql, $params);
}

function getAllEvenements() {
    $sql = "SELECT * FROM EVENEMENT";
    return get($sql);
}

function getEvenement($id) {
    $sql = "SELECT * FROM EVENEMENT WHERE id_evenement = ?";
    $params = [$id];
    return get($sql, $params)[0];
}

function getNextEvenement($date, $limit) {
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? AND deleted = false ORDER BY date_evenement ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    $params = [$date];
    return get($sql, $params);
}

function getPastEvenement($date, $limit) {
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < ? AND deleted = false ORDER BY date_evenement DESC" . ($limit != null ? " LIMIT $limit;" : ";");
    $params = [$date];
    return get($sql, $params);
}

function isPlaceDisponible($id) {
    $sql = "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;";
    $params = [$id];
    return get($sql, $params)[0]['isPlaceDisponible'];
}

function isSubscribed($id_membre, $id_evenement) {
    $sql = "SELECT m.id_membre FROM MEMBRE m JOIN INSCRIPTION i on m.id_membre = i.id_membre WHERE m.id_membre = ? AND i.id_evenement = ?;";
    $params = [$id_membre, $id_evenement];
    return !empty(get($sql, $params));
}