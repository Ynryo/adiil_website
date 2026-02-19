<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/public/styles/account_style.css">
    <link rel="stylesheet" href="/public/styles/general_style.css">
    <link rel="stylesheet" href="/public/styles/header_style.css">
    <link rel="stylesheet" href="/public/styles/footer_style.css">

</head>

<body class="body_margin">

    <?php
    require_once "header.php";

    use App\Helpers\FileSave;
    use App\Database\DB;

    use App\Helpers\Session;
    use App\Helpers\Csrf;

    $db = DB::getInstance();

    // Rediriger si non connecté
    if (!Session::isLoggedIn()) {
        header("Location: /login.php");
        exit;
    }

    $userId = Session::getUserId();

    // --- Traitement des formulaires POST ---
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        Csrf::check();

        // Déconnexion
        if (isset($_POST['deconnexion']) && $_POST['deconnexion'] === 'true') {
            Session::destroy();
            header("Location: /index.php");
            exit();
        }

        // Upload photo de profil
        if (isset($_FILES['file']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $fileName = FileSave::saveImage();

            if ($fileName !== null) {
                // Récupérer l'ancienne image pour la supprimer
                $currentPp = $db->select("SELECT pp_membre FROM MEMBRE WHERE id_membre = ?", "i", [$userId]);
                if (!empty($currentPp[0]['pp_membre'])) {
                    deleteFile($currentPp[0]['pp_membre']);
                }

                $db->query(
                    "UPDATE MEMBRE SET pp_membre = ? WHERE id_membre = ?",
                    "si",
                    [$fileName, $userId]
                );

                Session::flash("Mise à jour de la photo de profil réussie !", "success");
            } else {
                Session::flash("Erreur : veuillez vérifier le fichier envoyé.", "error");
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        // Modification des informations personnelles
        if (isset($_POST['name'], $_POST['lastName'], $_POST['mail'])) {
            $currentUserData = $db->select(
                "SELECT prenom_membre, nom_membre, email_membre, tp_membre FROM MEMBRE WHERE id_membre = ?",
                "i",
                [$userId]
            );

            if (!empty($currentUserData)) {
                $current = $currentUserData[0];
                $name = !empty($_POST['name']) ? htmlspecialchars($_POST['name']) : $current['prenom_membre'];
                $lastName = !empty($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : $current['nom_membre'];
                $mail = !empty($_POST['mail']) ? htmlspecialchars($_POST['mail']) : $current['email_membre'];
                $tp = !empty($_POST['tp']) ? htmlspecialchars($_POST['tp']) : $current['tp_membre'];

                // Vérifier unicité email
                $existingEmail = $db->select(
                    "SELECT id_membre FROM MEMBRE WHERE email_membre = ? AND id_membre != ?",
                    "si",
                    [$mail, $userId]
                );

                if (!empty($existingEmail)) {
                    Session::flash("Les modifications n'ont pas pu être effectuées car l'adresse e-mail est déjà utilisée par un autre compte.", "error");
                } else {
                    $db->query(
                        "UPDATE MEMBRE SET prenom_membre = ?, nom_membre = ?, email_membre = ?, tp_membre = ? WHERE id_membre = ?",
                        "ssssi",
                        [$name, $lastName, $mail, $tp, $userId]
                    );
                    Session::flash("Vos informations ont été mises à jour avec succès !", "success");
                }
            } else {
                Session::flash("Erreur : utilisateur introuvable dans la base de données.", "error");
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Modification du mot de passe
        if (isset($_POST['mdp'], $_POST['newMdp'], $_POST['newMdpVerif'])) {
            // Ne PAS htmlspecialchars les mots de passe
            $currentPassword = trim($_POST['mdp']);
            $newPassword = trim($_POST['newMdp']);
            $newPasswordVerif = trim($_POST['newMdpVerif']);

            $user = $db->select(
                "SELECT password_membre FROM MEMBRE WHERE id_membre = ?",
                "i",
                [$userId]
            );

            if (!empty($user)) {
                $password_ok = false;
                if ($user[0]['password_membre'] === null && $currentPassword === "") {
                    $password_ok = true;
                } else {
                    $password_ok = password_verify($currentPassword, $user[0]['password_membre']);
                }

                if ($password_ok && $newPassword === $newPasswordVerif) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $db->query(
                        "UPDATE MEMBRE SET password_membre = ? WHERE id_membre = ?",
                        "si",
                        [$hashedPassword, $userId]
                    );
                    Session::flash("Mot de passe mis à jour avec succès !", "success");
                } else {
                    Session::flash("Mot de passe actuel incorrect ou les nouveaux mots de passe ne correspondent pas.", "error");
                }
            } else {
                Session::flash("Erreur : utilisateur introuvable.", "error");
            }

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // --- Préparation des données pour l'affichage ---
    
    $infoUser = $db->select(
        "SELECT pp_membre, xp_membre, prenom_membre, nom_membre, email_membre, tp_membre, discord_token_membre, nom_grade, image_grade FROM MEMBRE LEFT JOIN ADHESION ON MEMBRE.id_membre = ADHESION.id_membre LEFT JOIN GRADE ON ADHESION.id_grade = GRADE.id_grade WHERE MEMBRE.id_membre = ?;",
        "i",
        [$userId]
    );

    $flash = Session::getFlash();

    $viewAll = isset($_GET['viewAll']) && $_GET['viewAll'] === '1';

    $sql = "SELECT type_transaction, element, quantite, montant, mode_paiement, date_transaction,
        CASE WHEN recupere = 1 THEN 'Récupéré' ELSE 'Non récupéré' END AS statut
        FROM HISTORIQUE_COMPLET WHERE id_membre=? ORDER BY date_transaction DESC";
    if (!$viewAll) {
        $sql .= " LIMIT 6";
    }
    $historiqueAchats = $db->select($sql, "i", [$userId]);
    ?>

    <!-- Affichage du message flash -->
    <?php if ($flash): ?>
        <div id="<?= $flash['type'] === 'error' ? 'error-message' : 'success-message' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <h2>MON COMPTE</h2>

    <section>
        <!-- Informations générales -->
        <div id="account-generalInfo">
            <div>
                <form method="POST" enctype="multipart/form-data" id="pp-form">
                    <?= Csrf::field() ?>
                    <label id="cadre-pp" for="profilePictureInput">
                        <?php if ($infoUser[0]['pp_membre'] === null): ?>
                            <img src="/admin/ressources/default_images/user.jpg" alt="Photo de profil de l'utilisateur" />
                        <?php else: ?>
                            <img src="/api/files/<?= htmlspecialchars($infoUser[0]['pp_membre']) ?>"
                                alt="Photo de profil de l'utilisateur" />
                        <?php endif ?>
                    </label>

                    <input type="file" id="profilePictureInput" name="file" accept="image/jpeg, image/png, image/webp"
                        style="display: none;" onchange="this.form.submit()">

                    <button type="button" id="edit-icon"
                        onclick="document.getElementById('profilePictureInput').click()">
                        <img src="/public/assets/edit_logo.png" alt="Éditer la photo de profil" />
                    </button>
                </form>
            </div>
            <div>
                <p><?= (int) $infoUser[0]['xp_membre'] ?></p>
                <p>XP</p>
            </div>
            <div id="cadre-grade">
                <?php if (empty($infoUser[0]['nom_grade'])): ?>
                    <p>Vous n'avez pas de grade</p>
                <?php else: ?>
                    <p><?= htmlspecialchars($infoUser[0]['nom_grade']) ?></p>
                    <?php if ($infoUser[0]['image_grade'] === null): ?>
                        <img src="/admin/ressources/default_images/grade.webp" alt="Image du grade" />
                    <?php else: ?>
                        <img src="/api/files/<?= htmlspecialchars($infoUser[0]['image_grade']) ?>"
                            alt="Illustration du grade de l'utilisateur" />
                    <?php endif ?>
                    <div></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire informations personnelles -->
        <form method="POST" action="" id="account-personalInfo-form">
            <?= Csrf::field() ?>
            <div>
                <div>
                    <input type="text" id="name" name="name" placeholder="Prénom"
                        value="<?= htmlspecialchars($infoUser[0]['prenom_membre']) ?>" required>
                    <input type="text" id="lastName" name="lastName" placeholder="Nom de famille"
                        value="<?= htmlspecialchars($infoUser[0]['nom_membre']) ?>" required>
                </div>
                <div>
                    <input type="email" id="mail" name="mail" placeholder="Adresse mail"
                        value="<?= htmlspecialchars($infoUser[0]['email_membre']) ?>" required>

                    <?php if (!empty($infoUser[0]['tp_membre'])): ?>
                        <select id="tp" name="tp">
                            <?php
                            $tpOptions = ['11A', '11B', '12C', '12D', '21A', '21B', '22C', '22D', '31A', '31B', '32C', '32D'];
                            foreach ($tpOptions as $tp):
                                ?>
                                <option value="<?= $tp ?>" <?= $infoUser[0]['tp_membre'] === $tp ? 'selected' : '' ?>>TP
                                    <?= substr($tp, 0, 2) . ' ' . substr($tp, 2) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit">
                <img src="/public/assets/save_logo.png" alt="Enregistrer les modifications" />
            </button>
        </form>

        <!-- Formulaire modification mot de passe -->
        <form method="POST" action="" id="account-editPass-form">
            <?= Csrf::field() ?>
            <div>
                <div>
                    <p>Modifier mon mot de passe :</p>
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe actuel">
                </div>
                <div>
                    <input type="password" id="newMdp" name="newMdp" placeholder="Nouveau mot de passe" required>
                    <input type="password" id="newMdpVerif" name="newMdpVerif"
                        placeholder="Confirmation du nouveau mot de passe" required>
                </div>
            </div>

            <button type="submit"><img src="/public/assets/save_logo.png" alt="Enregistrer le mot de passe" /></button>
        </form>
    </section>

    <section>
        <div id="buttons-section">
            <!-- Discord -->
            <button type="button">
                <a href="https://discord.com/login" target="_blank">
                    <img src="/public/assets/logo_discord.png" alt="Logo de Discord">
                    Associer mon compte à Discord
                </a>
            </button>

            <!-- Déconnexion -->
            <form action="" method="post">
                <?= Csrf::field() ?>
                <input type="hidden" name="deconnexion" value="true">
                <button type="submit">
                    <img src="/public/assets/logOut_icon.png" alt="Icône de déconnexion">
                    Déconnexion
                </button>
            </form>

            <!-- Supprimer son compte -->
            <form action="delete_account.php" method="post">
                <?= Csrf::field() ?>
                <input type="hidden" name="delete_account" value="true">
                <button type="submit">
                    <img src="/public/assets/delete_icon.png" alt="Icône de suppression">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </section>

    <!-- MES ACHATS -->
    <section id="section-mesAchats">
        <h2>MES ACHATS</h2>

        <div id="historique-achats">
            <form method="GET" action="#section-mesAchats" id="viewAll-form">
                <?php if ($viewAll): ?>
                    <button type="submit" name="viewAll" value="0">Afficher moins</button>
                <?php else: ?>
                    <button type="submit" name="viewAll" value="1">Afficher tout</button>
                <?php endif; ?>
            </form>

            <?php if (!empty($historiqueAchats)): ?>
                <table id="tab-historique-achats">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Mode de paiement</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historiqueAchats as $achat): ?>
                            <tr>
                                <td><?= htmlspecialchars($achat['date_transaction']) ?></td>
                                <td><?= htmlspecialchars($achat['type_transaction']) ?></td>
                                <td><?= htmlspecialchars($achat['element']) ?></td>
                                <td><?= htmlspecialchars($achat['quantite']) ?></td>
                                <td><?= htmlspecialchars(number_format($achat['montant'], 2, ',', ' ')) ?> €</td>
                                <td><?= htmlspecialchars($achat['mode_paiement']) ?></td>
                                <td><?= htmlspecialchars($achat['statut']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Vous n'avez effectué aucun achat pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once "footer.php" ?>
</body>

</html>