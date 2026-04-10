<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Connexion</title>

    <link rel="stylesheet" href="assets/css/base/login_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/error_style.css">
</head>

<body>
    <?php require_once 'src/view/header.php'; ?>

    <!-- Formulaire de connexion -->
    <form method="POST" action="" class="login-form">
        <h1>Connexion</h1>
        <label for="mail">Adresse Mail :</label>
        <input type="email" name="mail" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password">

        <button type="submit">Se connecter</button>
    </form>

    <form method="GET" action="/" id="create-account">
        <input type="hidden" name="page" value="base-signin">
        <h2>Pas encore de compte ?</h2>
        <button type="submit">Créez en un</button>
    </form>

    <?php require_once 'src/view/footer.php'; ?>
</body>

</html>