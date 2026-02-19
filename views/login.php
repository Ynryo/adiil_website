<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="/public/styles/login_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">

</head>

<body>
    <?php
    use App\Helpers\Csrf;
    require __DIR__ . '/layouts/header.php';
    ?>

    <!-- Formulaire de connexion -->
    <form method="POST" action="" class="login-form">
        <?= Csrf::field() ?>
        <h1>Connexion</h1>
        <label for="mail">Adresse Mail :</label>
        <input type="email" name="mail" id="mail" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password">

        <button type="submit">Se connecter</button>
    </form>

    <form method="GET" action="/signin.php" id="create-account">
        <h2>Pas encore de compte ?</h2>
        <button type="submit">Créez en un</button>
    </form>

    <?php if (!empty($loginError)): ?>
        <h3 class="login-error">
            <?= htmlspecialchars($loginError) ?>
        </h3>
    <?php endif; ?>

</body>

</html>