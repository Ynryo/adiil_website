<?php

require_once 'src/model/bdd/database.php';
require_once 'src/model/bdd/membre.php';
require_once 'src/model/utils/files_save.php';
require_once 'src/model/utils/cart_class.php';

class cart
{
    public function show()
    {
        $db = DB::getInstance();
        $cart = cart_class::getInstance();

        // On récupère les produits du panier
        $ids = array_keys($_SESSION['cart']);
        if (empty($ids)) {
            $products = [];
        } else {
            //Préparation de la requete SELECT
            $placeholders = implode(",", array_fill(0, count($ids), "?"));
            $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
            $types = str_repeat("i", count($ids));

            $products = $db->select(
                $query,
                $types,
                $ids
            );
        }

        include 'src/view/base/cartView.php';
    }
}