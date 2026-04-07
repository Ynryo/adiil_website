<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDE - Administration</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    <link rel="shortcut icon" href="assets/image/admin/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/admin/general.css">
    <link rel="stylesheet" href="assets/css/admin/admin.css">

</head>

<body id="main">
    <!-- Navigation -->
    <nav>
        <h1 href="/?base-home" style="cursor: pointer;">ADIIL - Admin</h1>
        <ul>
            <li perm="chat">
                <img src="assets/image/admin/panels_icons/chat.svg" alt="Icone du chat">
                <p>Chat</p>
            </li>
            <?php
            $onglets = [
                ["boutique", "p_boutique", "Boutique"], //nom img, id permission, nom onglet
                ["users", "p_utilisateur", "Utilisateurs"],
                ["grades", "p_grade", "Grades"],
                ["events", "p_evenement", "Événements"],
                ["comptabilite", "p_comptabilite", "Comptabilité"],
                ["reunions", "p_reunion", "Réunions"],
                ["roles", "p_role", "Rôles"],
                ["actualites", "p_actualite", "Actualités"],
                ["history", "p_boutique", "Historique"],
                ["logs", "p_log", "Logs"]
            ];
            foreach ($onglets as $onglet) {
                if (hasPermission($onglet[1])) {
                    echo '<li perm="' . $onglet[0] . '">
                        <img src="assets/image/admin/panels_icons/' . $onglet[0] . '.svg" alt="Icone de la ' . $onglet[0] . '">
                        <p>' . $onglet[2] . '</p>
                    </li>';
                }
            }
            ?>
        </ul>
    </nav>

    <!-- Permissions -->
    <main>
        <iframe frameborder="0" id="content" src="src/view/admin/chat.html"></iframe>
    </main>

    <!-- SCRIPT -->
    <script type="module" src="assets/js/admin/admin.js"></script>

</body>

</html>