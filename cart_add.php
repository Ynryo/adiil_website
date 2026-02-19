<?php
require_once __DIR__ . '/bootstrap.php';
require_once 'cart_class.php';

use App\Database\DB;

$db = DB::getInstance();
$cart = new cart($db);

$json = ['error' => true];

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $product = $db->select(
        "SELECT id_article FROM ARTICLE WHERE id_article = ?",
        "i",
        [$id]
    );

    if (empty($product)) {
        $json['message'] = "Ce produit n'existe pas";
        echo json_encode($json);
        exit;
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