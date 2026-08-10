<?php
require_once __DIR__ . '/BaseModel.php';

class Faq extends BaseModel {
    public static function getAll($status = null) {
        $db = self::getDB();
        $query = "SELECT * FROM `faqs`";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE `status` = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY `sort_order` ASC, `id` ASC";
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `faqs` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `status`) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['question'],
            $data['answer'],
            $data['sort_order'] ?? 0,
            $data['status'] ?? 'published'
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `faqs` SET `question`=?, `answer`=?, `sort_order`=?, `status`=? WHERE `id`=?");
        return $stmt->execute([
            $data['question'],
            $data['answer'],
            $data['sort_order'] ?? 0,
            $data['status'] ?? 'published',
            $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `faqs` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
