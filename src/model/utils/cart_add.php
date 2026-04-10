<?php

// Importer les fichiers
require_once 'src/model/bdd/article.php';
require_once 'src/model/utils/files_save.php';
require_once 'src/model/utils/cart_class.php';

// Initialisation du panier
$cart = Cart_class::getInstance();

$json = array('error' => true);

if (isset($_GET['id'])) {
    $product = getArticle($_GET['id']);

    if (empty($product)) {
        $json['message'] = "Ce produit n'existe pas";
    }

    $cart->add($product[0]['id_article']);
    $json['error'] = false;
    $json['total'] = $cart->total();
    $json['count'] = $cart->count();
    $json['message'] = "Le produit a bien été ajouté à votre panier";

} else {
    $json['message'] = "Vous n'avez pas ajouté de produit à ajouter au panier";
}

echo json_encode($json);