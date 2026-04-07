<?php
require_once "src/model/bdd/database.php";

// function getAllArticles() {
//     $bd = getDB();
//     $sql = "SELECT * FROM ARTICLE";
//     return $bd->select($sql);
// }

function getArticle($id) {
    $db = DB::getInstance();
    return $db->select(
        "SELECT * FROM ARTICLE WHERE id_article = ?",
        "i",
        [$id]
    );
}