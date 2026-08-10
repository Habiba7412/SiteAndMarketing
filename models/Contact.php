<?php
require_once __DIR__ . '/BaseModel.php';

class Contact extends BaseModel {
    public static function getAll() {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM `contact_submissions` ORDER BY `id` DESC");
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `contact_submissions` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `contact_submissions` (`name`, `email`, `subject`, `message`, `is_read`) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['email'], $data['subject'], $data['message'], $data['is_read'] ?? 0]);
    }

    public static function updateStatus($id, $isRead) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `contact_submissions` SET `is_read` = ? WHERE `id` = ?");
        return $stmt->execute([$isRead, $id]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `contact_submissions` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
