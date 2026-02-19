<?php

namespace App\Helpers;

/**
 * Protection CSRF pour les formulaires.
 */
class Csrf
{
    private const TOKEN_KEY = '_csrf_token';

    /**
     * Générer un token CSRF et le stocker en session.
     */
    public static function generate(): string
    {
        Session::start();
        $token = bin2hex(random_bytes(32));
        Session::set(self::TOKEN_KEY, $token);
        return $token;
    }

    /**
     * Retourner le champ HTML hidden contenant le token CSRF.
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="' . self::TOKEN_KEY . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Vérifier que le token CSRF soumis est valide.
     */
    public static function verify(): bool
    {
        Session::start();
        $sessionToken = Session::get(self::TOKEN_KEY);
        $postToken = $_POST[self::TOKEN_KEY] ?? '';

        if (empty($sessionToken) || empty($postToken)) {
            return false;
        }

        return hash_equals($sessionToken, $postToken);
    }

    /**
     * Vérifier le token CSRF et arrêter le script si invalide.
     */
    public static function check(): void
    {
        if (!self::verify()) {
            http_response_code(403);
            die('Token CSRF invalide. Veuillez actualiser la page et réessayer.');
        }
    }
}
