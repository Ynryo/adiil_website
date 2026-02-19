<?php
session_start();

require_once __DIR__ . '/../bootstrap.php';
use App\Models\Member;
use App\Models\Role;
use App\Helpers\Filter;
use App\Helpers\Tools;

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');


Tools::checkPermission('p_role');
Tools::checkPermission('p_utilisateur');


$methode = $_SERVER['REQUEST_METHOD'];


switch ($methode) {
    case 'GET':                      # READ
        get_userRoles();
        break;
    case 'POST':                     # CREATE
        create_role();
        break;
    case 'PUT':
        if (Tools::methodAccepted('application/json')) {
            setUserRoles();
        }
        break;

    case 'DELETE':                   # DELETE
        delete_role();
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_userRoles(): void
{
    if (isset($_GET['id'])) {
        // Si un ID est précisé, on renvoie les infos de l'utilisateur correspondant avec ses rôles
        $id = Filter::int($_GET['id']);

        $data = Member::getInstance($id);

        if (!$data) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }

        http_response_code(200);
        echo json_encode($data->getRoles());


    } else {
        http_response_code(400);
        echo json_encode(["message" => "Missing id"]);
    }
}

function setUserRoles(): void
{
    if (isset($_GET['id'])) {

        $id = Filter::int($_GET['id']);

        $data = Member::getInstance($id);

        if (!$data) {
            http_response_code(404);
            echo json_encode(["message" => "User not found"]);
            return;
        }
        $val = json_decode(file_get_contents('php://input'), true);

        if (!isset($val['roles'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing parameters']);
            return;
        }

        $roles = Filter::json($val['roles']);
        $success = $data->setRoles($roles);

        if ($success) {
            http_response_code(200);
            echo json_encode($data->getRoles());
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Error while updating roles"]);
        }

    }
}
