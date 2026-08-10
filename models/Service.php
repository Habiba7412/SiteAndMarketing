<?php
require_once __DIR__ . '/BaseModel.php';

class Service extends BaseModel {
    public static function getAll($status = null) {
        $db = self::getDB();
        $query = "SELECT * FROM `services`";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE `status` = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY `id` ASC";
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `services` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getBySlug($slug) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `services` WHERE `slug` = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = self::getDB();
        $slug = $data['slug'] ?? self::makeSlug($data['title']);
        $stmt = $db->prepare("INSERT INTO `services` (`title`, `slug`, `icon`, `description`, `long_description`, `image_url`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], $slug, $data['icon'] ?? '',
            $data['description'], $data['long_description'] ?? '',
            $data['image_url'] ?? '', $data['status'] ?? 'published'
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `services` SET `title`=?, `slug`=?, `icon`=?, `description`=?, `long_description`=?, `image_url`=?, `status`=? WHERE `id`=?");
        return $stmt->execute([
            $data['title'], $data['slug'] ?? self::makeSlug($data['title']),
            $data['icon'] ?? '', $data['description'], $data['long_description'] ?? '',
            $data['image_url'] ?? '', $data['status'] ?? 'published', $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `services` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    private static function makeSlug($title) {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($title)));
    }
}
