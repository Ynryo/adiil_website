<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Mon compte</title>

    <link rel="stylesheet" href="assets/css/base/account_style.css">
    <link rel="stylesheet" href="assets/css/base/general_style.css">

</head>

<body class="body_margin">
    <?php require_once 'src/view/header.php'; ?>

    <H2>MON COMPTE</H2>

    <!-- Affichage du message de succès ou d'erreur -->
    <?php
    if (isset($_SESSION['message'])) {
        $messageStyle = isset($_SESSION['message_type']) && $_SESSION['message_type'] === "error" ? "error-message" : "success-message";
        echo '<div id="' . $messageStyle . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
        unset($_SESSION['message']); // Supprimer le message après affichage
        unset($_SESSION['message_type']); // Supprimer le type après affichage
    }
    ?>

    <section> <!-- Ensemble des différents formulaires du compte -->
        <!-- Partie contenant les informations générales sur le compte de l'utilisateur -->
        <div id="account-generalInfo">
            <div>
                <form method="POST" enctype="multipart/form-data" id="pp-form">

                    <label id="cadre-pp" for="profilePictureInput">
                        <img src=<?= $imgLink ?> alt="User pp" />
                    </label>

                    <input type="file" id="profilePictureInput" name="file" accept="image/jpeg, image/png, image/webp"
                        style="display: none;" onchange="this.form.submit()">

                    <button type="button" id="edit-icon"
                        onclick="document.getElementById('profilePictureInput').click()">
                        <img src="assets/image/base/edit_logo.png" alt="Icone edit pp" />
                    </button>
                </form>
            </div>
            <div>
                <p><?= $this->infoUser[0]['xp_membre']; ?></p>
                <p>XP</p>
            </div>
            <div id="cadre-grade">
                <?php if (empty($this->infoUser[0]['nom_grade'])): ?>
                    <p>Vous n'avez pas de grade</p>
                <?php else: ?>
                    <p><?= $this->infoUser[0]['nom_grade']; ?></p>
                    <?php if ($this->infoUser[0]['image_grade'] == null): ?>
                        <img src="/admin/ressources/default_images/grade.webp" alt="Logo du grade" />
                    <?php else: ?>
                        <img src="assets/image/api/<?= $this->infoUser[0]['image_grade']; ?>"
                            alt="Logo du grade de l'utilisateur" />
                    <?php endif ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire contenant les données personnelles de l'utilisateur -->
        <form method="POST" action="" id="account-personalInfo-form" class="container">
            <p>Mes informations personnelles :</p>
            <div>
                <div>
                    <input type="text" id="name" name="name" placeholder="Prénom"
                        value="<?= htmlspecialchars($this->infoUser[0]['prenom_membre']); ?>" required>
                    <input type="text" id="lastName" name="lastName" placeholder="Nom de famille"
                        value="<?= htmlspecialchars($this->infoUser[0]['nom_membre']); ?>" required>
                </div>
                <div>
                    <input type="email" id="mail" name="mail" placeholder="Adresse mail"
                        value="<?= htmlspecialchars($this->infoUser[0]['email_membre']); ?>" required>

                    <select id="tp" name="tp">
                        <option value="11A" <?= $this->infoUser[0]['tp_membre'] === '11A' ? 'selected' : ''; ?>>TP 11 A
                        </option>
                        <option value="11B" <?= $this->infoUser[0]['tp_membre'] === '11B' ? 'selected' : ''; ?>>TP 11 B
                        </option>
                        <option value="12C" <?= $this->infoUser[0]['tp_membre'] === '12C' ? 'selected' : ''; ?>>TP 12 C
                        </option>
                        <option value="12D" <?= $this->infoUser[0]['tp_membre'] === '12D' ? 'selected' : ''; ?>>TP 12 D
                        </option>
                        <option value="21A" <?= $this->infoUser[0]['tp_membre'] === '21A' ? 'selected' : ''; ?>>TP 21 A
                        </option>
                        <option value="21B" <?= $this->infoUser[0]['tp_membre'] === '21B' ? 'selected' : ''; ?>>TP 21 B
                        </option>
                        <option value="22C" <?= $this->infoUser[0]['tp_membre'] === '22C' ? 'selected' : ''; ?>>TP 22 C
                        </option>
                        <option value="22D" <?= $this->infoUser[0]['tp_membre'] === '22D' ? 'selected' : ''; ?>>TP 22 D
                        </option>
                        <option value="31A" <?= $this->infoUser[0]['tp_membre'] === '31A' ? 'selected' : ''; ?>>TP 31 A
                        </option>
                        <option value="31B" <?= $this->infoUser[0]['tp_membre'] === '31B' ? 'selected' : ''; ?>>TP 31 B
                        </option>
                        <option value="32C" <?= $this->infoUser[0]['tp_membre'] === '32C' ? 'selected' : ''; ?>>TP 32 C
                        </option>
                        <option value="32D" <?= $this->infoUser[0]['tp_membre'] === '32D' ? 'selected' : ''; ?>>TP 32 D
                        </option>
                    </select>
                </div>
            </div>

            <button type="submit" class="save-button">
                <img src="assets/image/base/save_logo.png" alt="Logo enregistrer les modifications" />
                Sauvegarder
            </button>
        </form>

        <!-- Formulaire permettant à l'utilisateur de modifier son mot de passe-->
        <form method="POST" action="" id="account-editPass-form" class="container">
            <p>Modifier mon mot de passe :</p>
            <div>
                <div>
                    <input type="password" id="mdp" name="mdp" placeholder="Mot de passe actuel">
                </div>
                <div>
                    <input type="password" id="newMdp" name="newMdp" placeholder="Nouveau mot de passe" required>
                    <input type="password" id="newMdpVerif" name="newMdpVerif" placeholder="Répétez le mot de passe"
                        required>
                </div>
            </div>

            <button type="submit" class="save-button">
                <img src="assets/image/base/save_logo.png" alt="Logo enregistrer les modifications" />
                Sauvegarder
            </button>
        </form>
    </section>

    <section> <!-- Ensemble des différents boutons du compte -->

        <div class="buttons-section container">
            <!--Discord-->
            <button type="button">
                <a href="https://discord.com/login" target="_blank">
                    <img src="assets/image/base/logo_discord.png" alt="Logo de Discord">
                    Associer mon compte à Discord
                </a>
            </button>

            <!--Deconnexion-->
            <form action="" method="post">
                <input type="hidden" name="deconnexion" value="true">
                <button type="submit">
                    <img src="assets/image/base/logOut_icon.png" alt="icone de deconnexion">
                    Déconnexion
                </button>
            </form>

            <!--Supprimer son compte-->
            <form action="/?page=base-deleteAccount" method="post">
                <input type="hidden" name="delete_account" value="true">
                <button type="submit">
                    <img src="assets/image/base/delete_icon.png" alt="icone de suppression">
                    Supprimer mon compte
                </button>
            </form>
        </div>
    </section>

    <!-- PARTIE MES ACHATS -->
    <section id="section-mesAchats">
        <h2>MES ACHATS</h2>

        <!--Zone du tableau-->
        <div id=historique-achats>

            <!-- Bouton pour afficher tout ou afficher moins -->
            <form method="GET" action="" id="viewAll-form">
                <input type="hidden" name="page" value="base-account">

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
                                <td><?= htmlspecialchars($achat['date_transaction']); ?></td>
                                <td><?= htmlspecialchars($achat['type_transaction']); ?></td>
                                <td><?= htmlspecialchars($achat['element']); ?></td>
                                <td><?= htmlspecialchars($achat['quantite']); ?></td>
                                <td><?= htmlspecialchars(number_format($achat['montant'], 2, ',', ' ')) . " €"; ?></td>
                                <td><?= htmlspecialchars($achat['mode_paiement']); ?></td>
                                <td><?= htmlspecialchars($achat['statut']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Vous n'avez effectué aucun achat pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php require_once 'src/view/footer.php'; ?>
</body>

</html>