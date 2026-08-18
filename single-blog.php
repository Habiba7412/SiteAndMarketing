<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/models/Blog.php';
require_once __DIR__ . '/models/Setting.php';

$slug = trim($_GET['slug'] ?? '');

if (empty($slug)) {
    header("Location: blog");
    exit();
}

// 301 Legacy Redirect Interceptor: /single-blog.php?slug=... -> /blog/...
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($requestUri, 'single-blog.php') !== false) {
    http_response_code(301);
    header("Location: blog/" . rawurlencode($slug), true, 301);
    exit();
}

$blog = Blog::getBySlug($slug);

if (!$blog) {
    http_response_code(404);
    header("Location: blog");
    exit();
}

$relatedPosts = Blog::getRelated($blog['category'], $blog['id'], 3);
$latestPosts  = Blog::getPublished(4);

// Prepare Dynamic Blog SEO metadata
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'siteandmarketing.com';
$cleanBlogUrl = $protocol . $host . '/myitcomapny/blog/' . rawurlencode($slug);

$customSeoData = [
    'title' => $blog['title'] . ' | Site And Marketing Tech Insights',
    'description' => $blog['excerpt'],
    'canonical' => $cleanBlogUrl,
    'og_title' => $blog['title'],
    'og_description' => $blog['excerpt'],
    'og_image' => $blog['image_url'],
    'og_type' => 'article',
    'twitter_title' => $blog['title'],
    'twitter_description' => $blog['excerpt'],
    'twitter_image' => $blog['image_url']
];

$currentPage = 'single-blog.php';
require_once __DIR__ . '/includes/header.php';
?>

    <!-- Blog Hero Banner -->
    <section class="relative bg-brand-darker py-24 border-b border-slate-900 overflow-hidden">
        <div class="glow-bg top-0 right-10"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 mt-12">
            <div class="flex flex-col gap-4 max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-[11px] font-heading font-bold uppercase tracking-wider bg-brand-accent text-brand-dark w-fit">
                    <?php echo htmlspecialchars($blog['category']); ?>
                </span>
                <h1 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h1>
                <div class="flex items-center gap-4 text-sm text-slate-400">
                    <span>By <strong class="text-white"><?php echo htmlspecialchars($blog['author']); ?></strong></span>
                    <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                    <span><i class="fa-regular fa-clock text-xs mr-1 text-brand-accent"></i><?php echo date('F d, Y • h:i A', strtotime($blog['created_at'])); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Single Blog Content -->
    <section class="py-16 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                <!-- Main Blog Article -->
                <article class="lg:col-span-8">
                    <!-- Featured Image -->
                    <div class="rounded-3xl overflow-hidden border border-white/5 mb-10 aspect-[16/9]">
                        <img src="<?php echo htmlspecialchars($blog['image_url'] ?: 'uploads/blog/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" class="w-full h-full object-cover" onerror="this.src='uploads/blog/placeholder.svg';">
                    </div>

                    <!-- Blog Content Body -->
                    <div class="prose prose-invert prose-lg max-w-none text-slate-300 leading-relaxed space-y-6">
                        <?php echo $blog['content']; ?>
                    </div>

                    <!-- Tags / Category Footer -->
                    <div class="mt-10 pt-8 border-t border-slate-800 flex items-center gap-4 flex-wrap">
                        <span class="text-sm text-slate-400">Category:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-accent/10 text-brand-accent border border-brand-accent/20">
                            <?php echo htmlspecialchars($blog['category']); ?>
                        </span>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:col-span-4 flex flex-col gap-8">

                    <!-- Latest Posts Widget -->
                    <div class="glass-panel rounded-3xl p-6 border border-white/5">
                        <h4 class="font-heading font-bold text-lg text-white mb-4">Latest Articles</h4>
                        <div class="flex flex-col gap-4">
                            <?php foreach ($latestPosts as $lp): if ($lp['id'] === $blog['id']) continue; ?>
                            <a href="single-blog.php?slug=<?php echo urlencode($lp['slug']); ?>" class="flex gap-3 group">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                                    <img src="<?php echo htmlspecialchars($lp['image_url'] ?: 'uploads/blog/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($lp['title']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all" onerror="this.src='uploads/blog/placeholder.svg';">
                                </div>
                                <div>
                                    <h5 class="text-sm font-semibold text-slate-200 group-hover:text-brand-accent transition-colors leading-snug line-clamp-2"><?php echo htmlspecialchars($lp['title']); ?></h5>
                                    <span class="text-xs text-slate-500 mt-1 block"><?php echo date('M d, Y • h:i A', strtotime($lp['created_at'])); ?></span>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Categories Widget -->
                    <div class="glass-panel rounded-3xl p-6 border border-white/5">
                        <h4 class="font-heading font-bold text-lg text-white mb-4">Categories</h4>
                        <?php
                        $catStmt = $pdo->query("SELECT category, COUNT(*) as count FROM `blogs` WHERE `status` = 'published' GROUP BY category ORDER BY count DESC");
                        $cats = $catStmt->fetchAll();
                        ?>
                        <div class="flex flex-col gap-2">
                            <?php foreach ($cats as $cat): ?>
                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>" class="flex items-center justify-between py-2 border-b border-slate-800 hover:text-brand-accent text-slate-400 transition-colors text-sm">
                                <span><?php echo htmlspecialchars($cat['category']); ?></span>
                                <span class="text-xs font-bold text-brand-accent">(<?php echo $cat['count']; ?>)</span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
            </div>

            <!-- Related Posts -->
            <?php if (!empty($relatedPosts)): ?>
            <div class="mt-20">
                <h3 class="font-heading font-extrabold text-2xl text-white mb-8">Related Articles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($relatedPosts as $rp): ?>
                    <div class="glass-panel rounded-3xl overflow-hidden border border-white/5 group hover:border-brand-accent/20 transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="<?php echo htmlspecialchars($rp['image_url']); ?>" alt="<?php echo htmlspecialchars($rp['title']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
                        </div>
                        <div class="p-6 bg-brand-card">
                            <span class="text-xs text-brand-accent font-bold uppercase"><?php echo htmlspecialchars($rp['category']); ?></span>
                            <h4 class="font-heading font-bold text-lg text-white mt-2 group-hover:text-brand-accent transition-colors leading-snug line-clamp-2">
                                <a href="single-blog.php?slug=<?php echo urlencode($rp['slug']); ?>"><?php echo htmlspecialchars($rp['title']); ?></a>
                            </h4>
                            <p class="text-slate-400 text-sm mt-2 line-clamp-2"><?php echo htmlspecialchars($rp['excerpt']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

<?php include __DIR__ . '/includes/footer.php'; ?>
