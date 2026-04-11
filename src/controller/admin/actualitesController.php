<?php

require_once 'src/model/bdd/actualite.php';
require_once 'src/model/utils/files_save.php';

class ActualitesController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/actualites';

    public function show()
    {
        $actualites = getAllActualites();

        $selectedActualite = null;

        if (isset($_GET['id'])) {
            $selectedActualite = getActualite((int) $_GET['id']);
        } elseif (!empty($actualites)) {
            $selectedActualite = getActualite($actualites[0]['id_actualite']);
        }

        include_once 'src/view/admin/panels/actualites.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        $titre = trim($_POST['titre']) ?: 'N/A';
        $contenu = trim($_POST['contenu']);
        $date = trim($_POST['date']);

        updateActualite($id, $titre, $contenu, $date);

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $newId = createActualite();

        header(self::REDIRECT_URL . '&id=' . $newId);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        deleteActualite($id);

        header(self::REDIRECT_URL);
        exit();
    }

    public function uploadImage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        $actualite = getActualite($id);

        if (!$actualite) {
            header(self::REDIRECT_URL);
            exit();
        }

        $imageName = saveImage();

        if ($imageName) {
            if (!empty($actualite['image_actualite'])) {
                deleteFile($actualite['image_actualite']);
            }
            updateActualiteImage($id, $imageName);
        }

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }
}
