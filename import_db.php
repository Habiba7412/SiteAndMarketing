<?php
/**
 * Database Import Script - Auto Prefix Detection
 * 
 * ⚠️ DELETE THIS FILE AFTER IMPORT FOR SECURITY!
 */

// Security: Simple token check
if (!isset($_GET['key']) || $_GET['key'] !== 'digi2026import') {
    die('❌ Access denied. Use ?key=digi2026import');
}

// If user provides prefix manually via URL: ?key=digi2026import&prefix=siteandr
$manualPrefix = isset($_GET['prefix']) ? $_GET['prefix'] : null;

$host     = 'localhost';
$baseDb   = 'marketingandsite';
$baseUser = 'marketing';
$password = 'marketing';

$sqlFile = __DIR__ . '/db_export.sql';

if (!file_exists($sqlFile)) {
    die('❌ db_export.sql file not found! Make sure it was pulled from GitHub.');
}

echo "<h2>🚀 Database Import - Auto Prefix Detection</h2>";
echo "<pre>";

// Build list of credential combinations to try
$combos = [];

if ($manualPrefix) {
    // Try manual prefix first
    $combos[] = [
        'db'   => $manualPrefix . '_' . $baseDb,
        'user' => $manualPrefix . '_' . $baseUser,
    ];
}

// Try without prefix
$combos[] = [
    'db'   => $baseDb,
    'user' => $baseUser,
];

// Try common cPanel prefixes based on domain
$possiblePrefixes = ['siteandr', 'siteandm', 'sitean', 'market', 'marketi', 'marketin'];
foreach ($possiblePrefixes as $pfx) {
    $combos[] = [
        'db'   => $pfx . '_' . $baseDb,
        'user' => $pfx . '_' . $baseUser,
    ];
}

$connected = false;
$pdo = null;
$usedDb = '';
$usedUser = '';

foreach ($combos as $c) {
    try {
        $dsn = "mysql:host=$host;dbname={$c['db']};charset=utf8mb4";
        $pdo = new PDO($dsn, $c['user'], $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connected = true;
        $usedDb = $c['db'];
        $usedUser = $c['user'];
        echo "✅ Connected! DB: {$c['db']} | User: {$c['user']}\n\n";
        break;
    } catch (PDOException $e) {
        echo "❌ Tried DB: {$c['db']} | User: {$c['user']} → " . $e->getMessage() . "\n";
    }
}

if (!$connected) {
    echo "\n\n❌ Could not connect with any combination.\n";
    echo "👉 Please provide your cPanel prefix in the URL like:\n";
    echo "   import_db.php?key=digi2026import&prefix=YOUR_CPANEL_PREFIX\n\n";
    echo "   Find your prefix in cPanel → MySQL Databases.\n";
    echo "   Example: if DB name is 'abc_marketingandsite', prefix is 'abc'\n";
    echo "</pre>";
    exit;
}

// Read and execute SQL
$sql = file_get_contents($sqlFile);

if (empty($sql)) {
    die('❌ SQL file is empty!');
}

echo "📄 SQL file loaded (" . round(strlen($sql) / 1024, 2) . " KB)\n";
echo "⏳ Importing...\n\n";

try {
    // Disable foreign key checks during import
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec($sql);
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "✅ Database imported successfully!\n\n";
    
    // Show tables as verification
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "📊 Tables in database ($usedDb):\n";
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        echo "   ✓ $table ($count rows)\n";
    }
    
    echo "\n\n🎉 IMPORT COMPLETE!\n";
    echo "📌 Used credentials: DB=$usedDb | User=$usedUser\n";
    echo "\n⚠️  NOW DELETE these files from your server:\n";
    echo "   - import_db.php\n";
    echo "   - db_export.sql\n";
    
} catch (PDOException $e) {
    echo "❌ Import Error: " . $e->getMessage() . "\n";
}

echo "</pre>";
?>
