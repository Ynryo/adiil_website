<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Accueil</title>

    <link rel="stylesheet" href="assets/css/base/index_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/bubble.css">
</head>

<body id="index" class="body_margin">
    <?php require_once 'src/view/header.php'; ?>

    <div id="page-container">
        <!-- TODO: H1 A METTRE -->
        <section>
            <h2 class="titre_vertical"> ADIIL</h2>
            <div id="index_carrousel">
                <img src="assets/image/base/photo_accueil_BDE.png" alt="Carrousel ADIIL">
            </div>
        </section>

        <section>
            <div class="paragraphes">
                <p>
                    <b class="underline">L'ADIIL</b>, ou l'<b>Association</b> du <b>Département Informatique</b> de
                    l'<b>IUT</b> de <b>Laval</b>,
                    est une organisation étudiante dédiée à créer un environnement propice à l'épanouissement dans le
                    campus.
                    Participer a des évèvements, et plus globalement a la vie du département.
                </p>
                <p>
                    L'ADIIL, véritable moteur de la vie étudiante à l'IUT de Laval,
                    offre un cadre propice à l'épanouissement académique et social des étudiants en informatique.
                    En participant à ses événements variés, les étudiants enrichissent leur expérience universitaire,
                    tout en renforçant les liens au sein de la communauté.
                </p>
            </div>
            <h2 class="titre_vertical">L'ASSO</h2>
        </section>

        <section>
            <h2 class="titre_vertical">SCORES</h2>

            <div id="podium">
                <?php foreach ([2, 1, 3] as $member_number): ?>
                    <?php $pod = $podium[$member_number - 1]; ?>
                    <div class="podium_unit">
                        <h3>#0
                            <?= $member_number ?>
                        </h3>
                        <h4>
                            <?= $pod['prenom_membre'] ?>
                        </h4>
                        <div>
                            <?php
                            $pp = $pod['pp_membre'];
                            $imgLink = "assets/image/" . ($pp == null ? "admin/default_images/user.jpg" : "api/pp/$pp");
                            ?>
                            <img src="<?= $imgLink ?>" alt="Profile Picture" class="profile_picture">
                            <?= $pod['xp_membre']; ?> xp
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <div class="events-display">
                <?php foreach ($events_to_display as $event): ?>
                    <?php $eventid = $event["id_evenement"]; ?>
                    <div class="event" event-id="<?= $eventid ?>">
                        <div>
                            <h2>
                                <?= $event['nom_evenement'] ?>
                            </h2>
                            <?php
                            $event_date = substr($event['date_debut_evenement'], 0, 10);
                            $event_date_info = getdate(strtotime($event_date));
                            echo ucwords($event_date_info["mday"] . " " . $moisFr[$event_date_info['mon']] . ", " . $event["lieu_evenement"]);
                            ?>
                        </div>

                        <?php
                        if (isPlaceDisponible($eventid)) {
                            $event_subscription_color_class = "event-not-subscribed hover_effect";
                            $event_subscription_label = "S'inscrire";
                        } else {
                            $event_subscription_color_class = "event-full";
                            $event_subscription_label = "Complet";
                        }

                        if ($_SESSION["userid"] !== null) {
                            if (isSubscribed($_SESSION['userid'], $event["id_evenement"])) {
                                $event_subscription_color_class = "event-subscribed";
                                $event_subscription_label = "Inscrit";
                            }
                        }
                        ?>

                        <h4<?= "class=\"$event_subscription_color_class\"" ?>
                            <?= $event_subscription_label ?>>
                            </h4>
                    </div>
                <?php endforeach; ?>
                <h3><a href="/?page=base-events">Voir tous les événements</a></h3>
            </div>
            <h2 class="titre_vertical">EVENT</h2>

        </section>
    </div>

    <?php require_once 'src/view/footer.php' ?>

    <script src="assets/js/base/event_details_redirect.js"></script>
    <script src="assets/js/base/bubble.js"></script>
</body>

</html>