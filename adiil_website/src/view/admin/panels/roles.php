<nav>
    <?php if (empty($roles)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($roles as $role): ?>
                <li class="<?= ($selectedRole && $selectedRole['id_role'] == $role['id_role']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/roles&id=<?= $role['id_role'] ?>">
                        <?= htmlspecialchars($role['nom_role']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/roles/create">
        <button type="submit" class="btn-transparent navadd-btn">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter un rôle
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedRole): ?>
        <div id="main_content">

            <form method="POST" action="/?page=admin-admin/roles/save">
                <input type="hidden" name="id" value="<?= $selectedRole['id_role'] ?>">

                <div class="propertie">
                    <div>
                        <p>Nom du rôle</p>
                        <p>Nom affiché du rôle sur l'interface administrateur.</p>
                    </div>
                    <div>
                        <input type="text" name="nom" placeholder="Comptable"
                            value="<?= htmlspecialchars($selectedRole['nom_role']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion de la boutique</p>
                        <p>Ajouter, modifier ou supprimer des éléments de la boutique</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_boutique" <?= $selectedRole['p_boutique_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion des utilisateurs</p>
                        <p>Pouvoir modifier l'adresse mail, le nom le prénom et l'xp d'un membre</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_utilisateur" <?= $selectedRole['p_utilisateur_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion des grades</p>
                        <p>Gestion des grades, de leur nom, de leur prix et de leur réduction</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_grade" <?= $selectedRole['p_grade_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion Rôles</p>
                        <p>Modifier les noms des rôles, et leur permissions</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_role" <?= $selectedRole['p_roles_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion des actualités</p>
                        <p>Création, modification, publication et suppression d'actualités</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_actualite" <?= $selectedRole['p_actualite_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion des événements</p>
                        <p>Création, modification, publication et suppression d'événements</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_evenement" <?= $selectedRole['p_evenements_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion de la comptabité</p>
                        <p>Création, modification, publication de fiches de comptabilité.</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_comptabilite" <?= $selectedRole['p_comptabilite_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Gestion des réunions</p>
                        <p>Permet de créer, modifier et supprimer des réunions ainsi que d'ajouter les compte-rendus liées a
                            celle-çi.</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_reunion" <?= $selectedRole['p_reunion_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Vue des historiques des achats</p>
                        <p>Accès a la liste des derniers achats des utilisateurs.</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_achat" <?= $selectedRole['p_achats_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Vue des logs du server</p>
                        <p>Accède a la vue des derniers logs de la console du serveur.</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_log" <?= $selectedRole['p_log_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Moderation du contenu utilisateur</p>
                        <p>Permet la suppression de contenu utilisateur jugée inaproprié.</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="p_moderation" <?= $selectedRole['p_moderation_role'] ? 'checked' : '' ?>>
                            <span>Activé</span>
                        </label>
                    </div>
                </div>

                <div class="saves-buttons">
                    <button type="submit" class="btn-blue btn-transparent">
                        <img src="assets/image/admin/save.svg" alt="Sauvegarde">Sauvegarder
                    </button>
                </div>
            </form>

            <form method="POST" action="/?page=admin-admin/roles/delete"
                onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedRole['id_role'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>