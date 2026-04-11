<?php
require_once "src/model/bdd/database.php";

function getAllReunions()
{
    $db = DB::getInstance();
    return $db->select("SELECT id_reunion, date_reunion FROM REUNION ORDER BY date_reunion DESC");
}

function getReunion($id)
{
    $db = DB::getInstance();
    $result = $db->select(
        "SELECT * FROM REUNION WHERE id_reunion = ?",
        "i",
        [$id]
    );
    return $result[0] ?? null;
}

function createReunion($date, $fichier, $id_membre)
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO REUNION (date_reunion, fichier_reunion, id_membre) VALUES (?, ?, ?)",
        "ssi",
        [$date, $fichier, $id_membre]
    );
}

function deleteReunion($id)
{
    $db = DB::getInstance();
    $reunion = getReunion($id);
    if ($reunion && !empty($reunion['fichier_reunion'])) {
        require_once 'src/model/utils/files_save.php';
        deleteFile($reunion['fichier_reunion']);
    }
    $db->query("DELETE FROM REUNION WHERE id_reunion = ?", "i", [$id]);
}