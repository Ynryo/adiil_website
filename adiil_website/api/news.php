<?php
session_start();
require_once __DIR__ . '/../bootstrap.php';
use App\Models\File;
use App\Models\News;
use App\Models\Role;
use App\Helpers\Filter;
use App\Helpers\Tools;

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

Tools::checkPermission('p_actualite');

$DB = \App\Database\DB::getInstance();

$methode = $_SERVER['REQUEST_METHOD'];

switch ($methode) {
    case 'GET':                      # READ
        get_news();
        break;
    case 'POST':                     # CREATE
        create_news();
        break;
    case 'PUT':                     # UPDATE (données)
        if (Tools::methodAccepted('application/json')) {
            update_news();
        }
        break;

    case 'PATCH':                     # UPDATE (image)
        update_image();
        break;

    case 'DELETE':                   # DELETE
        delete_news();
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}



function get_news(): void
{
    if (isset($_GET['id'])) {
        $id = Filter::int($_GET['id']);
        $news = News::getInstance($id);

        if ($news == null) {
            http_response_code(404);
            echo json_encode(['error' => 'Role not found']);
            return;
        }
        echo $news;

    } else {
        $news = News::bulkFetch();
        echo json_encode($news);
    }
}

function create_news(): void
{
    $news = News::create("Nouvel article", "Description de l'article", "2021-01-01", $_SESSION['userid'], null);
    echo $news;
}

function update_news(): void
{
    $id = Filter::int($_GET['id']);
    $news = News::getInstance($id);

    if ($news == null) {
        http_response_code(404);
        echo json_encode(['error' => 'Role not found']);
        return;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $name = Filter::string($data['name'], maxLength: 100);
    $description = Filter::string($data['description'], maxLength: 1000);
    $date = Filter::string($data['date']);
    $id_membre = Filter::int($_SESSION['userid']);

    $news->update($name, $description, $date, $id_membre);

    echo $news;
}

function update_image(): void
{
    $id = Filter::int($_GET['id']);
    $news = News::getInstance($id);

    if ($news == null) {
        http_response_code(404);
        echo json_encode(['error' => 'Role not found']);
        return;
    }

    $image = File::saveImage();

    if ($image == null) {
        http_response_code(400);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }

    $news->updateImage($image);
    echo $news;
}


function delete_news(): void
{
    $id = Filter::int($_GET['id']);
    $news = News::getInstance($id);

    if ($news == null) {
        http_response_code(404);
        echo json_encode(['error' => 'News not found']);
        return;
    }

    $news->delete();
    http_response_code(200);
    echo json_encode(['message' => 'News deleted']);
}

