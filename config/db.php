<?php
require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $pdo = null;

    private function __construct() {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Attempt 1: Configured credentials
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            return;
        } catch (PDOException $e) {
            error_log("Primary DB Connection failed: " . $e->getMessage());
        }

        // Attempt 2: Local development fallbacks (Laragon / XAMPP / WAMP defaults)
        $fallback_credentials = [
            ['user' => 'root', 'pass' => ''],
            ['user' => 'root', 'pass' => 'root']
        ];

        foreach ($fallback_credentials as $fb) {
            try {
                $dsn_server = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
                $pdo_server = new PDO($dsn_server, $fb['user'], $fb['pass'], $options);

                // Auto-create database if missing
                $dbname = DB_NAME;
                $pdo_server->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo_server->exec("USE `$dbname`");

                $this->pdo = $pdo_server;

                // Auto-import schema if tables are not initialized
                $stmt = $this->pdo->query("SHOW TABLES LIKE 'listings'");
                if ($stmt->rowCount() === 0) {
                    $schema_file = __DIR__ . '/../database/schema.sql';
                    if (file_exists($schema_file)) {
                        $sql = file_get_contents($schema_file);
                        $this->pdo->exec($sql);
                    }
                }
                return;
            } catch (PDOException $e) {
                error_log("Fallback DB Connection failed: " . $e->getMessage());
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}

function getDB() {
    return Database::getInstance()->getConnection();
}
?>

