<?php
require_once __DIR__ . '/BaseModel.php';

class Testimonial extends BaseModel {
    public static function getAll($status = null) {
        $db = self::getDB();
        $query = "SELECT * FROM `testimonials`";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE `status` = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY `id` DESC";
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `testimonials` (`client_name`, `company`, `image_url`, `rating`, `review`, `status`) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['client_name'], $data['company'] ?? '', $data['image_url'] ?? '',
            $data['rating'] ?? 5, $data['review'], $data['status'] ?? 'published'
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `testimonials` SET `client_name` = ?, `company` = ?, `image_url` = ?, `rating` = ?, `review` = ?, `status` = ? WHERE `id` = ?");
        return $stmt->execute([
            $data['client_name'], $data['company'] ?? '', $data['image_url'] ?? '',
            $data['rating'] ?? 5, $data['review'], $data['status'] ?? 'published', $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
