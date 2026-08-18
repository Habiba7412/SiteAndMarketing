<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/seo.php';

// Execute 301/302 URL Redirect Engine if rule matched
checkSeoRedirects($pdo);

$siteSettings = getSiteSettings($pdo);

// Parse JSON website settings from settings key
$websiteSettingsJson = $siteSettings['websiteSettings'] ?? '{}';
$webSettings = json_decode($websiteSettingsJson, true);
$companyName = $webSettings['websiteName'] ?? ($siteSettings['company_name'] ?? 'Site And Marketing Technologies');
$faviconUrl = $webSettings['faviconUrl'] ?? 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=48&q=80';
$siteLogo = !empty($siteSettings['site_logo']) ? $siteSettings['site_logo'] : (!empty($webSettings['logoUrl']) ? $webSettings['logoUrl'] : '');

// Fetch dynamic menu items
$menusJson = $siteSettings['menus'] ?? '[]';
$menusData = json_decode($menusJson, true);
$headerItems = [];
if (is_array($menusData)) {
    foreach ($menusData as $m) {
        if ($m['id'] === 'menu-main' && $m['status'] === 'Active') {
            $headerItems = $m['items'];
            break;
        }
    }
}
if (empty($headerItems)) {
    $headerItems = [
        ['name' => 'Home', 'url' => 'index.php', 'target' => '_self'],
        ['name' => 'About', 'url' => 'about.php', 'target' => '_self'],
        ['name' => 'Services', 'url' => 'services.php', 'target' => '_self'],
        ['name' => 'Projects', 'url' => 'projects.php', 'target' => '_self'],
        ['name' => 'Blog', 'url' => 'blog.php', 'target' => '_self'],
        ['name' => 'Contact', 'url' => 'contact.php', 'target' => '_self']
    ];
}

// Get current page name
$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="Vn-Xz3Ig7mUJtVNwhK8QX-T2E8fl3KTEKKx4U0gi0lo" />
    
    <?php 
    // Render 100% Database-Driven Meta, OpenGraph, Twitter Cards, Schema, & Tracking Scripts
    renderSeoHead($pdo, $currentPage, $customSeoData ?? []);
    ?>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0a1118',
                            darker: '#060b10',
                            card: '#101a24',
                            accent: '#38bdf8',
                            glow: '#0284c7'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="bg-brand-dark text-slate-200 font-sans antialiased selection:bg-brand-accent selection:text-brand-dark overflow-x-hidden min-h-screen flex flex-col justify-between">

    <!-- Top Glow Overlay -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-brand-accent/5 blur-[120px] pointer-events-none z-0"></div>

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-brand-dark/80 backdrop-blur-md border-b border-slate-800/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex items-center justify-between">
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-3 group py-3">
                <?php if (!empty($siteLogo)): ?>
                    <img src="<?php echo htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($companyName); ?>" class="h-10 sm:h-12 w-auto object-contain max-w-[220px] group-hover:scale-105 transition-transform" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                    <div class="hidden items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-accent/20 group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-cubes text-brand-dark text-lg font-bold"></i>
                        </div>
                        <span class="font-heading font-extrabold text-2xl tracking-tight text-white group-hover:text-brand-accent transition-colors">
                            <?php echo htmlspecialchars($companyName); ?><span class="text-brand-accent">.</span>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-accent/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-cubes text-brand-dark text-lg font-bold"></i>
                    </div>
                    <span class="font-heading font-extrabold text-2xl tracking-tight text-white group-hover:text-brand-accent transition-colors">
                        <?php echo htmlspecialchars($companyName); ?><span class="text-brand-accent">.</span>
                    </span>
                <?php endif; ?>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-8 font-medium text-slate-300">
                <?php foreach ($headerItems as $item): 
                    $isActive = ($currentPage == basename($item['url']));
                    $class = $isActive ? 'text-white active' : 'hover:text-brand-accent';
                ?>
                    <a href="<?php echo htmlspecialchars($item['url']); ?>" target="<?php echo htmlspecialchars($item['target'] ?? '_self'); ?>" class="nav-link <?php echo $class; ?> transition-colors"><?php echo htmlspecialchars($item['name']); ?></a>
                <?php endforeach; ?>
            </nav>

            <!-- Navigation Actions -->
            <div class="hidden lg:flex items-center gap-5">
                <a href="<?php echo htmlspecialchars($siteSettings['consultation_btn_link'] ?? 'contact.php'); ?>" class="px-6 py-3 rounded-full font-heading font-semibold text-sm bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg hover:shadow-brand-accent/30 hover:scale-105 transition-all">
                    GET YOUR WEBSITE NOW
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button id="mobile-menu-btn" class="lg:hidden w-10 h-10 flex flex-col justify-center items-center gap-1.5 focus:outline-none" aria-label="Toggle Menu">
                <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
                <span class="w-6 h-0.5 bg-white transition-all duration-300"></span>
            </button>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div id="mobile-menu" class="fixed inset-0 bg-brand-dark/95 backdrop-blur-xl z-40 transform translate-x-full transition-transform duration-300 lg:hidden flex flex-col justify-center items-center gap-8 text-2xl font-heading font-semibold">
            <button id="close-menu-btn" class="absolute top-8 right-8 text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-3xl"></i>
            </button>
            <?php foreach ($headerItems as $item): 
                $isActive = ($currentPage == basename($item['url']));
                $class = $isActive ? 'text-brand-accent' : 'text-slate-300 hover:text-brand-accent';
            ?>
                <a href="<?php echo htmlspecialchars($item['url']); ?>" target="<?php echo htmlspecialchars($item['target'] ?? '_self'); ?>" class="<?php echo $class; ?>"><?php echo htmlspecialchars($item['name']); ?></a>
            <?php endforeach; ?>
            <a href="<?php echo htmlspecialchars($siteSettings['consultation_btn_link'] ?? 'contact.php'); ?>" class="mt-4 px-8 py-3 rounded-full text-base bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all">
                GET YOUR WEBSITE NOW
            </a>
        </div>
    </header>

