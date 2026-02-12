<?php
require_once "src/model/bdd/database.php";

// function insertMembre($nom, $prenom, $email, $password) {
//     $bd = DB::getInstance();
//     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//     $sql = 'INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, password_membre, xp_membre, discord_token_membre, pp_membre, tp_membre) 
//             VALUES (?, ?, ?, ?, 0, NULL, NULL, NULL)';
//     $params = [$nom, $prenom, $email, $hashedPassword];
//     return $bd->query($sql, $params);
// }

// function getAllMembres() {
//     $bd = DB::getInstance();
//     $sql = "SELECT * FROM MEMBRE";
//     return $bd->select($sql);
// }

// function getMembre($id) {
//     $bd = DB::getInstance();
//     $sql = "SELECT * FROM MEMBRE WHERE id_membre = ?";
//     $params = [$id];
//     return $bd->select($sql, $params);
// }

// function updateMembrePassword($id, $password) {
//     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//     $sql = "UPDATE MEMBRE SET mdp_membre = ? WHERE id_membre = ?";
//     $params = [$hashedPassword, $id];
//     return update($sql, $params);;
// }

// function isMembreExisting($mail){
//     $sql = "SELECT COUNT(*) FROM MEMBRE WHERE email_membre LIKE '$mail'";
//     return getCount($sql) > 0;
// }

// function isMembrePasswordCorrect($mail, $password){
//     if(!isMembreExisting($mail)) return false;

//     $sql = "SELECT * FROM MEMBRE WHERE email_membre LIKE '$mail'";
//     $Membre = get($sql);
//     return password_verify($password, $Membre[0]['mdp_membre']);
// }

function getPodium() {
    $bd = DB::getInstance();
    $sql = "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;";
    return $bd->select($sql);
}

function getAdherent($id) {
    $bd = DB::getInstance();
    $sql = "SELECT * FROM ADHESION INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE ADHESION.id_membre = ? AND reduction_grade > 0";
    $params = [$id];
    return $bd->select($sql, "i", $params);
}