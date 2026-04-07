<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Événements</title>
    <link rel="stylesheet" href="/public/styles/events_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">
    <?php require __DIR__ . '/layouts/header.php'; ?>

    <h1>LES ÉVÉNEMENTS</h1>
    <section>
        <a class="show-more" href="/events.php?show=<?= $show + 10 ?>">Voir plus loin dans le passé</a>
        <div class="events-display">
            <?php foreach ($eventsData as $item): ?>
                <div class="event-box <?= htmlspecialchars($item['other_classes']) ?>" <?= $item['isClosest'] ? 'id="closest-event"' : '' ?>>
                    <div class="timeline-event">
                        <h4>
                            <?= htmlspecialchars($item['formattedDate']) ?>
                        </h4>
                        <div class="vertical-line"></div>
                        <p>
                            <?= htmlspecialchars($item['date_pin_label']) ?>
                        </p>
                        <div class="timeline-marker <?= htmlspecialchars($item['date_pin_class']) ?>">
                            <div class="time-line"></div>
                        </div>
                    </div>
                    <div class="event" event-id="<?= (int) $item['eventid'] ?>">
                        <div>
                            <h2>
                                <?= htmlspecialchars($item['event']['nom_evenement']) ?>
                            </h2>
                            <?= htmlspecialchars(ucwords($item['event']["lieu_evenement"])) ?>
                        </div>
                        <h4 class="<?= htmlspecialchars($item['colorClass']) ?>">
                            <?= htmlspecialchars($item['label']) ?>
                        </h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require __DIR__ . '/layouts/footer.php'; ?>
    <script src="/public/scripts/event_details_redirect.js"></script>
    <script src="/public/scripts/scroll_to_closest_event.js"></script>
</body>

</html>