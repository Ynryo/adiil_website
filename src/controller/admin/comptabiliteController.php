<?php

require_once 'src/model/bdd/comptabilite.php';
require_once 'src/model/utils/files_save.php';

class ComptabiliteController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/comptabilite';

    public function show()
    {
        $comptabilites = getAllComptabilite();
        include_once 'src/view/admin/panels/comptabilite.php';
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $nom = trim($_POST['nom'] ?? 'Document');
        $date = $_POST['date'] ?? date('Y-m-d');
        $id_membre = $_SESSION['userid'];

        $fichier = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fichier = saveFile();
        }

        if ($fichier) {
            createComptabilite($nom, $date, $fichier, $id_membre);
        }

        header(self::REDIRECT_URL);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        deleteComptabilite($id);

        header(self::REDIRECT_URL);
        exit();
    }
}