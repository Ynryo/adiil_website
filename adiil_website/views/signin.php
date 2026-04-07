<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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

    <form method="POST" action="" class="login-form">
        <?= Csrf::field() ?>
        <h1>S'inscrire</h1>

        <label for="fname">Prénom :</label>
        <input type="text" name="fname" id="fname">

        <label for="lname">Nom :</label>
        <input type="text" name="lname" id="lname">

        <label for="mail">Adresse Mail :*</label>
        <input type="email" name="mail" id="mail" required>

        <label for="password">Mot de passe :*</label>
        <input type="password" name="password" id="password" required>

        <label for="password_verif">Confirmez le Mot de passe :*</label>
        <input type="password" name="password_verif" id="password_verif" required>

        <button type="submit">Confirmer</button>
    </form>

    <?php if (!empty($signupError)): ?>
        <h3 class="login-error">
            <?= htmlspecialchars($signupError) ?>
        </h3>
    <?php endif; ?>

</body>

</html>