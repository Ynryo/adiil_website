<!DOCTYPE html>
<html lang="fr">
<?php
$db = DB::getInstance();

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    header("Location: /index.php");
    exit;
}

$newsId = (int) $_GET['id'];
$news = $db->select(
    "SELECT * FROM ACTUALITE WHERE id_actualite = ?",
    "i",
    [$newsId]
);

if (empty($news)) {
    header("Location: /index.php");
    exit;
}
$news = $news[0];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?= htmlspecialchars($news['titre_actualite']) ?></title>

    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
    <link rel="stylesheet" href="/public/styles/event_details_style.css">
</head>

<body>
    <?php require_once 'header.php';
use App\Database\DB; ?>

    <section class="event-details">
        <?php if ($news['image_actualite'] === null): ?>
            <img src="/admin/ressources/default_images/event.jpg" alt="Image de l'actualité">
        <?php else: ?>
            <img src="/api/files/<?= htmlspecialchars($news['image_actualite']) ?>" alt="Image de l'actualité">
        <?php endif ?>

        <h1><?= htmlspecialchars(strtoupper($news['titre_actualite'])) ?></h1>

        <div>
            <h2><?= date('d/m/Y', strtotime($news['date_actualite'])) ?></h2>
        </div>

        <p>
            <?= nl2br(htmlspecialchars($news['contenu_actualite'])) ?>
        </p>
    </section>

    <?php require_once 'footer.php'; ?>
</body>

</html>