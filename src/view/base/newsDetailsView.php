<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title><?= $actu['titre_actualite']?></title>

    <link rel="stylesheet" href="assets/css/base/general_style.css">
    <link rel="stylesheet" href="assets/css/base/header_style.css">
    <link rel="stylesheet" href="assets/css/base/footer_style.css">
    <link rel="stylesheet" href="assets/css/base/event_details_style.css">
</head>

<body>
    <?php require_once 'src/view/header.php'; ?>

    <section class="event-details">
        <img src="<?= $imgLink ?>" alt="Image de l'actualite">
        <h1><?= strtoupper($actu['titre_actualite']); ?></h1>

        <div>
            <h2>
                <?php
                    $current_date = new DateTime(date("Y-m-d"));
                    $actu_date = new DateTime(substr($actu['date_actualite'], 0, 10));
                    echo date('d/m/Y', strtotime($actu['date_actualite']));
                ?>
            </h2>
        </div>
        <ul></ul>
        <p>
            <?= nl2br(htmlspecialchars($actu['contenu_actualite'])); ?>
        </p>

    </section>


    <?php require_once 'src/view/footer.php' ?>
</body>

</html>