<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/delete_account_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <title>Supprimer le compte</title>
</head>

<body>

    <?php
    require_once __DIR__ . '/bootstrap.php';
    
    use App\Helpers\Session;
    use App\Helpers\Csrf;

    Session::start();

    // Rediriger si non connecté
    if (!Session::isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }

    $db = DB::getInstance();
    $showConfirmation = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Csrf::check();

        if (isset($_POST['delete_account']) && $_POST['delete_account'] === 'true') {
            $showConfirmation = true;
        }

        if (isset($_POST['delete_account_valid']) && $_POST['delete_account_valid'] === 'true') {
            $db->query(
                "CALL suppressionCompte ( ? );",
                "i",
                [Session::getUserId()]
            );
            Session::destroy();
            header("Location: /index.php");
            exit();
        }
    }
    ?>

    <?php if ($showConfirmation): ?>
        <div id="deleteAccountAlert" class="alert-container">
            <div class="alert-content">
                <p>
                    ⚠️ Vous êtes sur le point de supprimer votre compte. Cette action est irréversible.
                    Toutes vos données seront perdues. Veuillez cocher la case ci-dessous pour confirmer que vous comprenez
                    les conséquences.
                </p>
                <input type="checkbox" id="confirmCheckbox"> <label for="confirmCheckbox">J'ai compris</label>
                <br><br>
                <ul>
                    <li>
                        <form action="" method="POST">
                            <?= Csrf::field() ?>
                            <button id="confirmDelete" name="delete_account_valid" value="true" disabled>Valider</button>
                        </form>
                    </li>
                    <li>
                        <button id="cancelDelete" onclick="window.location.href='/account.php'">Revenir en arrière</button>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <script src="/public/scripts/confirm_account_supression.js"></script>
</body>

</html>