<?php
require_once "src/model/bdd/database.php";

function insertMembre($nom, $prenom, $email, $password) {
    $db = DB::getInstance();
    $db->query(
        "CALL creationCompte (?, ?, ?, ?);",
        "ssss",
        [$nom, $prenom, $email, $password]
    );
}

// function getAllMembres() {
//     $db = DB::getInstance();
//     return $db->select(
//         "SELECT * FROM MEMBRE"
//     );
// }

function getMembre($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM MEMBRE WHERE id_membre = ?",
        "i",
        [$id]
    );
}

function updateMembrePassword($hashedPassword, $id) {
    $db = DB::getInstance();
    $db->query(
        "UPDATE MEMBRE SET password_membre = ? WHERE id_membre = ?",
        "si",
        [$hashedPassword, $id]
    );
}

function updateMembreAccountInfo($name, $lastName, $mail, $tp, $id) {
    $db = DB::getInstance();
    $db->query(
        "UPDATE MEMBRE SET prenom_membre = ?, nom_membre = ?, email_membre = ?, tp_membre = ? WHERE id_membre = ?",
        "ssssi",
        [$name, $lastName, $mail, $tp, $id]
    );
}

function isMailUsedByAnotherMembre($mail, $id) {
    $db = DB::getInstance();
    $membre = $db->select(
        "SELECT id_membre FROM MEMBRE WHERE email_membre = ? AND id_membre != ?",
        "si",
        [$mail, $id]
    );
    return !empty($membre);
}

function getInfoOfmembre($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT pp_membre, xp_membre, prenom_membre, nom_membre, email_membre, tp_membre, discord_token_membre, nom_grade, image_grade FROM MEMBRE LEFT JOIN ADHESION ON MEMBRE.id_membre = ADHESION.id_membre LEFT JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE MEMBRE.id_membre = ?;",
        "i",
        [$id]
    );
}

function getMembreByMail($mail) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM MEMBRE WHERE email_membre = ?",
        "s",
        [$mail]
    );
}

function getPodium() {
    $db = DB::getInstance();
    return $db->select(
        "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;"
    );
}

function getAdherent($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM ADHESION INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE ADHESION.id_membre = ? AND reduction_grade > 0",
        "i",
        [$id]
    );
}

function getNbRolesmembre($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT COUNT(*) as nb_roles FROM ASSIGNATION WHERE id_membre = ? ;",
        "i",
        [$id]
    );
}

function updateMembrePP($fileName, $id) {
    $db = DB::getInstance();
    $db->query(
        "UPDATE MEMBRE SET pp_membre = ? WHERE id_membre = ?",
        "si",
        [$fileName, $id]
    );
}

function getAchatsMembre($id, $limit) {
    $db = DB::getInstance();
    $sql = "
            SELECT type_transaction, element, quantite, montant, mode_paiement, date_transaction, 
            CASE 
            WHEN recupere = 1 THEN 'Récupéré'
            ELSE 'Non récupéré'
            END AS statut 
            FROM HISTORIQUE WHERE id_utilisateur = ? ORDER BY date_transaction DESC
    " . $limit;

    return $db->select(
        $sql,
        "i",
        [$id]
    );
}

function addXpToMembre($xp, $id) {
    $db = DB::getInstance();
    return $db->select(
        "UPDATE MEMBRE m SET m.xp_membre = m.xp_membre + ? where m.id_membre = ?;",
        "ii",
        [$xp, $id]
    );
}

function getDiscount($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT reduction_grade FROM ADHESION 
        JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade
        WHERE id_membre = ? AND reduction_grade > 0 order by ADHESION.date_adhesion DESC LIMIT 1",
        "i",
        [$id]
    );
}

function doesMembreHasGrade($id) {
    $db = DB::getInstance();
    return !empty($db->select(
        "SELECT * FROM ADHESION WHERE id_membre = ?",
        "i",
        [$id]
    ));
}

function deleteGradeOfMembre($id) {
    $db = DB::getInstance();
    $db->query(
        "DELETE FROM ADHESION WHERE id_membre = ?",
        "i",
        [$id]
    );
}

function addGradeToMembre($id_membre, $id_grade, $prix, $mode_paiement) {
    $db = DB::getInstance();
    $db->query(
        "INSERT INTO ADHESION (id_membre, id_grade, prix_adhesion, paiement_adhesion, date_adhesion) VALUES (?, ?, ?, ?, NOW())",
        "iiss",
        [$id_membre, $id_grade, $prix, $mode_paiement]
    );
}