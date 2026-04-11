<?php
require_once "src/model/bdd/database.php";

function getAllActualites()
{
    $db = DB::getInstance();
    return $db->select("SELECT id_actualite, titre_actualite, date_actualite FROM ACTUALITE ORDER BY date_actualite DESC");
}

function getActualite($id)
{
    $db = DB::getInstance();
    $result = $db->select(
        "SELECT * FROM ACTUALITE WHERE id_actualite = ?",
        "i",
        [$id]
    );
    return $result[0] ?? null;
}

function getNextActualite($limit)
{
    $db = DB::getInstance();
    $sql = "SELECT id_actualite, titre_actualite, date_actualite FROM ACTUALITE WHERE date_actualite <= NOW() ORDER BY date_actualite ASC" . ($limit != null ? " LIMIT $limit;" : ";");
    return $db->select($sql);
}

function createActualite()
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO ACTUALITE (titre_actualite, contenu_actualite, date_actualite, id_membre) VALUES (?, ?, NOW(), ?)",
        "ssi",
        ["Nouvelle actualité", "", $_SESSION['userid']]
    );
}

function updateActualite($id, $titre, $contenu, $date)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ACTUALITE SET titre_actualite = ?, contenu_actualite = ?, date_actualite = ? WHERE id_actualite = ?",
        "sssi",
        [$titre, $contenu, $date, $id]
    );
}

function updateActualiteImage($id, $imageName)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ACTUALITE SET image_actualite = ? WHERE id_actualite = ?",
        "si",
        [$imageName, $id]
    );
}

function deleteActualite($id)
{
    $db = DB::getInstance();
    $db->query("DELETE FROM ACTUALITE WHERE id_actualite = ?", "i", [$id]);
}