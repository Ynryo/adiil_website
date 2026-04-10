<?php

require_once 'src/model/bdd/membre.php';

class Signin
{
    public function show()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $user = getMembreByMail(htmlspecialchars(trim($_POST['mail'])));

            if (empty($user)) {
                if ($_POST['password'] == $_POST['password_verif']) {
                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    insertMembre(
                        htmlspecialchars(trim($_POST['lname'])),
                        htmlspecialchars(trim($_POST['fname'])),
                        htmlspecialchars(trim($_POST['mail'])),
                        $hashedPassword
                    );
                    header("Location: /?page=base-login");
                    exit;
                } else {
                    echo '<h3 class="form-error">Les mots de passe ne correspondent pas.</h3>';
                }
            } else {
                echo '<h3 class="form-error">Utilisateur déjà présent.</h3>';
            }
        }

        include_once 'src/view/base/signinView.php';
    }
}