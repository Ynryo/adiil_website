<?php

namespace App\Controllers;

use App\Database\DB;
use App\Helpers\Csrf;

class SigninController
{
    public function handle()
    {
        $db = DB::getInstance();
        $signupError = '';

        // Gestion de l'inscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::check();

            $mail = htmlspecialchars(trim($_POST['mail'] ?? ''));

            $selection_db = $db->select(
                "SELECT id_membre FROM MEMBRE WHERE email_membre = ?",
                "s",
                [$mail]
            );

            if (empty($selection_db)) {
                // Ne PAS htmlspecialchars le mot de passe
                $password = trim($_POST['password'] ?? '');
                $password_verif = trim($_POST['password_verif'] ?? '');

                if ($password === $password_verif) {
                    $fname = !empty($_POST['fname']) ? htmlspecialchars(trim($_POST['fname'])) : 'N/A';
                    $lname = !empty($_POST['lname']) ? htmlspecialchars(trim($_POST['lname'])) : 'N/A';

                    $db->query(
                        "CALL creationCompte ( ? , ? , ? , ? , ? );",
                        "sssss",
                        [$lname, $fname, $mail, password_hash($password, PASSWORD_DEFAULT), 'defaultPP.png']
                    );

                    header("Location: /login.php");
                    exit;
                } else {
                    $signupError = "Les mots de passe ne correspondent pas.";
                }
            } else {
                $signupError = "Un compte avec cette adresse mail existe déjà.";
            }
        }

        require __DIR__ . '/../../views/signin.php';
    }
}
