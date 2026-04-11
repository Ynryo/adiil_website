<nav>
    <?php if (empty($articles)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($articles as $article): ?>
                <li
                    class="<?= ($selectedArticle && $selectedArticle['id_article'] == $article['id_article']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/boutique&id=<?= $article['id_article'] ?>">
                        <?= htmlspecialchars($article['nom_article']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/boutique/create">
        <button type="submit" class="navadd-btn btn-transparent">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter un article
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedArticle): ?>
        <div id="main_content">

            <div class="propertie">
                <div>
                    <p>Image de présentation</p>
                    <p>Mettre à jour l'image de présentation de l'article.</p>
                </div>
                <div>
                    <?php
                    $imgSrc = (!empty($selectedArticle['image_article']))
                        ? 'assets/image/api/article/' . htmlspecialchars($selectedArticle['image_article'])
                        : 'assets/image/default_images/boutique.png';
                    ?>
                    <img id="prop_image" src="<?= $imgSrc ?>" alt="Article">
                </div>
            </div>

            <form method="POST" action="/?page=admin-admin/boutique/uploadImage" enctype="multipart/form-data"
                class="propertie">
                <input type="hidden" name="id" value="<?= $selectedArticle['id_article'] ?>">
                <div>
                    <p>Modifier l'image</p>
                    <p>Sélectionner une nouvelle image (JPEG, PNG, WebP).</p>
                </div>
                <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn-blue btn-transparent">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </form>

            <form method="POST" action="/?page=admin-admin/boutique/save">
                <input type="hidden" name="id" value="<?= $selectedArticle['id_article'] ?>">

                <div class="propertie">
                    <div>
                        <p>Nom de l'article</p>
                        <p>Nom affiché de l'article sur la boutique.</p>
                    </div>
                    <div>
                        <input type="text" name="name" placeholder="Canette Oasis"
                            value="<?= htmlspecialchars($selectedArticle['nom_article']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Prix</p>
                        <p>Prix de l'article TTC, hors réduction.</p>
                    </div>
                    <div>
                        <input type="number" name="price" min="0" step="0.01"
                            value="<?= htmlspecialchars($selectedArticle['prix_article']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Catégorie</p>
                        <p>Catégorie pour trier les articles dans la boutique.</p>
                    </div>
                    <div>
                        <input type="text" name="categorie" placeholder="Snacks"
                            value="<?= htmlspecialchars($selectedArticle['categorie_article']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Quantités</p>
                        <p>Quantités disponibles à l'achat de l'article (-1 signifie illimité).</p>
                    </div>
                    <div>
                        <input type="number" name="stocks" min="-1"
                            value="<?= htmlspecialchars($selectedArticle['stock_article']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>XP</p>
                        <p>XP rapportée par l'achat de l'article.</p>
                    </div>
                    <div>
                        <input type="number" name="xp" value="<?= htmlspecialchars($selectedArticle['xp_article']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Réductions applicables</p>
                        <p>Est-ce que les réductions du prix des grades peuvent s'appliquer sur cet article ?</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="reduction" <?= $selectedArticle['reduction_article'] ? 'checked' : '' ?>>
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

            <form method="POST" action="/?page=admin-admin/boutique/delete"
                onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedArticle['id_article'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>