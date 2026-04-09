<?php

require_once 'src/model/bdd/role.php';

class RolesController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/roles';

    public function show()
    {
        $roles = getAllRoles();

        $selectedRole = null;

        if (isset($_GET['id'])) {
            $selectedRole = getRole((int) $_GET['id']);
        } elseif (!empty($roles)) {
            $selectedRole = getRole($roles[0]['id_role']);
        }

        if ($selectedRole) {
            $selectedRole = $selectedRole[0] ?? null;
        }

        include_once 'src/view/admin/panels/roles.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        $nom = trim($_POST['nom']) ?: 'N/A';

        $p_log = isset($_POST['p_log']) ? 1 : 0;
        $p_boutique = isset($_POST['p_boutique']) ? 1 : 0;
        $p_reunion = isset($_POST['p_reunion']) ? 1 : 0;
        $p_utilisateur = isset($_POST['p_utilisateur']) ? 1 : 0;
        $p_grade = isset($_POST['p_grade']) ? 1 : 0;
        $p_role = isset($_POST['p_role']) ? 1 : 0;
        $p_actualite = isset($_POST['p_actualite']) ? 1 : 0;
        $p_evenement = isset($_POST['p_evenement']) ? 1 : 0;
        $p_comptabilite = isset($_POST['p_comptabilite']) ? 1 : 0;
        $p_achat = isset($_POST['p_achat']) ? 1 : 0;
        $p_moderation = isset($_POST['p_moderation']) ? 1 : 0;

        updateRole($id, $nom, $p_log, $p_boutique, $p_reunion, $p_utilisateur, $p_grade, $p_role, $p_actualite, $p_evenement, $p_comptabilite, $p_achat, $p_moderation);

        header(self::REDIRECT_URL . '&id=' . $id);
        exit();
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $id = (int) $_POST['id'];
        deleteRole($id);

        header(self::REDIRECT_URL);
        exit();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(self::REDIRECT_URL);
            exit();
        }

        $newId = createRole();

        header(self::REDIRECT_URL . '&id=' . $newId);
        exit();
    }
}