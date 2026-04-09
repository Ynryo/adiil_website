<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Actualités</title>

    <link rel="stylesheet" href="assets/css/base/news_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">
</head>

<body class="body_margin">
    <?php require_once 'src/view/header.php'; ?>

    <h1>ACTUALITES</h1>
    <section>
        <a class="show-more" href="/?page=base-news&show=<?= $show + 10 ?>">Voir plus loin dans le passé</a>
        <div class="events-display">
            <?php foreach ($actualitys_to_display as $actuality):
                $actualityid = $actuality["id_actualite"];
                $actuality_date = substr($actuality['date_actualite'], 0, 10);
                $actuality_date_info = getdate(strtotime($actuality_date));
                $actuality_date = new DateTime($actuality_date);

                if ($actuality_date == $current_date) {
                    $closest_actuality_id = "closest-eveactualitynt"; // Marquer l'événement du jour comme le plus proche
                } elseif (empty($closest_actuality_id)) {
                    $closest_actuality_id = "closest-event"; // Marquer le premier événement futur comme le plus proche
                } ?>
                <div class="event-box" id="<?= $closest_actuality_id ?>">
                    <div class="timeline-event">
                        <h4> <?= ucwords($joursFr[$actuality_date_info['wday']] . " " . $actuality_date_info["mday"] . " " . $moisFr[$actuality_date_info['mon']]) ?>
                        </h4>
                        <div class="vertical-line"></div>
                    </div>
                    <div class="event" event-id="<?= $actualityid ?>">
                        <div>
                            <h2 style="margin-bottom: 0px;"><?= $actuality['titre_actualite'] ?></h2>
                        </div>
                        <h4 class="<?= $actuality_subscription_color_class ?>">
                            <?= $actuality_subscription_label ?>
                        </h4>
                    </div>
                </div>
                <?php $closest_actuality_id = "" ?>
            <?php endforeach ?>
        </div>
    </section>

    <?php require_once 'src/view/footer.php' ?>

    <script src="assets/js/base/news_details_redirect.js"></script>
    <script src="assets/js/base/scroll_to_closest_event.js"></script>
</body>

</html>