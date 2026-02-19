<!DOCTYPE html>
<html lang="fr">
<?php
require_once __DIR__ . '/bootstrap.php';
use App\Database\DB;

use App\Helpers\Session;
use App\Helpers\Csrf;

$db = DB::getInstance();

$show = 8;

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    header("Location: /index.php");
    exit;
}

$eventid = (int) $_GET['id'];
$event = $db->select(
    "SELECT `nom_evenement`, `xp_evenement`, `places_evenement`, `prix_evenement`, `reductions_evenement`, `lieu_evenement`, `date_evenement`, `image_evenement`, `description_evenement`
        FROM EVENEMENT WHERE id_evenement = ?",
    "i",
    [$eventid]
);

if (empty($event)) {
    header("Location: /index.php");
    exit;
}
$event = $event[0];

if (isset($_GET['show']) && is_numeric($_GET['show']) && $_GET['show']) {
    $show = (int) $_GET['show'];
}

// Préparation des données de date
$current_date = new DateTime(date("Y-m-d"));
$event_date = new DateTime(substr($event['date_evenement'], 0, 10));
$isPassed = $event_date < $current_date;

// Vérification de l'inscription
$isSubscribed = false;
Session::start();
$isLoggedIn = Session::isLoggedIn();

if ($isLoggedIn && !$isPassed) {
    $sub = $db->select(
        "SELECT * FROM INSCRIPTION WHERE id_evenement = ? AND id_membre = ?;",
        "ii",
        [$eventid, Session::getUserId()]
    );
    $isSubscribed = !empty($sub);
}

// Mes médias (si connecté)
$myMedias = [];
if ($isLoggedIn) {
    $myMedias = $db->select(
        "SELECT url_media FROM `MEDIA` WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC LIMIT 4;",
        "ii",
        [Session::getUserId(), $eventid]
    );
}

// Médias généraux
$generalMedias = $db->select(
    "SELECT url_media FROM `MEDIA` WHERE id_evenement = ? ORDER by date_media ASC LIMIT ?;",
    "ii",
    [$eventid, $show]
);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?= htmlspecialchars($event['nom_evenement']) ?></title>

    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/event_details_style.css">
</head>

<body>
    <?php require_once 'header.php'; ?>

    <section class="event-details">
        <?php if ($event['image_evenement'] === null): ?>
            <img src="/admin/ressources/default_images/event.jpg" alt="Image de l'événement">
        <?php else: ?>
            <img src="/api/files/<?= htmlspecialchars($event['image_evenement']) ?>" alt="Image de l'événement">
        <?php endif ?>

        <h1><?= htmlspecialchars(strtoupper($event['nom_evenement'])) ?></h1>

        <div>
            <h2><?= date('d/m/Y', strtotime($event['date_evenement'])) ?></h2>

            <?php if ($isPassed): ?>
                <button class="subscription" id="passed_subscription" disabled>Passé</button>
            <?php elseif ($isSubscribed): ?>
                <button class="subscription" id="passed_subscription" disabled>Inscrit</button>
            <?php else: ?>
                <form class="subscription" action="event_subscription.php" method="post">
                    <?= Csrf::field() ?>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <button type="submit">Inscription</button>
                </form>
            <?php endif; ?>
        </div>

        <ul>
            <li>
                <div>📍<h3><?= htmlspecialchars($event['lieu_evenement']) ?></h3>
                </div>
            </li>
            <li>
                <div>💸<h3><?= htmlspecialchars($event['prix_evenement']) ?>€ par personne</h3>
                </div>
            </li>
            <?php if (boolval($event['reductions_evenement'])): ?>
                <li>
                    <div>💎<h3>-10% pour les membres Diamants</h3>
                    </div>
                </li>
            <?php endif; ?>
        </ul>

        <p>
            <?= nl2br(htmlspecialchars($event['description_evenement'])) ?>
        </p>
    </section>

    <section class="gallery">
        <h2>GALERIE</h2>

        <?php if ($isLoggedIn): ?>
            <h3>Mes photos</h3>
            <div class="my-medias">
                <?php foreach ($myMedias as $img): ?>
                    <img src="/api/files/<?= htmlspecialchars(trim($img['url_media'])) ?>"
                        alt="Image personnelle de l'événement">
                <?php endforeach; ?>

                <form id="add-media" action="/add_media.php" method="post" enctype="multipart/form-data">
                    <?= Csrf::field() ?>
                    <label for="file-picker">
                        <img src="/public/assets/add_media.png" alt="Ajouter un média">
                    </label>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                    <button type="submit" style="display:none;">Envoyer</button>
                </form>

                <form id="open-gallery" action="/my_gallery.php" method="get">
                    <label for="open-gallery-button">
                        <img src="/public/assets/explore_gallery.png" alt="Voir ma galerie entière">
                    </label>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <button id="open-gallery-button" type="submit" style="display:none;">Envoyer</button>
                </form>
            </div>
        <?php endif; ?>

        <h3>Collection Générale</h3>
        <div class="general-medias">
            <?php foreach ($generalMedias as $img): ?>
                <img src="/api/files/<?= htmlspecialchars(trim($img['url_media'])) ?>" alt="Image de l'événement">
            <?php endforeach; ?>
        </div>

        <div class="show-more">
            <form action="" method="GET" style="display: inline;">
                <input type="hidden" name="id" value="<?= $eventid ?>">
                <input type="hidden" name="show" value="<?= $show + 8 ?>">
                <button type="submit">Voir plus</button>
            </form>

            <?php if ($show >= 20): ?>
                <form action="" method="GET" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $eventid ?>">
                    <input type="hidden" name="show" value="<?= $show - 10 ?>">
                    <button type="submit">Voir Moins</button>
                </form>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>
    <script src="/public/scripts/open_media.js"></script>
    <script src="/public/scripts/add_media.js"></script>
    <script src="/public/scripts/open_gallery.js"></script>

</body>

</html>