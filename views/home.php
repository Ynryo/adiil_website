<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Accueil</title>

    <link rel="stylesheet" href="/public/styles/index_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
    <link rel="stylesheet" href="/public/styles/bubble.css">

</head>

<body id="index" class="body_margin">

    <?php require __DIR__ . '/layouts/header.php'; ?>

    <div id="page-container">
        <section>
            <h1 class="titre_vertical">ADIIL</h1>
            <div id="index_carrousel">
                <img src="/public/assets/photo_accueil_BDE.png" alt="Carrousel ADIIL">
            </div>
        </section>

        <section>
            <div class="paragraphes">
                <p>
                    <b class="underline">L'ADIIL</b>, ou l'<b>Association</b> du <b>Département</b> <b>Informatique</b>
                    de l'<b>IUT</b> de <b>Laval</b>,
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
                <?php foreach ([2, 1, 3] as $member_number):
                    if (isset($podium[$member_number - 1])) {
                        $pod = $podium[$member_number - 1];
                        ?>
                        <div class="podium_unit">
                            <h3>#0
                                <?= $member_number ?>
                            </h3>
                            <h4>
                                <?= htmlspecialchars($pod['prenom_membre']) ?>
                            </h4>
                            <div>
                                <?php if ($pod['pp_membre'] === null): ?>
                                    <img src="/admin/ressources/default_images/user.jpg" alt="Photo de profil par défaut"
                                        class="profile_picture">
                                <?php else: ?>
                                    <img src="/api/files/<?= htmlspecialchars($pod['pp_membre']) ?>" alt="Photo de profil"
                                        class="profile_picture">
                                <?php endif ?>
                                <?= (int) $pod['xp_membre'] ?> xp
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="podium_unit">
                            <h3>#0
                                <?= $member_number ?>
                            </h3>
                            <h4>-</h4>
                            <div>-</div>
                        </div>
                    <?php }
                endforeach; ?>
            </div>
        </section>

        <section>
            <div class="events-display">
                <?php foreach ($eventsData as $eventItem):
                    $event = $eventItem['event'];
                    $eventid = $event["id_evenement"];
                    ?>
                    <div class="event" event-id="<?= (int) $eventid ?>">
                        <div>
                            <h2>
                                <?= htmlspecialchars($event['nom_evenement']) ?>
                            </h2>
                            <?= htmlspecialchars($eventItem['formattedDate']) ?>
                        </div>

                        <h4 class="<?= htmlspecialchars($eventItem['colorClass']) ?>">
                            <?= htmlspecialchars($eventItem['label']) ?>
                        </h4>
                    </div>
                <?php endforeach; ?>
                <h3><a href="/events.php">Voir tous les événements</a></h3>
            </div>
            <h2 class="titre_vertical">EVENT</h2>

        </section>
    </div>
    <?php require __DIR__ . '/layouts/footer.php'; ?>
    <script src="/public/scripts/event_details_redirect.js"></script>
    <script src="/public/scripts/bubble.js"></script>
</body>

</html>