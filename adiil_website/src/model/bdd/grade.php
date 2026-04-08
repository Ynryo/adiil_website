<?php
require_once "src/model/bdd/database.php";

function getAllGrades() {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM GRADE WHERE deleted = false ORDER BY prix_grade"
    );
}

function getGrade($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM GRADE WHERE id_grade = ?",
        "i",
        [$id]
    );
}

function getGradeMembre($id_grade, $id_membre) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM GRADE INNER JOIN ADHESION ON GRADE.id_grade = ADHESION.id_grade INNER JOIN MEMBRE ON ADHESION.id_membre = MEMBRE.id_membre WHERE GRADE.id_grade = ? AND MEMBRE.id_membre = ?;", 
        "ii",
        [$id_grade, $id_membre]
    );
}

function updateGrade($id, $nom, $description, $prix, $reduction) {
    $db = DB::getInstance();
    $db->query(
        "UPDATE GRADE SET nom_grade = ?, description_grade = ?, prix_grade = ?, reduction_grade = ? WHERE id_grade = ?",
        "ssdii",
        [$nom, $description, $prix, (int)$reduction, $id]
    );
}

function createGrade() {
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO GRADE (nom_grade, description_grade, prix_grade, reduction_grade, image_grade) VALUES ('Nouveau grade', 'Ceci est un nouveau grade', 10.99, 0, NULL)",
        "",
        []
    );
}

function updateGradeImage($id, $imageName) {
    $db = DB::getInstance();
    $db->query(
        "UPDATE GRADE SET image_grade = ? WHERE id_grade = ?",
        "si",
        [$imageName, $id]
    );
}

function deleteGrade($id) {
    $db = DB::getInstance();
    $db->query("UPDATE GRADE SET deleted = TRUE WHERE id_grade = ?", "i", [$id]);
}