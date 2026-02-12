<?php
require_once "src/model/database.php";

function insertMembre($nom, $prenom, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = 'INSERT INTO MEMBRE (nom_membre, prenom_membre, email_membre, password_membre, xp_membre, discord_token_membre, pp_membre, tp_membre) 
            VALUES (?, ?, ?, ?, 0, NULL, NULL, NULL)';
    $params = [$nom, $prenom, $email, $hashedPassword];
    return insert($sql, $params);
}

function getAllMembres() {
    $sql = "SELECT * FROM MEMBRE";
    return get($sql);
}

function getMembre($id) {
    $sql = "SELECT * FROM MEMBRE WHERE id_membre = ?";
    $params = [$id];
    return get($sql, $params)[0];
}

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
    $sql = "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;";
    return get($sql);
}