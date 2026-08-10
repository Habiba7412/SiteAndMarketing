<?php
require_once __DIR__ . '/BaseModel.php';

class Team extends BaseModel {
    public static function getAll($status = null) {
        $db = self::getDB();
        $query = "SELECT * FROM `team_members`";
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
        $stmt = $db->prepare("SELECT * FROM `team_members` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `team_members` (`name`, `designation`, `bio`, `image_url`, `linkedin_url`, `twitter_url`, `github_url`, `sort_order`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'], $data['designation'], $data['bio'] ?? '', $data['image_url'] ?? '',
            $data['linkedin_url'] ?? '', $data['twitter_url'] ?? '', $data['github_url'] ?? '',
            $data['sort_order'] ?? 0, $data['status'] ?? 'published'
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `team_members` SET `name`=?, `designation`=?, `bio`=?, `image_url`=?, `linkedin_url`=?, `twitter_url`=?, `github_url`=?, `sort_order`=?, `status`=? WHERE `id`=?");
        return $stmt->execute([
            $data['name'], $data['designation'], $data['bio'] ?? '', $data['image_url'] ?? '',
            $data['linkedin_url'] ?? '', $data['twitter_url'] ?? '', $data['github_url'] ?? '',
            $data['sort_order'] ?? 0, $data['status'] ?? 'published', $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `team_members` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
