<?php
/**
 * Dynamic SEO Engine - DigiRare Technologies
 * Handles 301/302 Redirects, Database-Driven Meta Tags, Open Graph,
 * Twitter Cards, Verification, Analytics Scripts & JSON-LD Schemas.
 */

if (!function_exists('checkSeoRedirects')) {

    function checkSeoRedirects($pdo) {
        if (headers_sent() || !$pdo) return;
        
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($requestUri, PHP_URL_PATH);
        
        if (empty($path)) return;
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM `seo_redirects` WHERE (`old_url` = ? OR `old_url` = ?) AND `is_enabled` = 1 LIMIT 1");
            $stmt->execute([$path, ltrim($path, '/')]);
            $redirect = $stmt->fetch();
            
            if ($redirect) {
                // Increment hit counter
                $updateStmt = $pdo->prepare("UPDATE `seo_redirects` SET `hit_count` = `hit_count` + 1 WHERE `id` = ?");
                $updateStmt->execute([$redirect['id']]);
                
                $code = (int)($redirect['redirect_type'] ?? 301);
                $newUrl = $redirect['new_url'];
                
                http_response_code($code === 302 ? 302 : 301);
                header("Location: " . $newUrl, true, $code === 302 ? 302 : 301);
                exit;
            }
        } catch (Exception $e) {
            // Silently ignore redirect query failures
        }
    }

    function getSeoSuiteFull($pdo) {
        static $cachedSuite = null;
        if ($cachedSuite !== null) return $cachedSuite;

        try {
            $global = $pdo->query("SELECT * FROM `seo_global` WHERE `id` = 1")->fetch() ?: [];
            $social = $pdo->query("SELECT * FROM `seo_social` WHERE `id` = 1")->fetch() ?: [];
            $verif = $pdo->query("SELECT * FROM `seo_verification` WHERE `id` = 1")->fetch() ?: [];
            $analytics = $pdo->query("SELECT * FROM `seo_analytics` WHERE `id` = 1")->fetch() ?: [];
            $robots = $pdo->query("SELECT * FROM `robots_settings` WHERE `id` = 1")->fetch() ?: [];
            $sitemap = $pdo->query("SELECT * FROM `sitemap_settings` WHERE `id` = 1")->fetch() ?: [];
            $pages = $pdo->query("SELECT * FROM `seo_pages`")->fetchAll() ?: [];

            $cachedSuite = [
                'global' => $global,
                'social' => $social,
                'verification' => $verif,
                'analytics' => $analytics,
                'robots' => $robots,
                'sitemap' => $sitemap,
                'pages' => $pages
            ];
        } catch (Exception $e) {
            $cachedSuite = [
                'global' => [], 'social' => [], 'verification' => [],
                'analytics' => [], 'robots' => [], 'sitemap' => [], 'pages' => []
            ];
        }

        return $cachedSuite;
    }

    function getSeoDataForPage($pdo, $pageKey = 'index.php', $customData = []) {
        $suite = getSeoSuiteFull($pdo);
        $global = $suite['global'];
        $social = $suite['social'];

        // Find page SEO record
        $pageSeo = null;
        foreach ($suite['pages'] as $p) {
            if ($p['page_key'] === $pageKey || $p['page_key'] === basename($pageKey)) {
                $pageSeo = $p;
                break;
            }
        }

        // Base domain protocol & host
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $currentUrl = $protocol . $host . ($_SERVER['REQUEST_URI'] ?? '');

        // Standard Default Merging Logic
        $websiteName = !empty($global['website_name']) ? $global['website_name'] : 'DigiRare Technologies';
        
        $title = !empty($customData['title']) 
            ? $customData['title'] 
            : (!empty($pageSeo['meta_title']) ? $pageSeo['meta_title'] : (!empty($global['website_title']) ? $global['website_title'] : $websiteName));

        $description = !empty($customData['description']) 
            ? $customData['description'] 
            : (!empty($pageSeo['meta_description']) ? $pageSeo['meta_description'] : ($global['meta_description'] ?? ''));

        $keywords = !empty($customData['keywords']) 
            ? $customData['keywords'] 
            : (!empty($pageSeo['keywords']) ? $pageSeo['keywords'] : ($global['default_keywords'] ?? ''));

        $canonical = !empty($customData['canonical']) 
            ? $customData['canonical'] 
            : (!empty($pageSeo['canonical_url']) ? $pageSeo['canonical_url'] : (!empty($global['canonical_url']) ? $global['canonical_url'] : $currentUrl));

        $ogTitle = !empty($customData['og_title']) 
            ? $customData['og_title'] 
            : (!empty($pageSeo['og_title']) ? $pageSeo['og_title'] : $title);

        $ogDescription = !empty($customData['og_description']) 
            ? $customData['og_description'] 
            : (!empty($pageSeo['og_description']) ? $pageSeo['og_description'] : $description);

        $defaultImg = 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630';
        $ogImage = !empty($customData['og_image']) 
            ? $customData['og_image'] 
            : (!empty($pageSeo['og_image']) ? $pageSeo['og_image'] : (!empty($social['og_default_image']) ? $social['og_default_image'] : ($global['default_social_image'] ?? $defaultImg)));

        $twitterTitle = !empty($customData['twitter_title']) 
            ? $customData['twitter_title'] 
            : (!empty($pageSeo['twitter_title']) ? $pageSeo['twitter_title'] : $ogTitle);

        $twitterDescription = !empty($customData['twitter_description']) 
            ? $customData['twitter_description'] 
            : (!empty($pageSeo['twitter_description']) ? $pageSeo['twitter_description'] : $ogDescription);

        $twitterImage = !empty($customData['twitter_image']) 
            ? $customData['twitter_image'] 
            : (!empty($pageSeo['twitter_image']) ? $pageSeo['twitter_image'] : (!empty($social['twitter_default_image']) ? $social['twitter_default_image'] : $ogImage));

        $isIndexed = isset($customData['is_indexed']) ? $customData['is_indexed'] : (isset($pageSeo['is_indexed']) ? (bool)$pageSeo['is_indexed'] : true);
        $isFollowed = isset($customData['is_followed']) ? $customData['is_followed'] : (isset($pageSeo['is_followed']) ? (bool)$pageSeo['is_followed'] : true);

        $robotsMeta = ($isIndexed ? 'index' : 'noindex') . ', ' . ($isFollowed ? 'follow' : 'nofollow');

        return [
            'website_name'     => $websiteName,
            'title'            => $title,
            'description'      => $description,
            'keywords'         => $keywords,
            'canonical'        => $canonical,
            'robots'           => $robotsMeta,
            'author'           => $global['author'] ?? 'DigiRare Solutions',
            'language'         => $global['language'] ?? 'en',
            'charset'          => $global['charset'] ?? 'UTF-8',
            'theme_color'      => $global['theme_color'] ?? '#0b1315',
            'favicon_url'      => $global['favicon_url'] ?? '',
            'apple_touch_icon' => $global['apple_touch_icon'] ?? '',
            
            // OpenGraph
            'og_title'         => $ogTitle,
            'og_description'   => $ogDescription,
            'og_image'         => $ogImage,
            'og_url'           => $currentUrl,
            'og_type'          => $customData['og_type'] ?? ($social['og_type'] ?? 'website'),
            'og_site_name'     => $social['og_site_name'] ?? $websiteName,

            // Twitter Cards
            'twitter_card'     => $social['twitter_card_type'] ?? 'summary_large_image',
            'twitter_site'     => $social['twitter_site'] ?? '@digirare_tech',
            'twitter_creator'  => $social['twitter_creator'] ?? '@digirare_tech',
            'twitter_title'    => $twitterTitle,
            'twitter_description' => $twitterDescription,
            'twitter_image'    => $twitterImage,

            // Verification & Analytics
            'verification'     => $suite['verification'],
            'analytics'        => $suite['analytics'],
            'custom_schema'    => $pageSeo['schema_custom_json'] ?? ($customData['schema_json'] ?? null),
            'schema_type'      => $pageSeo['schema_type'] ?? 'WebPage'
        ];
    }

    function renderSeoHead($pdo, $pageKey = 'index.php', $customData = []) {
        $seo = getSeoDataForPage($pdo, $pageKey, $customData);

        echo "<!-- ===== DYNAMIC SEO MANAGEMENT SUITE ===== -->\n";
        echo "<title>" . htmlspecialchars($seo['title']) . "</title>\n";
        if (!empty($seo['description'])) {
            echo "<meta name=\"description\" content=\"" . htmlspecialchars($seo['description']) . "\">\n";
        }
        if (!empty($seo['keywords'])) {
            echo "<meta name=\"keywords\" content=\"" . htmlspecialchars($seo['keywords']) . "\">\n";
        }
        echo "<meta name=\"robots\" content=\"" . htmlspecialchars($seo['robots']) . "\">\n";
        echo "<meta name=\"author\" content=\"" . htmlspecialchars($seo['author']) . "\">\n";
        echo "<meta name=\"theme-color\" content=\"" . htmlspecialchars($seo['theme_color']) . "\">\n";
        echo "<link rel=\"canonical\" href=\"" . htmlspecialchars($seo['canonical']) . "\">\n";

        if (!empty($seo['favicon_url'])) {
            echo "<link rel=\"icon\" type=\"image/x-icon\" href=\"" . htmlspecialchars($seo['favicon_url']) . "\">\n";
        }
        if (!empty($seo['apple_touch_icon'])) {
            echo "<link rel=\"apple-touch-icon\" href=\"" . htmlspecialchars($seo['apple_touch_icon']) . "\">\n";
        }

        // OpenGraph Tags
        echo "<!-- Open Graph / Facebook -->\n";
        echo "<meta property=\"og:type\" content=\"" . htmlspecialchars($seo['og_type']) . "\">\n";
        echo "<meta property=\"og:site_name\" content=\"" . htmlspecialchars($seo['og_site_name']) . "\">\n";
        echo "<meta property=\"og:locale\" content=\"" . htmlspecialchars($seo['og_locale'] ?? 'en_US') . "\">\n";
        echo "<meta property=\"og:title\" content=\"" . htmlspecialchars($seo['og_title']) . "\">\n";
        echo "<meta property=\"og:description\" content=\"" . htmlspecialchars($seo['og_description']) . "\">\n";
        echo "<meta property=\"og:image\" content=\"" . htmlspecialchars($seo['og_image']) . "\">\n";
        echo "<meta property=\"og:url\" content=\"" . htmlspecialchars($seo['og_url']) . "\">\n";

        // Twitter Cards Tags
        echo "<!-- Twitter Cards -->\n";
        echo "<meta name=\"twitter:card\" content=\"" . htmlspecialchars($seo['twitter_card']) . "\">\n";
        echo "<meta name=\"twitter:site\" content=\"" . htmlspecialchars($seo['twitter_site']) . "\">\n";
        echo "<meta name=\"twitter:creator\" content=\"" . htmlspecialchars($seo['twitter_creator']) . "\">\n";
        echo "<meta name=\"twitter:title\" content=\"" . htmlspecialchars($seo['twitter_title']) . "\">\n";
        echo "<meta name=\"twitter:description\" content=\"" . htmlspecialchars($seo['twitter_description']) . "\">\n";
        echo "<meta name=\"twitter:image\" content=\"" . htmlspecialchars($seo['twitter_image']) . "\">\n";

        // Search Engine Verification Meta Tags
        $verif = $seo['verification'];
        if (!empty($verif['google_verification'])) {
            echo "<meta name=\"google-site-verification\" content=\"" . htmlspecialchars($verif['google_verification']) . "\">\n";
        }
        if (!empty($verif['bing_verification'])) {
            echo "<meta name=\"msvalidate.01\" content=\"" . htmlspecialchars($verif['bing_verification']) . "\">\n";
        }
        if (!empty($verif['yandex_verification'])) {
            echo "<meta name=\"yandex-verification\" content=\"" . htmlspecialchars($verif['yandex_verification']) . "\">\n";
        }
        if (!empty($verif['pinterest_verification'])) {
            echo "<meta name=\"p:domain_verify\" content=\"" . htmlspecialchars($verif['pinterest_verification']) . "\">\n";
        }
        if (!empty($verif['baidu_verification'])) {
            echo "<meta name=\"baidu-site-verification\" content=\"" . htmlspecialchars($verif['baidu_verification']) . "\">\n";
        }

        // Schema JSON-LD Generation (Supports 10 Schema Types)
        echo "<!-- Structured Data / JSON-LD Schema (Module 10) -->\n";
        $schemaArray = [];
        if (!empty($seo['custom_schema'])) {
            $decoded = json_decode($seo['custom_schema'], true);
            if ($decoded) $schemaArray = $decoded;
        }

        if (empty($schemaArray)) {
            $sType = $seo['schema_type'] ?? 'Organization';
            switch ($sType) {
                case 'Article':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "Article",
                        "headline" => $seo['title'],
                        "description" => $seo['description'],
                        "image" => $seo['og_image'],
                        "author" => [
                            "@type" => "Organization",
                            "name" => $seo['website_name']
                        ],
                        "publisher" => [
                            "@type" => "Organization",
                            "name" => $seo['website_name'],
                            "logo" => [
                                "@type" => "ImageObject",
                                "url" => $seo['favicon_url'] ?: $seo['og_image']
                            ]
                        ]
                    ];
                    break;
                case 'Service':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "Service",
                        "serviceType" => $seo['title'],
                        "provider" => [
                            "@type" => "Organization",
                            "name" => $seo['website_name']
                        ],
                        "description" => $seo['description']
                    ];
                    break;
                case 'LocalBusiness':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "LocalBusiness",
                        "name" => $seo['website_name'],
                        "image" => $seo['og_image'],
                        "url" => $seo['canonical'],
                        "description" => $seo['description']
                    ];
                    break;
                case 'SoftwareApplication':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "SoftwareApplication",
                        "name" => $seo['website_name'],
                        "operatingSystem" => "All",
                        "applicationCategory" => "BusinessApplication",
                        "description" => $seo['description']
                    ];
                    break;
                case 'FAQPage':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "FAQPage",
                        "mainEntity" => []
                    ];
                    break;
                case 'BreadcrumbList':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "BreadcrumbList",
                        "itemListElement" => [
                            ["@type" => "ListItem", "position" => 1, "name" => "Home", "item" => $seo['canonical']]
                        ]
                    ];
                    break;
                case 'Person':
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "Person",
                        "name" => $seo['author'],
                        "jobTitle" => "Software Engineer",
                        "worksFor" => [
                            "@type" => "Organization",
                            "name" => $seo['website_name']
                        ]
                    ];
                    break;
                case 'WebPage':
                case 'Organization':
                case 'WebSite':
                default:
                    $schemaArray = [
                        "@context" => "https://schema.org",
                        "@type" => "Organization",
                        "name" => $seo['website_name'],
                        "url" => $seo['canonical'],
                        "logo" => $seo['og_image'],
                        "description" => $seo['description']
                    ];
                    break;
            }
        }
        echo "<script type=\"application/ld+json\">\n" . json_encode($schemaArray, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "\n</script>\n";

        // Analytics & Tracking Scripts
        $analytics = $seo['analytics'];
        if (!empty($analytics['ga_tracking_id'])) {
            $gaId = htmlspecialchars($analytics['ga_tracking_id']);
            echo "<!-- Google Analytics 4 -->\n";
            echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>\n";
            echo "<script>\n  window.dataLayer = window.dataLayer || [];\n  function gtag(){dataLayer.push(arguments);}\n  gtag('js', new Date());\n  gtag('config', '{$gaId}');\n</script>\n";
        }

        if (!empty($analytics['gtm_container_id'])) {
            $gtmId = htmlspecialchars($analytics['gtm_container_id']);
            echo "<!-- Google Tag Manager -->\n";
            echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtmId}');</script>\n";
        }

        if (!empty($analytics['fb_pixel_id'])) {
            $fbId = htmlspecialchars($analytics['fb_pixel_id']);
            echo "<!-- Facebook Pixel Code -->\n";
            echo "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '{$fbId}');fbq('track', 'PageView');</script>\n";
        }

        if (!empty($analytics['clarity_id'])) {
            $clarityId = htmlspecialchars($analytics['clarity_id']);
            echo "<!-- Microsoft Clarity -->\n";
            echo "<script>(function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};t=l.createElement(r);t.async=1;t.src=\"https://www.clarity.ms/tag/\"+i;y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})(window, document, \"clarity\", \"script\", \"{$clarityId}\");</script>\n";
        }

        if (!empty($analytics['custom_head_script'])) {
            echo "<!-- Custom SEO Head Script -->\n" . $analytics['custom_head_script'] . "\n";
        }
    }
}
