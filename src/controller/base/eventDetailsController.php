<?php

require_once 'src/model/bdd/evenement.php';
require_once 'src/model/bdd/media.php';

class eventDetails {
    public function show() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
            header("Location: /?page=base-home");
            exit;
        }

        $show = 8;

        $eventid = $_GET['id'];
        $event = getEvenement($eventid);
        if(empty($event) || $event === null) {
            header("Location: /?page=base-home");
            exit;
        }
        $event = $event[0];

        if (isset($_GET['show']) && is_numeric($_GET['show']) && $_GET['show']) {
            $show = (int) $_GET['show'];
        }

        $img = $event['image_evenement'];
        $imgLink = "assets/image/" . ($img == null ? "admin/default_images/event.jpg" : "api/event/$img");

        $current_date = new DateTime(date("Y-m-d"));
        $event_date = new DateTime(substr($event['date_debut_evenement'], 0, 10));

        $btnHTML = null;
        // l'évènement est déja passé
        if($event_date < $current_date) {
            $btnHTML = '<button class="subscription" id="passed_subscription">Passé</button>';
        }
        // l'utilisateur est déja inscrit
        elseif(isSubscribed($_SESSION['userid'], $eventid)) {
            $btnHTML = '<button class="subscription" id="passed_subscription">Inscrit</button>';
        }
        // l'évènement est complet
        elseif(!isPlaceDisponible($eventid)) {
            $btnHTML = '<button class="subscription" id="passed_subscription">Complet</button>';
        }
        // sinon l'utilisateur peut s'inscrire
        else {
            $btnHTML = '
                <form class="subscription" action="/?page=base-eventSubscription" method="post">
                    <input type="text" name="eventid" value="'.$eventid.'" hidden>
                    <button type="submit">Inscription</a></button>
                </form>
            ';
        }
        
        include 'src/view/base/eventDetailsView.php';
    }
}