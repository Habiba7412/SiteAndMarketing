<?php
require_once __DIR__ . '/BaseModel.php';

class Blog extends BaseModel {
    public static function getAll($limit = null, $search = '') {
        $db = self::getDB();
        $query = "SELECT * FROM `blogs` WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (`title` LIKE ? OR `content` LIKE ? OR `category` LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $query .= " ORDER BY `created_at` DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public static function getPublished($limit = null, $search = '', $offset = 0) {
        $db = self::getDB();
        $query = "SELECT * FROM `blogs` WHERE `status` = 'published'";
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (`title` LIKE ? OR `content` LIKE ? OR `category` LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $query .= " ORDER BY `created_at` DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT " . intval($limit) . " OFFSET " . intval($offset);
        }
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getCount($search = '') {
        $db = self::getDB();
        $query = "SELECT COUNT(*) FROM `blogs` WHERE `status` = 'published'";
        $params = [];
        if (!empty($search)) {
            $query .= " AND (`title` LIKE ? OR `content` LIKE ? OR `category` LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public static function getBySlug($slug) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `blogs` WHERE `slug` = ? AND `status` = 'published'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public static function getById($id) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `blogs` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getRelated($category, $excludeId, $limit = 3) {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM `blogs` WHERE `category` = ? AND `id` != ? AND `status` = 'published' ORDER BY `created_at` DESC LIMIT " . intval($limit));
        $stmt->execute([$category, $excludeId]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = self::getDB();
        $stmt = $db->prepare("INSERT INTO `blogs` (`title`, `slug`, `category`, `image_url`, `excerpt`, `content`, `author`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'], $data['slug'], $data['category'], $data['image_url'],
            $data['excerpt'], $data['content'], $data['author'], $data['status']
        ]);
    }

    public static function update($id, $data) {
        $db = self::getDB();
        $stmt = $db->prepare("UPDATE `blogs` SET `title` = ?, `slug` = ?, `category` = ?, `image_url` = ?, `excerpt` = ?, `content` = ?, `author` = ?, `status` = ? WHERE `id` = ?");
        return $stmt->execute([
            $data['title'], $data['slug'], $data['category'], $data['image_url'],
            $data['excerpt'], $data['content'], $data['author'], $data['status'], $id
        ]);
    }

    public static function delete($id) {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM `blogs` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }
}
