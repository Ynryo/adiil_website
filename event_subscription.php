<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/event_subscription_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">

    <?php
    require_once "header.php";
use App\Database\DB;
    
    use App\Helpers\Session;
    use App\Helpers\Csrf;

    // Rediriger si non connecté
    if (!Session::isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }

    $userid = Session::getUserId();
    $db = DB::getInstance();

    // La requête doit être POST avec un eventid
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST["eventid"])) {
        header("Location: /login.php");
        exit;
    }

    Csrf::check();
    $eventid = (int) $_POST["eventid"];

    // Si le prix est fourni, c'est la confirmation de paiement
    if (isset($_POST["price"])) {
        $db->query(
            "INSERT INTO `INSCRIPTION` (`id_membre`, `id_evenement`, `date_inscription`, `paiement_inscription`, `prix_inscription`)
        VALUES (?, ?, NOW(), 'WEB', ?);",
            "iid",
            [$userid, $eventid, (float) $_POST["price"]]
        );
        $xp = $db->select("SELECT xp_evenement FROM EVENEMENT WHERE id_evenement = ?", "i", [$eventid])[0]['xp_evenement'];
        $db->query(
            "UPDATE MEMBRE SET MEMBRE.xp_membre = MEMBRE.xp_membre + ? where MEMBRE.id_membre = ?;",
            "ii",
            [$xp, $userid]
        );
        header("Location: /events.php");
        exit;
    }

    // Sinon, afficher la page de paiement
    $event = $db->select(
        "SELECT nom_evenement, xp_evenement, prix_evenement, reductions_evenement FROM EVENEMENT WHERE id_evenement = ?;",
        "i",
        [$eventid]
    );

    if (empty($event)) {
        header("Location: /index.php");
        exit;
    }

    $event = $event[0];
    $title = $event["nom_evenement"];
    $price = (float) $event["prix_evenement"];

    // Calcul de la réduction
    $user_reduction = 1;
    if (boolval($event["reductions_evenement"])) {
        $gradeReduc = $db->select(
            "SELECT reduction_grade FROM ADHESION
        JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade
        WHERE id_membre = ? AND reduction_grade > 0 ORDER BY ADHESION.date_adhesion DESC LIMIT 1",
            "i",
            [$userid]
        );
        if (!empty($gradeReduc)) {
            $user_reduction = 1 - ($gradeReduc[0]["reduction_grade"] / 100);
        }
    }

    $finalPrice = $price * $user_reduction;
    ?>

    <h1>INSCRIPTION</h1>

    <div>
        <button id="cart-button">
            <a href="/event_details.php?id=<?= $eventid ?>">
                <img src="/public/assets/fleche_retour.png" alt="Flèche de retour">
                Retourner à l'évènement
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
                    <tr>
                        <td><?= htmlspecialchars(strtoupper($title)) ?></td>
                        <td>1</td>
                        <td><?= number_format($price, 2, ',', ' ') ?> €</td>
                        <td><?= number_format($price, 2, ',', ' ') ?> €</td>
                    </tr>
                </tbody>
            </table>

            <h3>Total : <?= number_format($price, 2, ',', ' ') ?> €</h3>
            <?php if ($user_reduction < 1): ?>
                <h3>Total après réductions : <?= number_format($finalPrice, 2, ',', ' ') ?> €</h3>
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
                <form method="POST" action="/event_subscription.php">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <input type="hidden" name="price" value="<?= $finalPrice ?>">
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
                <form method="POST" action="/event_subscription.php">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <input type="hidden" name="price" value="<?= $finalPrice ?>">
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