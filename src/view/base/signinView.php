<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Inscription</title>

    <link rel="stylesheet" href="assets/css/base/login_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">
</head>

<body>
    <?php require_once 'src/view/header.php' ?>

    <form method="POST" action="" class="login-form">
        <h1>S'inscrire</h1>

        <label for="mail">Prénom :</label>
        <input type="text" name="fname" required>

        <label for="mail">Nom :</label>
        <input type="text" name="lname" required>
    
        <label for="mail">Adresse Mail :*</label>
        <input type="email" name="mail" required>

        <label for="password">Mot de passe :*</label>
        <input type="password" name="password" required>

        <label for="password">Confirmez le Mot de passe :*</label>
        <input type="password" name="password_verif" required>

        <button type="submit">Confirmer</button>
    </form>

    <?php require_once 'src/view/footer.php' ?>
</body>
</html>