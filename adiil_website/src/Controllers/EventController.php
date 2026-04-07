<?php

namespace App\Controllers;

use App\Database\DB;
use App\Helpers\Session;
use DateTime;

class EventController
{
    public function handle()
    {
        $db = DB::getInstance();
        $isLoggedIn = Session::isLoggedIn();
        $show = 5;

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['show']) && is_numeric($_GET['show'])) {
            $show = (int) $_GET['show'];
        }

        // --- Préparation des données ---
        $date = getdate();
        $sql_date = $date["year"] . "-" . $date["mon"] . "-" . $date["mday"];

        $joursFr = [0 => 'Dimanche', 1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
        $moisFr = [1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'];
        $current_date = new DateTime(date("Y-m-d"));

        $events_upcoming = $db->select(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? AND deleted = false ORDER BY date_evenement ASC;",
            "s",
            [$sql_date]
        );
        $passed_events = $db->select(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement < ? AND deleted = false ORDER BY date_evenement ASC LIMIT ?;",
            "si",
            [$sql_date, $show]
        );
        $events_to_display = array_merge($passed_events, $events_upcoming);

        // Préparer les données d'affichage pour chaque événement
        $eventsData = [];
        $closestFound = false;

        foreach ($events_to_display as $event) {
            $eventid = $event["id_evenement"];
            $event_date_str = substr($event['date_evenement'], 0, 10);
            $event_date_info = getdate(strtotime($event_date_str));
            $event_date = new DateTime($event_date_str);

            $other_classes = "";
            $isPassed = false;
            $isClosest = false;
            $date_pin_class = "";
            $date_pin_label = "";

            if ($event_date < $current_date) {
                $date_pin_class = "passed";
                $date_pin_label = "Passé";
                $other_classes = 'passed';
                $isPassed = true;
            } elseif ($event_date == $current_date) {
                $date_pin_class = "today";
                $date_pin_label = "Aujourd'hui";
                if (!$closestFound) {
                    $isClosest = true;
                    $closestFound = true;
                }
            } else {
                $date_pin_class = "upcoming";
                $date_pin_label = "A venir";
                if (!$closestFound) {
                    $isClosest = true;
                    $closestFound = true;
                }
            }

            // Déterminer le statut d'inscription
            $colorClass = "event-not-subscribed hover_effect";
            $label = "S'inscrire";

            if ($isPassed) {
                $colorClass = "event-full";
                $label = "Passé";
            } else {
                // Check availability
                $result = $db->select(
                    "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;",
                    "i",
                    [$eventid]
                );

                $isPlaceDisponible = false;
                if (!empty($result)) {
                    $isPlaceDisponible = $result[0]['isPlaceDisponible'];
                }

                if (!$isPlaceDisponible) {
                    $colorClass = "event-full";
                    $label = "Complet";
                }

                if ($isLoggedIn) {
                    $isSubscribed = !empty($db->select(
                        "SELECT MEMBRE.id_membre FROM MEMBRE JOIN INSCRIPTION on MEMBRE.id_membre = INSCRIPTION.id_membre WHERE MEMBRE.id_membre = ? AND INSCRIPTION.id_evenement = ?;",
                        "ii",
                        [Session::getUserId(), $eventid]
                    ));

                    if ($isSubscribed) {
                        $colorClass = "event-subscribed";
                        $label = "Inscrit";
                    }
                }
            }

            $formattedDate = ucwords($joursFr[$event_date_info['wday']] . " " . $event_date_info["mday"] . " " . $moisFr[$event_date_info['mon']]);

            $eventsData[] = [
                'event' => $event,
                'eventid' => $eventid,
                'formattedDate' => $formattedDate,
                'date_pin_class' => $date_pin_class,
                'date_pin_label' => $date_pin_label,
                'other_classes' => $other_classes,
                'isClosest' => $isClosest,
                'colorClass' => $colorClass,
                'label' => $label,
            ];
        }

        require __DIR__ . '/../../views/events.php';
    }
}
