<?php
require_once "src/model/bdd/database.php";

// function insertEvenement($nom, $xp, $places, $prix, $reductions, $lieu, $date) {
//     $bd = DB::getInstance();
//     $sql = 'INSERT INTO EVENEMENT (nom,_evenement xp, _evenementplac_evenementes, prix_evenement, redu_evenementctions, lieu_evenement, date_evenement) 
//             VALUES (?, ?, ?, ?, ?, ?, ?)';
//     $params = [$nom, $xp, $places, $prix, $reductions, $lieu, $date];
//     return $bd->query($sql, $params);
// }

// function getAllEvenements() {
//     $bd = DB::getInstance();
//     $sql = "SELECT * FROM EVENEMENT";
//     return $bd->select($sql);
// }

function getEvenement($id) {
    $bd = DB::getInstance();
    return $bd->select(
        "SELECT * FROM EVENEMENT WHERE id_evenement = ?",
        "i",
        [$id]
    );
}

function getNextEvenement($date, $limit) {
    $bd = DB::getInstance();
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? AND deleted = false ORDER BY date_evenement ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $bd->select(
        $sql,
        "s",
        [$date]
    );
}

function getPastEvenement($date, $limit) {
    $bd = DB::getInstance();
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < ? AND deleted = false ORDER BY date_evenement DESC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $bd->select(
        $sql,
        "s",
        [$date]
    );
}

function isPlaceDisponible($id) {
    $bd = DB::getInstance();
    return $bd->select(
        "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;",
        "i",
        [$id]
    )[0]['isPlaceDisponible'];
}

function isSubscribed($id_membre, $id_evenement) {
    $bd = DB::getInstance();
    return !empty($bd->select(
        "SELECT m.id_membre FROM MEMBRE m JOIN INSCRIPTION i on m.id_membre = i.id_membre WHERE m.id_membre = ? AND i.id_evenement = ?;", 
        "ii", 
        [$id_membre, $id_evenement]
    ));
}