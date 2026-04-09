<?php
require_once __DIR__ . '/../bdd/database.php';

function hasPermission($permission): bool
{

    if (!isset($_SESSION['userid'])) {
        return false;
    }

    $db = DB::getInstance();
    $perms = $db->select("SELECT * FROM LISTE_PERMISSIONS WHERE id_membre = ?", 'i', [$_SESSION['userid']]);

    if (count($perms) == 0 || !isset($perms[0][$permission]) || $perms[0][$permission] == 0) {
        return false;
    }

    return true;

}

function checkPermission($permission): void
{
    if (hasPermission($permission) === false) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Forbidden',
            'message' => 'You do not have permission to access this resource.'
        ]);
        exit;
    }
}