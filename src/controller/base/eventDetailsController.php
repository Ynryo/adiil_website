<?php

require_once 'src/model/evenement.php';
require_once 'src/model/media.php';

class eventDetails {
    public function show() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
            header("Location: /?page=base-home");
            exit;
        }

        $show = 8;

        $eventid = $_GET['id'];
        $event = getEvenement($eventid);
        if(empty($event) || $event === null || boolval($event["deleted"])) {
            header("Location: /?page=base-home");
            exit;
        }

        if (isset($_GET['show']) && is_numeric($_GET['show']) && $_GET['show']) {
            $show = (int) $_GET['show'];
        }

        $img = $event['image_evenement'];
        $imgLink = ($img == null ? "assets/image/admin/default_images/event.jpg" : "/api/files/$img");

        $current_date = new DateTime(date("Y-m-d"));
        $event_date = new DateTime(substr($event['date_evenement'], 0, 10));

        // le bouton
        $btnHTML = null;
        if($event_date < $current_date) {   // l'évènement est déja passé
            $btnHTML = '<button class="subscription" id="passed_subscription">Passé</button>';
        } elseif(isSubscribed($_SESSION['userid'], $eventid)) {   // l'utilisateur est déja inscrit
            $btnHTML = '<button class="subscription" id="passed_subscription">Inscrit</button>';
        } elseif(!isPlaceDisponible($eventid)) {   // l'évènement est complet
            $btnHTML = '<button class="subscription" id="passed_subscription">Complet</button>';
        } else {   // sinon l'utilisateur peut s'inscrire
            $btnHTML = '
                <form class="subscription" action="/?page=base-eventSubscription" method="post">
                    <input type="text" name="eventid" value="<?= $eventid?>" hidden>
                    <button type="submit">Inscription</a></button>
                </form>
            ';
        }
        
        include 'src/view/base/eventDetailsView.php';
    }
}