<?php

require_once 'src/model/actualite.php';

class news {
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

        $actualitys_to_display = getNextActualite($show);

        $closest_event_id = "";

        $actuality_subscription_color_class = "event-not-subscribed";
        $actuality_subscription_label = "Consulter";

        include 'src/view/base/newsView.php';
    }
}