<?php

require_once 'src/model/bdd/evenement.php';
require_once 'src/model/bdd/media.php';

class myGallery
{
    public function show()
    {
        $isLoggedIn = isset($_SESSION["userid"]);
        $limit = 10;

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (isset($_GET["show"]) && ctype_digit($_GET["show"])) {
                $limit = (int) $_GET["show"];
            }

            if (isset($_GET['eventid']) && $isLoggedIn) {
                $eventid = $_GET['eventid'];
                $userid = $_SESSION["userid"];
            } else {
                header("Location: /?page=base-home");
                exit;
            }
        }

        $event = getEvenement($eventid)[0];

        include 'src/view/base/myGalleryView.php';
    }
}