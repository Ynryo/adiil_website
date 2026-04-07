<?php

namespace App\Controllers;

use App\Database\DB;
use App\Helpers\Session;
use App\Helpers\Csrf;

class LoginController
{
    public function handle()
    {
        $db = DB::getInstance();
        $loginError = '';

        // Gestion de la connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::check();

            $mail = trim($_POST['mail'] ?? '');
            // Ne PAS htmlspecialchars le mot de passe avant password_verify
            $password = trim($_POST['password'] ?? '');

            $selection_db = $db->select(
                "SELECT id_membre, email_membre, password_membre FROM MEMBRE WHERE email_membre = ?",
                "s",
                [$mail]
            );

            if (!empty($selection_db)) {
                $db_password = $selection_db[0]["password_membre"];

                $password_ok = false;
                if ($db_password === null && $password === "") {
                    $password_ok = true;
                } else {
                    $password_ok = password_verify($password, $db_password);
                }

                if ($password_ok) {
                    Session::set('userid', $selection_db[0]["id_membre"]);

                    // Vérifier si l'utilisateur a des rôles (admin)
                    $roles = $db->select(
                        "SELECT COUNT(*) as nb_roles FROM ASSIGNATION WHERE id_membre = ?;",
                        "i",
                        [$selection_db[0]["id_membre"]]
                    );
                    if ($roles[0]["nb_roles"] > 0) {
                        Session::set('isAdmin', true);
                    }

                    header("Location: /index.php");
                    exit;
                } else {
                    $loginError = "Erreur dans les informations de connexion.";
                }
            } else {
                $loginError = "Erreur dans les informations de connexion.";
            }
        }

        require __DIR__ . '/../../views/login.php';
    }
}
