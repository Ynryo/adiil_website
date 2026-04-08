<?php

require_once 'src/model/bdd/membre.php';
require_once 'src/model/utils/files_save.php';

class UsersController
{
    public function show()
    {
        $membres = getAllMembresAdmin();
        $allRoles = getAllRoles();

        $selectedUser = null;
        $userRoles = [];

        if (isset($_GET['id'])) {
            $selectedUser = getMembre((int)$_GET['id']);
        } elseif (!empty($membres)) {
            $selectedUser = getMembre($membres[0]['id_membre']);
        }

        if ($selectedUser) {
            $selectedUser = $selectedUser[0] ?? null;
        }

        if ($selectedUser) {
            $userRoles = getMembreRoles($selectedUser['id_membre']);
        }

        include_once 'src/view/admin/panels/utilisateurs.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/users');
            exit();
        }

        $id = (int)$_POST['id'];
        $nom = trim($_POST['nom']) ?: 'N/A';
        $prenom = trim($_POST['prenom']) ?: 'N/A';
        $email = trim($_POST['email']) ?: 'N/A';
        $tp = trim($_POST['tp']);
        $xp = (int)($_POST['xp'] ?? 0);

        updateMembreAdmin($id, $nom, $prenom, $email, $tp, $xp);

        $roles = $_POST['roles'] ?? [];
        setMembreRoles($id, $roles);

        header('Location: /?page=admin-admin/users&id=' . $id);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/users');
            exit();
        }

        $id = (int)$_POST['id'];
        deleteMembre($id);

        header('Location: /?page=admin-admin/users');
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/users');
            exit();
        }

        $newId = createMembreAdmin();

        header('Location: /?page=admin-admin/users&id=' . $newId);
        exit();
    }

    public function uploadImage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?page=admin-admin/users');
            exit();
        }

        $id = (int)$_POST['id'];
        $user = getMembre($id);
        $user = $user[0] ?? null;

        if (!$user) {
            header('Location: /?page=admin-admin/users');
            exit();
        }

        $imageName = saveImage();

        if ($imageName) {
            if (!empty($user['pp_membre']) && $user['pp_membre'] !== 'N/A') {
                deleteFile($user['pp_membre']);
            }
            updateMembrePP($imageName, $id);
        }

        header('Location: /?page=admin-admin/users&id=' . $id);
        exit();
    }
}
