<?php
require_once __DIR__ . '/includes/db.php';

try {
    // Check if columns exist first
    $stmt = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'reset_token'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_token` VARCHAR(64) NULL AFTER `password`");
        echo "Added reset_token column.\n";
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM `users` LIKE 'reset_expires'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_expires` DATETIME NULL AFTER `reset_token`");
        echo "Added reset_expires column.\n";
    }
    
    echo "Database updated successfully.\n";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage() . "\n";
}
