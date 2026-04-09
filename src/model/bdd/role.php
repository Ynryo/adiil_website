<?php
require_once "src/model/bdd/database.php";

function getAllRoles()
{
    $db = DB::getInstance();
    return $db->select("SELECT id_role, nom_role FROM ROLE ORDER BY nom_role");
}

function getRole($id)
{
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM ROLE WHERE id_role = ?",
        "i",
        [$id]
    );
}

function createRole()
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO ROLE (nom_role, p_log_role, p_boutique_role, p_reunion_role, p_utilisateur_role, p_grade_role, p_roles_role, p_actualite_role, p_evenements_role, p_comptabilite_role, p_achats_role, p_moderation_role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        "siiiiiiiiiii",
        ["Nouveau role", 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
    );
}

function updateRole($id, $nom, $p_log, $p_boutique, $p_reunion, $p_utilisateur, $p_grade, $p_role, $p_actualite, $p_evenement, $p_comptabilite, $p_achat, $p_moderation)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ROLE SET nom_role = ?, p_log_role = ?, p_boutique_role = ?, p_reunion_role = ?, p_utilisateur_role = ?, p_grade_role = ?, p_roles_role = ?, p_actualite_role = ?, p_evenements_role = ?, p_comptabilite_role = ?, p_achats_role = ?, p_moderation_role = ? WHERE id_role = ?",
        "siiiiiiiiiiii",
        [$nom, $p_log, $p_boutique, $p_reunion, $p_utilisateur, $p_grade, $p_role, $p_actualite, $p_evenement, $p_comptabilite, $p_achat, $p_moderation, $id]
    );
}

function deleteRole($id)
{
    $db = DB::getInstance();
    $db->query("DELETE FROM ASSIGNATION WHERE id_role = ?", "i", [$id]);
    $db->query("DELETE FROM ROLE WHERE id_role = ?", "i", [$id]);
}