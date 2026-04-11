<?php
require_once "src/model/bdd/database.php";

function getAllComptabilite()
{
    $db = DB::getInstance();
    return $db->select("SELECT * FROM COMPTABILITE ORDER BY date_comptabilite DESC");
}

function getComptabilite($id)
{
    $db = DB::getInstance();
    $result = $db->select(
        "SELECT * FROM COMPTABILITE WHERE id_comptabilite = ?",
        "i",
        [$id]
    );
    return $result[0] ?? null;
}

function createComptabilite($nom, $date, $url, $id_membre)
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO COMPTABILITE (nom_comptabilite, date_comptabilite, url_comptabilite, id_membre) VALUES (?, ?, ?, ?)",
        "sssi",
        [$nom, $date, $url, $id_membre]
    );
}

function deleteComptabilite($id)
{
    $db = DB::getInstance();
    $compta = getComptabilite($id);
    if ($compta && !empty($compta['url_comptabilite'])) {
        require_once 'src/model/utils/files_save.php';
        deleteFile($compta['url_comptabilite']);
    }
    $db->query("DELETE FROM COMPTABILITE WHERE id_comptabilite = ?", "i", [$id]);
}