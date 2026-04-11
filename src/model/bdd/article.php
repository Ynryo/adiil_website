<?php
require_once "src/model/bdd/database.php";

function getAllArticles()
{
    $db = DB::getInstance();
    return $db->select("SELECT id_article, nom_article FROM ARTICLE WHERE deleted = FALSE");
}

function getArticle($id)
{
    $db = DB::getInstance();
    $result = $db->select(
        "SELECT * FROM ARTICLE WHERE id_article = ? AND deleted = FALSE",
        "i",
        [$id]
    );
    return $result[0] ?? null;
}

function createArticle()
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO ARTICLE (nom_article, xp_article, stock_article, reduction_article, prix_article, image_article, categorie_article) VALUES (?, ?, ?, ?, ?, ?, ?)",
        "siiidss",
        ["Nouvel article", 1, 0, 1, 1.99, "", "Non défini"]
    );
}

function updateArticle($id, $name, $xp, $stocks, $reduction, $price, $categorie)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ARTICLE SET nom_article = ?, xp_article = ?, stock_article = ?, reduction_article = ?, prix_article = ?, categorie_article = ? WHERE id_article = ?",
        "siiidsi",
        [$name, $xp, $stocks, $reduction, $price, $categorie, $id]
    );
}

function deleteArticle($id)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ARTICLE SET deleted = TRUE WHERE id_article = ?",
        "i",
        [$id]
    );
}

function updateArticleImage($id, $imageName)
{
    $db = DB::getInstance();
    $db->query(
        "UPDATE ARTICLE SET image_article = ? WHERE id_article = ?",
        "si",
        [$imageName, $id]
    );
}