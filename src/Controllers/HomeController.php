<?php

namespace App\Controllers;

use App\Database\DB;
use App\Helpers\Session;

class HomeController
{
    public function handle()
    {
        $db = DB::getInstance();
        $isLoggedIn = Session::isLoggedIn();

        // --- Préparation des données ---

        // Podium (top 3 XP)
        $podium = $db->select(
            "SELECT prenom_membre, xp_membre, pp_membre FROM MEMBRE ORDER BY xp_membre DESC LIMIT 3;"
        );

        // Prochains événements
        $date = getdate();
        $sql_date = $date["year"] . "-" . $date["mon"] . "-" . $date["mday"];
        $events_to_display = $db->select(
            "SELECT id_evenement, nom_evenement, lieu_evenement, date_evenement FROM EVENEMENT WHERE date_evenement >= ? ORDER BY date_evenement ASC LIMIT 2;",
            "s",
            [$sql_date]
        );

        // Mois en français
        $moisFr = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        // Préparer les données de subscription pour chaque événement
        $eventsData = [];
        foreach ($events_to_display as $event) {
            $eventid = $event["id_evenement"];

            // Vérifier les places disponibles
            $isPlaceDisponible = $db->select(
                "SELECT (EVENEMENT.places_evenement - (SELECT COUNT(*) FROM INSCRIPTION WHERE INSCRIPTION.id_evenement = EVENEMENT.id_evenement)) > 0 AS isPlaceDisponible FROM EVENEMENT WHERE EVENEMENT.id_evenement = ?;",
                "i",
                [$eventid]
            )[0]['isPlaceDisponible'];

            if ($isPlaceDisponible) {
                $colorClass = "event-not-subscribed hover_effect";
                $label = "S'inscrire";
            } else {
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

            // Formater la date
            $event_date = substr($event['date_evenement'], 0, 10);
            $event_date_info = getdate(strtotime($event_date));
            $formattedDate = ucwords($event_date_info["mday"] . " " . $moisFr[$event_date_info['mon']] . ", " . $event["lieu_evenement"]);

            $eventsData[] = [
                'event' => $event,
                'colorClass' => $colorClass,
                'label' => $label,
                'formattedDate' => $formattedDate,
            ];
        }

        // Render view
        require __DIR__ . '/../../views/home.php';
    }
}
