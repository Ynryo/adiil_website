<?php

require_once 'src/model/bdd/reunion.php';
require_once 'src/model/utils/files_save.php';

class ReunionsController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/reunions';

    public function show()
    {
        $reunions = getAllReunions();

        $selectedReunion = null;

        if (isset($_GET['id'])) {
            $selectedReunion = getReunion((int)$_GET['id']);
        } elseif (!empty($reunions)) {
            $selectedReunion = getReunion($reunions[0]['id_reunion']);
        }

        include_once 'src/view/admin/panels/reunions.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $date = $_POST['date'] ?? date('Y-m-d');
        $id_membre = $_SESSION['userid'];
        
        $fichier = null;
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fichier = saveFile();
        }

        $newId = createReunion($date, $fichier, $id_membre);

        header(self::REDIRECT_URL . '&id=' . $newId);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int)$_POST['id'];
        deleteReunion($id);

        header(self::REDIRECT_URL);
        exit();
    }
}