<?php
/**
 * Database Import Script
 * 
 * Run this file ONCE on the live server to import the local database dump.
 * URL: https://yoursite.com/import_db.php
 * 
 * ⚠️ DELETE THIS FILE AFTER IMPORT FOR SECURITY!
 */

// Security: Simple token check
if (!isset($_GET['key']) || $_GET['key'] !== 'digi2026import') {
    die('❌ Access denied. Use ?key=digi2026import');
}

// Live server database credentials
$host     = 'localhost';
$dbname   = 'marketingandsite';
$username = 'marketing';
$password = 'marketing';

$sqlFile = __DIR__ . '/db_export.sql';

if (!file_exists($sqlFile)) {
    die('❌ db_export.sql file not found! Make sure it was pulled from GitHub.');
}

echo "<h2>🚀 Database Import Started</h2>";
echo "<pre>";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to database: $dbname\n\n";
    
    // Read the SQL file
    $sql = file_get_contents($sqlFile);
    
    if (empty($sql)) {
        die('❌ SQL file is empty!');
    }
    
    echo "📄 SQL file loaded (" . round(strlen($sql) / 1024, 2) . " KB)\n\n";
    
    // Execute the SQL dump
    $pdo->exec($sql);
    
    echo "✅ Database imported successfully!\n\n";
    
    // Show tables as verification
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📊 Tables in database ($dbname):\n";
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "   ✓ $table ($count rows)\n";
    }
    
    echo "\n\n🎉 IMPORT COMPLETE!\n";
    echo "⚠️  DELETE this file (import_db.php) and db_export.sql from your server for security!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
