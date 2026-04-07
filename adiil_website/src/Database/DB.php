<?php

namespace App\Database;

/**
 * Classe de connexion et d'exécution de requêtes sur la base de données.
 * Utilise le pattern Singleton pour éviter les connexions multiples.
 */
class DB
{
    private static ?DB $instance = null;

    private string $host;
    private string $port;
    private string $db;
    private string $dbUser;
    private string $dbPass;
    private string $charset;

    private function __construct()
    {
        $configPath = __DIR__ . '/../../config.php';
        if (!file_exists($configPath)) {
            die('Fichier config.php introuvable. Copiez config.example.php vers config.php et configurez vos accès.');
        }

        $config = require_once $configPath;
        $dbConfig = $config['db'];

        $this->host = $dbConfig['host'];
        $this->port = $dbConfig['port'];
        $this->db = $dbConfig['database'];
        $this->dbUser = $dbConfig['username'];
        $this->dbPass = $dbConfig['password'];
        $this->charset = $dbConfig['charset'] ?? 'utf8mb4';
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connect(): \mysqli
    {
        $conn = new \mysqli($this->host, $this->dbUser, $this->dbPass, $this->db, $this->port);
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        $conn->set_charset($this->charset);
        return $conn;
    }

    /**
     * Exécuter une requête INSERT/UPDATE/DELETE et retourner l'insert_id.
     */
    public function query(string $sql, string $types = '', array $args = []): int
    {
        $conn = $this->connect();

        $stmt = $conn->prepare($sql);
        if (!empty($types)) {
            $stmt->bind_param($types, ...$args);
        }

        $stmt->execute();

        $id = $conn->insert_id;
        $stmt->close();
        $conn->close();
        return $id;
    }

    /**
     * Exécuter une requête SELECT et retourner un tableau associatif.
     */
    public function select(string $sql, string $types = '', array $args = []): array
    {
        $conn = $this->connect();

        $stmt = $conn->prepare($sql);
        if (!empty($types)) {
            $stmt->bind_param($types, ...$args);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $data;
    }

    /**
     * Échapper les caractères spéciaux pour l'affichage HTML.
     */
    public static function clean(mixed $input): string
    {
        return htmlspecialchars((string) $input, ENT_QUOTES, 'UTF-8');
    }
}
