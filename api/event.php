<?php
session_start();
require_once __DIR__ . '/../bootstrap.php';
use App\Models\Event;
use App\Models\File;
use App\Helpers\Filter;
use App\Helpers\Tools;

// TODO: Remove this line in production
ini_set('display_errors', 1);

header('Content-Type: application/json');

Tools::checkPermission('p_evenement');

$methode = $_SERVER['REQUEST_METHOD'];
$DB = \App\Database\DB::getInstance();

switch ($methode) {
    case 'GET':                      # READ
        get_events();
        break;
    case 'POST':                     # CREATE
        create_event();
        break;
    case 'PUT':                      # UPDATE (données seulement)
        if (Tools::methodAccepted('application/json')) {
            update_event();
        }
        break;
    case 'PATCH':                    # UPDATE (image seulement)
        update_image();
        break;
    case 'DELETE':                   # DELETE
        delete_event();
        break;
    default:
        # 405 Method Not Allowed
        http_response_code(405);
        break;
}

function get_events(): void
{
    if (isset($_GET['id'])) {
        $id = Filter::int($_GET['id']);
        $events = Event::getInstance($id);

        if ($events == null) {
            http_response_code(404);
            echo json_encode(['error' => 'Event not found']);
            return;
        }
    } else {
        $events = Event::bulkFetch();
    }

    echo json_encode($events);
}

function create_event(): void
{
    $event = Event::create("Nouvel événement", "Description de l'événement", 0, 0, false, 0, "Lieu de l'événement", "2021-01-01");

    echo json_encode($event);
}

function update_event(): void
{
    $data = json_decode(file_get_contents('php://input'), true);
    $event = Event::getInstance($_GET['id']);

    if (!$event) {
        http_response_code(404);
        echo json_encode(['error' => 'Event not found']);
        return;
    }

    $event->update(
        Filter::string($data['nom'], maxLength: 100),
        Filter::string($data['description'], maxLength: 1000),
        Filter::int($data['xp']),
        Filter::int($data['places'], -100000),
        Filter::bool($data['reductions']),
        Filter::float($data['prix']),
        Filter::string($data['lieu'], maxLength: 50),
        Filter::date($data['date'])
    );
    echo json_encode($event);
}

function update_image(): void
{
    $event = Event::getInstance($_GET['id']);

    if (!$event) {
        http_response_code(404);
        echo json_encode(['error' => 'Event not found']);
        return;
    }

    $image = File::saveImage();

    if (!$image) {
        http_response_code(400);
        echo json_encode(['error' => 'Image could not be processed']);
        return;
    }

    $event->updateImage($image);
    echo json_encode($event);
}

function delete_event(): void
{
    $event = Event::getInstance($_GET['id']);

    if (!$event) {
        http_response_code(404);
        echo json_encode(['error' => 'Event not found']);
        return;
    }

    $event->delete();
    http_response_code(200);
    echo json_encode(['message' => 'Event deleted']);
}