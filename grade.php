<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/grade_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">

    <?php
    require_once "header.php";
use App\Database\DB;
    
    use App\Helpers\Session;

    $db = DB::getInstance();

    // --- Préparation des données ---
    $grades = $db->select("SELECT * FROM GRADE WHERE deleted = false ORDER BY prix_grade");

    // Si connecté, récupérer les grades possédés par l'utilisateur
    $ownedGrades = [];
    if (Session::isLoggedIn()) {
        $adhesions = $db->select(
            "SELECT id_grade FROM ADHESION WHERE id_membre = ?",
            "i",
            [Session::getUserId()]
        );
        foreach ($adhesions as $a) {
            $ownedGrades[] = $a['id_grade'];
        }
    }

    $flash = Session::getFlash();
    ?>

    <h1>Les grades</h1>

    <?php if ($flash): ?>
        <div id="<?= $flash['type'] === 'error' ? 'error-message' : 'success-message' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($grades)): ?>
        <div id="product-list">
            <?php foreach ($grades as $grade): ?>
                <div class="grade-card">
                    <div>
                        <?php if ($grade['image_grade'] === null): ?>
                            <img src="/admin/ressources/default_images/grade.webp" alt="Image du grade" />
                        <?php else: ?>
                            <img src="/api/files/<?= htmlspecialchars($grade['image_grade']) ?>" alt="Image du grade" />
                        <?php endif ?>

                        <h3 title="<?= htmlspecialchars($grade['nom_grade']) ?>">
                            <?= htmlspecialchars($grade['nom_grade']) ?>
                        </h3>
                        <?php if (!empty($grade['description_grade'])): ?>
                            <p><?= htmlspecialchars($grade['description_grade']) ?></p>
                        <?php endif; ?>
                        <p>-- Prix : <?= number_format((float) $grade['prix_grade'], 2, ',', ' ') ?> € --</p>
                    </div>
                    <div>
                        <p class="adhesion-status">
                            <?php if (in_array($grade['id_grade'], $ownedGrades)): ?>
                                <button class="owned-grade" disabled>Vous détenez ce grade</button>
                            <?php else: ?>
                                <a class="buy-button" href="/grade_subscription.php?id=<?= (int) $grade['id_grade'] ?>">
                                    Acheter
                                </a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucun grade trouvé.</p>
    <?php endif; ?>

    <?php require_once "footer.php" ?>

</body>

</html>