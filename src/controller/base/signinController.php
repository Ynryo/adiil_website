<?php

require_once 'src/model/bdd/membre.php';

class signin {
    public function show() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = getMembreByMail($_POST['mail']);

            if(empty($user)) {
                if($_POST['password'] == $_POST['password_verif']){
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    insertMembre($_POST['lname'], $_POST['fname'], $_POST['mail'], $hashedPassword);
                }
                header("Location: /?page=base-login");
                exit;
            } else {
                echo 'Utilisateur déjà présent';
            }
        }

        include 'src/view/base/signinView.php';
    }
}