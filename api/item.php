<?php
session_start();
require_once __DIR__ . '/../bootstrap.php';
use App\Models\File;
use App\Models\Item;
use App\Helpers\Filter;
use App\Helpers\Tools;

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

Tools::checkPermission('p_boutique');

$methode = $_SERVER['REQUEST_METHOD'];

switch ($methode) {
    case 'GET':                      # READ
        get_items();
        break;
    case 'POST':                     # CREATE
        create_item();
        break;
    case 'PUT':                      # UPDATE (données seulement)
        if (Tools::methodAccepted('application/json')) {
            update_item();
        }
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        update_image();
        break;
    case 'DELETE':                   # DELETE
        delete_item();
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}


function get_items(): void
{
    if (isset($_GET['id'])) {
        $id = Filter::int($_GET['id']);
        $item = Item::getInstance($id);

        if (!$item) {
            http_response_code(404);
            echo json_encode(['error' => 'Item not found']);
            return;
        }

    } else {
        $item = Item::bulkFetch();
    }

    http_response_code(200);
    echo json_encode($item);
}

function create_item(): void
{
    $item = Item::create(
        "Nouvel article",
        1,
        0,
        true,
        1.99,
        null
    );

    http_response_code(201);
    echo $item;
}

function update_item(): void
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_GET['id'], $data['name'], $data['xp'], $data['stocks'], $data['reduction'], $data['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $id = Filter::int($_GET['id']);
    $name = Filter::string($data['name'], maxLength: 100);
    $xp = Filter::int($data['xp']);
    $stocks = Filter::int($data['stocks'], min: -100000);
    $reduction = Filter::bool($data['reduction']);
    $price = Filter::float($data['price']);

    $item = Item::getInstance($id);

    if (!$item) {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
        return;
    }

    $item->update($name, $xp, $stocks, $reduction, $price);

    echo $item;
}

function update_image(): void
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $item = Item::getInstance(Filter::int($_GET['id']));

    if (!$item) {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
        return;
    }

    $imageName = File::saveImage();

    if (!$imageName) {
        http_response_code(400);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }


    $item->getImage()?->deleteFile();

    $item->updateImage($imageName);

    echo $item;
}


function delete_item(): void
{
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing parameters']);
        return;
    }

    $id = Filter::int($_GET['id']);
    $item = Item::getInstance($id);

    if (!$item) {
        http_response_code(404);
        echo json_encode(['error' => 'Item not found']);
        return;
    }

    $item->delete();

    http_response_code(200);
    echo json_encode(['message' => 'Item deleted']);
}