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
        $whereClauses[] = "type_transaction IN ($typePlaceholders)";
        foreach ($filters['types'] as $type) {
            $params[] = $type;
            $types .= 's';
        }
    }

    if (!empty($filters['user_search'])) {
        $whereClauses[] = "(CONCAT(m.nom_membre COLLATE utf8mb4_unicode_ci, ' ', m.prenom_membre COLLATE utf8mb4_unicode_ci) LIKE ? COLLATE utf8mb4_unicode_ci)";
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