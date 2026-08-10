<?php
require_once __DIR__ . '/BaseModel.php';

class Project extends BaseModel {
    public static function getAll($status = null, $limit = null) {
        $db = self::getDB();
        $query = "SELECT * FROM `projects`";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE `status` = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY `id` DESC";
        if ($limit !== null) {
            $query .= " LIMIT " . intval($limit);
        }
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `projects` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getBySlug($slug) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `projects` WHERE `slug` = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function getCategories() {
        $db = self::getDB();
        $stmt = $db->query("SELECT DISTINCT `category` FROM `projects` WHERE `status` = 'published' ORDER BY `category` ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function create($data) {
        $db = self::getDB();
        $slug = $data['slug'] ?? self::makeSlug($data['title']);
        $stmt = $db->prepare("INSERT INTO `projects` (`title`, `slug`, `category`, `image_url`, `client`, `year`, `tags`, `project_date`, `link`, `description`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], $slug, $data['category'], $data['image_url'] ?? '',
            $data['client'] ?? '', $data['year'] ?? date('Y'),
            $data['tags'] ?? '', $data['project_date'] ?? null,
            $data['link'] ?? '#', $data['description'], $data['status'] ?? 'published'
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `projects` SET `title`=?, `slug`=?, `category`=?, `image_url`=?, `client`=?, `year`=?, `tags`=?, `project_date`=?, `link`=?, `description`=?, `status`=? WHERE `id`=?");
        return $stmt->execute([
            $data['title'], $data['slug'] ?? self::makeSlug($data['title']),
            $data['category'], $data['image_url'] ?? '',
            $data['client'] ?? '', $data['year'] ?? date('Y'),
            $data['tags'] ?? '', $data['project_date'] ?? null,
            $data['link'] ?? '#', $data['description'], $data['status'] ?? 'published', $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `projects` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    private static function makeSlug($title) {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($title)));
    }
}
