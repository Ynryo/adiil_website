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
            if (hasPermission('p_boutique')) {
                echo '<li perm="boutique">
                        <img src="assets/image/admin/panels_icons/boutique.svg" alt="Icone de la boutique">
                        <p>Boutique</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_utilisateur')) {
                echo '<li perm="utilisateurs">
                        <img src="assets/image/admin/panels_icons/users.svg" alt="Icone des utilisateurs">
                        <p>Utilisateurs</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_grade')) {
                echo '<li perm="grades">
                        <img src="assets/image/admin/panels_icons/grades.svg" alt="Icone des grades">
                        <p>Grades</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_evenement')) {
                echo '<li perm="evenements">
                        <img src="assets/image/admin/panels_icons/events.svg" alt="Icone des événements">
                        <p>Evenements</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_comptabilite')) {
                echo '<li perm="comptabilite">
                        <img src="assets/image/admin/panels_icons/comptabilite.svg" alt="Icone de la comptabilite">
                        <p>Comptabilite</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_reunion')) {
                echo '<li perm="reunions">
                        <img src="assets/image/admin/panels_icons/reunions.svg" alt="Icone des réunions">
                        <p>Réunions</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_role')) {
                echo '<li perm="roles">
                        <img src="assets/image/admin/panels_icons/roles.svg" alt="Icone des roles">
                        <p>Rôles</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_actualite')) {
                echo '<li perm="actualites">
                        <img src="assets/image/admin/panels_icons/actualite.svg" alt="Icone des actualités">
                        <p>Actualités</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_boutique')) {
                echo '<li perm="history">
                        <img src="assets/image/admin/panels_icons/history.svg" alt="Icone de l\'historique d\'achat">
                        <p>Historique d\'achats</p>
                    </li>';
            }
            ?>
            <?php
            if (hasPermission('p_log')) {
                echo '<li perm="logs">
                        <img src="assets/image/admin/panels_icons/logs.svg" alt="Icone des logs du serveur">
                        <p>Logs du serveur</p>
                    </li>';
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