<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Auto Environment Detection ───────────────────────────────────────────
// Detects whether running on local XAMPP or the live hosting server
$_httpHost = strtolower(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
$_serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
$isLocal = in_array($_serverName, ['localhost', '127.0.0.1', '::1'])
           || (strpos($_httpHost, 'localhost') !== false);

// Load secure environment config if it exists
$configFile = __DIR__ . '/../config.env.php';
if (file_exists($configFile)) {
    $env = require $configFile;
    define('DB_HOST', $env['DB_HOST'] ?? 'localhost');
    define('DB_USER', $env['DB_USER'] ?? 'root');
    define('DB_PASS', $env['DB_PASS'] ?? '');
    define('DB_NAME', $env['DB_NAME'] ?? 'myitcomapny');
    ini_set('display_errors', $env['DISPLAY_ERRORS'] ? 1 : 0);
} else {
    // ── LIVE HOSTING FALLBACK ─────────────────────────────────────────────
    // In case config.env.php is missing, fallback to standard cPanel defaults
    // However, creating config.env.php is highly recommended.
    define('DB_HOST', 'localhost');
    define('DB_USER', 'marketing');
    define('DB_PASS', 'marketing');
    define('DB_NAME', 'marketingandsite');
    ini_set('display_errors', 0); // Hide errors on live site
}


try {
    // Connect to the detected database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // --- DATABASE SCHEMA SETUP ---
    
    // 1. Users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `name` VARCHAR(100) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 2. Site settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
        `setting_key` VARCHAR(55) PRIMARY KEY,
        `setting_value` TEXT NULL
    ) ENGINE=InnoDB;");

    // 3. SEO settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `page_name` VARCHAR(50) NOT NULL UNIQUE,
        `title` VARCHAR(255) NOT NULL,
        `meta_description` TEXT NULL,
        `meta_keywords` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 4. Contact submissions table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_submissions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `subject` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `is_read` TINYINT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 5. Services table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `services` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `icon` VARCHAR(100) NOT NULL,
        `description` TEXT NOT NULL,
        `long_description` TEXT NULL,
        `status` ENUM('draft', 'published') DEFAULT 'published',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 6. Projects table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `projects` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `category` VARCHAR(100) NOT NULL,
        `image_url` VARCHAR(255) NOT NULL,
        `client` VARCHAR(100) NULL,
        `project_date` DATE NULL,
        `link` VARCHAR(255) NULL,
        `description` TEXT NOT NULL,
        `status` ENUM('draft', 'published') DEFAULT 'published',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 7. Blogs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `blogs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) NOT NULL UNIQUE,
        `category` VARCHAR(50) NOT NULL,
        `image_url` VARCHAR(255) NOT NULL,
        `excerpt` TEXT NOT NULL,
        `content` LONGTEXT NOT NULL,
        `author` VARCHAR(100) NOT NULL,
        `status` ENUM('draft', 'published') DEFAULT 'published',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 8. Team members table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `team_members` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `designation` VARCHAR(100) NOT NULL,
        `bio` TEXT NULL,
        `image_url` VARCHAR(255) NULL,
        `linkedin_url` VARCHAR(255) NULL,
        `twitter_url` VARCHAR(255) NULL,
        `github_url` VARCHAR(255) NULL,
        `sort_order` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 9. Testimonials table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `client_name` VARCHAR(100) NOT NULL,
        `company` VARCHAR(100) NULL,
        `image_url` VARCHAR(255) NULL,
        `rating` TINYINT DEFAULT 5,
        `review` TEXT NOT NULL,
        `status` ENUM('draft', 'published') DEFAULT 'published',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // 10. FAQs table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `faqs` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `question` VARCHAR(255) NOT NULL,
        `answer` TEXT NOT NULL,
        `display_order` INT DEFAULT 0,
        `status` ENUM('draft', 'published') DEFAULT 'published',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // --- SCHEMA MIGRATIONS (safe ALTER TABLE for missing columns) ---
    // Run via information_schema checks so existing databases are patched non-destructively

    $dbName = DB_NAME;

    // services: add slug, image_url if missing
    $cols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='services'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('slug', $cols))      $pdo->exec("ALTER TABLE `services` ADD `slug` VARCHAR(255) NULL AFTER `title`");
    if (!in_array('image_url', $cols)) $pdo->exec("ALTER TABLE `services` ADD `image_url` VARCHAR(255) NULL");

    // projects: add slug, year, tags if missing
    $cols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='projects'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('slug', $cols))  $pdo->exec("ALTER TABLE `projects` ADD `slug` VARCHAR(255) NULL AFTER `title`");
    if (!in_array('year', $cols))  $pdo->exec("ALTER TABLE `projects` ADD `year` VARCHAR(10) NULL");
    if (!in_array('tags', $cols))  $pdo->exec("ALTER TABLE `projects` ADD `tags` VARCHAR(255) NULL");

    // blogs: add seo_title, meta_description if missing
    $cols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='blogs'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('seo_title', $cols))        $pdo->exec("ALTER TABLE `blogs` ADD `seo_title` VARCHAR(255) NULL");
    if (!in_array('meta_description', $cols)) $pdo->exec("ALTER TABLE `blogs` ADD `meta_description` TEXT NULL");

    // team_members: rename display_order -> sort_order if needed, add status if missing
    $cols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='team_members'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('sort_order', $cols) && in_array('display_order', $cols))
        $pdo->exec("ALTER TABLE `team_members` CHANGE `display_order` `sort_order` INT DEFAULT 0");
    if (!in_array('sort_order', $cols) && !in_array('display_order', $cols))
        $pdo->exec("ALTER TABLE `team_members` ADD `sort_order` INT DEFAULT 0");
    if (!in_array('status', $cols))
        $pdo->exec("ALTER TABLE `team_members` ADD `status` ENUM('draft','published') DEFAULT 'published'");

    // --- SEO MANAGEMENT SUITE TABLES ---
    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_global` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `website_name` VARCHAR(255) NOT NULL DEFAULT 'DigiRare Technologies',
        `website_title` VARCHAR(255) NOT NULL DEFAULT 'DigiRare | NextGen IT & Software Solutions',
        `meta_title` VARCHAR(255) NULL,
        `meta_description` TEXT NULL,
        `website_url` VARCHAR(255) NOT NULL DEFAULT 'https://siteandmarketing.com',
        `canonical_url` VARCHAR(255) NULL,
        `default_keywords` TEXT NULL,
        `author` VARCHAR(100) NOT NULL DEFAULT 'DigiRare Solutions',
        `language` VARCHAR(10) NOT NULL DEFAULT 'en',
        `charset` VARCHAR(20) NOT NULL DEFAULT 'UTF-8',
        `theme_color` VARCHAR(20) NOT NULL DEFAULT '#0b1315',
        `favicon_url` VARCHAR(255) NULL,
        `apple_touch_icon` VARCHAR(255) NULL,
        `default_social_image` VARCHAR(255) NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_pages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `page_key` VARCHAR(100) NOT NULL UNIQUE,
        `page_name` VARCHAR(100) NOT NULL,
        `meta_title` VARCHAR(255) NULL,
        `meta_description` TEXT NULL,
        `keywords` TEXT NULL,
        `canonical_url` VARCHAR(255) NULL,
        `og_title` VARCHAR(255) NULL,
        `og_description` TEXT NULL,
        `og_image` VARCHAR(255) NULL,
        `twitter_title` VARCHAR(255) NULL,
        `twitter_description` TEXT NULL,
        `twitter_image` VARCHAR(255) NULL,
        `schema_type` VARCHAR(50) DEFAULT 'WebPage',
        `schema_custom_json` LONGTEXT NULL,
        `is_indexed` TINYINT(1) DEFAULT 1,
        `is_followed` TINYINT(1) DEFAULT 1,
        `sitemap_priority` VARCHAR(10) DEFAULT '0.8',
        `sitemap_changefreq` VARCHAR(20) DEFAULT 'weekly',
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_social` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `og_site_name` VARCHAR(255) DEFAULT 'DigiRare Technologies',
        `og_type` VARCHAR(50) DEFAULT 'website',
        `og_locale` VARCHAR(20) DEFAULT 'en_US',
        `og_default_image` VARCHAR(255) NULL,
        `twitter_site` VARCHAR(100) DEFAULT '@digirare_tech',
        `twitter_creator` VARCHAR(100) DEFAULT '@digirare_tech',
        `twitter_card_type` VARCHAR(50) DEFAULT 'summary_large_image',
        `twitter_default_image` VARCHAR(255) NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_schema` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `schema_type` VARCHAR(50) NOT NULL,
        `schema_name` VARCHAR(100) NOT NULL,
        `is_active` TINYINT(1) DEFAULT 1,
        `json_data` LONGTEXT NOT NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_redirects` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `old_url` VARCHAR(255) NOT NULL UNIQUE,
        `new_url` VARCHAR(255) NOT NULL,
        `redirect_type` VARCHAR(10) NOT NULL DEFAULT '301',
        `is_enabled` TINYINT(1) DEFAULT 1,
        `hit_count` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_verification` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `google_verification` VARCHAR(255) NULL,
        `bing_verification` VARCHAR(255) NULL,
        `yandex_verification` VARCHAR(255) NULL,
        `pinterest_verification` VARCHAR(255) NULL,
        `baidu_verification` VARCHAR(255) NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_analytics` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `ga_tracking_id` VARCHAR(100) NULL,
        `gtm_container_id` VARCHAR(100) NULL,
        `fb_pixel_id` VARCHAR(100) NULL,
        `clarity_id` VARCHAR(100) NULL,
        `hotjar_id` VARCHAR(100) NULL,
        `custom_head_script` TEXT NULL,
        `custom_body_script` TEXT NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_image_settings` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `default_alt_pattern` VARCHAR(255) DEFAULT '{title} - DigiRare Technologies',
        `lazy_loading_enabled` TINYINT(1) DEFAULT 1,
        `webp_support` TINYINT(1) DEFAULT 1,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `seo_audit` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `score` INT DEFAULT 85,
        `audit_data_json` LONGTEXT NULL,
        `recommendations_json` LONGTEXT NULL,
        `last_audit_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `robots_settings` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `robots_content` TEXT NULL,
        `sitemap_url` VARCHAR(255) NULL,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `sitemap_settings` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `auto_generate` TINYINT(1) DEFAULT 1,
        `include_pages` TINYINT(1) DEFAULT 1,
        `include_blogs` TINYINT(1) DEFAULT 1,
        `include_services` TINYINT(1) DEFAULT 1,
        `include_projects` TINYINT(1) DEFAULT 1,
        `default_priority` VARCHAR(10) DEFAULT '0.8',
        `default_changefreq` VARCHAR(20) DEFAULT 'weekly',
        `last_generated` TIMESTAMP NULL
    ) ENGINE=InnoDB;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `email_settings` (
        `id` INT PRIMARY KEY DEFAULT 1,
        `mail_engine` ENUM('smtp', 'mail') DEFAULT 'smtp',
        `smtp_host` VARCHAR(255) DEFAULT 'smtp.gmail.com',
        `smtp_port` INT DEFAULT 587,
        `smtp_encryption` ENUM('tls', 'ssl', 'none') DEFAULT 'tls',
        `smtp_auth` TINYINT(1) DEFAULT 1,
        `smtp_username` VARCHAR(255) DEFAULT '',
        `smtp_password` VARCHAR(255) DEFAULT '',
        `from_name` VARCHAR(255) DEFAULT 'DigiRare Technologies',
        `from_email` VARCHAR(255) DEFAULT 'digiraremarketing@gmail.com',
        `admin_email` VARCHAR(255) DEFAULT 'digiraremarketing@gmail.com',
        `is_enabled` TINYINT(1) DEFAULT 1,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");

    // Ensure columns exist on upgrade
    $verifCols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='seo_verification'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('baidu_verification', $verifCols)) {
        $pdo->exec("ALTER TABLE `seo_verification` ADD `baidu_verification` VARCHAR(255) NULL");
    }

    $socCols = $pdo->query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='seo_social'")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('og_locale', $socCols)) {
        $pdo->exec("ALTER TABLE `seo_social` ADD `og_locale` VARCHAR(20) DEFAULT 'en_US'");
    }

    // Initialize Default Rows if Empty
    $pdo->exec("INSERT IGNORE INTO `seo_global` (`id`, `website_name`, `website_title`, `meta_description`, `default_keywords`, `favicon_url`, `default_social_image`) 
    VALUES (1, 'DigiRare Technologies', 'DigiRare Technologies | Enterprise Software & IT Solutions', 'We build next-generation software platforms, custom cloud backends, cybersecurity defense systems, and enterprise UI/UX applications.', 'software, IT company, cloud development, cybersecurity, web apps', 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=48&q=80', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630');");

    $pdo->exec("INSERT IGNORE INTO `seo_social` (`id`, `og_site_name`, `og_type`, `og_locale`, `og_default_image`, `twitter_site`, `twitter_card_type`) 
    VALUES (1, 'DigiRare Technologies', 'website', 'en_US', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630', '@digirare_tech', 'summary_large_image');");

    $pdo->exec("INSERT IGNORE INTO `seo_verification` (`id`, `google_verification`, `bing_verification`) 
    VALUES (1, '', '');");

    $pdo->exec("INSERT IGNORE INTO `seo_analytics` (`id`, `ga_tracking_id`, `gtm_container_id`) 
    VALUES (1, '', '');");

    $pdo->exec("INSERT IGNORE INTO `seo_image_settings` (`id`, `default_alt_pattern`, `lazy_loading_enabled`, `webp_support`) 
    VALUES (1, '{title} - DigiRare Technologies', 1, 1);");

    $pdo->exec("INSERT IGNORE INTO `robots_settings` (`id`, `robots_content`, `sitemap_url`) 
    VALUES (1, \"User-agent: *\nAllow: /\nDisallow: /cms-dashboard/\nDisallow: /includes/\n\nSitemap: https://siteandmarketing.com/sitemap.php\", 'https://siteandmarketing.com/sitemap.php');");

    $pdo->exec("INSERT IGNORE INTO `sitemap_settings` (`id`, `auto_generate`, `include_pages`, `include_blogs`, `include_services`, `include_projects`) 
    VALUES (1, 1, 1, 1, 1, 1);");

    $pdo->exec("INSERT IGNORE INTO `email_settings` (`id`, `mail_engine`, `smtp_host`, `smtp_port`, `smtp_encryption`, `from_name`, `from_email`, `admin_email`) 
    VALUES (1, 'smtp', 'smtp.gmail.com', 587, 'tls', 'DigiRare Technologies', 'digiraremarketing@gmail.com', 'digiraremarketing@gmail.com');");

    // All 15 Module 2 Pages for Dynamic Page SEO
    $defaultPages = [
        ['index.php', 'Homepage', 'DigiRare Technologies | Enterprise Software & IT Solutions', 'Transforming Ideas Into Powerful Digital Solutions for Modern Businesses. Custom software engineering, cloud architecture & cyber security.', 'software company, cloud backend, custom web development, IT consulting', '1.0', 'daily'],
        ['about.php', 'About Us', 'About Us | DigiRare Technologies', 'Learn about our engineering expertise, company culture, and the dedicated software developers crafting world-class digital solutions.', 'about digirare, IT team, software engineers, company profile', '0.8', 'monthly'],
        ['services.php', 'Services & Solutions', 'Professional IT Services & Cloud Solutions | DigiRare Technologies', 'Comprehensive technical services including custom software engineering, DevOps automation, cybersecurity audits, and UI/UX design.', 'it services, cloud deployment, web development, cybersecurity audit', '0.9', 'weekly'],
        ['solutions.php', 'Enterprise Solutions', 'Enterprise Software & Cloud Architecture Solutions | DigiRare', 'Scalable enterprise software systems, microservices infrastructure, and automated cloud deployments tailored for high growth.', 'enterprise solutions, microservices, cloud architecture, system design', '0.9', 'weekly'],
        ['projects.php', 'Portfolio & Case Studies', 'Portfolio & System Case Studies | DigiRare Technologies', 'Explore our portfolio of enterprise applications, cloud infrastructure deployments, and scalable web solutions delivered worldwide.', 'portfolio, IT case studies, software projects, web applications', '0.9', 'weekly'],
        ['team.php', 'Our Engineering Team', 'Meet Our Software Engineering Experts | DigiRare Technologies', 'Meet the brilliant minds, system architects, UI designers, and security engineers driving innovation at DigiRare.', 'it team, software engineers, devops team, tech leads', '0.7', 'monthly'],
        ['pricing.php', 'Pricing & Project Plans', 'Flexible IT Service Pricing & Project Estimates | DigiRare', 'Transparent project pricing models, dedicated developer retainers, and enterprise software engineering contracts.', 'it pricing, software cost estimate, developer retainer, tech rates', '0.8', 'monthly'],
        ['careers.php', 'Careers & Join Us', 'Join Our Engineering Team | Careers at DigiRare Technologies', 'Explore exciting career opportunities for senior full-stack developers, cloud architects, and UI/UX designers.', 'it careers, software engineer jobs, devops jobs, tech hiring', '0.7', 'monthly'],
        ['blog.php', 'Tech Insights & Blog', 'Tech Insights & Dev Articles | DigiRare Technologies', 'Deep dive into cloud architecture trends, DevOps practices, software design patterns, and enterprise cybersecurity insights.', 'tech blog, programming tutorials, devops guides, software engineering blog', '0.8', 'daily'],
        ['blog-category.php', 'Blog Category Archive', 'Category Topics | DigiRare Insights', 'Browse tech articles grouped by category including software engineering, cloud architecture, and cybersecurity.', 'blog categories, dev topics, tech archives', '0.7', 'weekly'],
        ['single-blog.php', 'Single Blog Article Details', 'Tech Article | DigiRare Insights', 'Detailed technical breakdown and software engineering guide by DigiRare experts.', 'tech post, software guide, IT article', '0.7', 'weekly'],
        ['contact.php', 'Contact Us', 'Contact Us & Free Tech Consultation | DigiRare Technologies', 'Connect with our solutions engineering group. Schedule a free technical consultation and request project cost estimation.', 'contact IT company, technical support, free consultation, software estimate', '0.8', 'monthly'],
        ['privacy.php', 'Privacy Policy', 'Privacy Policy & Data Security | DigiRare Technologies', 'Read how DigiRare Technologies protects client data, respects user privacy, and adheres to global security standards.', 'privacy policy, data protection, gdpr compliance', '0.5', 'yearly'],
        ['terms.php', 'Terms & Conditions', 'Terms & Conditions of Service | DigiRare Technologies', 'Review our master service agreement, terms of service, and software licensing conditions.', 'terms of service, service agreement, terms and conditions', '0.5', 'yearly'],
        ['404.php', '404 Page Not Found', '404 Page Not Found | DigiRare Technologies', 'The requested page could not be located. Return to DigiRare homepage or explore our IT services.', '404 page, error 404, page not found', '0.1', 'never']
    ];

    $insertPage = $pdo->prepare("INSERT IGNORE INTO `seo_pages` (`page_key`, `page_name`, `meta_title`, `meta_description`, `keywords`, `sitemap_priority`, `sitemap_changefreq`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($defaultPages as $p) {
        $insertPage->execute($p);
    }

    // --- SEED DEFAULT DATA ---

    
    // Seed admin user
    $stmt = $pdo->query("SELECT COUNT(*) FROM `users`");
    if ($stmt->fetchColumn() == 0) {
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $email = 'admin@teckko-it.com';
        $name = 'Teckko Administrator';
        
        $insertUser = $pdo->prepare("INSERT INTO `users` (`username`, `password`, `email`, `name`) VALUES (?, ?, ?, ?)");
        $insertUser->execute([$username, $password, $email, $name]);
    }

    // Seed SEO Settings
    $stmt = $pdo->query("SELECT COUNT(*) FROM `seo_settings`");
    if ($stmt->fetchColumn() == 0) {
        $seos = [
            ['index.php', 'NextGen Software Innovators & Digital Soft Solutions | Teckko IT Company', 'We provide state-of-the-art software engineering, cybersecurity, cloud architecture, and modern IT services tailored to accelerate your business growth.', 'software, cloud, cybersecurity, nextgen, developers'],
            ['about.php', 'About Us | Teckko IT Company', 'Learn about our engineering expertise, company history, and meet the development team behind our top-tier IT products and services.', 'it company team, software engineers, about teckko'],
            ['services.php', 'Professional IT Services & Pricing | Teckko IT Company', 'Comprehensive technical solutions including software development, cloud migration, security audit operations, and AI backend integrations.', 'it services, cloud deployment, tech support, custom web dev'],
            ['projects.php', 'IT Case Studies & Portfolio | Teckko IT Company', 'Read about our successful deployments, enterprise systems, and UI/UX design portfolios engineered for scale.', 'case studies, portfolio, system architecture, web design projects'],
            ['blog.php', 'Tech Insights & News | Teckko IT Company', 'Browse tech articles, cloud computing guides, threat analysis reports, and software development methodologies.', 'blog, tech blog, devops articles, programming trends'],
            ['contact.php', 'Contact Us | Teckko IT Company', 'Get in touch with our solutions design group. Request a free consultation and project scope estimation.', 'contact center, it consulting support, email support']
        ];
        
        $insertSeo = $pdo->prepare("INSERT INTO `seo_settings` (`page_name`, `title`, `meta_description`, `meta_keywords`) VALUES (?, ?, ?, ?)");
        foreach ($seos as $seo) {
            $insertSeo->execute($seo);
        }
    }

    // Seed Site Settings
    $stmt = $pdo->query("SELECT COUNT(*) FROM `site_settings`");
    if ($stmt->fetchColumn() == 0) {
        $settings = [
            'company_name' => 'DigiRare Technologies',
            'office_address' => 'Islamabad, Pakistan',
            'phone_number' => '00923199564230',
            'support_phone' => '00923199564230',
            'support_email' => 'digiraremarketing@gmail.com',
            'sales_email' => 'digiraremarketing@gmail.com',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'linkedin_url' => 'https://linkedin.com',
            'github_url' => 'https://github.com',
            'consultation_btn_link' => 'contact.php',
            'hero_sub_heading' => 'NextGen Software Innovators',
            'hero_headline' => 'Transforming Ideas Into Powerful Digital Solutions for Modern Businesses',
            'hero_description' => 'We empower startups, businesses, and enterprises with innovative software development, custom web applications, digital marketing, UI/UX design, branding, and cloud-based solutions. Our expert team creates secure, scalable, and high-performance digital products that help businesses grow faster in today\'s competitive market.',
            'hero_btn_text_1' => 'Get Started Today',
            'hero_btn_url_1' => 'contact.php',
            'hero_btn_text_2' => 'Explore Our Services',
            'hero_btn_url_2' => 'services.php',
            'hero_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80',
            'about_sub_title' => 'Innovate & Grow',
            'about_title' => 'Innovate Soft Solutions to Grow Tech Business',
            'about_description' => 'Our customized software frameworks are designed to resolve real-world operations limitations. We collaborate with you to build scalable platforms that increase productivity, eliminate overheads, and secure user data.',
            'about_image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=700&q=80',
            'about_mission' => 'To deliver cutting-edge digital experiences that empower clients to scale efficiently and securely.',
            'about_vision' => 'To become the leading software engineering and branding partner globally for growing businesses.',
            'about_counters' => '[{"value":"250+","label":"Successful Projects Delivered"},{"value":"120+","label":"Happy Clients Worldwide"},{"value":"99%","label":"Satisfaction Rate"},{"value":"6+","label":"Years Experience"}]',
            'about_features' => '["Custom Software Development","Cloud DevOps Integrations","Enterprise Cybersecurity Operations","Proactive Site Support & Maintenance"]'
        ];
        
        $insertSetting = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES (?, ?)");
        foreach ($settings as $key => $value) {
            $insertSetting->execute([$key, $value]);
        }
    } else {
        // Force sync contact settings for DigiRare Technologies
        $updates = [
            'company_name'   => 'DigiRare Technologies',
            'office_address' => 'Islamabad, Pakistan',
            'phone_number'   => '00923199564230',
            'support_phone'  => '00923199564230',
            'support_email'  => 'digiraremarketing@gmail.com',
            'sales_email'    => 'digiraremarketing@gmail.com'
        ];
        $updateSetting = $pdo->prepare("UPDATE `site_settings` SET `setting_value` = ? WHERE `setting_key` = ?");
        foreach ($updates as $key => $val) {
            $updateSetting->execute([$val, $key]);
        }
        $pdo->exec("UPDATE `email_settings` SET `from_email` = 'digiraremarketing@gmail.com', `admin_email` = 'digiraremarketing@gmail.com' WHERE `from_email` = 'hello@digirare.com' OR `admin_email` = 'hello@digirare.com'");
    }

    // Seed Services
    // If the database has old default seeded services, clear them to make room for the new requested digital services
    $checkOld = $pdo->query("SELECT COUNT(*) FROM `services` WHERE `title` = 'Software Engineering'")->fetchColumn();
    if ($checkOld > 0) {
        $oldTitles = ['Software Engineering', 'Cloud Architecture & DevOps', 'Cybersecurity Operations', 'AI Backend Integrations', 'Enterprise UX Design', 'Database System Audits'];
        $placeholders = implode(',', array_fill(0, count($oldTitles), '?'));
        $deleteOld = $pdo->prepare("DELETE FROM `services` WHERE `title` IN ($placeholders)");
        $deleteOld->execute($oldTitles);
    }

    $checkWordPress = $pdo->query("SELECT COUNT(*) FROM `services` WHERE `title` = 'WordPress Website Development'")->fetchColumn();
    if ($checkWordPress == 0) {
        $services = [
            [
                'WordPress Website Development',
                'fa-brands fa-wordpress',
                'Build fast, secure, SEO-friendly, and fully responsive WordPress websites with custom themes and plugin integration.',
                'Our WordPress solutions deliver enterprise-grade performance, clean code architecture, SEO structure validation, and customized administration workflows. We handle plugin integrations, payment gateways, and custom ACF theme structures.'
            ],
            [
                'Custom Coding & Web Applications',
                'fa-solid fa-laptop-code',
                'Develop custom websites, admin panels, SaaS platforms, CRM systems, dashboards, and web applications using modern technologies.',
                'We engineer lightweight, fully responsive custom systems from scratch. Using Node.js, React, Laravel, or PHP frameworks, we construct fast dashboards, RESTful database integrations, SaaS multi-tenant portals, and secure API gateways.'
            ],
            [
                'E-Commerce Websites',
                'fa-solid fa-cart-shopping',
                'Create professional online stores with secure payment gateways, inventory management, order tracking, and responsive shopping experiences.',
                'Convert visitors into buyers with modern e-commerce systems. We deploy Shopify integrations, custom WooCommerce frameworks, secure Stripe/PayPal gateways, stock tracking interfaces, and automated notification scripts.'
            ],
            [
                'Landing Pages',
                'fa-solid fa-bullhorn',
                'Design high-converting landing pages optimized for lead generation, sales, marketing campaigns, and business growth.',
                'Aesthetically stunning landing pages designed with strict conversion optimization rules, quick loading indexes, clear CTA hooks, visual content flow, and full device responsiveness to maximize advertising ROI.'
            ],
            [
                'Graphic Designing',
                'fa-solid fa-palette',
                'Create professional logos, social media posts, business cards, brochures, banners, flyers, and complete brand visuals.',
                'High-end visual communication that elevates your digital presence. Our visual designers build custom vector designs, marketing banners, corporate decks, and high-impact social media layouts that stand out.'
            ],
            [
                'Canva Designs',
                'fa-solid fa-pen-nib',
                'Design engaging Canva templates for social media, presentations, advertisements, marketing campaigns, and business promotions.',
                'Empower your team with customized, editable Canva design templates. We establish layout grids, image placements, typography templates, and style options for social media posts, slides, and flyers.'
            ],
            [
                'Business Branding',
                'fa-solid fa-award',
                'Build strong brand identity including logo design, color palette, typography, brand guidelines, and complete visual identity.',
                'Establish trust with a cohesive brand footprint. We draft identity guidelines, customize professional primary/secondary logotypes, detail color systems, select premium web font palettes, and package assets.'
            ],
            [
                'Website Maintenance & Support',
                'fa-solid fa-screwdriver-wrench',
                'Provide website updates, backups, bug fixing, security monitoring, performance optimization, and technical support.',
                'Proactive technical support, weekly offsite security backups, database defragmentation, theme code updates, plugin validation audits, and quick troubleshooting response schedules.'
            ]
        ];
        
        $insertService = $pdo->prepare("INSERT INTO `services` (`title`, `icon`, `description`, `long_description`) VALUES (?, ?, ?, ?)");
        foreach ($services as $service) {
            $insertService->execute($service);
        }
    }

    // Seed Projects
    $stmt = $pdo->query("SELECT COUNT(*) FROM `projects`");
    if ($stmt->fetchColumn() == 0) {
        $projects = [
            [
                'Mobile Payment Portal',
                'UI / UX Design',
                'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80',
                'Fintech Client',
                '2026-05-10',
                '#',
                'Fintech payment portal dashboard with real-time graphs and multi-currency secure gateway interfaces.'
            ],
            [
                'ERP Enterprise Architecture',
                'Software Engineering',
                'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                'Global Logistics Inc.',
                '2026-04-15',
                '#',
                'Enterprise logistics & inventory controller system deployed on automated AWS Kubernetes servers.'
            ],
            [
                'Multi-Region Database Sync',
                'Cloud / DevOps',
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                'Transit Corp',
                '2026-06-20',
                '#',
                'Mirroring transaction processing systems across multiple globally distributed clusters with absolute latency reduction.'
            ],
            [
                'E-Commerce User Journey',
                'UI / UX Design',
                'https://images.unsplash.com/photo-1542744094-3a31f103e35f?auto=format&fit=crop&w=800&q=80',
                'Aero Retail',
                '2026-02-18',
                '#',
                'Customer retail layouts and conversion rate testing configurations implemented inside standard templates.'
            ]
        ];
        
        $insertProject = $pdo->prepare("INSERT INTO `projects` (`title`, `category`, `image_url`, `client`, `project_date`, `link`, `description`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($projects as $project) {
            $insertProject->execute($project);
        }
    }

    // Seed Blogs
    $stmt = $pdo->query("SELECT COUNT(*) FROM `blogs`");
    if ($stmt->fetchColumn() == 0) {
        $blogs = [
            [
                'Building Modern Digital Solutions for Tomorrow\'s Businesses',
                'building-modern-digital-solutions-for-tomorrows-businesses',
                'Technology',
                'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'Discover how modern software development, cloud technologies, artificial intelligence, and user-focused design are helping businesses become more efficient, scalable, and competitive in today\'s digital landscape.',
                'In today\'s rapidly evolving digital landscape, businesses face unprecedented challenges and opportunities. Transforming legacy systems into agile, scalable, and high-performance digital products is no longer optional—it is essential for long-term market survival. At DigiRare Technologies, we combine modern software engineering paradigms with artificial intelligence and cloud-native architecture to deliver enterprise solutions that drive measurable business outcomes.',
                'DigiRare Technologies'
            ],
            [
                'The Future of Artificial Intelligence in Business',
                'future-of-artificial-intelligence-in-business',
                'Artificial Intelligence',
                'https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=800&q=80',
                'Artificial Intelligence is transforming industries through automation, predictive analytics, customer support, and intelligent decision-making. Learn how businesses can leverage AI to improve productivity and customer satisfaction.',
                'Artificial Intelligence is rapidly revolutionizing corporate workflows across all sectors. From intelligent chatbots automating 24/7 customer service to machine learning models predicting financial market movements, AI integrations provide a decisive competitive edge. Implementing responsible AI workflows allows teams to streamline routine tasks and focus on core strategic growth.',
                'DigiRare Technologies'
            ],
            [
                'Why Every Business Needs a Professional Website',
                'why-every-business-needs-a-professional-website',
                'Web Development',
                'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
                'A modern website is more than an online presence—it\'s a powerful marketing tool that builds trust, generates leads, improves customer engagement, and increases revenue.',
                'Your website is often the very first touchpoint prospective clients have with your brand. A slow, outdated, or unresponsive site creates instant friction and loses high-value leads to competitors. A custom-built, fast, and mobile-optimized web application acts as your 24/7 sales representative, establishing immediate brand credibility and maximizing conversion rates.',
                'DigiRare Technologies'
            ],
            [
                'Top SEO Strategies to Improve Google Rankings',
                'top-seo-strategies-to-improve-google-rankings',
                'SEO',
                'https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=800&q=80',
                'Explore practical SEO techniques including keyword research, technical optimization, content marketing, internal linking, page speed optimization, and backlink strategies.',
                'Organic search traffic remains the highest ROI channel for digital growth. Achieving page-one Google rankings requires a holistic strategy combining technical site speed optimization, clean HTML structure, semantic schemas, keyword research, and high-quality backlinks. Continuous optimization ensures long-term organic visibility.',
                'DigiRare Technologies'
            ],
            [
                'Cybersecurity Best Practices for Small Businesses',
                'cybersecurity-best-practices-for-small-businesses',
                'Cybersecurity',
                'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80',
                'Protect your organization from cyber threats by implementing strong authentication, secure backups, employee awareness, regular software updates, and network security measures.',
                'Cyberattacks targeting small and medium enterprises have escalated dramatically. Implementing multi-factor authentication (MFA), automated offsite database backups, encrypted SSL connections, and routine vulnerability scanning forms an unshakeable defense perimeter protecting critical customer data and business operations.',
                'DigiRare Technologies'
            ],
            [
                'How Cloud Computing Improves Business Efficiency',
                'how-cloud-computing-improves-business-efficiency',
                'Cloud Computing',
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                'Cloud technologies enable businesses to reduce infrastructure costs, improve collaboration, increase flexibility, and scale operations quickly while maintaining high security.',
                'Migrating on-premise servers to cloud environments like AWS or Google Cloud offers unparalleled scalability and uptime. Automated server scaling adjusts compute capacity to match traffic spikes seamlessly, reducing infrastructure costs by up to 40% while preserving sub-second response times.',
                'DigiRare Technologies'
            ],
            [
                'UI/UX Design Trends That Increase User Engagement',
                'ui-ux-design-trends-that-increase-user-engagement',
                'UI/UX Design',
                'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?auto=format&fit=crop&w=800&q=80',
                'Learn how intuitive navigation, accessibility, responsive layouts, interactive elements, and modern design principles create exceptional user experiences.',
                'Great design is invisible—it guides users effortlessly toward their goal without cognitive friction. Utilizing glassmorphic UI elements, micro-animations, accessible color contrasts, and responsive layouts dramatically improves user retention rates and customer satisfaction scores.',
                'DigiRare Technologies'
            ],
            [
                'Digital Marketing Strategies That Drive More Leads',
                'digital-marketing-strategies-that-drive-more-leads',
                'Digital Marketing',
                'https://images.unsplash.com/photo-1432888622747-4eb9a8efeb07?auto=format&fit=crop&w=800&q=80',
                'Understand how SEO, Google Ads, social media marketing, content marketing, and email campaigns work together to generate qualified leads and business growth.',
                'Multi-channel digital marketing generates consistent pipeline growth. Combining high-intent Google Search Ads with retargeting Meta campaigns and SEO content establishes brand dominance across all touchpoints, turning casual browsers into loyal customers.',
                'DigiRare Technologies'
            ],
            [
                'Why Custom Software is Better Than Ready-Made Solutions',
                'why-custom-software-is-better-than-ready-made-solutions',
                'Software Development',
                'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80',
                'Custom software provides better security, flexibility, scalability, performance, and seamless integration compared to generic off-the-shelf applications.',
                'Off-the-shelf software often forces businesses to compromise operational workflows to fit rigid vendor software limits. Custom software development creates tailored applications engineered strictly around your exact business requirements, ensuring complete data ownership and long-term scalability.',
                'DigiRare Technologies'
            ]
        ];
        
        $insertBlog = $pdo->prepare("INSERT INTO `blogs` (`title`, `slug`, `category`, `image_url`, `excerpt`, `content`, `author`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($blogs as $blog) {
            $insertBlog->execute($blog);
        }
    }

    // Seed Team Members
    $stmt = $pdo->query("SELECT COUNT(*) FROM `team_members`");
    if ($stmt->fetchColumn() == 0) {
        $teams = [
            ['Sarah Connor', 'CTO & Co-Founder', 'Sarah drives the technical direction of the company, focusing on scalable enterprise platforms and secure cloud architectures.', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80', 'https://linkedin.com', 'https://twitter.com', 'https://github.com', 1],
            ['Jack Devlin', 'Lead Software Architect', 'Jack is an expert in distributed networks and cloud application scalability, leading the development of custom SaaS components.', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80', 'https://linkedin.com', 'https://twitter.com', 'https://github.com', 2],
            ['Connor McLeod', 'Lead Cybersecurity Auditor', 'Connor ensures all deployments pass zero-trust network checks and database protection validation routines.', 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80', 'https://linkedin.com', 'https://twitter.com', 'https://github.com', 3]
        ];
        $insertTeam = $pdo->prepare("INSERT INTO `team_members` (`name`, `designation`, `bio`, `image_url`, `linkedin_url`, `twitter_url`, `github_url`, `sort_order`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($teams as $team) {
            $insertTeam->execute($team);
        }
    }

    // Seed Testimonials
    $stmt = $pdo->query("SELECT COUNT(*) FROM `testimonials`");
    if ($stmt->fetchColumn() == 0) {
        $testimonials = [
            ['Alex Rivers', 'CEO, Innovate Corp', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80', 5, 'DigiRare Technologies delivered our payments portal ahead of schedule. Their attention to security protocols and custom dashboards was phenomenal!', 'published'],
            ['Brenda Chen', 'CTO, CloudScale Inc.', 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=150&q=80', 5, 'Their database clustering and cybersecurity migration saved our system from countless latencies. They are our go-to partners for cloud operations!', 'published'],
            ['David Miller', 'Founder, Transit Logistics', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80', 5, 'The custom CRM and order tracking system they designed for our logistics team boosted our delivery rates by 35%. Excellent UI/UX execution!', 'published']
        ];
        $insertTestimonial = $pdo->prepare("INSERT INTO `testimonials` (`client_name`, `company`, `image_url`, `rating`, `review`, `status`) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($testimonials as $t) {
            $insertTestimonial->execute($t);
        }
    }

    // Seed FAQs
    $stmt = $pdo->query("SELECT COUNT(*) FROM `faqs`");
    if ($stmt->fetchColumn() == 0) {
        $faqs = [
            ['What services does DigiRare Technologies provide?', 'We specialize in WordPress customized developments, custom web applications (CRM, dashboard system), secure e-commerce portals, landing page conversions, corporate graphic branding, Canva templates, and monthly website support.', 1, 'published'],
            ['How do we begin a project estimate with your team?', 'Simply fill out the Estimate form on our homepage or click the Free Consultation button to supply your project scopes. Our architects will contact you within 24 hours to schedule a call.', 2, 'published'],
            ['Do you offer hosting and monthly database updates?', 'Yes! We offer proactive support packages including weekly offsite cloud backups, database defragmentation, security updates, and performance checks.', 3, 'published']
        ];
        $insertFaq = $pdo->prepare("INSERT INTO `faqs` (`question`, `answer`, `display_order`, `status`) VALUES (?, ?, ?, ?)");
        foreach ($faqs as $f) {
            $insertFaq->execute($f);
        }
    }

} catch (PDOException $e) {
    die("Database Connection failed: " . $e->getMessage() . "<br><br>Please verify MySQL in XAMPP is running and active on localhost.");
}

// Function to fetch active site settings
function getSiteSettings($pdo) {
    $stmt = $pdo->query("SELECT * FROM `site_settings`");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Function to fetch modern SVG outline icon based on service title
function getServiceIcon($title) {
    $normalizedTitle = strtolower(trim($title));
    
    // WordPress Website Development
    if (strpos($normalizedTitle, 'wordpress') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <path d="M3 9h18M9 21V9M3 15h6" />
        </svg>';
    }
    // Custom Coding & Web Applications
    if (strpos($normalizedTitle, 'custom coding') !== false || strpos($normalizedTitle, 'web application') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
        </svg>';
    }
    // E-Commerce Websites
    if (strpos($normalizedTitle, 'e-commerce') !== false || strpos($normalizedTitle, 'commerce') !== false || strpos($normalizedTitle, 'online store') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>';
    }
    // Landing Pages
    if (strpos($normalizedTitle, 'landing') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>';
    }
    // Graphic Designing
    if (strpos($normalizedTitle, 'graphic') !== false || strpos($normalizedTitle, 'designing') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>';
    }
    // Canva Designs
    if (strpos($normalizedTitle, 'canva') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>';
    }
    // Business Branding
    if (strpos($normalizedTitle, 'branding') !== false || strpos($normalizedTitle, 'brand') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
        </svg>';
    }
    // Website Maintenance & Support
    if (strpos($normalizedTitle, 'maintenance') !== false || strpos($normalizedTitle, 'support') !== false) {
        return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>';
    }

    // Default Fallback icon (Code bracket)
    return '<svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M8 9l3 3-3 3m5 0h3" />
    </svg>';
}
?>
