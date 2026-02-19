<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Actualités</title>
    <link rel="stylesheet" href="/public/styles/news_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">
    <?php
    require_once 'header.php';
use App\Database\DB;
    
    $db = DB::getInstance();
    $show = 5;

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && is_numeric($_GET['show'])) {
        $show = (int) $_GET['show'];
    }

    // --- Préparation des données ---
    $joursFr = [0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
    $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];
    $current_date = new DateTime(date("Y-m-d"));

    $news_items = $db->select(
        "SELECT id_actualite, titre_actualite, date_actualite FROM ACTUALITE WHERE date_actualite <= NOW() ORDER BY date_actualite ASC LIMIT ?;",
        "i",
        [$show]
    );

    $newsData = [];
    $closestFound = false;

    foreach ($news_items as $item) {
        $newsId = $item["id_actualite"];
        $news_date_str = substr($item['date_actualite'], 0, 10);
        $news_date_info = getdate(strtotime($news_date_str));
        $news_date = new DateTime($news_date_str);

        $isClosest = false;
        if ($news_date == $current_date || !$closestFound) {
            $isClosest = true;
            $closestFound = true;
        }

        $formattedDate = ucwords($joursFr[$news_date_info['wday']] . " " . $news_date_info["mday"] . " " . $moisFr[$news_date_info['mon']]);

        $newsData[] = [
            'id' => $newsId,
            'title' => $item['titre_actualite'],
            'formattedDate' => $formattedDate,
            'isClosest' => $isClosest,
        ];
        // Only the first one gets closest
        if ($isClosest) {
            $closestFound = true;
        }
    }
    ?>

    <h1>ACTUALITÉS</h1>
    <section>
        <a class="show-more" href="/news.php?show=<?= $show + 10 ?>">Voir plus loin dans le passé</a>
        <div class="events-display">
            <?php foreach ($newsData as $item): ?>
                <div class="event-box" <?= $item['isClosest'] ? 'id="closest-event"' : '' ?>>
                    <div class="timeline-event">
                        <h4><?= htmlspecialchars($item['formattedDate']) ?></h4>
                        <div class="vertical-line"></div>
                    </div>
                    <div class="event" event-id="<?= (int) $item['id'] ?>">
                        <div>
                            <h2 style="margin-bottom: 0px;"><?= htmlspecialchars($item['title']) ?></h2>
                        </div>
                        <h4 class="event-not-subscribed">Consulter</h4>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?php require_once "footer.php" ?>

    <script src="/public/scripts/news_details_redirect.js"></script>
    <script src="/public/scripts/scroll_to_closest_event.js"></script>

</body>

</html>