<?php

require_once 'src/model/bdd/database.php';

class deleteAccount
{
    public function show()
    {
        if (isset($_POST['delete_account_valid']) && $_POST['delete_account_valid'] === 'true') {
            $db = DB::getInstance();
            $db->query(
                "CALL suppressionCompte (?);",
                "i",
                [$_SESSION["userid"]]
            );
            session_destroy();
            header("Location: /?page=base-home");
            exit();
        }

        include 'src/view/base/deleteAccountView.php';
    }
}