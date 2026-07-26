<?php
require_once __DIR__ . '/../config/config.php';

echo "=== Setting up MySQL Database User & Privileges ===\n";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Connect as root to manage database users
$root_credentials = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => ''],
    ['host' => 'localhost', 'user' => 'root', 'pass' => 'root'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => 'root']
];

$pdo = null;
foreach ($root_credentials as $cred) {
    try {
        $dsn = "mysql:host=" . $cred['host'] . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, $cred['user'], $cred['pass'], $options);
        echo "Connected as root via " . $cred['user'] . "@" . $cred['host'] . "\n";
        break;
    } catch (PDOException $e) {
        // Continue checking fallback root passwords
    }
}

if (!$pdo) {
    echo "Could not connect as root to MySQL to create dedicated user. Trying direct connection...\n";
} else {
    $db_name = DB_NAME;
    $db_user = DB_USER;
    $db_pass = DB_PASS;

    echo "1. Creating database `$db_name` if not exists...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

    echo "2. Creating MySQL user '$db_user'@'localhost'...\n";
    $pdo->exec("CREATE USER IF NOT EXISTS '$db_user'@'localhost' IDENTIFIED BY '$db_pass';");
    $pdo->exec("ALTER USER '$db_user'@'localhost' IDENTIFIED BY '$db_pass';");

    echo "3. Granting privileges on `$db_name` to '$db_user'@'localhost'...\n";
    $pdo->exec("GRANT ALL PRIVILEGES ON `$db_name`.* TO '$db_user'@'localhost';");

    echo "4. Creating MySQL user '$db_user'@'%' for remote access if needed...\n";
    try {
        $pdo->exec("CREATE USER IF NOT EXISTS '$db_user'@'%' IDENTIFIED BY '$db_pass';");
        $pdo->exec("ALTER USER '$db_user'@'%' IDENTIFIED BY '$db_pass';");
        $pdo->exec("GRANT ALL PRIVILEGES ON `$db_name`.* TO '$db_user'@'%';");
    } catch (PDOException $e) {
        // % host user might not be allowed depending on MySQL setup
    }

    $pdo->exec("FLUSH PRIVILEGES;");
    echo "Database & User privileges created/updated successfully!\n";
}

echo "\n=== Verifying connection with user '" . DB_USER . "' ===\n";
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $testPdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "SUCCESS! Successfully connected using username: " . DB_USER . " and database: " . DB_NAME . "\n";
    $table_count = count($testPdo->query("SHOW TABLES")->fetchAll());
    echo "Total tables in database: $table_count\n";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage() . "\n";
}
