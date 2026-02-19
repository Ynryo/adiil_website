<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhérer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/grade_subscription_style.css">
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

    $db = DB::getInstance();

    // Rediriger si non connecté
    if (!Session::isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }

    $userid = Session::getUserId();

    // Vérification de l'ID du grade
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: /grade.php");
        exit;
    }
    $id_grade = (int) $_GET['id'];

    // Infos du grade
    $grade = $db->select("SELECT * FROM GRADE WHERE id_grade = ?", "i", [$id_grade]);
    if (empty($grade)) {
        Session::flash("Le grade sélectionné n'existe pas.", "error");
        header("Location: /grade.php");
        exit;
    }

    // Vérifier grade existant
    $currentGrade = $db->select("SELECT * FROM ADHESION WHERE id_membre = ?", "i", [$userid]);

    // Traitement de l'achat
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Csrf::check();

        if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
            $mode_paiement = $_POST['mode_paiement'];
            if (!empty($currentGrade)) {
                $db->query("DELETE FROM ADHESION WHERE id_membre = ?", "i", [$userid]);
            }
            $db->query(
                "INSERT INTO ADHESION (id_membre, id_grade, prix_adhesion, paiement_adhesion, date_adhesion) VALUES (?, ?, ?, ?, NOW())",
                "iiss",
                [$userid, $id_grade, $grade[0]['prix_grade'], $mode_paiement]
            );

            Session::flash("Adhésion au grade réussie !", "success");
            header("Location: /grade.php");
            exit;
        }
    }

    $prix = (float) $grade[0]['prix_grade'];
    ?>

    <h1>MON ADHÉSION</h1>

    <div>
        <button id="cart-button">
            <a href="/grade.php">
                <img src="/public/assets/fleche_retour.png" alt="Flèche de retour">
                Retourner aux grades
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
                        <td>Grade <?= htmlspecialchars($grade[0]['nom_grade']) ?></td>
                        <td>1</td>
                        <td><?= number_format($prix, 2, ',', ' ') ?> €</td>
                        <td><?= number_format($prix, 2, ',', ' ') ?> €</td>
                    </tr>
                </tbody>
            </table>

            <h3>Total : <?= number_format($prix, 2, ',', ' ') ?> €</h3>
        </div>

        <div>
            <h3>Paiement</h3>

            <label for="mode_paiement">Mode de Paiement :</label>
            <select id="mode_paiement" name="mode_paiement" required>
                <option value="carte_credit">Carte de Crédit</option>
                <option value="paypal">PayPal</option>
            </select><br><br>

            <div id="carte_credit" class="mode_paiement_fields">
                <form method="POST" action="/grade_subscription.php?id=<?= $id_grade ?>">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="mode_paiement" value="carte_credit">

                    <label for="numero_carte">Numéro de Carte :</label>
                    <input type="text" id="numero_carte" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX"
                        required><br><br>

                    <label for="expiration">Date d'Expiration :</label>
                    <input type="text" id="expiration" name="expiration" placeholder="MM/AA" required><br><br>

                    <label for="cvv">CVV :</label>
                    <input type="text" id="cvv" name="cvv" placeholder="XXX" required><br><br>

                    <button type="submit" id="finalise-order-button">Valider l'adhésion</button>
                </form>
            </div>
            <div id="paypal" class="mode_paiement_fields" style="display: none;">
                <form method="POST" action="/grade_subscription.php?id=<?= $id_grade ?>">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="mode_paiement" value="paypal">

                    <button type="button" id="paypal-button">Se connecter à PayPal</button><br><br>

                    <button type="submit" class="finalise-order-button">Valider l'adhésion</button>
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