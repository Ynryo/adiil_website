<link rel="shortcut icon" href="/admin/ressources/favicon.png" type="image/x-icon">

<?php
    @session_start();
    $isUserLoggedIn = isset($_SESSION['userid']);
    $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ;
?>


<!-- HEADER -->
<header>
    <a id="accueil" href="">
        <img src="assets/image/base/logo.png" alt="Logo de l'ADIIL">
    </a>
    <nav>
        <ul>
            <li>
                <a href="/?page=base-events">Événements</a>
            </li>
            <li>
                <a href="/?page=base-news">Actualités</a>
            </li>
            <li>
                <a href="/?page=base-shop">Boutique</a>
            </li>
            <li>
                <a href="/?page=base-grade">Grades</a>
            </li>
            
            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="/?page=base-agenda">Agenda</a>
                </li>
            <?php endif; ?>

            <li>
                <a href="/?page=base-about">À propos</a>
            </li>

            <?php if ($isUserLoggedIn): ?>
                <li>
                    <a href="/?page=base-account">Mon compte</a>
                </li>

                <?php if ($isAdmin): ?>
                  <li>
                      <a id="header_admin" href="/?page=admin/admin">Panel Admin</a>
                  </li>
                <?php endif; ?>

            <?php else: ?>
                <li>
                    <a href="/?page=login">Se connecter</a>
                </li>
            <?php endif; ?>

      
        </ul>
    </nav>
</header>
