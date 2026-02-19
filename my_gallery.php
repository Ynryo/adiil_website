<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Ma Galerie</title>
    <link rel="stylesheet" href="/public/styles/my_gallery_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body>
    <?php
    require_once 'header.php';
use App\Database\DB;
    
    use App\Helpers\Session;
    use App\Helpers\Csrf;

    $db = DB::getInstance();
    $limit = 10;

    // Rediriger si non connecté ou pas d'eventid
    if (!Session::isLoggedIn() || !isset($_GET['eventid'])) {
        header("Location: /index.php");
        exit;
    }

    $userid = Session::getUserId();
    $eventid = (int) $_GET['eventid'];

    if (isset($_GET["show"]) && ctype_digit($_GET["show"])) {
        $limit = (int) $_GET["show"];
    }

    $event = $db->select(
        "SELECT `nom_evenement` FROM EVENEMENT WHERE id_evenement = ?",
        "i",
        [$eventid]
    );

    if (empty($event)) {
        header("Location: /index.php");
        exit;
    }
    $event = $event[0];

    $medias = $db->select(
        "SELECT id_media, url_media FROM `MEDIA` WHERE id_membre = ? and id_evenement = ? ORDER by date_media ASC LIMIT ?;",
        "iii",
        [$userid, $eventid, $limit]
    );
    ?>

    <section class="user-gallery">
        <a href="/event_details.php?id=<?= $eventid ?>" class="back-arrow">
            &#8592;<span>Retour</span>
        </a>
        <h1>MA GALERIE</h1>
        <h2><?= htmlspecialchars($event['nom_evenement']) ?></h2>

        <div class="my-medias">
            <form id="add-media" action="add_media.php" method="post" enctype="multipart/form-data">
                <?= Csrf::field() ?>
                <label for="file-picker">
                    <img src="/public/assets/add_media.png" alt="Ajouter un média">
                </label>
                <input type="hidden" name="eventid" value="<?= $eventid ?>">
                <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                <button type="submit" style="display:none;">Envoyer</button>
            </form>

            <?php foreach ($medias as $img): ?>
                <div class="media-container">
                    <img src="/api/files/<?= htmlspecialchars(trim($img['url_media'])) ?>"
                        alt="Image personnelle de l'événement">
                    <div class="delete-icon">
                        <form class="delete-media" action="delete_media.php" method="post">
                            <?= Csrf::field() ?>
                            <label for="del-media-<?= (int) $img['id_media'] ?>">
                                <img src="assets/delete_icon.png" alt="Supprimer">
                            </label>
                            <input type="hidden" name="mediaid" value="<?= (int) $img['id_media'] ?>">
                            <input type="hidden" name="eventid" value="<?= $eventid ?>">
                            <button type="submit" id="del-media-<?= (int) $img['id_media'] ?>"
                                style="display:none;">Envoyer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="/public/scripts/open_media.js"></script>
    <script src="/public/scripts/add_media.js"></script>
    <script src="/public/scripts/delete_media.js"></script>

</body>

</html>