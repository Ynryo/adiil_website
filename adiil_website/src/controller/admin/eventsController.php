<?php

require_once 'src/model/bdd/evenement.php';
require_once 'src/model/utils/files_save.php';

class EventsController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/events';

    public function show()
    {
        $evenements = getAllEvenements();

        $selectedEvent = null;

        if (isset($_GET['id'])) {
            $selectedEvent = getEvenement((int) $_GET['id']);
        } elseif (!empty($evenements)) {
            $selectedEvent = getEvenement($evenements[0]['id_evenement']);
        }

        include_once 'src/view/admin/panels/events.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        $nom = trim($_POST['nom']) ?: 'N/A';
        $description = trim($_POST['description']);
        $xp = (int) ($_POST['xp'] ?? 10);
        $places = (int) ($_POST['places'] ?? 0);
        $prix = (float) ($_POST['prix'] ?? 0);
        $lieu = trim($_POST['lieu']);
        $date = trim($_POST['date']);
        $reductions = isset($_POST['reductions']) ? 1 : 0;

        updateEvenement($id, $nom, $description, $xp, $places, $prix, $lieu, $date, $reductions);

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        deleteEvenement($id);

        header(self::REDIRECT_URL);
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $newId = createEvenement();

        header(self::REDIRECT_URL . '&id=' . $newId);
        exit();
    }

    public function uploadImage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        $event = getEvenement($id);

        if (!$event) {
            header(self::REDIRECT_URL);
            exit();
        }

        $imageName = saveImage();

        if ($imageName) {
            if (!empty($event['image_evenement'])) {
                deleteFile($event['image_evenement']);
            }
            updateEvenementImage($id, $imageName);
        }

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }
}