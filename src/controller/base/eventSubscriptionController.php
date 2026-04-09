<?php

require_once 'src/model/bdd/evenement.php';
require_once 'src/model/utils/files_save.php';

class eventSubscription {
    public function show() {
        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION["userid"])) {
            header("Location: /?page=base-login");
            exit;
        }

        // Vérifie que la requête est POST et contient les données nécessaires
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /?page=base-login");
            exit;
        }

        $userid = $_SESSION["userid"];
        $eventid = $_POST["eventid"];

        if(isset($_POST["price"], $_POST["eventid"])) {
            subscribeMembreToEvenement($userid, $eventid, $_POST["price"]);
            
            $xp = getEvenement($eventid)[0]['xp_evenement'];

            addXpToMembre($xp, $userid);

            header("Location: /?page=base-events");
            exit;
        }

        elseif(isset($_POST["eventid"])){
            $event =  getEvenement($eventid);

            if(empty($event)){
                header("Location: /?page=base-home");
                exit;
            }

            $event = $event[0];
            $title = $event["nom_evenement"];
            $xp = $event["xp_evenement"];
            $price = $event["prix_evenement"];

            $isDiscounted = boolval($event["reductions_evenement"]);
            $user_reduction = 1;

            if($isDiscounted) {
                $user_reduction = getDiscount($userid);
                
                $user_reduction = (empty($user_reduction) ? 1 : 1 - ($user_reduction[0]["reduction_grade"] / 100));
            }
        } else {
            header("Location: /?page=base-login");
            exit;
        }

        include 'src/view/base/eventSubscriptionView.php';
    }
}