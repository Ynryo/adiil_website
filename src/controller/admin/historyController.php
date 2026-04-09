<?php

require_once 'src/model/bdd/historique.php';

class HistoryController
{
    public function show()
    {
        $filters = [];

        $showBoutique = isset($_GET['boutique']) && $_GET['boutique'] === '1';
        $showGrades = isset($_GET['grades']) && $_GET['grades'] === '1';
        $showEvents = isset($_GET['events']) && $_GET['events'] === '1';

        $types = [];
        if ($showBoutique) {
            $types[] = 'Commande';
        }
        if ($showGrades) {
            $types[] = 'Adhesion';
        }
        if ($showEvents) {
            $types[] = 'Inscription';
        }

        if (!empty($types)) {
            $filters['types'] = $types;
        }

        if (!empty($_GET['userSearch'])) {
            $filters['user_search'] = $_GET['userSearch'];
        }

        $historique = getHistorique($filters);

        include_once 'src/view/admin/panels/history.php';
    }
}