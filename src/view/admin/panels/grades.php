<nav>
    <?php if (empty($grades)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($grades as $grade): ?>
                <li class="<?= ($selectedGrade && $selectedGrade['id_grade'] == $grade['id_grade']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/grades&id=<?= $grade['id_grade'] ?>">
                        <?= htmlspecialchars($grade['nom_grade']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/grades/create">
        <button type="submit" class="btn-transparent navadd-btn">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter un grade
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedGrade): ?>
        <div id="main_content">

            <div class="propertie">
                <div>
                    <p>Image de présentation</p>
                    <p>Mettre à jour l'image de présentation du produit.</p>
                </div>
                <div>
                    <?php
                        $imgSrc = (!empty($selectedGrade['image_grade']) && $selectedGrade['image_grade'] !== 'N/A')
                            ? 'assets/image/api/grade/' . htmlspecialchars($selectedGrade['image_grade'])
                            : 'assets/image/default_images/grade.webp';
                    ?>
                    <img id="prop_image_grade" src="<?= $imgSrc ?>" alt="Grade">
                </div>
            </div>

            <form method="POST" action="/?page=admin-admin/grades/uploadImage" enctype="multipart/form-data" class="propertie">
                <input type="hidden" name="id" value="<?= $selectedGrade['id_grade'] ?>">
                <div>
                    <p>Modifier la photo</p>
                    <p>Sélectionner une nouvelle image (JPEG, PNG, WebP).</p>
                </div>
                <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn-blue btn-transparent">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </form>

            <form method="POST" action="/?page=admin-admin/grades/save">
                <input type="hidden" name="id" value="<?= $selectedGrade['id_grade'] ?>">

                <div class="propertie">
                    <div>
                        <p>Nom affiché</p>
                        <p>Nom affiché sur le profil et dans la boutique.</p>
                    </div>
                    <div>
                        <input type="text" name="nom" placeholder="Or" value="<?= htmlspecialchars($selectedGrade['nom_grade']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Description affiché</p>
                        <p>Description affiché dans la boutique.</p>
                    </div>
                    <div>
                        <input type="text" name="description" placeholder="Or" value="<?= htmlspecialchars($selectedGrade['description_grade']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Prix d'achat</p>
                        <p>Prix à débourser pour obtenir le grade.</p>
                    </div>
                    <div>
                        <input type="number" name="prix" placeholder="13" min="0" step="0.01" value="<?= htmlspecialchars($selectedGrade['prix_grade']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Réduction sur les achats</p>
                        <p>Taux de réduction (en pourcentage) à appliquer à tous les achats éligibles sur la boutique. 0 pour désactiver.</p>
                    </div>
                    <div>
                        <input type="number" name="reduction" placeholder="5" min="0" max="100" value="<?= htmlspecialchars($selectedGrade['reduction_grade']) ?>">
                    </div>
                </div>

                <div class="saves-buttons">
                    <button type="submit" class="btn-blue btn-transparent">
                        <img src="assets/image/admin/save.svg" alt="Sauvegarde">Sauvegarder
                    </button>
                </div>
            </form>

            <form method="POST" action="/?page=admin-admin/grades/delete" onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedGrade['id_grade'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>
