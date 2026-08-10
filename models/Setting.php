<?php
require_once __DIR__ . '/BaseModel.php';

class Setting extends BaseModel {
    public static function get($key, $default = '') {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT `setting_value` FROM `site_settings` WHERE `setting_key` = ?");
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    }

    public static function set($key, $val) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `setting_value` = ?");
        return $stmt->execute([$key, $val, $val]);
    }

    public static function getAll() {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM `site_settings`");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}
