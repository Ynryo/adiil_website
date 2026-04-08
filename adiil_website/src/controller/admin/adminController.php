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
        if (!isset($_SESSION['userid'])) {
            header('Location: src/view/login.php');
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header('Location: src/view/admin/unauthorized.html');
            exit();
        }
        include_once 'src/model/utils/permissions.php';

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/boutiqueController.php';
        $boutiqueController = new BoutiqueController();

        if ($action === 'show') {
            include_once 'src/view/admin/header.php';
            $boutiqueController->show();
        } elseif (method_exists($boutiqueController, $action)) {
            $boutiqueController->$action();
        } else {
            include_once 'src/view/admin/header.php';
            $boutiqueController->show();
        }
    }

    public function users()
    {
        $this->show();
        include_once 'src/controller/admin/usersController.php';
        $usersController = new UsersController();
        $usersController->show();
    }

    public function grades()
    {
        $this->show();
        include_once 'src/controller/admin/gradesController.php';
        $gradesController = new GradesController();
        $gradesController->show();
    }

    public function events()
    {
        $this->show();
        include_once 'src/controller/admin/eventsController.php';
        $eventsController = new EventsController();
        $eventsController->show();
    }

    public function comptabilite()
    {
        $this->show();
        include_once 'src/controller/admin/comptabiliteController.php';
        $comptabiliteController = new ComptabiliteController();
        $comptabiliteController->show();
    }

    public function reunions()
    {
        $this->show();
        include_once 'src/controller/admin/reunionsController.php';
        $reunionsController = new ReunionsController();
        $reunionsController->show();
    }

    public function roles()
    {
        $this->show();
        include_once 'src/controller/admin/rolesController.php';
        $rolesController = new RolesController();
        $rolesController->show();
    }

    public function actualites()
    {
        $this->show();
        include_once 'src/controller/admin/actualitesController.php';
        $actualitesController = new ActualitesController();
        $actualitesController->show();
    }

    public function history()
    {
        $this->show();
        include_once 'src/controller/admin/historyController.php';
        $historyController = new HistoryController();
        $historyController->show();
    }

    public function logs()
    {
        $this->show();
        include_once 'src/controller/admin/logsController.php';
        $logsController = new LogsController();
        $logsController->show();
    }
}