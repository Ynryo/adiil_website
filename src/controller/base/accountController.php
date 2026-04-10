<?php

require_once 'src/model/bdd/membre.php';
require_once 'src/model/utils/files_save.php';

class Account
{
    private $infoUser;

    public function show()
    {
        // Gère les requêtes
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRequest();
        }

        if (!isset($_SESSION['userid'])) {
            header("Location: /?page=base-login");
            exit();
        }

        // Recuperer les informations de l'utilisateur
        $this->infoUser = getInfoOfmembre($_SESSION['userid']);

        $img = $this->infoUser[0]['pp_membre'];
        $imgLink = "assets/image/" . ($img == null ? "admin/default_images/user.jpg" : "api/pp/$img");

        // Vérifie si "viewAll" est défini et vaut "1" dans l'URL
        $viewAll = isset($_GET['viewAll']) && $_GET['viewAll'] === '1';
        $historiqueAchats = getAchatsMembre($_SESSION['userid'], ($viewAll ? "" : " LIMIT 6"));

        include_once 'src/view/base/accountView.php';
    }

    /**
     * Permet de gérer les requêtes.
     * Chaque fonction a une petite explication de ce qu'elle fait
     */
    private function processRequest()
    {
        if (isset($_POST['deconnexion']) && $_POST['deconnexion'] === 'true') {
            $this->requestDisconnect();
        } elseif (isset($_FILES['file'])) {
            $this->requestUpdatePP();
        } elseif (isset($_POST['name'], $_POST['lastName'], $_POST['mail'])) {
            $this->requestProcessForm();
        } elseif (isset($_POST['mdp'], $_POST['newMdp'], $_POST['newMdpVerif'])) {
            $this->requestUpdatePassword();
        }
    }

    /**
     * Déconnecte l'utilisateur si celui-ci le souhaite
     */
    private function requestDisconnect()
    {
        session_destroy();
        header("Location: /?page=base-home");
        exit();
    }

    /**
     * Formulaire permettant de modifier la photo de profil de l'utilisateur
     */
    private function requestUpdatePP()
    {
        // Appelle saveImage() pour traiter l'image
        $fileName = saveImage();

        if ($fileName === null) {
            $_SESSION['message'] = "Erreur : veuillez vérifier le fichier envoyé.";
            $_SESSION['message_type'] = "error";
            $this->redirectOnLastPage();
        }

        // Suppression de l'ancienne image si elle existe
        if (!empty($this->infoUser[0]['pp_membre'])) {
            deleteFile($this->infoUser[0]['pp_membre']);
        }

        // Met à jour la base de données avec le nom du fichier
        updateMembrePP($fileName, $_SESSION['userid']);

        $_SESSION['message'] = "Mise à jour de la photo de profil réussie !";
        $_SESSION['message_type'] = "success";

        // Recharge la page pour afficher la nouvelle image
        $this->redirectOnLastPage();
    }

    /**
     * Formulaire contenant les données personnelles de l'utilisateur
     */
    private function requestProcessForm()
    {
        // Charger les informations actuelles de l'utilisateur depuis la base de données
        $currentUserData = getMembre($_SESSION['userid']);

        // Vérifier si les données actuelles existent
        if (empty($currentUserData)) {
            // Cas où l'utilisateur actuel n'existe pas dans la base
            $_SESSION['message'] = "Erreur : utilisateur introuvable dans la base de données.";
            $_SESSION['message_type'] = "error";
            $this->redirectOnLastPage();
        }

        $currentName = $currentUserData[0]['prenom_membre'];
        $currentLastName = $currentUserData[0]['nom_membre'];
        $currentMail = $currentUserData[0]['email_membre'];
        $currentTp = $currentUserData[0]['tp_membre'];

        // Récupérer les nouvelles valeurs ou conserver les anciennes si aucune modification
        $name = empty($_POST['name']) ? $currentName : htmlspecialchars($_POST['name']);
        $lastName = empty($_POST['lastName']) ? $currentLastName : htmlspecialchars($_POST['lastName']);
        $mail = empty($_POST['mail']) ? $currentMail : htmlspecialchars($_POST['mail']);
        $tp = isset($_POST['tp']) && !empty($_POST['tp']) ? htmlspecialchars($_POST['tp']) : $currentTp;

        // Vérifier si l'adresse e-mail existe déjà (et appartient à un autre utilisateur)
        if (isMailUsedByAnotherMembre($mail, $_SESSION['userid'])) {
            // Cas où l'adresse e-mail est déjà utilisée
            $_SESSION['message'] = "Les modifications n'ont pas pu être effectuées car l'adresse e-mail est déjà utilisée par un autre compte.";
            $_SESSION['message_type'] = "error"; // Pour gérer les styles
        } else {
            // Mettre à jour les informations de l'utilisateur
            updateMembreAccountInfo($name, $lastName, $mail, $tp, $_SESSION['userid']);

            // Message de succès suite aux modifications
            $_SESSION['message'] = "Vos informations ont été mises à jour avec succès !";
            $_SESSION['message_type'] = "success"; // Pour gérer les styles
        }

        // Recharger la page
        $this->redirectOnLastPage();
    }

    /**
     * Formulaire permettant à l'utilisateur de modifier son mot de passe
     */
    private function requestUpdatePassword()
    {
        $currentPassword = htmlspecialchars(trim($_POST['mdp']));
        $newPassword = htmlspecialchars(trim($_POST['newMdp']));
        $newPasswordVerif = htmlspecialchars(trim($_POST['newMdpVerif']));

        // Récupérer l'utilisateur et le mot de passe actuel depuis la base de données
        $user = getMembre($_SESSION['userid']);

        if ($user[0]['password_membre'] == null || $currentPassword == "") {
            $_SESSION['message'] = "Veuillez saisir votre mot de passe actuel.";
            $_SESSION['message_type'] = "error";
            $this->redirectOnLastPage();
        }

        if (!password_verify($currentPassword, $user[0]['password_membre'])) {
            $_SESSION['message'] = "Mot de passe actuel incorrect.";
            $_SESSION['message_type'] = "error";
            $this->redirectOnLastPage();
        }

        if ($newPassword == $newPasswordVerif) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            updateMembrePassword($hashedPassword, $_SESSION['userid']);
            $_SESSION['message'] = "Mot de passe mis à jour avec succès !";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Les nouveaux mots de passe ne correspondent pas.";
            $_SESSION['message_type'] = "error";
        }

        // Redirection pour éviter le double envoi du formulaire
        $this->redirectOnLastPage();
    }

    /**
     * Fonction pour améliorer la lisibilité du code
     */
    private function redirectOnLastPage()
    {
        header("Location: ?page=base-account");
        exit();
    }
}