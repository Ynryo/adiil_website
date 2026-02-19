<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon panier</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/cart_style.css">
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
    $cart = new cart($db);

    // --- Préparation des données ---
    $ids = array_keys($_SESSION['cart'] ?? []);
    $products = [];

    if (!empty($ids)) {
        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $query = "SELECT * FROM ARTICLE WHERE id_article IN ($placeholders)";
        $types = str_repeat("i", count($ids));
        $products = $db->select($query, $types, $ids);
    }

    // Calcul réduction grade si connecté
    $totalWithReduc = null;
    if (!empty($products) && Session::isLoggedIn()) {
        $adherant = $db->select(
            "SELECT * FROM ADHESION INNER JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE ADHESION.id_membre = ? AND reduction_grade > 0",
            "i",
            [Session::getUserId()]
        );

        if (!empty($adherant)) {
            $reductionGrade = floatval($adherant[0]["reduction_grade"] ?? 0);
            $user_reduction = 1 - ($reductionGrade / 100);
            $totalWithReduc = 0;

            foreach ($products as $product) {
                $qty = $_SESSION['cart'][$product['id_article']] ?? 0;
                if (!empty($product['reduction_article'])) {
                    $totalWithReduc += $product['prix_article'] * $qty * $user_reduction;
                } else {
                    $totalWithReduc += $product['prix_article'] * $qty;
                }
            }
        }
    }

    $flash = Session::getFlash();
    ?>

    <div>
        <h1>MON PANIER</h1>

        <?php if ($flash): ?>
            <div id="<?= $flash['type'] === 'error' ? 'error-message' : 'success-message' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <div>
            <button id="shop-button">
                <a href="shop.php">
                    <img src="/public/assets/fleche_retour.png" alt="Flèche de retour">
                    Retourner à la boutique
                </a>
            </button>
        </div>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div id="cart-container">
            <form method="POST" action="/cart.php" id="form-quantity">
                <table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product):
                            $qty = $_SESSION['cart'][$product['id_article']] ?? 0;
                            $subtotal = $product['prix_article'] * $qty;
                            ?>
                            <tr>
                                <td class="article-cell">
                                    <?php if ($product['image_article'] !== null): ?>
                                        <img src="/api/files/<?= htmlspecialchars($product['image_article']) ?>"
                                            alt="Image de l'article" />
                                    <?php endif; ?>
                                    <p><?= htmlspecialchars($product['nom_article']) ?></p>
                                </td>
                                <td><?= number_format((float) $product['prix_article'], 2, ',', ' ') ?> €</td>
                                <td><input type="text" name="cart[quantity][<?= (int) $product['id_article'] ?>]"
                                        value="<?= (int) $qty ?>"></td>
                                <td><?= number_format($subtotal, 2, ',', ' ') ?> €</td>
                                <td>
                                    <a href="/cart.php?del=<?= (int) $product['id_article'] ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nombre d'articles :</th>
                            <td><?= $cart->count() ?></td>
                        </tr>
                        <tr>
                            <th>Total :</th>
                            <td><?= number_format($cart->total(), 2, ',', ' ') ?> €</td>
                        </tr>
                        <?php if ($totalWithReduc !== null): ?>
                            <tr>
                                <th>Total après réductions :</th>
                                <td><?= number_format($totalWithReduc, 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </form>
        </div>
        <div>
            <form class="subscription" action="/order.php" method="post">
                <?= Csrf::field() ?>
                <?php if (isset($_SESSION['cart'])): ?>
                    <input type="hidden" name="cart"
                        value="<?= htmlspecialchars(json_encode($_SESSION['cart'], JSON_UNESCAPED_UNICODE)) ?>">
                <?php endif; ?>
                <button type="submit" id="order-button">Commander</button>
            </form>
        </div>

    <?php else: ?>
        <p id="empty-cart">Votre panier est vide</p>
    <?php endif; ?>

    <?php require_once "footer.php" ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("form-quantity");
            if (form) {
                form.addEventListener("keydown", function (event) {
                    if (event.key === "Enter") {
                        event.preventDefault();
                        form.submit();
                    }
                });
            }
        });
    </script>

</body>

</html>