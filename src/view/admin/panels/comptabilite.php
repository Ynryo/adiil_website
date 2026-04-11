<main>
    <div id="main_content">
        <button type="button" class="btn-transparent btn-blue upload-button"
            onclick="document.getElementById('uploadModal').style.display='flex'">
            <img src="assets/image/admin/download.svg" alt="Upload">Upload file
        </button>

        <?php if (empty($comptabilites)): ?>
            <p style="text-align: center; color: var(--text-lighter);">Aucun fichier comptable</p>
        <?php else: ?>
            <?php foreach ($comptabilites as $compta): ?>
                <div class="file-element">
                    <img src="assets/image/admin/sheet.png" alt="Document">
                    <div>
                        <p>
                            <?= htmlspecialchars($compta['nom_comptabilite']) ?>
                        </p>
                        <p>
                            <?= htmlspecialchars($compta['date_comptabilite']) ?>
                        </p>
                    </div>
                    <a href="/api/files/<?= htmlspecialchars($compta['url_comptabilite']) ?>" target="_blank"
                        class="btn-transparent btn-blue">
                        <img src="assets/image/admin/download.svg" alt="Télécharger">
                    </a>
                    <form method="POST" action="/?page=admin-admin/comptabilite/delete" style="display: inline;"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer ce fichier ?');">
                        <input type="hidden" name="id" value="<?= $compta['id_comptabilite'] ?>">
                        <button type="submit" class="btn-transparent btn-red">
                            <img src="assets/image/admin/delete.svg" alt="Supprimer">
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<div id="uploadModal"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; width: 90%;">
        <h2 style="margin-bottom: 20px; color: black;">Upload un fichier</h2>
        <form method="POST" action="/?page=admin-admin/comptabilite/upload" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: black;">Nom du fichier</label>
                <input type="text" name="nom" required style="width: 100%;" placeholder="Compta mars 2024">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: black;">Date</label>
                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; color: black;">Fichier</label>
                <input type="file" name="file" required>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn-transparent"
                    onclick="document.getElementById('uploadModal').style.display='none'">
                    Annuler
                </button>
                <button type="submit" class="btn-transparent btn-blue">
                    <img src="assets/image/admin/save.svg" alt="Upload">Upload
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="assets/css/admin/comptabilite.css">