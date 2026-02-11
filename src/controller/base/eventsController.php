<?php

require_once 'src/model/database.php';

class events {
    public function show() {
        $show = 5;
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && is_numeric($_GET['show'])) {
            $show = (int) $_GET['show'];
        }

        $date = getdate();
        $sql_date = $date["year"]."-".$date["mon"]."-".$date["mday"];
        $joursFr = [0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
        $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];
        $current_date = new DateTime(date("Y-m-d"));

        $events_to_display = get(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? AND deleted = false ORDER BY date_evenement ASC;",
            [$sql_date]
        );
        $passed_events = get(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < ? AND deleted = false ORDER BY date_evenement ASC LIMIT $show;",
            [$sql_date]
        );
        $events_to_display = array_merge($passed_events, $events_to_display);

        $closest_event_id = "";

        include 'src/view/base/eventsView.php';
    }
}