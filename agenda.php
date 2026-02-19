<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/planner_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">
</head>

<body class="body_margin">

    <?php require_once "header.php"; ?>

    <h1>Agenda</h1>
    <div>
        <iframe src="https://edt.gemino.dev" title="Emploi du temps"></iframe>
    </div>

    <?php require_once "footer.php" ?>
</body>

</html>