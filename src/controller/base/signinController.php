<?php

// require_once 'src/model/bdd/database.php';

class signin {
    public function show() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $mail = getMembreByMail($_POST['mail']);

            if(empty($selection_db)) {
                $password = $_POST['password'];
                $password_verif = $_POST['password_verif'];

                if($password == $password_verif){
                    $fname = "N/A";
                    $lname = "N/A";
    
                    if(isset($_POST['fname'])){
                        $fname = $_POST['fname'];
                    }
                    if(isset($_POST['lname'])){
                        $lname = $_POST['lname'];
                    }

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    insertMembre($lname, $fname, $mail, $hashedPassword);
                }
                header("Location: /?page=base-login.php");
                exit;
            } else {
                echo 'Utilisateur déjà présent';
            }
        }

        include 'src/view/base/signinView.php';
    }
}