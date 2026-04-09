<?php
require_once "src/model/bdd/database.php";

function getAllEvenements()
{
    $db = DB::getInstance();
    return $db->select("SELECT id_evenement, nom_evenement FROM EVENEMENT WHERE deleted = FALSE ORDER BY date_debut_evenement DESC");
}

function getEvenement($id)
{
    $db = DB::getInstance();
    $result = $db->select(
        "SELECT * FROM EVENEMENT WHERE id_evenement = ? AND deleted = FALSE",
        "i",
        [$id]
    );
    return $result[0] ?? null;
}

function createEvenement()
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO EVENEMENT (nom_evenement, xp_evenement, places_evenement, prix_evenement, reductions_evenement, lieu_evenement, date_debut_evenement, image_evenement, description_evenement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "siiidssss",
        ["Nouvel événement", 10, 0, 0, 1, "Lieu de l'événement", date('Y-m-d'), NULL, "Description de l'événement"]
    );
}

function updateEvenement($id, $nom, $description, $xp, $places, $prix, $lieu, $date, $reductions)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE EVENEMENT SET nom_evenement = ?, description_evenement = ?, xp_evenement = ?, places_evenement = ?, prix_evenement = ?, lieu_evenement = ?, date_debut_evenement = ?, reductions_evenement = ? WHERE id_evenement = ?",
        "ssiidssii",
        [$nom, $description, $xp, $places, $prix, $lieu, $date, $reductions, $id]
    );
}

function deleteEvenement($id)
{
    $db = DB::getInstance();
    $db->query("UPDATE EVENEMENT SET deleted = TRUE WHERE id_evenement = ?", "i", [$id]);
}

function updateEvenementImage($id, $imageName)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE EVENEMENT SET image_evenement = ? WHERE id_evenement = ?",
        "si",
        [$imageName, $id]
    );
}

function getNextEvenement($date, $limit)
{
    $db = DB::getInstance();
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_debut_evenement FROM EVENEMENT WHERE date_debut_evenement >= ? AND deleted = false ORDER BY date_debut_evenement ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $db->select(
        $sql,
        "s",
        [$date]
    );
}

function getPastEvenement($date, $limit)
{
    $db = DB::getInstance();
    $sql = "SELECT id_evenement, nom_evenement, lieu_evenement, date_debut_evenement FROM EVENEMENT WHERE date_debut_evenement < ? AND deleted = false ORDER BY date_debut_evenement DESC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $db->select(
        $sql,
        "s",
        [$date]
    );
}

function isPlaceDisponible($id)
{
    $db = DB::getInstance();
    return $db->select(
        "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;",
        "i",
        [$id]
    )[0]['isPlaceDisponible'];
}

function isSubscribed($id_membre, $id_evenement)
{
    $db = DB::getInstance();
    return !empty($db->select(
        "SELECT m.id_membre FROM MEMBRE m JOIN INSCRIPTION i on m.id_membre = i.id_membre WHERE m.id_membre = ? AND i.id_evenement = ?;",
        "ii",
        [$id_membre, $id_evenement]
    ));
}

function eventWhereMembreIsSubscribed($id_membre)
{
    $db = DB::getInstance();
    return $db->select(
        "SELECT id_evenement FROM INSCRIPTION WHERE id_membre = ?;",
        "i",
        [$id_membre]
    );
}

function subscribeMembreToEvenement($id_membre, $id_evenement, $prix)
{
    $db = DB::getInstance();
    $db->query(
        "INSERT INTO `INSCRIPTION` (`id_membre`, `id_evenement`, `date_inscription`, `paiement_inscription`, `prix_inscription`)
        VALUES (?, ?, NOW(), 'WEB', ?);",
        "iid",
        [$id_membre, $id_evenement, $prix]
    );
}