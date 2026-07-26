<?php
require_once __DIR__ . '/../config/db.php';

$db = getDB();
if (!$db) {
    echo "Failed to connect to database!\n";
    exit(1);
}

echo "Database connected successfully.\n";
echo "Listing all tables and record counts:\n";
echo str_repeat("-", 40) . "\n";

$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $count = $db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    printf("%-20s : %d records\n", $table, $count);
}
echo str_repeat("-", 40) . "\n";
