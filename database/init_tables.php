<?php
require_once __DIR__ . '/../config/config.php';

echo "=== Initializing SaranIndex Database & Tables ===\n";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Fallback connections (root with empty password or 'root')
$credentials = [
    ['host' => DB_HOST, 'user' => DB_USER, 'pass' => DB_PASS],
    ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'root']
];

$pdo = null;

foreach ($credentials as $cred) {
    try {
        $dsn = "mysql:host=" . $cred['host'] . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, $cred['user'], $cred['pass'], $options);
        echo "Connected successfully to MySQL server via user: " . $cred['user'] . "@" . $cred['host'] . "\n";
        break;
    } catch (PDOException $e) {
        // try next
    }
}

if (!$pdo) {
    die("ERROR: Could not connect to MySQL server. Please ensure MySQL is running.\n");
}

$dbname = DB_NAME;
echo "Creating database `$dbname` if not exists...\n";
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$pdo->exec("USE `$dbname`");

$schema_file = __DIR__ . '/schema.sql';
if (!file_exists($schema_file)) {
    die("ERROR: schema.sql file not found at $schema_file\n");
}

echo "Executing schema.sql to create all tables and insert seed data...\n";
$sql = file_get_contents($schema_file);

// Execute SQL queries split by semicolon or direct multi query
try {
    $pdo->exec($sql);
    echo "Successfully executed schema.sql!\n";
} catch (PDOException $e) {
    echo "Executing multi-query statements individually...\n";
    $queries = explode(";\n", $sql);
    foreach ($queries as $q) {
        $q = trim($q);
        if (!empty($q)) {
            try {
                $pdo->exec($q);
            } catch (PDOException $ex) {
                echo "Warning on query: " . substr($q, 0, 50) . " -> " . $ex->getMessage() . "\n";
            }
        }
    }
}

// Ensure default Admin exists
$stmt = $pdo->query("SELECT COUNT(*) FROM `admins`");
if ((int)$stmt->fetchColumn() === 0) {
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $ins = $pdo->prepare("INSERT INTO `admins` (username, password_hash, full_name, email, role) VALUES ('admin', :hash, 'SaranIndex Administrator', 'admin@saranindex.com', 'SUPER_ADMIN')");
    $ins->execute(['hash' => $hash]);
    echo "Created default admin user ('admin' / 'admin123')\n";
}

echo "\n--- Tables present in database `$dbname` ---\n";
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) {
    $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
    echo "- Table: $t ($count rows)\n";
}

echo "\n=== Database Initialization Complete! ===\n";
