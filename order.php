<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/order_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">

    <?php
    require_once "header.php";
    require_once 'cart_class.php';

    use App\Database\DB;
    use App\Helpers\Session;
    use App\Helpers\Csrf;

    $db = DB::getInstance();

    // Rediriger si non connecté
    if (!Session::isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }

    $userid = Session::getUserId();

    // Rediriger si panier vide
    if (empty($_SESSION['cart'])) {
        header("Location: /cart.php");
        exit;
    }

    // --- Préparation des données ---
    $total = 0;
    $cartData = $_SESSION['cart'];
    $product_ids = array_keys($cartData);
    $placeholders = implode(",", array_fill(0, count($product_ids), "?"));
    $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
    $types = str_repeat("i", count($product_ids));
    $products = $db->select($query, $types, $product_ids);

    $cart_items = [];
    foreach ($products as $product) {
        $qty = $cartData[$product['id_article']];
        if ($product['stock_article'] > 0 && $qty > $product['stock_article']) {
            $qty = $product['stock_article'];
            $cartData[$product['id_article']] = $qty;
        }
        $cart_items[$product['id_article']] = [
            'nom_article' => $product['nom_article'],
            'prix_article' => $product['prix_article'],
            'quantite' => $qty,
        ];
        $total += $product['prix_article'] * $qty;
    }

    // Calcul réduction
    $totalWithReduc = null;
    $adherant = $db->select(
        "SELECT * FROM ADHESION INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE ADHESION.id_membre = ? AND reduction_grade > 0",
        "i",
        [$userid]
    );

    if (!empty($adherant)) {
        $reductionGrade = floatval($adherant[0]["reduction_grade"] ?? 0);
        $user_reduction = 1 - ($reductionGrade / 100);
        $totalWithReduc = 0;

        foreach ($products as $product) {
            $qty = $cartData[$product['id_article']] ?? 0;
            if (!empty($product['reduction_article'])) {
                $totalWithReduc += $product['prix_article'] * $qty * $user_reduction;
            } else {
                $totalWithReduc += $product['prix_article'] * $qty;
            }
        }
    }

    // Traitement de la commande
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Csrf::check();

        if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
            $mode_paiement = $_POST['mode_paiement'];

            foreach ($cart_items as $product_id => $item) {
                $db->query(
                    "CALL achat_article(?, ?, ?, ?)",
                    "iiis",
                    [$userid, $product_id, $item['quantite'], $mode_paiement]
                );
            }
            $_SESSION['cart'] = [];
            Session::flash("Commande réalisée avec succès !", "success");
            header("Location: /cart.php");
            exit;
        }
    }
    ?>

    <h1>MA COMMANDE</h1>

    <div>
        <button id="cart-button">
            <a href="/cart.php">
                <img src="/public/assets/fleche_retour.png" alt="Flèche de retour">
                Retourner au panier
            </a>
        </button>
    </div>

    <div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $product_id => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nom_article']) ?></td>
                            <td><?= (int) $item['quantite'] ?></td>
                            <td><?= number_format((float) $item['prix_article'], 2, ',', ' ') ?> €</td>
                            <td><?= number_format($item['prix_article'] * $item['quantite'], 2, ',', ' ') ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total : <?= number_format($total, 2, ',', ' ') ?> €</h3>
            <?php if ($totalWithReduc !== null): ?>
                <h3>Total après réductions : <?= number_format($totalWithReduc, 2, ',', ' ') ?> €</h3>
            <?php endif; ?>
        </div>

        <div>
            <h3>Paiement</h3>

            <label for="mode_paiement">Mode de Paiement :</label>
            <select id="mode_paiement" name="mode_paiement" required>
                <option value="carte_credit">Carte de Crédit</option>
                <option value="paypal">PayPal</option>
            </select><br><br>

            <div id="carte_credit" class="mode_paiement_fields">
                <form method="POST" action="/order.php">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="mode_paiement" value="carte_credit">

                    <label for="numero_carte">Numéro de Carte :</label>
                    <input type="text" id="numero_carte" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX"
                        required><br><br>

                    <label for="expiration">Date d'Expiration :</label>
                    <input type="text" id="expiration" name="expiration" placeholder="MM/AA" required><br><br>

                    <label for="cvv">CVV :</label>
                    <input type="text" id="cvv" name="cvv" placeholder="XXX" required><br><br>

                    <button type="submit" id="finalise-order-button">Valider la commande</button>
                </form>
            </div>
            <div id="paypal" class="mode_paiement_fields" style="display: none;">
                <form method="POST" action="/order.php">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="mode_paiement" value="paypal">

                    <button type="button" id="paypal-button">Se connecter à PayPal</button><br><br>

                    <button type="submit" class="finalise-order-button">Valider la commande</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('mode_paiement').addEventListener('change', function () {
            var modePaiement = this.value;
            document.getElementById('carte_credit').style.display = modePaiement === 'carte_credit' ? 'block' : 'none';
            document.getElementById('paypal').style.display = modePaiement === 'paypal' ? 'block' : 'none';
        });
    </script>

    <?php require_once "footer.php" ?>

</body>

</html>