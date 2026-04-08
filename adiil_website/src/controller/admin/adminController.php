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
        include_once 'src/view/admin/header.php';
    }

    public function chat()
    {
        $this->show();
        include_once 'src/controller/admin/chatController.php';
        $chatController = new ChatController();
        $chatController->show();
    }

    public function boutique()
    {
        $this->show();
        include_once 'src/controller/admin/boutiqueController.php';
        $boutiqueController = new BoutiqueController();
        $boutiqueController->show();
    }

    public function grades()
    {
        $this->show();
        include_once 'src/controller/admin/gradesController.php';
        $gradesController = new GradesController();
        $gradesController->show();
    }

    public function logs()
    {
        include_once 'src/view/admin/logs.php';
    }

    public function reunions()
    {
        include_once 'src/view/admin/reunions.php';
    }

    public function evenements()
    {
        include_once 'src/view/admin/evenements.php';
    }

    public function actualites()
    {
        include_once 'src/view/admin/actualites.php';
    }
}