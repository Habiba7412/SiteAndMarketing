<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$activeTab = $_GET['tab'] ?? 'pages';

// ─── POST HANDLERS ─────────────────────────────────────────────────────────────

// 1. Handle Global SEO & Verification Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_global_seo') {
    $website_name = trim($_POST['website_name'] ?? '');
    $website_title = trim($_POST['website_title'] ?? '');
    $meta_desc = trim($_POST['meta_description'] ?? '');
    $keywords = trim($_POST['default_keywords'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $social_image = trim($_POST['default_social_image'] ?? '');
    $google_verif = trim($_POST['google_verification'] ?? '');
    $bing_verif = trim($_POST['bing_verification'] ?? '');
    $ga_tracking = trim($_POST['ga_tracking_id'] ?? '');

    try {
        // Update seo_global
        $stmtGlobal = $pdo->prepare("UPDATE `seo_global` SET 
            `website_name` = ?, `website_title` = ?, `meta_description` = ?, 
            `default_keywords` = ?, `author` = ?, `default_social_image` = ? WHERE `id` = 1");
        $stmtGlobal->execute([$website_name, $website_title, $meta_desc, $keywords, $author, $social_image]);

        // Update seo_verification
        $stmtVerif = $pdo->prepare("UPDATE `seo_verification` SET 
            `google_verification` = ?, `bing_verification` = ? WHERE `id` = 1");
        $stmtVerif->execute([$google_verif, $bing_verif]);

        // Update seo_analytics
        $stmtAnalytics = $pdo->prepare("UPDATE `seo_analytics` SET 
            `ga_tracking_id` = ? WHERE `id` = 1");
        $stmtAnalytics->execute([$ga_tracking]);

        $success = "Global SEO, Verification & Analytics settings updated successfully!";
        $activeTab = 'global';
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// 2. Handle Page-Specific SEO Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_page_seo') {
    $page_key = trim($_POST['page_key'] ?? '');
    $page_name = trim($_POST['page_name'] ?? '');
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_desc = trim($_POST['meta_description'] ?? '');
    $keywords = trim($_POST['keywords'] ?? '');
    $canonical_url = trim($_POST['canonical_url'] ?? '');
    
    $og_title = trim($_POST['og_title'] ?? '');
    $og_desc = trim($_POST['og_description'] ?? '');
    $og_image = trim($_POST['og_image'] ?? '');
    
    $twitter_title = trim($_POST['twitter_title'] ?? '');
    $twitter_desc = trim($_POST['twitter_description'] ?? '');
    $twitter_image = trim($_POST['twitter_image'] ?? '');
    
    $schema_type = trim($_POST['schema_type'] ?? 'WebPage');
    $is_indexed = isset($_POST['is_indexed']) ? 1 : 0;
    $is_followed = isset($_POST['is_followed']) ? 1 : 0;
    $sitemap_priority = trim($_POST['sitemap_priority'] ?? '0.8');
    $sitemap_changefreq = trim($_POST['sitemap_changefreq'] ?? 'weekly');

    if (!empty($page_key) && !empty($meta_title)) {
        try {
            // Update seo_pages table
            $stmtPage = $pdo->prepare("UPDATE `seo_pages` SET 
                `page_name` = ?, `meta_title` = ?, `meta_description` = ?, `keywords` = ?, 
                `canonical_url` = ?, `og_title` = ?, `og_description` = ?, `og_image` = ?, 
                `twitter_title` = ?, `twitter_description` = ?, `twitter_image` = ?, 
                `schema_type` = ?, `is_indexed` = ?, `is_followed` = ?, 
                `sitemap_priority` = ?, `sitemap_changefreq` = ? 
                WHERE `page_key` = ?");
            $stmtPage->execute([
                $page_name, $meta_title, $meta_desc, $keywords,
                $canonical_url, $og_title, $og_desc, $og_image,
                $twitter_title, $twitter_desc, $twitter_image,
                $schema_type, $is_indexed, $is_followed,
                $sitemap_priority, $sitemap_changefreq,
                $page_key
            ]);

            // Sync with legacy seo_settings table
            $stmtLegacy = $pdo->prepare("INSERT INTO `seo_settings` (`page_name`, `title`, `meta_description`, `meta_keywords`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `title` = ?, `meta_description` = ?, `meta_keywords` = ?");
            $stmtLegacy->execute([$page_key, $meta_title, $meta_desc, $keywords, $meta_title, $meta_desc, $keywords]);

            $success = "SEO configuration for '" . htmlspecialchars($page_name) . "' saved successfully!";
            $activeTab = 'pages';
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Meta Title is required.";
    }
}

// 3. Handle Add New Page SEO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_page_seo') {
    $new_page_key  = trim($_POST['new_page_key'] ?? '');
    $new_page_name = trim($_POST['new_page_name'] ?? '');
    $new_title     = trim($_POST['new_meta_title'] ?? '');
    $new_desc      = trim($_POST['new_meta_description'] ?? '');
    $new_keywords  = trim($_POST['new_keywords'] ?? '');

    if (!empty($new_page_key) && !empty($new_page_name) && !empty($new_title)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO `seo_pages` (`page_key`, `page_name`, `meta_title`, `meta_description`, `keywords`) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$new_page_key, $new_page_name, $new_title, $new_desc, $new_keywords]);

            $stmtLegacy = $pdo->prepare("INSERT INTO `seo_settings` (`page_name`, `title`, `meta_description`, `meta_keywords`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `title` = ?, `meta_description` = ?, `meta_keywords` = ?");
            $stmtLegacy->execute([$new_page_key, $new_title, $new_desc, $new_keywords, $new_title, $new_desc, $new_keywords]);

            $success = "New Page SEO record '" . htmlspecialchars($new_page_name) . "' created successfully!";
            $activeTab = 'pages';
        } catch (PDOException $e) {
            $error = "Database error (Page key may already exist): " . $e->getMessage();
        }
    } else {
        $error = "Page Key, Name, and Meta Title are required.";
    }
}

// 4. Handle Delete Page SEO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_page_seo') {
    $delete_id = (int)($_POST['delete_id'] ?? 0);
    if ($delete_id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM `seo_pages` WHERE `id` = ?");
            $stmt->execute([$delete_id]);
            $success = "Page SEO configuration deleted successfully.";
            $activeTab = 'pages';
        } catch (PDOException $e) {
            $error = "Error deleting record: " . $e->getMessage();
        }
    }
}

// ─── DATA FETCHING ─────────────────────────────────────────────────────────────

try {
    $seoGlobal = $pdo->query("SELECT * FROM `seo_global` WHERE `id` = 1")->fetch() ?: [];
    $seoVerif = $pdo->query("SELECT * FROM `seo_verification` WHERE `id` = 1")->fetch() ?: [];
    $seoAnalytics = $pdo->query("SELECT * FROM `seo_analytics` WHERE `id` = 1")->fetch() ?: [];
    $seoPages = $pdo->query("SELECT * FROM `seo_pages` ORDER BY `id` ASC")->fetchAll() ?: [];
} catch (PDOException $e) {
    $seoGlobal = $seoVerif = $seoAnalytics = [];
    $seoPages = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <!-- Page Header & Tab Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800/60 pb-5">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Search Engine Optimization (SEO Suite)</h2>
                <p class="text-xs text-slate-400 mt-1">Manage Meta Tags, OpenGraph, Twitter Cards, Schema Types, Canonical URLs & Verification Tags.</p>
            </div>

            <!-- Tab Switcher -->
            <div class="flex items-center gap-2 bg-brand-card p-1.5 rounded-2xl border border-slate-800/80">
                <a href="seo.php?tab=pages" class="px-4 py-2 rounded-xl text-xs font-bold font-heading transition-all flex items-center gap-2 <?php echo ($activeTab === 'pages') ? 'bg-brand-accent text-brand-dark shadow-md' : 'text-slate-400 hover:text-white'; ?>">
                    <i class="fa-solid fa-file-lines text-xs"></i>
                    <span>All On-Page SEO (SEO Title, Keywords, Meta Desc)</span>
                </a>
                <a href="seo.php?tab=global" class="px-4 py-2 rounded-xl text-xs font-bold font-heading transition-all flex items-center gap-2 <?php echo ($activeTab === 'global') ? 'bg-brand-accent text-brand-dark shadow-md' : 'text-slate-400 hover:text-white'; ?>">
                    <i class="fa-solid fa-sliders text-xs"></i>
                    <span>Google Console ID & Global Meta</span>
                </a>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo addslashes($success); ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo addslashes($error); ?>", "error"));</script>
        <?php endif; ?>


        <?php if ($activeTab === 'global'): ?>
            <!-- ════════════════════════════════════════════════════════════════ -->
            <!-- GLOBAL SEO, VERIFICATION & ANALYTICS FORM -->
            <!-- ════════════════════════════════════════════════════════════════ -->
            <form action="seo.php?tab=global" method="POST" class="flex flex-col gap-6 max-w-5xl">
                <input type="hidden" name="action" value="update_global_seo">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Global Identity Card -->
                    <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-4 border border-white/5 bg-brand-card">
                        <h3 class="font-heading font-bold text-sm text-brand-accent uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-globe"></i>
                            <span>Website Global Meta Info</span>
                        </h3>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Website Brand Name</label>
                            <input type="text" name="website_name" value="<?php echo htmlspecialchars($seoGlobal['website_name'] ?? 'DigiRare Technologies'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Default Fallback Website Title</label>
                            <input type="text" name="website_title" value="<?php echo htmlspecialchars($seoGlobal['website_title'] ?? ''); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Default Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($seoGlobal['meta_description'] ?? ''); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Default Keywords (Comma separated)</label>
                            <input type="text" name="default_keywords" value="<?php echo htmlspecialchars($seoGlobal['default_keywords'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Author Tag</label>
                                <input type="text" name="author" value="<?php echo htmlspecialchars($seoGlobal['author'] ?? 'DigiRare Solutions'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Default Social Image URL</label>
                                <input type="text" name="default_social_image" value="<?php echo htmlspecialchars($seoGlobal['default_social_image'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Webmaster Verification & Analytics Card -->
                    <div class="flex flex-col gap-6">
                        <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-4 border border-white/5 bg-brand-card">
                            <h3 class="font-heading font-bold text-sm text-emerald-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Search Console Verification Codes</span>
                            </h3>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Google Site Verification Code</label>
                                <input type="text" name="google_verification" value="<?php echo htmlspecialchars($seoVerif['google_verification'] ?? ''); ?>" placeholder="e.g. google-site-verification=abc123xyz" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Bing Webmaster Verification Code</label>
                                <input type="text" name="bing_verification" value="<?php echo htmlspecialchars($seoVerif['bing_verification'] ?? ''); ?>" placeholder="e.g. 1234567890ABCDEF" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>
                        </div>

                        <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-4 border border-white/5 bg-brand-card">
                            <h3 class="font-heading font-bold text-sm text-sky-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Web Analytics</span>
                            </h3>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Google Analytics (GA4 Measurement ID)</label>
                                <input type="text" name="ga_tracking_id" value="<?php echo htmlspecialchars($seoAnalytics['ga_tracking_id'] ?? ''); ?>" placeholder="e.g. G-XXXXXXX" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-7 py-3 rounded-2xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk text-sm"></i>
                        <span>Save Global Settings</span>
                    </button>
                </div>
            </form>

        <?php else: ?>
            <!-- ════════════════════════════════════════════════════════════════ -->
            <!-- PAGE-BY-PAGE SEO CARDS GRID WITH SEARCH & ADD MODAL -->
            <!-- ════════════════════════════════════════════════════════════════ -->
            
            <!-- Controls Bar: Search & Add Page Button -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-brand-card p-4 rounded-3xl border border-white/5">
                <div class="relative w-full sm:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                    <input type="text" id="seo-search-input" onkeyup="filterSeoCards()" placeholder="Search page name or URI (e.g. index.php)..." class="w-full bg-brand-dark/60 border border-slate-800/80 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent transition-colors">
                </div>

                <button type="button" onclick="openAddSeoModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add New Page SEO</span>
                </button>
            </div>

            <!-- Add Page SEO Modal -->
            <div id="add-seo-modal" class="fixed inset-0 z-50 hidden bg-brand-dark/80 backdrop-blur-md flex items-center justify-center p-4">
                <div class="bg-brand-card border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl flex flex-col gap-5 relative animate-fade-in">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="font-heading font-bold text-base text-white flex items-center gap-2">
                            <i class="fa-solid fa-file-circle-plus text-brand-accent"></i>
                            <span>Add New Dynamic Page SEO</span>
                        </h3>
                        <button type="button" onclick="closeAddSeoModal()" class="text-slate-400 hover:text-white">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form action="seo.php?tab=pages" method="POST" class="flex flex-col gap-4">
                        <input type="hidden" name="action" value="add_page_seo">

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Page URI / Key * (e.g. pricing.php)</label>
                            <input type="text" name="new_page_key" placeholder="e.g. pricing.php or custom-landing" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent font-mono">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Page Display Name *</label>
                            <input type="text" name="new_page_name" placeholder="e.g. Pricing & Plans" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Meta Title *</label>
                            <input type="text" name="new_meta_title" placeholder="e.g. Flexible IT Service Pricing | DigiRare" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Meta Description</label>
                            <textarea name="new_meta_description" rows="2" placeholder="Page description..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Keywords (Comma separated)</label>
                            <input type="text" name="new_keywords" placeholder="keyword1, keyword2, keyword3" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent">
                        </div>

                        <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                            <button type="button" onclick="closeAddSeoModal()" class="px-4 py-2 rounded-xl text-xs text-slate-400 hover:text-white">Cancel</button>
                            <button type="submit" class="px-5 py-2 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg">Create Page SEO</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6" id="seo-cards-container">
                <?php foreach ($seoPages as $index => $page): ?>
                    <?php 
                        $cardId = 'page-card-' . $page['id'];
                    ?>
                    <!-- Individual Page SEO Card -->
                    <div class="dashboard-card seo-card-item p-6 rounded-3xl relative overflow-hidden flex flex-col justify-between border border-white/5 bg-brand-card" data-search-term="<?php echo strtolower(htmlspecialchars($page['page_name'] . ' ' . $page['page_key'])); ?>">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-slate-800/50 pb-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-accent/15 flex items-center justify-center text-brand-accent shrink-0">
                                    <i class="fa-solid fa-file-code text-base"></i>
                                </div>
                                <div>
                                    <span class="font-heading font-bold text-base text-white block capitalize"><?php echo htmlspecialchars($page['page_name']); ?></span>
                                    <span class="text-[10px] text-slate-500 font-mono block">URI: /<?php echo htmlspecialchars($page['page_key']); ?></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[9px] font-mono font-bold uppercase tracking-wider <?php echo ($page['is_indexed'] ?? 1) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'; ?>">
                                    <?php echo ($page['is_indexed'] ?? 1) ? 'Indexed' : 'NoIndex'; ?>
                                </span>
                                <a href="../<?php echo htmlspecialchars($page['page_key']); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-slate-850 hover:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="View Live Page">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                                <form action="seo.php?tab=pages" method="POST" onsubmit="return confirm('Are you sure you want to delete SEO settings for this page?');">
                                    <input type="hidden" name="action" value="delete_page_seo">
                                    <input type="hidden" name="delete_id" value="<?php echo $page['id']; ?>">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 flex items-center justify-center transition-colors" title="Delete Page SEO">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                                <div>
                                    <span class="font-heading font-bold text-base text-white block capitalize"><?php echo htmlspecialchars($page['page_name']); ?></span>
                                    <span class="text-[10px] text-slate-500 font-mono block">URI: /<?php echo htmlspecialchars($page['page_key']); ?></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[9px] font-mono font-bold uppercase tracking-wider <?php echo ($page['is_indexed'] ?? 1) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'; ?>">
                                    <?php echo ($page['is_indexed'] ?? 1) ? 'Indexed' : 'NoIndex'; ?>
                                </span>
                                <a href="../<?php echo htmlspecialchars($page['page_key']); ?>" target="_blank" class="w-8 h-8 rounded-lg bg-slate-850 hover:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="View Live Page">
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card Form with Section Tabs -->
                        <form action="seo.php?tab=pages" method="POST" class="flex flex-col gap-4">
                            <input type="hidden" name="action" value="update_page_seo">
                            <input type="hidden" name="page_key" value="<?php echo htmlspecialchars($page['page_key']); ?>">
                            <input type="hidden" name="page_name" value="<?php echo htmlspecialchars($page['page_name']); ?>">

                            <!-- Inner Sub-Tabs Navigation -->
                            <div class="flex items-center gap-1.5 border-b border-slate-850 pb-2 text-[11px] font-bold font-heading">
                                <button type="button" onclick="switchCardTab('<?php echo $cardId; ?>', 'general')" class="sub-tab-btn-<?php echo $cardId; ?> sub-tab-general px-3 py-1.5 rounded-lg transition-all text-brand-accent bg-brand-accent/10">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> All On-Page SEO
                                </button>
                                <button type="button" onclick="switchCardTab('<?php echo $cardId; ?>', 'social')" class="sub-tab-btn-<?php echo $cardId; ?> sub-tab-social px-3 py-1.5 rounded-lg transition-all text-slate-400 hover:text-white">
                                    <i class="fa-solid fa-share-nodes mr-1"></i> Meta & OpenGraph
                                </button>
                                <button type="button" onclick="switchCardTab('<?php echo $cardId; ?>', 'tech')" class="sub-tab-btn-<?php echo $cardId; ?> sub-tab-tech px-3 py-1.5 rounded-lg transition-all text-slate-400 hover:text-white">
                                    <i class="fa-solid fa-gear mr-1"></i> Robots & Sitemap
                                </button>
                            </div>

                            <!-- 1. General SEO Tab -->
                            <div id="<?php echo $cardId; ?>-tab-general" class="card-tab-content-<?php echo $cardId; ?> flex flex-col gap-3.5">
                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Meta Title Tag *</label>
                                    <input type="text" name="meta_title" value="<?php echo htmlspecialchars($page['meta_title'] ?? ''); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Meta Description</label>
                                    <textarea name="meta_description" rows="2" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($page['meta_description'] ?? ''); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Keywords Tag (Comma separated)</label>
                                    <input type="text" name="keywords" value="<?php echo htmlspecialchars($page['keywords'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Canonical URL (Optional)</label>
                                    <input type="text" name="canonical_url" value="<?php echo htmlspecialchars($page['canonical_url'] ?? ''); ?>" placeholder="Auto-generated if left blank" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                                </div>
                            </div>

                            <!-- 2. OpenGraph & Twitter Cards Tab -->
                            <div id="<?php echo $cardId; ?>-tab-social" class="card-tab-content-<?php echo $cardId; ?> hidden flex flex-col gap-3.5">
                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">OpenGraph Title (Facebook & LinkedIn)</label>
                                    <input type="text" name="og_title" value="<?php echo htmlspecialchars($page['og_title'] ?? ''); ?>" placeholder="Defaults to Meta Title if blank" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">OpenGraph Description</label>
                                    <textarea name="og_description" rows="2" placeholder="Defaults to Meta Description if blank" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($page['og_description'] ?? ''); ?></textarea>
                                </div>

                                <div>
                                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">OpenGraph Showcase Image URL</label>
                                    <input type="text" name="og_image" value="<?php echo htmlspecialchars($page['og_image'] ?? ''); ?>" placeholder="e.g. https://images.unsplash.com/photo-..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Twitter Card Title</label>
                                        <input type="text" name="twitter_title" value="<?php echo htmlspecialchars($page['twitter_title'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Twitter Image URL</label>
                                        <input type="text" name="twitter_image" value="<?php echo htmlspecialchars($page['twitter_image'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3.5 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                    </div>
                                </div>
                            </div>

                            <!-- 3. Technical, Robots & Schema Tab -->
                            <div id="<?php echo $cardId; ?>-tab-tech" class="card-tab-content-<?php echo $cardId; ?> hidden flex flex-col gap-3.5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">JSON-LD Schema Type</label>
                                        <select name="schema_type" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                            <option value="WebPage" <?php echo (($page['schema_type'] ?? '') === 'WebPage') ? 'selected' : ''; ?>>WebPage</option>
                                            <option value="Organization" <?php echo (($page['schema_type'] ?? '') === 'Organization') ? 'selected' : ''; ?>>Organization</option>
                                            <option value="Service" <?php echo (($page['schema_type'] ?? '') === 'Service') ? 'selected' : ''; ?>>Service</option>
                                            <option value="Article" <?php echo (($page['schema_type'] ?? '') === 'Article') ? 'selected' : ''; ?>>Article</option>
                                            <option value="LocalBusiness" <?php echo (($page['schema_type'] ?? '') === 'LocalBusiness') ? 'selected' : ''; ?>>LocalBusiness</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Sitemap Priority</label>
                                        <select name="sitemap_priority" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                            <option value="1.0" <?php echo (($page['sitemap_priority'] ?? '') === '1.0') ? 'selected' : ''; ?>>1.0 (Highest)</option>
                                            <option value="0.9" <?php echo (($page['sitemap_priority'] ?? '') === '0.9') ? 'selected' : ''; ?>>0.9 (High)</option>
                                            <option value="0.8" <?php echo (($page['sitemap_priority'] ?? '') === '0.8') ? 'selected' : ''; ?>>0.8 (Standard)</option>
                                            <option value="0.5" <?php echo (($page['sitemap_priority'] ?? '') === '0.5') ? 'selected' : ''; ?>>0.5 (Low)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1">Sitemap Change Frequency</label>
                                        <select name="sitemap_changefreq" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-3 py-2 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                            <option value="daily" <?php echo (($page['sitemap_changefreq'] ?? '') === 'daily') ? 'selected' : ''; ?>>Daily</option>
                                            <option value="weekly" <?php echo (($page['sitemap_changefreq'] ?? '') === 'weekly') ? 'selected' : ''; ?>>Weekly</option>
                                            <option value="monthly" <?php echo (($page['sitemap_changefreq'] ?? '') === 'monthly') ? 'selected' : ''; ?>>Monthly</option>
                                            <option value="yearly" <?php echo (($page['sitemap_changefreq'] ?? '') === 'yearly') ? 'selected' : ''; ?>>Yearly</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center gap-4 pt-4">
                                        <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                                            <input type="checkbox" name="is_indexed" value="1" <?php echo ($page['is_indexed'] ?? 1) ? 'checked' : ''; ?> class="rounded bg-brand-dark border-slate-700 text-brand-accent focus:ring-0">
                                            <span>Allow Google Indexing</span>
                                        </label>
                                        <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                                            <input type="checkbox" name="is_followed" value="1" <?php echo ($page['is_followed'] ?? 1) ? 'checked' : ''; ?> class="rounded bg-brand-dark border-slate-700 text-brand-accent focus:ring-0">
                                            <span>Follow Links</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Submit Footer -->
                            <div class="flex justify-end pt-3 border-t border-slate-850 mt-2">
                                <button type="submit" class="px-5 py-2 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                                    <i class="fa-solid fa-floppy-disk text-[10px]"></i>
                                    <span>Save Page SEO</span>
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- JS Helper for Sub-Tabs Switching in Cards & Modal Controls -->
    <script>
        function openAddSeoModal() {
            document.getElementById('add-seo-modal').classList.remove('hidden');
        }

        function closeAddSeoModal() {
            document.getElementById('add-seo-modal').classList.add('hidden');
        }

        function filterSeoCards() {
            const query = (document.getElementById('seo-search-input').value || '').toLowerCase().trim();
            const cards = document.querySelectorAll('.seo-card-item');
            cards.forEach(card => {
                const term = card.getAttribute('data-search-term') || '';
                if (!query || term.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        function switchCardTab(cardId, tabName) {
            // Hide all tab contents for this card
            document.querySelectorAll('.card-tab-content-' + cardId).forEach(el => el.classList.add('hidden'));
            // Remove active classes on sub-tab buttons
            document.querySelectorAll('.sub-tab-btn-' + cardId).forEach(btn => {
                btn.classList.remove('text-brand-accent', 'bg-brand-accent/10');
                btn.classList.add('text-slate-400');
            });

            // Show selected content
            const targetContent = document.getElementById(cardId + '-tab-' + tabName);
            if (targetContent) targetContent.classList.remove('hidden');

            // Highlight button
            const activeBtn = document.querySelector('.sub-tab-btn-' + cardId + '.sub-tab-' + tabName);
            if (activeBtn) {
                activeBtn.classList.remove('text-slate-400');
                activeBtn.classList.add('text-brand-accent', 'bg-brand-accent/10');
            }
        }
    </script>

<?php require_once __DIR__ . '/footer.php'; ?>
