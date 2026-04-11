<nav>
    <?php if (empty($membres)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($membres as $membre): ?>
                <li class="<?= ($selectedUser && $selectedUser['id_membre'] == $membre['id_membre']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/users&id=<?= $membre['id_membre'] ?>">
                        <?= htmlspecialchars($membre['prenom_membre'] . ' ' . strtoupper($membre['nom_membre'])) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/users/create">
        <button type="submit" class="btn-transparent navadd-btn">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter un utilisateur
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedUser): ?>
        <div id="main_content">

            <div class="propertie">
                <div>
                    <p>Photo de profil</p>
                    <p>Mettre à jour l'image de photo de profil de l'utilisateur.</p>
                </div>
                <div>
                    <?php
                        // Note: Depending on where pictures are stored. We assume the same logic as items.
                        $imgSrc = (!empty($selectedUser['pp_membre']) && $selectedUser['pp_membre'] !== 'N/A')
                            ? 'assets/image/api/user/' . htmlspecialchars($selectedUser['pp_membre'])
                            : 'assets/image/default_images/user.jpg';
                    ?>
                    <img id="prop_img" src="<?= $imgSrc ?>" alt="Profil de l'utilisateur">
                </div>
            </div>

            <form method="POST" action="/?page=admin-admin/users/uploadImage" enctype="multipart/form-data" class="propertie">
                <input type="hidden" name="id" value="<?= $selectedUser['id_membre'] ?>">
                <div>
                    <p>Modifier la photo</p>
                    <p>Sélectionner une nouvelle image (JPEG, PNG, WebP).</p>
                </div>
                <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn-blue btn-transparent">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </form>

            <form method="POST" action="/?page=admin-admin/users/save">
                <input type="hidden" name="id" value="<?= $selectedUser['id_membre'] ?>">

                <div class="propertie">
                    <div>
                        <p>Nom de famille</p>
                        <p>Nom de famille affiché de l'utilisateur.</p>
                    </div>
                    <div>
                        <input type="text" name="nom" placeholder="Barbecue de l'ADIIL" value="<?= htmlspecialchars($selectedUser['nom_membre'] !== 'N/A' ? $selectedUser['nom_membre'] : '') ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Prénom</p>
                        <p>Prénom affiché de l'utilisateur.</p>
                    </div>
                    <div>
                        <input type="text" name="prenom" placeholder="Parking Batiment Info" value="<?= htmlspecialchars($selectedUser['prenom_membre'] !== 'N/A' ? $selectedUser['prenom_membre'] : '') ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Adresse e-mail</p>
                        <p>Adresse e-mail associée à l'utilisateur.</p>
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="example@univ-lemans.fr" value="<?= htmlspecialchars($selectedUser['email_membre'] !== 'N/A' ? $selectedUser['email_membre'] : '') ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Points d'expériences</p>
                        <p>Sélectionnez le niveau d'xp de l'utilisateur.</p>
                    </div>
                    <div>
                        <input type="number" name="xp" placeholder="512" min="-42" value="<?= htmlspecialchars($selectedUser['xp_membre']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>TP</p>
                        <p>Sélectionnez le groupe de l'utilisateur.</p>
                    </div>
                    <div>
                        <select name="tp" id="prop_tp">
                            <?php 
                            $options = ['11a', '11b', '12c', '12d', '21a', '21b', '22c', '22d', '31a', '31b', '32c', '32d', '']; 
                            foreach($options as $opt): 
                                $selected = ($selectedUser['tp_membre'] == $opt) ? 'selected' : '';
                                $label = ($opt === '') ? 'Aucun' : $opt;
                            ?>
                                <option value="<?= htmlspecialchars($opt) ?>" <?= $selected ?>><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Rôles</p>
                        <p>Lister les rôles à attribuer aux utilisateurs.</p>
                    </div>
                    <div id="prop_roles" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <?php foreach($allRoles as $role): ?>
                            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer; background: var(--background-color-whiter); padding: 5px 10px; border-radius: 8px; border: 1px solid var(--border-color);">
                                <input type="checkbox" name="roles[]" value="<?= $role['id_role'] ?>" <?= in_array($role['id_role'], $userRoles) ? 'checked' : '' ?>>
                                <span><?= htmlspecialchars($role['nom_role']) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="saves-buttons">
                    <button type="submit" class="btn-blue btn-transparent">
                        <img src="assets/image/admin/save.svg" alt="Sauvegarde">Sauvegarder
                    </button>
                </div>
            </form>

            <form method="POST" action="/?page=admin-admin/users/delete" onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedUser['id_membre'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>
