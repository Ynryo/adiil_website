<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Ma Gallerie</title>
    
    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/my_gallery_style.css">
</head>

<body>
    <?php require_once 'src/view/header.php'; ?>

    <section class="user-gallery">

        <a href="/?page=eventDetails&id=<?= "$eventid";?>" class="back-arrow">
            &#8592;<span>Retour</span>
        </a>
        <h1>MA GALLERIE</h1>
        <h2><?= $event['nom_evenement']?></h2>

        <div class="my-medias">

            <form id="add-media" action="add_media.php" method="post" enctype="multipart/form-data">
                <label for="file-picker">
                    <img src="assets/image/base/add_media.png" alt="Ajouter un média">
                </label>
                <input type="hidden" name="eventid" value="<?= $eventid?>">
                <input type="hidden" name="userid" value="<?= $_SESSION['userid']?>">

                <input type="file" id="file-picker" name="file" accept="image/jpeg, image/png, image/webp" hidden>
                <button type="submit" style="display:none;">Envoyer</button>
            </form>

        <?php
            $medias = getUserEvenementMedia($userid, $eventid, $limit);
        ?>
                
        <?php foreach($medias as $media => $img): ?>
                <div class="media-container">
                    <img src="assets/image/api/<?= trim($img['url_media']); ?>" alt="Image Personnelle de l'événement">
                    <div class="delete-icon">

                        <form class="delete-media" action="delete_media.php" method="post">
                            <label for="del-media">
                                <img src="assets/image/base/delete_icon.png" alt="poubelle">
                            </label>
                            <input type="hidden" name="mediaid" value="<?= $img['id_media']?>">
                            <input type="hidden" name="eventid" value="<?= $eventid?>">

                            <button type="submit" style="display:none;">Envoyer</button>
                        </form>

                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </section>

    <?php require_once 'src/view/footer.php'; ?>

    <script src="assets/js/base/open_media.js"></script>
    <script src="assets/js/base/add_media.js"></script>
    <script src="assets/js/base/delete_media.js"></script>
</body>
</html>