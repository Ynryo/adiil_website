<?php

require_once 'src/model/bdd/chat.php';

class ChatController
{
    private const REDIRECT_URL = 'Location: /?page=admin-admin/chat';

    public function show()
    {
        $messages = getMessages(0);
        $lastId = getLastMessageId();
        include_once 'src/view/admin/panels/chat.php';
    }

    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['userid'])) {
            header(self::REDIRECT_URL);
            exit();
        }

        $message = trim($_POST['message'] ?? '');

        if ($message === '') {
            header(self::REDIRECT_URL);
            exit();
        }

        insertMessage((int) $_SESSION['userid'], $message);

        header(self::REDIRECT_URL);
        exit();
    }

    public function poll()
    {
        if (!isset($_SESSION['userid'])) {
            http_response_code(403);
            echo json_encode([]);
            exit();
        }

        $afterId = (int) ($_GET['after'] ?? 0);
        $messages = getMessages($afterId);

        header('Content-Type: application/json');
        echo json_encode($messages);
        exit();
    }
}