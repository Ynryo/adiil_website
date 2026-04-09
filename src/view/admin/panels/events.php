<nav>
    <?php if (empty($evenements)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($evenements as $evenement): ?>
                <li
                    class="<?= ($selectedEvent && $selectedEvent['id_evenement'] == $evenement['id_evenement']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/events&id=<?= $evenement['id_evenement'] ?>">
                        <?= htmlspecialchars($evenement['nom_evenement']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/?page=admin-admin/events/create">
        <button type="submit" class="btn-transparent navadd-btn">
            <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter un événement
        </button>
    </form>
</nav>

<main>
    <?php if ($selectedEvent): ?>
        <div id="main_content">

            <div class="propertie">
                <div>
                    <p>Image de présentation</p>
                    <p>Mettre à jour l'image de présentation de l'événement.</p>
                </div>
                <div>
                    <?php
                    $imgSrc = (!empty($selectedEvent['image_evenement']))
                        ? 'assets/image/api/event/' . htmlspecialchars($selectedEvent['image_evenement'])
                        : 'assets/image/default_images/event.jpg';
                    ?>
                    <img id="prop_image" src="<?= $imgSrc ?>" alt="Événement">
                </div>
            </div>

            <form method="POST" action="/?page=admin-admin/events/uploadImage" enctype="multipart/form-data"
                class="propertie">
                <input type="hidden" name="id" value="<?= $selectedEvent['id_evenement'] ?>">
                <div>
                    <p>Modifier l'image</p>
                    <p>Sélectionner une nouvelle image (JPEG, PNG, WebP).</p>
                </div>
                <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
                <button type="submit" class="btn-blue btn-transparent">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </form>

            <form method="POST" action="/?page=admin-admin/events/save">
                <input type="hidden" name="id" value="<?= $selectedEvent['id_evenement'] ?>">

                <div class="propertie">
                    <div>
                        <p>Nom de l'événement</p>
                        <p>Nom affiché de l'événement sur le calendrier.</p>
                    </div>
                    <div>
                        <input type="text" name="nom" placeholder="Barbecue de l'ADIIL"
                            value="<?= htmlspecialchars($selectedEvent['nom_evenement']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Description</p>
                        <p>Description de l'événement.</p>
                    </div>
                    <div>
                        <input type="text" name="description" placeholder="Super barbecue de l'ADIIL"
                            value="<?= htmlspecialchars($selectedEvent['description_evenement'] ?? '') ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>XP</p>
                        <p>XP rapporter par l'événement.</p>
                    </div>
                    <div>
                        <input type="number" name="xp" value="<?= htmlspecialchars($selectedEvent['xp_evenement']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Date de l'événement</p>
                        <p>Date a laquel aura lieu l'événement.</p>
                    </div>
                    <div>
                        <input type="date" name="date"
                            value="<?= htmlspecialchars(explode(' ', $selectedEvent['date_evenement'])[0]) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Lieu de l'événement</p>
                        <p>Endroit où l'événement va se dérouller.</p>
                    </div>
                    <div>
                        <input type="text" name="lieu" placeholder="Parking Batiment Info"
                            value="<?= htmlspecialchars($selectedEvent['lieu_evenement']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Places disponibles</p>
                        <p>Places disponibles à la vente de l'évènement. -1 Signifie illimité.</p>
                    </div>
                    <div>
                        <input type="number" name="places" placeholder="50" min="-1"
                            value="<?= htmlspecialchars($selectedEvent['places_evenement']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Prix de la place</p>
                        <p>Prix (en euros) d'une place à cet évènement. Mettre 0 pour afficher "gratuit".</p>
                    </div>
                    <div>
                        <input type="number" name="prix" placeholder="4.99" min="0" step="0.01"
                            value="<?= htmlspecialchars($selectedEvent['prix_evenement']) ?>">
                    </div>
                </div>

                <div class="propertie">
                    <div>
                        <p>Réductions applicables</p>
                        <p>Est-ce que les réductions du prix des grades peuvent s'appliquer sur cet événement ?</p>
                    </div>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="reductions" <?= $selectedEvent['reductions_evenement'] ? 'checked' : '' ?>>
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

            <form method="POST" action="/?page=admin-admin/events/delete"
                onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');">
                <input type="hidden" name="id" value="<?= $selectedEvent['id_evenement'] ?>">
                <div class="saves-buttons">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </div>
            </form>

        </div>
    <?php endif; ?>
</main>