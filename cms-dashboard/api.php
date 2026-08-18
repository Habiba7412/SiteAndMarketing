<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/seo.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ─── Helper: serialize a PDO result as JSON ───────────────────────────────────
function jsonOk($data) { echo json_encode(['status' => 'success', 'data' => $data]); exit; }
function jsonErr($msg)  { echo json_encode(['error' => $msg]); exit; }

function slugifyString($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    if (function_exists('iconv')) {
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    }
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'blog-post' : $text;
}

function makeUniqueBlogSlug($pdo, $baseSlug, $excludeId = 0) {
    $slug = $baseSlug;
    $count = 1;
    while (true) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM `blogs` WHERE `slug` = ? AND `id` != ?");
        $stmt->execute([$slug, $excludeId]);
        if ($stmt->fetchColumn() == 0) {
            break;
        }
        $count++;
        $slug = $baseSlug . '-' . $count;
    }
    return $slug;
}

switch ($action) {

    // ═══════════════════════════════════════════════════════════════════════════
    // GET ALL STATE — called on dashboard load
    // ═══════════════════════════════════════════════════════════════════════════
    case 'get_all':
        try {
            // 1. Blogs
            $blogs = $pdo->query("SELECT * FROM `blogs` ORDER BY `id` DESC")->fetchAll();
            $blogsFormatted = array_map(function($b) {
                return [
                    'id'              => 'blog-' . $b['id'],
                    'title'           => $b['title'],
                    'slug'            => $b['slug'],
                    'clean_url'       => 'blog/' . $b['slug'],
                    'content'         => $b['content'],
                    'excerpt'         => $b['excerpt'],
                    'category'        => $b['category'],
                    'tags'            => ['Tech', 'IT'],
                    'author'          => $b['author'],
                    'coverImage'      => $b['image_url'],
                    'seoTitle'        => $b['seo_title'] ?? $b['title'],
                    'metaDescription' => $b['meta_description'] ?? $b['excerpt'],
                    'readingTime'     => '5 min read',
                    'status'          => ucfirst($b['status']),
                    'isFeatured'      => false,
                    'dateCreated'     => $b['created_at'],
                ];
            }, $blogs);

            // 2. Contact Submissions
            $contacts = $pdo->query("SELECT * FROM `contact_submissions` ORDER BY `id` DESC")->fetchAll();
            $contactsFormatted = array_map(function($c) {
                return [
                    'id'      => 'msg-' . $c['id'],
                    'name'    => $c['name'],
                    'email'   => $c['email'],
                    'phone'   => '',
                    'company' => '',
                    'subject' => $c['subject'],
                    'message' => $c['message'],
                    'date'    => $c['created_at'],
                    'status'  => $c['is_read'] ? 'read' : 'unread',
                ];
            }, $contacts);

            // 3. Users
            $users = $pdo->query("SELECT * FROM `users` ORDER BY `id` ASC")->fetchAll();
            $usersFormatted = array_map(function($u) {
                return [
                    'id'               => 'usr-' . $u['id'],
                    'name'             => $u['name'],
                    'email'            => $u['email'],
                    'phone'            => '',
                    'status'           => 'Active',
                    'role'             => $u['username'] === 'admin' ? 'Super Admin' : 'Editor',
                    'avatar'           => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                    'lastLogin'        => $u['created_at'],
                    'registrationDate' => $u['created_at'],
                ];
            }, $users);

            // 4. Services
            $services = $pdo->query("SELECT * FROM `services` ORDER BY `id` ASC")->fetchAll();
            $servicesFormatted = array_map(function($s) {
                return [
                    'id'          => 'svc-' . $s['id'],
                    'title'       => $s['title'],
                    'slug'        => $s['slug'],
                    'description' => $s['description'],
                    'icon'        => $s['icon'],
                    'image_url'   => $s['image_url'],
                    'status'      => ucfirst($s['status']),
                ];
            }, $services);

            // 5. Projects
            $projects = $pdo->query("SELECT * FROM `projects` ORDER BY `id` DESC")->fetchAll();
            $projectsFormatted = array_map(function($p) {
                return [
                    'id'          => 'proj-' . $p['id'],
                    'title'       => $p['title'],
                    'slug'        => $p['slug'],
                    'description' => $p['description'],
                    'category'    => $p['category'],
                    'image_url'   => $p['image_url'],
                    'client'      => $p['client'] ?? '',
                    'year'        => $p['year'] ?? '',
                    'tags'        => $p['tags'] ?? '',
                    'status'      => ucfirst($p['status']),
                ];
            }, $projects);

            // 6. Team Members
            $team = $pdo->query("SELECT * FROM `team_members` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
            $teamFormatted = array_map(function($t) {
                return [
                    'id'           => 'tm-' . $t['id'],
                    'name'         => $t['name'],
                    'designation'  => $t['designation'],
                    'bio'          => $t['bio'] ?? '',
                    'image_url'    => $t['image_url'],
                    'linkedin_url' => $t['linkedin_url'] ?? '',
                    'github_url'   => $t['github_url'] ?? '',
                    'twitter_url'  => $t['twitter_url'] ?? '',
                    'sort_order'   => $t['sort_order'],
                    'status'       => ucfirst($t['status']),
                ];
            }, $team);

            // 7. Testimonials
            $testimonials = $pdo->query("SELECT * FROM `testimonials` ORDER BY `id` ASC")->fetchAll();
            $testimonialsFormatted = array_map(function($t) {
                return [
                    'id'          => 'tst-' . $t['id'],
                    'client_name' => $t['client_name'],
                    'company'     => $t['company'] ?? '',
                    'review'      => $t['review'],
                    'rating'      => $t['rating'],
                    'image_url'   => $t['image_url'] ?? '',
                    'status'      => ucfirst($t['status']),
                ];
            }, $testimonials);

            // 8. FAQs
            $faqs = $pdo->query("SELECT * FROM `faqs` ORDER BY `sort_order` ASC, `id` ASC")->fetchAll();
            $faqsFormatted = array_map(function($f) {
                return [
                    'id'         => 'faq-' . $f['id'],
                    'question'   => $f['question'],
                    'answer'     => $f['answer'],
                    'sort_order' => $f['sort_order'],
                    'status'     => ucfirst($f['status']),
                ];
            }, $faqs);

            // 9. site_settings key-value pairs
            $stmt = $pdo->query("SELECT * FROM `site_settings`");
            $dbSettings = [];
            while ($row = $stmt->fetch()) {
                $dbSettings[$row['setting_key']] = $row['setting_value'];
            }

            $menus           = isset($dbSettings['menus'])           ? json_decode($dbSettings['menus'], true)           : [];
            $emailSettings   = isset($dbSettings['emailSettings'])   ? json_decode($dbSettings['emailSettings'], true)   : null;
            $seoSettings     = isset($dbSettings['seoSettings'])     ? json_decode($dbSettings['seoSettings'], true)     : null;
            $websiteSettings = isset($dbSettings['websiteSettings']) ? json_decode($dbSettings['websiteSettings'], true) : null;
            $mediaLibrary    = isset($dbSettings['mediaLibrary'])    ? json_decode($dbSettings['mediaLibrary'], true)    : [];
            $activityLogs    = isset($dbSettings['activityLogs'])    ? json_decode($dbSettings['activityLogs'], true)    : [];
            $backups         = isset($dbSettings['backups'])         ? json_decode($dbSettings['backups'], true)         : [];
            $roles           = isset($dbSettings['roles'])           ? json_decode($dbSettings['roles'], true)           : [];
            $notifications   = isset($dbSettings['notifications'])   ? json_decode($dbSettings['notifications'], true)   : [];

            echo json_encode([
                'blogs'           => $blogsFormatted,
                'contacts'        => $contactsFormatted,
                'users'           => $usersFormatted,
                'services'        => $servicesFormatted,
                'projects'        => $projectsFormatted,
                'team'            => $teamFormatted,
                'testimonials'    => $testimonialsFormatted,
                'faqs'            => $faqsFormatted,
                'menus'           => $menus,
                'emailSettings'   => $emailSettings,
                'seoSettings'     => $seoSettings,
                'websiteSettings' => $websiteSettings,
                'mediaLibrary'    => $mediaLibrary,
                'activityLogs'    => $activityLogs,
                'backups'         => $backups,
                'roles'           => $roles,
                'notifications'   => $notifications,
            ]);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    // ═══════════════════════════════════════════════════════════════════════════
    // SAVE STATE — full sync write back from dashboard state
    // ═══════════════════════════════════════════════════════════════════════════
    case 'save_state':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        try {
            $json    = file_get_contents('php://input');
            $payload = json_decode($json, true);
            if (!$payload || !isset($payload['key']) || !isset($payload['value'])) {
                jsonErr('Invalid parameters payload');
            }

            $key   = $payload['key'];
            $value = $payload['value'];

            // ── Blogs ─────────────────────────────────────────────────────────
            if ($key === 'blogs') {
                $syncedIds = [];
                foreach ($value as $b) {
                    $dbId      = intval(str_replace('blog-', '', $b['id']));
                    $status    = strtolower($b['status']) === 'published' ? 'published' : 'draft';
                    $seoTitle  = $b['seoTitle'] ?? $b['title'];
                    $metaDesc  = $b['metaDescription'] ?? $b['excerpt'];
                    $createdAt = !empty($b['dateCreated']) ? date('Y-m-d H:i:s', strtotime($b['dateCreated'])) : date('Y-m-d H:i:s');
                    
                    $rawSlug   = !empty($b['slug']) ? $b['slug'] : $b['title'];
                    $cleanSlug = slugifyString($rawSlug);
                    $cleanSlug = makeUniqueBlogSlug($pdo, $cleanSlug, $dbId);

                    if ($dbId > 0) {
                        $stmt = $pdo->prepare("UPDATE `blogs` SET `title`=?, `slug`=?, `category`=?, `image_url`=?, `excerpt`=?, `content`=?, `author`=?, `status`=?, `seo_title`=?, `meta_description`=?, `created_at`=? WHERE `id`=?");
                        $stmt->execute([$b['title'], $cleanSlug, $b['category'], $b['coverImage'], $b['excerpt'], $b['content'], $b['author'], $status, $seoTitle, $metaDesc, $createdAt, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO `blogs` (`title`,`slug`,`category`,`image_url`,`excerpt`,`content`,`author`,`status`,`seo_title`,`meta_description`,`created_at`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                        $stmt->execute([$b['title'], $cleanSlug, $b['category'], $b['coverImage'], $b['excerpt'], $b['content'], $b['author'], $status, $seoTitle, $metaDesc, $createdAt]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `blogs` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `blogs`");
                }

            // ── Contacts (read/unread + delete) ──────────────────────────────
            } elseif ($key === 'contacts') {
                $syncedIds = [];
                foreach ($value as $c) {
                    $dbId   = intval(str_replace('msg-', '', $c['id']));
                    $isRead = $c['status'] === 'read' ? 1 : 0;
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `contact_submissions` SET `is_read`=? WHERE `id`=?")->execute([$isRead, $dbId]);
                        $syncedIds[] = $dbId;
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `contact_submissions` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                }

            // ── Users ─────────────────────────────────────────────────────────
            } elseif ($key === 'users') {
                $syncedIds = [];
                foreach ($value as $u) {
                    $dbId = intval(str_replace('usr-', '', $u['id']));
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `users` SET `name`=?, `email`=? WHERE `id`=?")->execute([$u['name'], $u['email'], $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pw = password_hash('default123', PASSWORD_DEFAULT);
                        $un = strtolower(str_replace(' ', '', $u['name']));
                        $pdo->prepare("INSERT INTO `users` (`username`,`password`,`email`,`name`) VALUES (?,?,?,?)")->execute([$un, $pw, $u['email'], $u['name']]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `users` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                }

            // ── Services ──────────────────────────────────────────────────────
            } elseif ($key === 'services') {
                $syncedIds = [];
                foreach ($value as $s) {
                    $dbId   = intval(str_replace('svc-', '', $s['id']));
                    $status = strtolower($s['status']) === 'published' ? 'published' : 'draft';
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `services` SET `title`=?,`slug`=?,`description`=?,`icon`=?,`image_url`=?,`status`=? WHERE `id`=?")
                            ->execute([$s['title'], $s['slug'] ?? '', $s['description'], $s['icon'] ?? '', $s['image_url'] ?? '', $status, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pdo->prepare("INSERT INTO `services` (`title`,`slug`,`description`,`icon`,`image_url`,`status`) VALUES (?,?,?,?,?,?)")
                            ->execute([$s['title'], $s['slug'] ?? '', $s['description'], $s['icon'] ?? '', $s['image_url'] ?? '', $status]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `services` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `services`");
                }

            // ── Projects ──────────────────────────────────────────────────────
            } elseif ($key === 'projects') {
                $syncedIds = [];
                foreach ($value as $p) {
                    $dbId   = intval(str_replace('proj-', '', $p['id']));
                    $status = strtolower($p['status']) === 'published' ? 'published' : 'draft';
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `projects` SET `title`=?,`slug`=?,`description`=?,`category`=?,`image_url`=?,`client`=?,`year`=?,`tags`=?,`status`=? WHERE `id`=?")
                            ->execute([$p['title'], $p['slug'] ?? '', $p['description'], $p['category'], $p['image_url'] ?? '', $p['client'] ?? '', $p['year'] ?? '', $p['tags'] ?? '', $status, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pdo->prepare("INSERT INTO `projects` (`title`,`slug`,`description`,`category`,`image_url`,`client`,`year`,`tags`,`status`) VALUES (?,?,?,?,?,?,?,?,?)")
                            ->execute([$p['title'], $p['slug'] ?? '', $p['description'], $p['category'], $p['image_url'] ?? '', $p['client'] ?? '', $p['year'] ?? '', $p['tags'] ?? '', $status]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `projects` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `projects`");
                }

            // ── Team Members ──────────────────────────────────────────────────
            } elseif ($key === 'team') {
                $syncedIds = [];
                foreach ($value as $t) {
                    $dbId   = intval(str_replace('tm-', '', $t['id']));
                    $status = strtolower($t['status']) === 'published' ? 'published' : 'draft';
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `team_members` SET `name`=?,`designation`=?,`bio`=?,`image_url`=?,`linkedin_url`=?,`github_url`=?,`twitter_url`=?,`sort_order`=?,`status`=? WHERE `id`=?")
                            ->execute([$t['name'], $t['designation'], $t['bio'] ?? '', $t['image_url'] ?? '', $t['linkedin_url'] ?? '', $t['github_url'] ?? '', $t['twitter_url'] ?? '', $t['sort_order'] ?? 0, $status, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pdo->prepare("INSERT INTO `team_members` (`name`,`designation`,`bio`,`image_url`,`linkedin_url`,`github_url`,`twitter_url`,`sort_order`,`status`) VALUES (?,?,?,?,?,?,?,?,?)")
                            ->execute([$t['name'], $t['designation'], $t['bio'] ?? '', $t['image_url'] ?? '', $t['linkedin_url'] ?? '', $t['github_url'] ?? '', $t['twitter_url'] ?? '', $t['sort_order'] ?? 0, $status]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `team_members` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `team_members`");
                }

            // ── Testimonials ──────────────────────────────────────────────────
            } elseif ($key === 'testimonials') {
                $syncedIds = [];
                foreach ($value as $t) {
                    $dbId   = intval(str_replace('tst-', '', $t['id']));
                    $status = strtolower($t['status']) === 'published' ? 'published' : 'draft';
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `testimonials` SET `client_name`=?,`company`=?,`review`=?,`rating`=?,`image_url`=?,`status`=? WHERE `id`=?")
                            ->execute([$t['client_name'], $t['company'] ?? '', $t['review'], $t['rating'] ?? 5, $t['image_url'] ?? '', $status, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pdo->prepare("INSERT INTO `testimonials` (`client_name`,`company`,`review`,`rating`,`image_url`,`status`) VALUES (?,?,?,?,?,?)")
                            ->execute([$t['client_name'], $t['company'] ?? '', $t['review'], $t['rating'] ?? 5, $t['image_url'] ?? '', $status]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `testimonials` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `testimonials`");
                }

            // ── FAQs ──────────────────────────────────────────────────────────
            } elseif ($key === 'faqs') {
                $syncedIds = [];
                foreach ($value as $f) {
                    $dbId   = intval(str_replace('faq-', '', $f['id']));
                    $status = strtolower($f['status']) === 'published' ? 'published' : 'draft';
                    if ($dbId > 0) {
                        $pdo->prepare("UPDATE `faqs` SET `question`=?,`answer`=?,`sort_order`=?,`status`=? WHERE `id`=?")
                            ->execute([$f['question'], $f['answer'], $f['sort_order'] ?? 0, $status, $dbId]);
                        $syncedIds[] = $dbId;
                    } else {
                        $pdo->prepare("INSERT INTO `faqs` (`question`,`answer`,`sort_order`,`status`) VALUES (?,?,?,?)")
                            ->execute([$f['question'], $f['answer'], $f['sort_order'] ?? 0, $status]);
                        $syncedIds[] = $pdo->lastInsertId();
                    }
                }
                if (!empty($syncedIds)) {
                    $ph = implode(',', array_fill(0, count($syncedIds), '?'));
                    $pdo->prepare("DELETE FROM `faqs` WHERE `id` NOT IN ($ph)")->execute($syncedIds);
                } else {
                    $pdo->exec("DELETE FROM `faqs`");
                }

            // ── Flat site_settings (menus, SEO, email, website, etc.) ─────────
            } else {
                $jsonValue = is_string($value) ? $value : json_encode($value);
                $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`,`setting_value`) VALUES (?,?) ON DUPLICATE KEY UPDATE `setting_value`=?");
                $stmt->execute([$key, $jsonValue, $jsonValue]);
            }

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    // ═══════════════════════════════════════════════════════════════════════════
    // UPLOAD IMAGE — accepts multipart file, saves to /uploads/ dir, returns URL
    // ═══════════════════════════════════════════════════════════════════════════
    case 'upload_image':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }

        if (empty($_FILES['image'])) {
            jsonErr('No image file received');
        }

        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            jsonErr('File upload error code: ' . $file['error']);
        }

        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/svg+xml', 'image/svg', 'text/plain', 'text/xml'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $maxSize      = 5 * 1024 * 1024; // 5 MB

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExts)) {
            jsonErr('Invalid file format. Allowed formats: JPG, JPEG, PNG, WEBP, SVG');
        }

        if ($file['size'] > $maxSize) {
            jsonErr('File too large. Maximum file size is 5MB');
        }

        // Validate MIME type if finfo available
        if (function_exists('finfo_open')) {
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if ($mimeType && !in_array($mimeType, $allowedMimes) && $ext !== 'svg') {
                jsonErr('Invalid file type (' . $mimeType . '). Allowed: JPG, JPEG, PNG, WEBP, SVG');
            }
        }

        // Build upload directory relative to project root
        $uploadDir = __DIR__ . '/../uploads/blog/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Unique safe filename
        $filename = 'blog_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            jsonErr('Failed to save file. Check server permissions on /uploads/blog/');
        }

        // Return relative public path for database & dashboard
        $publicUrl = 'uploads/blog/' . $filename;
        echo json_encode([
            'status'   => 'success',
            'url'      => $publicUrl,
            'filename' => $filename,
            'original' => $file['name'],
            'size'     => $file['size']
        ]);
        break;

        // ═══════════════════════════════════════════════════════════════════════════
        // SEO MANAGEMENT SUITE ENDPOINTS
        // ═══════════════════════════════════════════════════════════════════════════
    case 'save_seo_global':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $stmt = $pdo->prepare("UPDATE `seo_global` SET 
                `website_name` = ?, `website_title` = ?, `meta_title` = ?, `meta_description` = ?,
                `website_url` = ?, `canonical_url` = ?, `default_keywords` = ?, `author` = ?,
                `language` = ?, `charset` = ?, `theme_color` = ?, `favicon_url` = ?,
                `apple_touch_icon` = ?, `default_social_image` = ?
                WHERE `id` = 1");
            $stmt->execute([
                $body['website_name'] ?? 'Site And Marketing Technologies',
                $body['website_title'] ?? '',
                $body['meta_title'] ?? '',
                $body['meta_description'] ?? '',
                $body['website_url'] ?? 'https://siteandmarketing.com',
                $body['canonical_url'] ?? '',
                $body['default_keywords'] ?? '',
                $body['author'] ?? 'Site And Marketing',
                $body['language'] ?? 'en',
                $body['charset'] ?? 'UTF-8',
                $body['theme_color'] ?? '#0b1315',
                $body['favicon_url'] ?? '',
                $body['apple_touch_icon'] ?? '',
                $body['default_social_image'] ?? ''
            ]);
            jsonOk(['message' => 'Global SEO settings saved successfully']);
        } catch (Exception $e) {
            jsonErr('Failed to save global SEO: ' . $e->getMessage());
        }
        break;

    case 'save_seo_page':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        if (empty($body['page_key'])) { jsonErr('Page key is required'); }
        try {
            $check = $pdo->prepare("SELECT `id` FROM `seo_pages` WHERE `page_key` = ?");
            $check->execute([$body['page_key']]);
            $exists = $check->fetch();

            if ($exists) {
                $stmt = $pdo->prepare("UPDATE `seo_pages` SET 
                    `page_name` = ?, `meta_title` = ?, `meta_description` = ?, `keywords` = ?,
                    `canonical_url` = ?, `og_title` = ?, `og_description` = ?, `og_image` = ?,
                    `twitter_title` = ?, `twitter_description` = ?, `twitter_image` = ?,
                    `schema_type` = ?, `schema_custom_json` = ?, `is_indexed` = ?, `is_followed` = ?,
                    `sitemap_priority` = ?, `sitemap_changefreq` = ?
                    WHERE `page_key` = ?");
                $stmt->execute([
                    $body['page_name'] ?? $body['page_key'],
                    $body['meta_title'] ?? '',
                    $body['meta_description'] ?? '',
                    $body['keywords'] ?? '',
                    $body['canonical_url'] ?? '',
                    $body['og_title'] ?? '',
                    $body['og_description'] ?? '',
                    $body['og_image'] ?? '',
                    $body['twitter_title'] ?? '',
                    $body['twitter_description'] ?? '',
                    $body['twitter_image'] ?? '',
                    $body['schema_type'] ?? 'WebPage',
                    $body['schema_custom_json'] ?? null,
                    isset($body['is_indexed']) ? (int)$body['is_indexed'] : 1,
                    isset($body['is_followed']) ? (int)$body['is_followed'] : 1,
                    $body['sitemap_priority'] ?? '0.8',
                    $body['sitemap_changefreq'] ?? 'weekly',
                    $body['page_key']
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO `seo_pages` 
                    (`page_key`, `page_name`, `meta_title`, `meta_description`, `keywords`,
                    `canonical_url`, `og_title`, `og_description`, `og_image`,
                    `twitter_title`, `twitter_description`, `twitter_image`, `schema_type`,
                    `schema_custom_json`, `is_indexed`, `is_followed`, `sitemap_priority`, `sitemap_changefreq`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $body['page_key'],
                    $body['page_name'] ?? $body['page_key'],
                    $body['meta_title'] ?? '',
                    $body['meta_description'] ?? '',
                    $body['keywords'] ?? '',
                    $body['canonical_url'] ?? '',
                    $body['og_title'] ?? '',
                    $body['og_description'] ?? '',
                    $body['og_image'] ?? '',
                    $body['twitter_title'] ?? '',
                    $body['twitter_description'] ?? '',
                    $body['twitter_image'] ?? '',
                    $body['schema_type'] ?? 'WebPage',
                    $body['schema_custom_json'] ?? null,
                    isset($body['is_indexed']) ? (int)$body['is_indexed'] : 1,
                    isset($body['is_followed']) ? (int)$body['is_followed'] : 1,
                    $body['sitemap_priority'] ?? '0.8',
                    $body['sitemap_changefreq'] ?? 'weekly'
                ]);
            }
            jsonOk(['message' => 'Page SEO configuration saved successfully']);
        } catch (Exception $e) {
            jsonErr('Failed to save page SEO: ' . $e->getMessage());
        }
        break;

    case 'delete_seo_page':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        if (empty($body['page_key'])) { jsonErr('Page key is required'); }
        try {
            $stmt = $pdo->prepare("DELETE FROM `seo_pages` WHERE `page_key` = ?");
            $stmt->execute([$body['page_key']]);
            jsonOk(['message' => 'Page SEO configuration deleted']);
        } catch (Exception $e) {
            jsonErr('Failed to delete page SEO: ' . $e->getMessage());
        }
        break;

    case 'get_seo_suite':
        try {
            $suite = getSeoSuiteFull($pdo);
            $redirects = $pdo->query("SELECT * FROM `seo_redirects` ORDER BY `id` DESC")->fetchAll() ?: [];
            $latestAudit = $pdo->query("SELECT * FROM `seo_audit` ORDER BY `id` DESC LIMIT 1")->fetch() ?: null;
            $imgSettings = $pdo->query("SELECT * FROM `seo_image_settings` WHERE `id` = 1")->fetch() ?: [];
            $suite['redirects'] = $redirects;
            $suite['audit'] = $latestAudit;
            $suite['image_settings'] = $imgSettings;
            jsonOk($suite);
        } catch (Exception $e) {
            jsonErr('Failed to load SEO suite: ' . $e->getMessage());
        }
        break;

    case 'save_seo_social':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $stmt = $pdo->prepare("UPDATE `seo_social` SET 
                `og_site_name` = ?, `og_type` = ?, `og_locale` = ?, `og_default_image` = ?,
                `twitter_site` = ?, `twitter_creator` = ?, `twitter_card_type` = ?, `twitter_default_image` = ?
                WHERE `id` = 1");
            $stmt->execute([
                $body['og_site_name'] ?? 'Site And Marketing Technologies',
                $body['og_type'] ?? 'website',
                $body['og_locale'] ?? 'en_US',
                $body['og_default_image'] ?? '',
                $body['twitter_site'] ?? '@siteandmarketing',
                $body['twitter_creator'] ?? '@siteandmarketing',
                $body['twitter_card_type'] ?? 'summary_large_image',
                $body['twitter_default_image'] ?? ''
            ]);
            jsonOk(['message' => 'OpenGraph & Twitter settings saved']);
        } catch (Exception $e) {
            jsonErr('Failed to save social SEO: ' . $e->getMessage());
        }
        break;

    case 'save_seo_verification':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $stmt = $pdo->prepare("UPDATE `seo_verification` SET 
                `google_verification` = ?, `bing_verification` = ?, `yandex_verification` = ?,
                `pinterest_verification` = ?, `baidu_verification` = ?
                WHERE `id` = 1");
            $stmt->execute([
                $body['google_verification'] ?? '',
                $body['bing_verification'] ?? '',
                $body['yandex_verification'] ?? '',
                $body['pinterest_verification'] ?? '',
                $body['baidu_verification'] ?? ''
            ]);
            jsonOk(['message' => 'Verification tags updated successfully']);
        } catch (Exception $e) {
            jsonErr('Failed to save verification tags: ' . $e->getMessage());
        }
        break;

    case 'save_seo_image':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $stmt = $pdo->prepare("UPDATE `seo_image_settings` SET 
                `default_alt_pattern` = ?, `lazy_loading_enabled` = ?, `webp_support` = ?
                WHERE `id` = 1");
            $stmt->execute([
                $body['default_alt_pattern'] ?? '{title} - Site And Marketing Technologies',
                isset($body['lazy_loading_enabled']) ? (int)$body['lazy_loading_enabled'] : 1,
                isset($body['webp_support']) ? (int)$body['webp_support'] : 1
            ]);
            jsonOk(['message' => 'Image SEO settings saved successfully']);
        } catch (Exception $e) {
            jsonErr('Failed to save Image SEO: ' . $e->getMessage());
        }
        break;

    case 'duplicate_seo_page':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $sourceKey = $body['source_page_key'] ?? '';
        $targetKey = $body['target_page_key'] ?? '';
        if (empty($sourceKey) || empty($targetKey)) { jsonErr('Source and Target page keys are required'); }
        try {
            $srcStmt = $pdo->prepare("SELECT * FROM `seo_pages` WHERE `page_key` = ?");
            $srcStmt->execute([$sourceKey]);
            $src = $srcStmt->fetch();
            if (!$src) { jsonErr('Source page SEO not found'); }

            $stmt = $pdo->prepare("INSERT INTO `seo_pages` 
                (`page_key`, `page_name`, `meta_title`, `meta_description`, `keywords`,
                `canonical_url`, `og_title`, `og_description`, `og_image`,
                `twitter_title`, `twitter_description`, `twitter_image`, `schema_type`,
                `schema_custom_json`, `is_indexed`, `is_followed`, `sitemap_priority`, `sitemap_changefreq`)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                `meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`),
                `keywords` = VALUES(`keywords`), `og_title` = VALUES(`og_title`), `og_description` = VALUES(`og_description`);");
            $stmt->execute([
                $targetKey,
                $body['target_page_name'] ?? $targetKey,
                $src['meta_title'], $src['meta_description'], $src['keywords'],
                $src['canonical_url'], $src['og_title'], $src['og_description'], $src['og_image'],
                $src['twitter_title'], $src['twitter_description'], $src['twitter_image'],
                $src['schema_type'], $src['schema_custom_json'], $src['is_indexed'], $src['is_followed'],
                $src['sitemap_priority'], $src['sitemap_changefreq']
            ]);
            jsonOk(['message' => "Duplicated SEO settings to '{$targetKey}' successfully"]);
        } catch (Exception $e) {
            jsonErr('Failed to duplicate page SEO: ' . $e->getMessage());
        }
        break;

    case 'save_seo_analytics':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        try {
            $stmt = $pdo->prepare("UPDATE `seo_analytics` SET 
                `ga_tracking_id` = ?, `gtm_container_id` = ?, `fb_pixel_id` = ?,
                `clarity_id` = ?, `hotjar_id` = ?, `custom_head_script` = ?, `custom_body_script` = ?
                WHERE `id` = 1");
            $stmt->execute([
                $body['ga_tracking_id'] ?? '',
                $body['gtm_container_id'] ?? '',
                $body['fb_pixel_id'] ?? '',
                $body['clarity_id'] ?? '',
                $body['hotjar_id'] ?? '',
                $body['custom_head_script'] ?? '',
                $body['custom_body_script'] ?? ''
            ]);
            jsonOk(['message' => 'Analytics tracking scripts updated']);
        } catch (Exception $e) {
            jsonErr('Failed to save analytics: ' . $e->getMessage());
        }
        break;

    case 'save_seo_redirect':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        if (empty($body['old_url']) || empty($body['new_url'])) { jsonErr('Old URL and New URL are required'); }
        try {
            if (!empty($body['id'])) {
                $stmt = $pdo->prepare("UPDATE `seo_redirects` SET `old_url` = ?, `new_url` = ?, `redirect_type` = ?, `is_enabled` = ? WHERE `id` = ?");
                $stmt->execute([
                    $body['old_url'],
                    $body['new_url'],
                    $body['redirect_type'] ?? '301',
                    isset($body['is_enabled']) ? (int)$body['is_enabled'] : 1,
                    $body['id']
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO `seo_redirects` (`old_url`, `new_url`, `redirect_type`, `is_enabled`) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $body['old_url'],
                    $body['new_url'],
                    $body['redirect_type'] ?? '301',
                    isset($body['is_enabled']) ? (int)$body['is_enabled'] : 1
                ]);
            }
            jsonOk(['message' => 'URL redirect rule saved successfully']);
        } catch (Exception $e) {
            jsonErr('Failed to save redirect rule: ' . $e->getMessage());
        }
        break;

    case 'delete_seo_redirect':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        if (empty($body['id'])) { jsonErr('Redirect ID is required'); }
        try {
            $stmt = $pdo->prepare("DELETE FROM `seo_redirects` WHERE `id` = ?");
            $stmt->execute([$body['id']]);
            jsonOk(['message' => 'Redirect rule deleted']);
        } catch (Exception $e) {
            jsonErr('Failed to delete redirect rule: ' . $e->getMessage());
        }
        break;

    case 'save_robots_txt':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $content = $body['robots_content'] ?? '';
        try {
            $stmt = $pdo->prepare("UPDATE `robots_settings` SET `robots_content` = ?, `sitemap_url` = ? WHERE `id` = 1");
            $stmt->execute([$content, $body['sitemap_url'] ?? 'https://siteandmarketing.com/sitemap.php']);
            
            // Sync to physical robots.txt file in project root
            $robotsFile = __DIR__ . '/../robots.txt';
            file_put_contents($robotsFile, $content);
            jsonOk(['message' => 'Robots.txt updated and synchronized']);
        } catch (Exception $e) {
            jsonErr('Failed to save robots.txt: ' . $e->getMessage());
        }
        break;

    case 'run_seo_audit':
        try {
            $pages = $pdo->query("SELECT * FROM `seo_pages`")->fetchAll() ?: [];
            $global = $pdo->query("SELECT * FROM `seo_global` WHERE `id` = 1")->fetch() ?: [];
            
            $totalChecks = 0;
            $passedChecks = 0;
            $recommendations = [];
            
            // Global Checks
            $totalChecks++;
            if (!empty($global['website_title']) && strlen($global['website_title']) >= 10 && strlen($global['website_title']) <= 60) {
                $passedChecks++;
            } else {
                $recommendations[] = ['type' => 'warning', 'msg' => 'Global website title should be between 10 and 60 characters for optimal SERP visibility.'];
            }

            $totalChecks++;
            if (!empty($global['meta_description']) && strlen($global['meta_description']) >= 50 && strlen($global['meta_description']) <= 160) {
                $passedChecks++;
            } else {
                $recommendations[] = ['type' => 'warning', 'msg' => 'Global meta description should ideally be 50 to 160 characters long.'];
            }

            $totalChecks++;
            if (!empty($global['favicon_url'])) {
                $passedChecks++;
            } else {
                $recommendations[] = ['type' => 'info', 'msg' => 'Favicon icon URL is missing in Global SEO settings.'];
            }

            // Page Level Checks
            foreach ($pages as $p) {
                $pName = $p['page_name'] ?? $p['page_key'];
                
                // Title length
                $totalChecks++;
                $tLen = mb_strlen($p['meta_title'] ?? '');
                if ($tLen >= 15 && $tLen <= 65) {
                    $passedChecks++;
                } else {
                    $recommendations[] = ['type' => 'danger', 'msg' => "Page '{$pName}': Meta title length ({$tLen} chars) should be between 15 and 65 characters."];
                }

                // Description length
                $totalChecks++;
                $dLen = mb_strlen($p['meta_description'] ?? '');
                if ($dLen >= 60 && $dLen <= 160) {
                    $passedChecks++;
                } else {
                    $recommendations[] = ['type' => 'warning', 'msg' => "Page '{$pName}': Meta description length ({$dLen} chars) should be between 60 and 160 characters."];
                }

                // Canonical URL check
                $totalChecks++;
                if (!empty($p['canonical_url'])) {
                    $passedChecks++;
                } else {
                    $recommendations[] = ['type' => 'info', 'msg' => "Page '{$pName}': Explicit canonical URL not set (using self-referencing fallback)."];
                }

                // OpenGraph Image check
                $totalChecks++;
                if (!empty($p['og_image'])) {
                    $passedChecks++;
                } else {
                    $recommendations[] = ['type' => 'info', 'msg' => "Page '{$pName}': Social share OpenGraph image is not explicitly set."];
                }
            }

            $score = $totalChecks > 0 ? round(($passedChecks / $totalChecks) * 100) : 85;

            $stmt = $pdo->prepare("INSERT INTO `seo_audit` (`score`, `audit_data_json`, `recommendations_json`) VALUES (?, ?, ?)");
            $stmt->execute([
                $score,
                json_encode(['total_checks' => $totalChecks, 'passed_checks' => $passedChecks]),
                json_encode($recommendations)
            ]);

            jsonOk([
                'score' => $score,
                'total_checks' => $totalChecks,
                'passed_checks' => $passedChecks,
                'recommendations' => $recommendations
            ]);
        } catch (Exception $e) {
            jsonErr('Failed to run SEO audit: ' . $e->getMessage());
        }
        break;

    case 'export_seo_data':
        try {
            $export = getSeoSuiteFull($pdo);
            $export['redirects'] = $pdo->query("SELECT * FROM `seo_redirects`")->fetchAll() ?: [];
            header('Content-Disposition: attachment; filename="seo_backup_' . date('Y-m-d') . '.json"');
            echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        } catch (Exception $e) {
            jsonErr('Failed to export SEO data: ' . $e->getMessage());
        }
        break;

    case 'import_seo_data':
        if ($method !== 'POST') { jsonErr('Method not allowed'); }
        $body = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        if (empty($body['global'])) { jsonErr('Invalid SEO backup JSON format'); }
        try {
            // Import Global
            if (!empty($body['global'])) {
                $g = $body['global'];
                $stmt = $pdo->prepare("UPDATE `seo_global` SET `website_name` = ?, `website_title` = ?, `meta_title` = ?, `meta_description` = ?, `website_url` = ?, `canonical_url` = ?, `default_keywords` = ?, `author` = ? WHERE `id` = 1");
                $stmt->execute([$g['website_name'] ?? '', $g['website_title'] ?? '', $g['meta_title'] ?? '', $g['meta_description'] ?? '', $g['website_url'] ?? '', $g['canonical_url'] ?? '', $g['default_keywords'] ?? '', $g['author'] ?? '']);
            }
            jsonOk(['message' => 'SEO backup data successfully imported']);
        } catch (Exception $e) {
            jsonErr('Failed to import SEO data: ' . $e->getMessage());
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid action parameter']);
        break;
}
