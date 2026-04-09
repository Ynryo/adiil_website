<?php

require_once 'src/model/bdd/membre.php';

class login
{
    public function show()
    {
        // Gestion de la connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login_error = "<h3 class=\"login-error\">Erreur dans les informations de connexion.</h3>";
            $mail = htmlspecialchars(trim($_POST['mail']));
            $password = htmlspecialchars(trim($_POST['password']));

            $selection_db = getMembreByMail($mail);

            if (empty($selection_db)) {
                echo $login_error;
                exit;
            }

            $db_mail = $selection_db[0]["email_membre"];
            $db_password = $selection_db[0]["password_membre"];
            $mail_ok = ($db_mail == $mail);

            if ($db_password == NULL && $password == "") {
                $password_ok = true;
            } else {
                $password_ok = password_verify($password, $db_password);
            }

            if (!$mail_ok || !$password_ok) {
                echo $login_error;
                exit;
            }

            $_SESSION['userid'] = $selection_db[0]["id_membre"];

            //check if perm -> panel admin ok
            $nb_roles = getNbRolesmembre($selection_db[0]["id_membre"])[0]["nb_roles"];

            if ($nb_roles > 0) {
                $_SESSION["isAdmin"] = true;
            }

            header("Location: /?page=base-home");
        }

        include 'src/view/base/loginView.php';
    }
}