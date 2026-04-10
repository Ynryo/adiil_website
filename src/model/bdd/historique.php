<?php
require_once "src/model/bdd/database.php";

function getHistorique($filters = [])
{
    $db = DB::getInstance();

    $whereClauses = [];
    $params = [];
    $types = '';

    if (!empty($filters['types'])) {
        $typePlaceholders = implode(',', array_fill(0, count($filters['types']), '?'));
        // On aligne ici aussi la collation sur ta base de données
        $whereClauses[] = "type_transaction COLLATE utf8mb4_general_ci IN ($typePlaceholders)";
        foreach ($filters['types'] as $type) {
            $params[] = $type;
            $types .= 's';
        }
    }

    if (!empty($filters['user_search'])) {
        // Remplacement de unicode_ci par general_ci pour correspondre à ta BDD
        $whereClauses[] = "(CONCAT(m.nom_membre COLLATE utf8mb4_general_ci, ' ', m.prenom_membre COLLATE utf8mb4_general_ci) LIKE ? COLLATE utf8mb4_general_ci)";
        $params[] = '%' . $filters['user_search'] . '%';
        $types .= 's';
    }

    $whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

    $sql = "SELECT
        h.type_transaction,
        h.element,
        h.quantite,
        h.date_transaction,
        h.montant,
        h.mode_paiement,
        m.nom_membre,
        m.prenom_membre
    FROM HISTORIQUE h
    INNER JOIN MEMBRE m ON h.id_utilisateur = m.id_membre
    $whereSQL
    ORDER BY h.date_transaction DESC";

    return $db->select($sql, $types, $params);
}