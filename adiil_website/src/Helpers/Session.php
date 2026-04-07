<?php

namespace App\Helpers;

/**
 * Helper pour la gestion des sessions.
 */
class Session
{
    private static bool $started = false;

    /**
     * Démarrer la session si elle n'est pas déjà démarrée.
     */
    public static function start(): void
    {
        if (!self::$started && session_status() === PHP_SESSION_NONE) {
            session_start();
            self::$started = true;
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté.
     */
    public static function isLoggedIn(): bool
    {
        self::start();
        return isset($_SESSION['userid']);
    }

    /**
     * Récupérer l'ID de l'utilisateur connecté.
     */
    public static function getUserId(): ?int
    {
        self::start();
        return $_SESSION['userid'] ?? null;
    }

    /**
     * Vérifier si l'utilisateur est admin.
     */
    public static function isAdmin(): bool
    {
        self::start();
        return !empty($_SESSION['isAdmin']);
    }

    /**
     * Définir une variable de session.
     */
    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Récupérer une variable de session.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Supprimer une variable de session.
     */
    public static function remove(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Définir un message flash (affiché une seule fois).
     */
    public static function flash(string $message, string $type = 'success'): void
    {
        self::start();
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }

    /**
     * Récupérer et supprimer le message flash.
     * Retourne null s'il n'y en a pas.
     */
    public static function getFlash(): ?array
    {
        self::start();
        if (!isset($_SESSION['flash_message'])) {
            return null;
        }

        $flash = [
            'message' => $_SESSION['flash_message'],
            'type' => $_SESSION['flash_type'] ?? 'success',
        ];

        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return $flash;
    }

    /**
     * Détruire la session.
     */
    public static function destroy(): void
    {
        self::start();
        session_destroy();
        $_SESSION = [];
        self::$started = false;
    }
}
