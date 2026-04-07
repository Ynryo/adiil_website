<?php

class admin
{
    public function show()
    {
        if (!isset($_SESSION['userid'])) {
            header('Location: src/view/login.php');
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header('Location: src/view/admin/unauthorized.html');
            exit();
        }

        include 'src/model/utils/permissions.php';
        include 'src/view/admin/adminView.php';
    }
}
