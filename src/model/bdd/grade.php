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