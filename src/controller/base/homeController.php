<?php

require_once 'src/model/database.php';

class home {
    public function show() {
        $podium = get(
            "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;"
        );
            
        $date = getdate();
        $sql_date = $date["year"]."-".$date["mon"]."-".$date["mday"];
        $events_to_display = get(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? ORDER BY date_evenement ASC LIMIT 2;",
            [$sql_date]
        );
        
        $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];

        include 'src/view/base/homeView.php';
    }
}