<?php

require_once "src/model/bdd/membre.php";
require_once "src/model/utils/files_save.php";
require_once "src/model/utils/cart_class.php";

class Order
{
    public function show()
    {
        $cart = Cart_class::getInstance();

        if (!isset($_SESSION["userid"])) {
            header("Location: /?page=base-login");
            exit;
        }

        $userid = $_SESSION["userid"];

        // Récupérer le panier
        if (empty($_SESSION['cart'])) {
            header("Location: /?page=base-cart");
            exit;
        }

        $db = DB::getInstance();

        // Calculer le total de la commande
        $total = 0;
        $cart = $_SESSION['cart'];
        $product_ids = array_keys($cart);

        $placeholders = implode(",", array_fill(0, count($product_ids), "?"));
        $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
        $types = str_repeat("i", count($product_ids));
        $products = $db->select($query, $types, $product_ids);

        $cart_items = [];
        foreach ($products as $product) {
            if ($product['stock_article'] > 0 && $_SESSION['cart'][$product['id_article']] > $product['stock_article']) {
                $cart[$product['id_article']] = $product['stock_article'];
            }

            $cart_items[$product['id_article']] = [
                'nom_article' => $product['nom_article'], // Ajout du nom de l'article
                'prix_article' => $product['prix_article'],
                'quantite' => $cart[$product['id_article']],
            ];

            $total += $product['prix_article'] * $cart[$product['id_article']];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
                $mode_paiement = $_POST['mode_paiement'];

                // Enregistrer la commande dans la base de données
                foreach ($cart_items as $product_id => $item) {
                    $db->query(
                        "CALL achat_article(?, ?, ?, ?)",
                        "iiis",
                        [$userid, $product_id, $item['quantite'], $mode_paiement]
                    );
                }
                $_SESSION['cart'] = [];

                $_SESSION['message'] = "Commande réalisée avec succès !";
                $_SESSION['message_type'] = "success";

                header("Location: /?page=base-cart"); // Rediriger vers le panier
                exit;
            }
        }

        // Vérifie l'adhésion de l'utilisateur
        $adherant = getAdherent($userid);

        $reductionGrade = floatval($adherant[0]["reduction_grade"] ?? 0);
        $user_reduction = 1 - ($reductionGrade / 100);
        $totalWithReduc = 0;

        // Calcule le total en tenant compte des réductions applicables
        foreach ($products as $product) {
            if (!empty($product['reduction_article'])) { // Vérifie si une réduction est applicable
                $totalWithReduc += $product['prix_article'] * $_SESSION['cart'][$product['id_article']] * $user_reduction;
            } else {
                $totalWithReduc += $product['prix_article'] * $_SESSION['cart'][$product['id_article']];
            }
        }

        include_once 'src/view/base/orderView.php';
    }
}