<main>
    <form method="GET" action="/?page=admin-admin/history" class="filters">
        <input type="hidden" name="page" value="admin-admin/history">

        <div>
            <span>
                Boutique
                <label style="cursor: pointer;">
                    <input type="checkbox" name="boutique" value="1" <?= isset($_GET['boutique']) && $_GET['boutique'] === '1' ? 'checked' : '' ?>
                    onchange="this.form.submit()">
                </label>
            </span>
            <span>
                Grades
                <label style="cursor: pointer;">
                    <input type="checkbox" name="grades" value="1" <?= isset($_GET['grades']) && $_GET['grades'] === '1' ? 'checked' : '' ?>
                    onchange="this.form.submit()">
                </label>
            </span>
            <span>
                Evenements
                <label style="cursor: pointer;">
                    <input type="checkbox" name="events" value="1" <?= isset($_GET['events']) && $_GET['events'] === '1' ? 'checked' : '' ?>
                    onchange="this.form.submit()">
                </label>
            </span>
        </div>

        <div>
            <input type="text" placeholder="Recherche un utilisateur" name="userSearch"
                value="<?= htmlspecialchars($_GET['userSearch'] ?? '') ?>">
            <button type="submit" class="btn-transparent btn-blue">Rechercher</button>
        </div>
    </form>

    <table id="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Element</th>
                <th>Utilisateur</th>
                <th>Date</th>
                <th>Quantité</th>
                <th>Prix payé</th>
                <th>Paiement</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($historique)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Aucun résultat</td>
                </tr>
            <?php else: ?>
                <?php foreach ($historique as $item): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['type_transaction']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($item['element']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(strtoupper($item['nom_membre']) . ' ' . $item['prenom_membre']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(explode(' ', $item['date_transaction'])[0]) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($item['quantite']) ?>
                        </td>
                        <td>
                            <?= number_format($item['montant'], 2, ',', ' ') ?> €
                        </td>
                        <td>
                            <?= htmlspecialchars($item['mode_paiement']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<link rel="stylesheet" href="assets/css/admin/historique.css">