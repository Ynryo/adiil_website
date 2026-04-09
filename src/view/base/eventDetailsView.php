<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?= $event['nom_evenement'] ?></title>

    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/event_details_style.css">
</head>

<body>
    <?php require_once 'src/view/header.php' ?>

    <section class="event-details">
        <h1><?= strtoupper($event['nom_evenement']); ?></h1>
        <img src="<?= $imgLink ?>" alt="Image de l'événement">

        <div>
            <h2><?= date('d/m/Y', strtotime($event['date_debut_evenement'])); ?></h2>
            <?= $btnHTML ?>
        </div>

        <ul>
            <li>
                <div>📍<h3><?= $event['lieu_evenement'] ?></h3>
                </div>
            </li>
            <li>
                <div>💸<h3><?= $event['prix_evenement'] ?>€ par personne</h3>
                </div>
            </li>
            <?php if (boolval($event['reductions_evenement']))
                echo "<li><div>💎<h3>-10% pour les membres Diamants</h3></div></li>"; ?>
        </ul>

        <p><?= nl2br(htmlspecialchars($event['description_evenement'])) ?></p>
    </section>


    <section class="gallery">
        <h2>GALLERIE</h2>
        <?php if (isset($_SESSION["iduser"])): ?>
            <h3>Mes photos</h3>
            <div class="my-medias">
                <?php
                $medias = getUserEvenementMedia($_SESSION["userid"], $eventid, 4);
                foreach ($medias as $media => $img): ?>
                    <img src="assets/image/api/<?= trim($img['url_media']); ?>" alt="Image Personelle de l'événement">
                <?php endforeach; ?>

                <form id="add-media" action="/add_media.php" method="post" enctype="multipart/form-data">
                    <label for="file-picker">
                        <img src="assets/image/base/add_media.png" alt="Ajouter un média">
                    </label>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <input type="hidden" name="userid" value="<?= $_SESSION['userid'] ?>">

                    <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                    <button type="submit" style="display:none;">Envoyer</button>
                </form>

                <form id="open-gallery" action="/my_gallery.php" method="get">
                    <label for="open-gallery-button">
                        <img src="assets/image/base/explore_gallery.png" alt="Voir ma galerie entière">
                    </label>
                    <input type="hidden" name="eventid" value="<?= $eventid ?>">
                    <button id="open-gallery-button" type="submit" style="display:none;">Envoyer</button>
                </form>
            </div>
        <?php endif ?>

        <h3>Collection Generale</h3>

        <div class="general-medias">
            <?php
            $medias = getEvenementMedia($eventid, $show);
            ?>

            <?php foreach ($medias as $media => $img): ?>
                <img src="assets/image/api/event/<?= trim($img['url_media']); ?>" alt="Image de l'événement">
            <?php endforeach; ?>
        </div>

        <div class="show-more">
            <form action="" method="GET" style="display: inline;">
                <input type="hidden" name="page" value="base-eventDetails">
                <input type="hidden" name="id" value="<?= $eventid ?>">
                <input type="hidden" name="show" value="<?= $show + 8 ?>">

                <button type="submit">Voir plus</button>
            </form>

            <form action="" method="GET" style="display: inline;">
                <input type="hidden" name="page" value="base-eventDetails">
                <input type="hidden" name="id" value="<?= $eventid ?>">
                <?php if ($show >= 20): ?>
                    <input type="hidden" name="show" value="<?= $show - 10 ?>">
                <?php endif; ?>
                <button type="submit">Voir Moins</button>
            </form>
        </div>


    </section>


    <?php require_once 'src/view/footer.php'; ?>

    <script src="assets/js/base/open_media.js"></script>
    <script src="assets/js/base/add_media.js"></script>
    <script src="assets/js/base/open_gallery.js"></script>
</body>

</html>