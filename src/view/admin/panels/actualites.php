<nav>
    <?php if (empty($actualites)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($actualites as $actualite): ?>
                <li
                    class="<?= ($selectedActualite && $selectedActualite['id_actualite'] == $actualite['id_actualite']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/actualites&id=<?= $actualite['id_actualite'] ?>">
                        <?= htmlspecialchars($actualite['titre_actualite']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/actualites/create">
        <button type="submit" class="btn-transparent navadd-btn">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter une actualité
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedActualite): ?>
        <div id="main_content">

            <div class="propertie">
                <div>
                    <p>Image de l'actualité</p>
                    <p>Mettre à jour l'image de présentation de l'actualité.</p>
                </div>
                <div>
                    <?php
                    $imgSrc = (!empty($selectedActualite['image_actualite']))
                        ? 'assets/image/api/news/' . htmlspecialchars($selectedActualite['image_actualite'])
                        : 'assets/image/default_images/event.jpg';
                    ?>
                    <img id="prop_image" src="<?= $imgSrc ?>" alt="Actualité">
                </div>
            </div>

            <form method="POST" action="/?page=admin-admin/actualites/uploadImage" enctype="multipart/form-data"
                class="propertie">
                <input type="hidden" name="id" value="<?= $selectedActualite['id_actualite'] ?>">
                <div>
                    <p>Modifier l'image</p>
                    <p>Sélectionner une nouvelle image (JPEG, PNG, WebP).</p>
                </div>
                <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn-blue btn-transparent">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </form>

            <form method="POST" action="/?page=admin-admin/actualites/save">
                <input type="hidden" name="id" value="<?= $selectedActualite['id_actualite'] ?>">

                <div class="propertie">
                    <div>
                        <p>Titre de l'actualité</p>
                        <p>Titre affiché pour présenter l'actualité en quelques mots.</p>
                    </div>
                    <div>
                        <input type="text" name="titre" placeholder="Titre de l'actualité"
                            value="<?= htmlspecialchars($selectedActualite['titre_actualite']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Date de publication</p>
                        <p>Date à laquelle l'actualité sera publiée.</p>
                    </div>
                    <div>
                        <input type="date" name="date"
                            value="<?= htmlspecialchars(explode(' ', $selectedActualite['date_actualite'])[0]) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Contenu</p>
                        <p>Corps de l'actualité.</p>
                    </div>
                </div>

                <textarea name="contenu"
                    placeholder="Contenu de l'actualité..."><?= htmlspecialchars($selectedActualite['contenu_actualite'] ?? '') ?></textarea>

                <div class="saves-buttons">
                    <button type="submit" class="btn-blue btn-transparent">
                        <img src="assets/image/admin/save.svg" alt="Sauvegarde">Sauvegarder
                    </button>
                </div>
            </form>

            <form method="POST" action="/?page=admin-admin/actualites/delete"
                onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedActualite['id_actualite'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>