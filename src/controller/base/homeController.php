<?php

require_once 'src/model/membre.php';
require_once 'src/model/evenement.php';

class home {
    public function show() {
        $podium = getPodium();
            
        $date = getdate();
        $sql_date = $date["year"]."-".$date["mon"]."-".$date["mday"];
        $events_to_display = getNextEvenement($sql_date, 2);
        
        $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];

        include 'src/view/base/homeView.php';
    }
}