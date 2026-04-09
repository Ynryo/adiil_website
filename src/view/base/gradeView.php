<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Grades</title>

    <link rel="stylesheet" href="assets/css/base/grade_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">
</head>

<body class="body_margin">
    <?php require_once 'src/view/header.php'; ?>

    <H1>Les grades</H1>

    <!-- Affichage du message de succès ou d'erreur -->
    <div>
        <?php
            if (isset($_SESSION['message'])) {
                $messageStyle = isset($_SESSION['message_type']) && $_SESSION['message_type'] === "error" ? "error-message" : "success-message";
                echo '<div id="' . $messageStyle . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']); // Supprimer le message après affichage
                unset($_SESSION['message_type']); // Supprimer le type après affichage
            }
        ?>
    </div>

    <?php if (!empty($products)) : ?>
        <div id="product-list">
            <?php foreach ($products as $product) : ?>
                    <div id="one-product">
                        <div>
                            <?php
                                $img = $product['image_grade'];
                                $imgLink = "assets/image/" . ($img == null ? "admin/default_images/grade.webp" : "api/grade/$img");
                            ?>
                            <img src=<?= $imgLink ?> alt="Image du grade" />

                            <h3 title="<?= htmlspecialchars($product['nom_grade']) ?>">
                                <?= htmlspecialchars($product['nom_grade']) ?>
                            </h3>

                            <?php if (!empty($product['description_grade'])) : ?>
                                <p><?= htmlspecialchars($product['description_grade'])?></p>
                            <?php endif ?>

                            <p>-- Prix : <?= number_format(htmlspecialchars($product['prix_grade']), 2, ',', ' ') ?> € --</p>
                        </div>
                        <div>
                            <p id="adhesion-status">

                                <?php
                                    if (!empty($_SESSION['userid'])) {
                                        $unAdherant = getGradeMembre($product['id_grade'], $_SESSION['userid']);
                                    } 
                                ?>
                                <?php if (!empty($_SESSION) && !empty($unAdherant)): ?>
                                    <button id="detention">Vous détenez ce grade</button>
                                <?php else: ?>
                                    <a id="buy-button" href="?page=base-gradeSubscription&id=<?= htmlspecialchars($product['id_grade']) ?>">
                                        Acheter
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Aucun grade trouvé.</p>
    <?php endif ?>

    <?php require_once 'src/view/footer.php'; ?>
</body>
</html>