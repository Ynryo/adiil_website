<?php
require_once "src/model/bdd/database.php";

function getMessages(int $afterId = 0): array
{
    $db = DB::getInstance();
    return $db->select(
        "SELECT cm.id_message, cm.message, cm.created_at,
                m.prenom_membre, m.nom_membre, m.pp_membre
         FROM CHAT_MESSAGE cm
         JOIN MEMBRE m ON m.id_membre = cm.id_membre
         WHERE cm.id_message > ?
         ORDER BY cm.created_at ASC
         LIMIT 100",
        "i",
        [$afterId]
    );
}

function getLastMessageId(): int
{
    $db = DB::getInstance();
    $result = $db->select("SELECT MAX(id_message) AS last_id FROM CHAT_MESSAGE");
    return (int) ($result[0]['last_id'] ?? 0);
}

function insertMessage(int $idMembre, string $message): int
{
    $db = DB::getInstance();
    return $db->query(
        "INSERT INTO CHAT_MESSAGE (id_membre, message, created_at) VALUES (?, ?, NOW())",
        "is",
        [$idMembre, $message]
    );
}