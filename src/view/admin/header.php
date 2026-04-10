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
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" />
    <link rel="shortcut icon" href="assets/image/admin/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/admin/general.css">
    <link rel="stylesheet" href="assets/css/admin/admin.css">
    <link rel="stylesheet" href="assets/css/admin/panels.css">
</head>

<body id="main">
    <nav>
        <a href="/?page=base-home" class="logo">
            <img src="assets/image/base/logo.png" alt="Logo de l'ADIIL">
            <h1>Administration</h1>
        </a>
        <ul>
            <?php
            $onglets = [ //nom img, id permission, nom onglet
                ["chat", "p_chat", "Chat"],
                ["boutique", "p_boutique", "Boutique"],
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
                if ($onglet[1] == "p_chat" || hasPermission($onglet[1])) {
                    echo '<li perm="' . $onglet[0] . '">
                        <a href="/?page=admin-admin/' . $onglet[0] . '">
                            <img src="assets/image/admin/panels_icons/' . $onglet[0] . '.svg" alt="Icone de la ' . $onglet[0] . '">
                            <p>' . $onglet[2] . '</p>
                        </a>
                    </li>';
                }
            }
            ?>
        </ul>
    </nav>