<?php

class Admin
{
    public const UNAUTHORIZED_REDIRECT = 'Location: src/view/admin/unauthorized.html';
    public const LOGIN_REDIRECT = 'Location: ?page=base-login';
    public const HEADER = "src/view/admin/header.php";
    public const MODEL_PERMS = "src/model/utils/permissions.php";

    public function show()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;
        include_once self::HEADER;
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
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/boutiqueController.php';
        $boutiqueController = new BoutiqueController();

        if ($action === 'show' || !method_exists($boutiqueController, $action)) {
            include_once self::HEADER;
            $boutiqueController->show();
        } else {
            $boutiqueController->$action();
        }
    }

    public function users()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/usersController.php';
        $usersController = new UsersController();

        if ($action === 'show' || !method_exists($usersController, $action)) {
            include_once self::HEADER;
            $usersController->show();
        } else {
            $usersController->$action();
        }
    }

    public function grades()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/gradesController.php';
        $gradesController = new GradesController();

        if ($action === 'show' || !method_exists($gradesController, $action)) {
            include_once self::HEADER;
            $gradesController->show();
        } else {
            $gradesController->$action();
        }
    }

    public function events()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/eventsController.php';
        $eventsController = new EventsController();

        if ($action === 'show' || !method_exists($eventsController, $action)) {
            include_once self::HEADER;
            $eventsController->show();
        } else {
            $eventsController->$action();
        }
    }

    public function comptabilite()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/comptabiliteController.php';
        $comptabiliteController = new ComptabiliteController();
        $comptabiliteController->show();
    }

    public function reunions()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/reunionsController.php';
        $reunionsController = new ReunionsController();

        if ($action === 'show' || !method_exists($reunionsController, $action)) {
            include_once self::HEADER;
            $reunionsController->show();
        } else {
            $reunionsController->$action();
        }
    }

    public function roles()
    {
        if (!isset($_SESSION['userid'])) {
            header(self::LOGIN_REDIRECT);
            exit();
        }
        if (!(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])) {
            header(self::UNAUTHORIZED_REDIRECT);
            exit();
        }
        include_once self::MODEL_PERMS;

        $page = $_GET['page'] ?? '';
        $parts = explode('/', $page);
        $action = $parts[2] ?? 'show';

        include_once 'src/controller/admin/rolesController.php';
        $rolesController = new RolesController();

        if ($action === 'show' || !method_exists($rolesController, $action)) {
            include_once self::HEADER;
            $rolesController->show();
        } else {
            $rolesController->$action();
        }
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