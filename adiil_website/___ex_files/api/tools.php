<?php

require_once 'DB.php';

class tools
{
    public static function methodAccepted(...$acceptedContentType)
    {
        // On récupère le Content-Type de la requête (chaine vide si non précisé)
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        // Si le Content-Type de la requête est dans la liste des types acceptés, on retourne vrai
        foreach ($acceptedContentType as $type) {
            if (str_starts_with($contentType, $type)) {
                return true;
            }
        }

        // Erreur si le Content-Type n'est pas supporté
        http_response_code(415);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Unsupported Media Type',
            'message' => "Content-Type '{$contentType}' is not supported. Accepted types: " . implode(', ', $acceptedContentType)
        ]);

        // On arrête le script
        exit;
    }
}
