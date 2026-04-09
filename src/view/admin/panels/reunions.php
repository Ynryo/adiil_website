<nav>
    <?php if (empty($reunions)): ?>
        <p id="empty_navbar">Il n'y a pas grand chose ici</p>
    <?php else: ?>
        <ul id="content_navbar">
            <?php foreach ($reunions as $reunion): ?>
                <li
                    class="<?= ($selectedReunion && $selectedReunion['id_reunion'] == $reunion['id_reunion']) ? 'active' : '' ?>">
                    <a href="/?page=admin-admin/reunions&id=<?= $reunion['id_reunion'] ?>">
                        <?= htmlspecialchars(date('d/m/Y', strtotime($reunion['date_reunion']))) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <button type="button" class="btn-transparent navadd-btn"
        onclick="document.getElementById('addReunionModal').style.display='flex'">
        <img src="assets/image/admin/add.svg" alt="Ajouter">Ajouter une réunion
    </button>
</nav>

<main>
    <?php if ($selectedReunion): ?>
        <div id="main_content">
            <div class="reunion-btns">
                <?php if (!empty($selectedReunion['fichier_reunion'])): ?>
                    <a href="/api/files/<?= htmlspecialchars($selectedReunion['fichier_reunion']) ?>" target="_blank"
                        class="btn-transparent btn-blue">
                        <img src="assets/image/admin/download.svg" alt="Télécharger">Télécharger
                    </a>
                <?php else: ?>
                    <p style="color: var(--text-lighter);">Aucun compte-rendu associé</p>
                <?php endif; ?>

                <form method="POST" action="/?page=admin-admin/reunions/delete"
                    onsubmit="return confirm('Êtes-vous sûr ? Cette action est définitive.');" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $selectedReunion['id_reunion'] ?>">
                    <button type="submit" class="btn-transparent btn-red">
                        <img src="assets/image/admin/delete.svg" alt="Supprimer">Supprimer
                    </button>
                </form>
            </div>

            <?php if (!empty($selectedReunion['fichier_reunion'])): ?>
                <iframe id="pdf_preview" src="/api/files/<?= htmlspecialchars($selectedReunion['fichier_reunion']) ?>"
                    title="Aperçu du PDF"></iframe>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<div id="addReunionModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%;">
        <h2 style="margin-bottom: 20px;">Ajouter une réunion</h2>
        <form method="POST" action="/?page=admin-admin/reunions/create" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px;">Date de la réunion</label>
                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px;">Compte-rendu (PDF - optionnel)</label>
                <input type="file" name="file" accept="application/pdf">
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn-transparent"
                    onclick="document.getElementById('addReunionModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-transparent btn-blue">
                    <img src="assets/image/admin/save.svg" alt="Créer">Créer
                </button>
            </div>
        </form>
    </div>
</div>