<?php

require_once __DIR__ . '/../bootstrap.php';
use App\Helpers\Tools;
use App\Helpers\Session;

Session::start();

if (!Session::isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}
if (!Session::isAdmin()) {
    header('Location: /admin/panels/unauthorized.html');
    exit();
}

// Définir les entrées de navigation avec leurs permissions
$navItems = [
    ['perm' => null, 'key' => 'chat', 'icon' => 'chat.svg', 'label' => 'Chat'],
    ['perm' => 'p_boutique', 'key' => 'boutique', 'icon' => 'boutique.svg', 'label' => 'Boutique'],
    ['perm' => 'p_utilisateur', 'key' => 'utilisateurs', 'icon' => 'users.svg', 'label' => 'Utilisateurs'],
    ['perm' => 'p_grade', 'key' => 'grades', 'icon' => 'grades.svg', 'label' => 'Grades'],
    ['perm' => 'p_evenement', 'key' => 'evenements', 'icon' => 'events.svg', 'label' => 'Evenements'],
    ['perm' => 'p_comptabilite', 'key' => 'comptabilite', 'icon' => 'comptabilite.svg', 'label' => 'Comptabilité'],
    ['perm' => 'p_reunion', 'key' => 'reunions', 'icon' => 'reunions.svg', 'label' => 'Réunions'],
    ['perm' => 'p_role', 'key' => 'roles', 'icon' => 'roles.svg', 'label' => 'Rôles'],
    ['perm' => 'p_actualite', 'key' => 'actualites', 'icon' => 'actualite.svg', 'label' => 'Actualités'],
    ['perm' => 'p_boutique', 'key' => 'history', 'icon' => 'history.svg', 'label' => "Historique d'achats"],
    ['perm' => 'p_log', 'key' => 'logs', 'icon' => 'logs.svg', 'label' => 'Logs du serveur'],
];
?>

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

    <link rel="shortcut icon" href="ressources/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="styles/general.css">
    <link rel="stylesheet" href="styles/admin.css">

</head>

<body id="main">

    <!-- Navigation -->
    <nav>
        <h1 onclick="window.location.href='/'" style="cursor: pointer;">ADIIL - Admin</h1>

        <ul>
            <?php foreach ($navItems as $item): ?>
                <?php if ($item['perm'] === null || Tools::hasPermission($item['perm'])): ?>
                    <li perm="<?= htmlspecialchars($item['key']) ?>">
                        <img src="ressources/panels_icons/<?= htmlspecialchars($item['icon']) ?>"
                            alt="Icone <?= htmlspecialchars($item['label']) ?>">
                        <p><?= htmlspecialchars($item['label']) ?></p>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Contenu principal -->
    <main>
        <iframe frameborder="0" id="content" src="./panels/chat.html" title="Panneau d'administration"></iframe>
    </main>

    <script type="module" src="scripts/admin.js"></script>

</body>

</html>