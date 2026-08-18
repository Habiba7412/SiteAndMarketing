<?php
/**
 * Dynamic XML Sitemap Generator - Site And Marketing Technologies
 * Generates standards-compliant XML sitemap for search engine indexing.
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=utf-8');

$suite = getSeoSuiteFull($pdo);
$pages = $suite['pages'];

$baseUrl = rtrim($suite['global']['website_url'] ?? 'https://siteandmarketing.com', '/');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// 1. Static & Custom Pages
foreach ($pages as $p) {
    if (!$p['is_indexed']) continue;
    
    $loc = !empty($p['canonical_url']) ? $p['canonical_url'] : ($baseUrl . '/' . ltrim($p['page_key'], '/'));
    $priority = !empty($p['sitemap_priority']) ? $p['sitemap_priority'] : '0.8';
    $changefreq = !empty($p['sitemap_changefreq']) ? $p['sitemap_changefreq'] : 'weekly';
    $lastmod = !empty($p['updated_at']) ? date('Y-m-d', strtotime($p['updated_at'])) : date('Y-m-d');
    
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    echo "    <lastmod>{$lastmod}</lastmod>\n";
    echo "    <changefreq>{$changefreq}</changefreq>\n";
    echo "    <priority>{$priority}</priority>\n";
    echo "  </url>\n";
}

// 2. Published Blog Posts
try {
    $blogs = $pdo->query("SELECT `slug`, `created_at` FROM `blogs` WHERE `status` = 'published' ORDER BY `id` DESC")->fetchAll();
    foreach ($blogs as $b) {
        $loc = $baseUrl . '/blog/' . urlencode($b['slug']);
        $lastmod = !empty($b['created_at']) ? date('Y-m-d', strtotime($b['created_at'])) : date('Y-m-d');
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "    <lastmod>{$lastmod}</lastmod>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>\n";
    }
} catch (Exception $e) {}

// 3. Published Services
try {
    $services = $pdo->query("SELECT `slug`, `created_at` FROM `services` WHERE `status` = 'published' ORDER BY `id` DESC")->fetchAll();
    foreach ($services as $s) {
        $slug = $s['slug'] ?? '';
        $loc = $baseUrl . '/services.php#' . urlencode($slug);
        $lastmod = !empty($s['created_at']) ? date('Y-m-d', strtotime($s['created_at'])) : date('Y-m-d');
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
        echo "    <lastmod>{$lastmod}</lastmod>\n";
        echo "    <changefreq>monthly</changefreq>\n";
        echo "    <priority>0.8</priority>\n";
        echo "  </url>\n";
    }
} catch (Exception $e) {}

echo '</urlset>';
