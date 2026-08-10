<?php
require_once __DIR__ . '/../includes/db.php';

class BaseModel {
    protected static function getDB() {
        global $pdo;
        return $pdo;
    }
}
