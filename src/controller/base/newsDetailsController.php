<?php

require_once 'src/model/bdd/actualite.php';

class NewsDetails
{
    public function show()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
            header("Location: /?page=base-home");
            exit;
        }

        $actuid = $_GET['id'];
        $actu = getActualite($actuid);
        if (empty($actu) || is_null($actu)) {
            header("Location: /?page=base-home");
            exit;
        }
        $actu = $actu[0];

        $img = $actu['image_actualite'];
        $imgLink = "assets/image/" . ($img == null ? "admin/default_images/event.jpg" : "api/news/$img");

        include_once 'src/view/base/newsDetailsView.php';
    }
}