<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Supprimer le compte</title>

    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/delete_account_style.css">
</head>

<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <?php if (isset($_POST['delete_account']) && $_POST['delete_account'] === 'true'): ?>
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
                                <button id="confirmDelete" name="delete_account_valid" value="true" disabled>Valider</button>
                            </form>
                        </li>
                        <li>
                            <button id="cancelDelete" onclick="window.location.href='/?page=base-account'">Revenir en
                                arrière</button>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endif ?>
    <?php endif ?>

    <script src="assets/js/base/confirm_account_supression.js"></script>
</body>

</html>