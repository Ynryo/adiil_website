<?php

require_once 'src/model/bdd/grade.php';
require_once 'src/model/bdd/membre.php';
require_once 'src/model/utils/files_save.php';

class gradeSubscription
{
    public function show()
    {
        if (!isset($_SESSION["userid"])) {
            header("Location: /?page=base-login");
            exit;
        }

        $userid = $_SESSION["userid"];

        // Vérification que l'ID du grade est fourni dans l'URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: /?page=base-grade");
            exit;
        }
        $id_grade = intval($_GET['id']);

        // On récupère les informations du grade
        $grade = getGrade($id_grade);

        // Vérifie que le grade existe
        if (empty($grade)) {
            $_SESSION['message'] = "Le grade sélectionné n'existe pas.";
            $_SESSION['message_type'] = "error";
            header("Location: /?page=base-grade");
            exit;
        }

        // Gestion de l'achat d'un grade
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['mode_paiement']) && !empty($_POST['mode_paiement'])) {
                $mode_paiement = $_POST['mode_paiement'];

                // Vérifie si l'utilisateur possède déjà un grade
                if (doesMembreHasGrade($userid)) {
                    deleteGradeOfMembre($userid);
                }

                addGradeToMembre($userid, $id_grade, $grade[0]['prix_grade'], $mode_paiement);

                $_SESSION['message'] = "Adhésion au grade réussie !";
                $_SESSION['message_type'] = "success";
                header("Location: /?page=base-grade");
                exit;
            }
        }

        include 'src/view/base/gradeSubscriptionView.php';
    }
}