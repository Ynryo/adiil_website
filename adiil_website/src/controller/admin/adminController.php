<?php

class Admin
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
        include_once 'src/model/utils/permissions.php';
        include_once 'src/view/admin/adminView.php';
    }
}
