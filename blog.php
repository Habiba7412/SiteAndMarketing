<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/models/Blog.php';
require_once __DIR__ . '/models/Setting.php';

$search   = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

// Fetch dynamic blog posts from database with fallbacks
$blogs = [];
$featuredBlog = null;
$popularBlogs = [];
$categoriesList = [];

try {
    // 1. Fetch Featured Article
    $featStmt = $pdo->query("SELECT * FROM `blogs` WHERE `status` = 'published' AND `category` = 'Technology' ORDER BY `id` ASC LIMIT 1");
    $featuredBlog = $featStmt->fetch();
    if (!$featuredBlog) {
        $featStmtFallback = $pdo->query("SELECT * FROM `blogs` WHERE `status` = 'published' ORDER BY `id` ASC LIMIT 1");
        $featuredBlog = $featStmtFallback->fetch();
    }

    // 2. Query Blog Listing
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT * FROM `blogs` WHERE `status` = 'published' AND (`title` LIKE ? OR `excerpt` LIKE ? OR `category` LIKE ? OR `content` LIKE ?) ORDER BY `created_at` DESC");
        $stmt->execute(['%' . $search . '%', '%' . $search . '%', '%' . $search . '%', '%' . $search . '%']);
    } elseif (!empty($category)) {
        $stmt = $pdo->prepare("SELECT * FROM `blogs` WHERE `status` = 'published' AND (`category` LIKE ? OR `title` LIKE ?) ORDER BY `created_at` DESC");
        $stmt->execute(['%' . $category . '%', '%' . $category . '%']);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM `blogs` WHERE `status` = 'published' ORDER BY `created_at` DESC");
        $stmt->execute();
    }
    $blogs = $stmt->fetchAll();

    // 3. Total Published Articles Count
    $totalCountStmt = $pdo->query("SELECT COUNT(*) FROM `blogs` WHERE `status` = 'published'");
    $totalBlogCount = intval($totalCountStmt->fetchColumn());

    // 4. Popular Posts
    $popStmt = $pdo->query("SELECT * FROM `blogs` WHERE `status` = 'published' ORDER BY `id` DESC LIMIT 4");
    $popularBlogs = $popStmt->fetchAll();

    // 5. Categories Count
    $catStmt = $pdo->query("SELECT `category`, COUNT(*) as count FROM `blogs` WHERE `status` = 'published' GROUP BY `category` ORDER BY count DESC");
    $categoriesList = $catStmt->fetchAll();

} catch (PDOException $e) {
    $blogs = [];
}

include __DIR__ . '/includes/header.php';
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="relative bg-[#081018] py-24 md:py-32 border-b border-slate-900/80 overflow-hidden">
    <!-- Glowing Background Accents -->
    <div class="glow-bg top-0 right-1/4 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-10 opacity-30"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center flex flex-col items-center gap-6 mt-8">
        <!-- Small Label badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase reveal-on-scroll">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>OUR BLOG</span>
        </div>

        <!-- Main Heading -->
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white max-w-4xl leading-[1.15] tracking-tight reveal-on-scroll delay-75">
            Insights, Technology Trends & <span class="text-gradient-blue">Digital Innovation</span>
        </h1>

        <!-- Description -->
        <p class="text-slate-400 text-base sm:text-lg max-w-3xl font-medium leading-relaxed reveal-on-scroll delay-100">
            Stay updated with the latest articles, industry insights, technology trends, software development guides, cybersecurity tips, AI innovations, digital marketing strategies, and business growth ideas from Site And Marketing Technologies.
        </p>

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="mt-2 reveal-on-scroll delay-150">
            <ol class="inline-flex items-center space-x-2 text-sm font-semibold tracking-wider font-heading uppercase text-slate-400">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-xs text-blue-400"></i> Home
                    </a>
                </li>
                <li><span class="text-slate-600">/</span></li>
                <li class="text-blue-400 font-bold">Blog & Insights</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ==================== SEARCH & CATEGORY FILTERS BAR ==================== -->
<section class="py-12 bg-[#060c14] border-b border-slate-900/80 sticky top-[72px] z-30 backdrop-blur-xl bg-slate-950/80">
    <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col lg:flex-row items-center justify-between gap-6">
        
        <!-- Search Input Form -->
        <form action="blog.php" method="GET" class="relative w-full lg:w-96 flex items-center bg-slate-900/90 border border-white/10 rounded-full p-1.5 focus-within:border-blue-500/50 transition-all">
            <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search articles, AI, SEO..." class="bg-transparent text-sm text-slate-100 placeholder-slate-500 focus:outline-none px-4 py-2 w-full font-medium">
            <button type="submit" class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white shrink-0 hover:scale-105 transition-transform shadow-md shadow-blue-500/20">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
        </form>

        <!-- Category Filter Pills (Horizontal Scrollable Bar) -->
        <div class="flex items-center gap-2 overflow-x-auto w-full lg:w-auto py-1 scrollbar-none">
            <a href="blog.php" class="px-4 py-2 rounded-full font-heading text-xs font-bold whitespace-nowrap transition-all <?php echo (empty($category) && empty($search)) ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/25 border border-blue-400/30' : 'bg-slate-900/80 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white'; ?>">
                All Articles
            </a>
            <?php 
            $allCats = [
                'Web Development', 'Software Development', 'Mobile Apps', 
                'Artificial Intelligence', 'UI/UX Design', 'Digital Marketing', 
                'SEO', 'Cybersecurity', 'Cloud Computing', 'DevOps', 
                'Business Growth', 'Technology News', 'Tutorials'
            ];
            foreach ($allCats as $catItem):
                $isActive = (strtolower($category) === strtolower($catItem));
            ?>
            <a href="blog.php?category=<?php echo urlencode($catItem); ?>" class="px-4 py-2 rounded-full font-heading text-xs font-bold whitespace-nowrap transition-all <?php echo $isActive ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/25 border border-blue-400/30' : 'bg-slate-900/80 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white'; ?>">
                <?php echo htmlspecialchars($catItem); ?>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ==================== FEATURED ARTICLE SECTION ==================== -->
<?php if (empty($search) && empty($category) && $featuredBlog): ?>
<section class="py-16 bg-[#081018] relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <div class="inline-flex items-center gap-2 text-xs font-extrabold tracking-widest text-blue-400 uppercase font-heading mb-6">
            <i class="fa-solid fa-star text-amber-400"></i> FEATURED ARTICLE
        </div>

        <div class="bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center group transition-all duration-500">
            <!-- Featured Image -->
            <div class="lg:col-span-7 aspect-video lg:aspect-[16/10] overflow-hidden relative">
                <img src="<?php echo htmlspecialchars($featuredBlog['image_url'] ?: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($featuredBlog['title']); ?>" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-80"></div>
                <span class="absolute top-6 left-6 px-3.5 py-1.5 rounded-full text-xs font-bold font-heading bg-blue-600 text-white shadow-lg shadow-blue-600/30">
                    <?php echo htmlspecialchars($featuredBlog['category'] ?: 'Technology'); ?>
                </span>
            </div>

            <!-- Featured Content -->
            <div class="lg:col-span-5 p-8 lg:p-12 flex flex-col gap-6 justify-between">
                <div class="flex items-center gap-4 text-xs text-slate-400 font-medium">
                    <span><i class="fa-solid fa-user text-blue-400 mr-1"></i> <?php echo htmlspecialchars($featuredBlog['author'] ?: 'Site And Marketing Technologies'); ?></span>
                    <span>•</span>
                    <span><i class="fa-solid fa-clock text-slate-500 mr-1"></i> 8 Minutes Read</span>
                </div>

                <h2 class="font-heading font-black text-2xl sm:text-3xl lg:text-4xl text-white group-hover:text-blue-400 transition-colors leading-tight">
                    <a href="single-blog.php?slug=<?php echo urlencode($featuredBlog['slug']); ?>">
                        <?php echo htmlspecialchars($featuredBlog['title']); ?>
                    </a>
                </h2>

                <p class="text-slate-300 text-base leading-relaxed">
                    <?php echo htmlspecialchars($featuredBlog['excerpt']); ?>
                </p>

                <div>
                    <a href="single-blog.php?slug=<?php echo urlencode($featuredBlog['slug']); ?>" class="btn-gradient-blue px-7 py-3.5 rounded-full font-heading font-bold text-white text-xs uppercase tracking-wider inline-flex items-center gap-2 group/btn">
                        <span>Read Featured Article</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ==================== BLOG POSTS GRID SECTION ==================== -->
<section class="py-20 bg-[#081018] relative overflow-hidden">
    <div class="glow-bg top-20 right-10 opacity-25"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="font-heading font-black text-3xl sm:text-4xl text-white">
                    <?php 
                    if (!empty($search)) echo 'Search Results for "' . htmlspecialchars($search) . '"';
                    elseif (!empty($category)) echo htmlspecialchars($category) . ' Articles';
                    else echo 'Latest Blog Posts';
                    ?>
                </h2>
                <p class="text-slate-400 text-sm mt-1">Discover expert guides, technological breakdowns, and growth insights.</p>
            </div>
            <?php if (!empty($search) || !empty($category)): ?>
                <a href="blog.php" class="px-4 py-2 rounded-full bg-slate-900 border border-slate-800 text-xs font-bold text-slate-300 hover:text-white transition-colors">
                    Reset Filter
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($totalBlogCount) && $totalBlogCount === 0): ?>
            <!-- Scenario 1: No Articles Available Yet (DB is empty) -->
            <div class="bg-saas-card bg-saas-card-hover p-10 sm:p-14 rounded-3xl border border-white/10 max-w-2xl mx-auto text-center flex flex-col items-center gap-6 shadow-2xl backdrop-blur-xl reveal-on-scroll">
                <!-- Blue Gradient Icon Container -->
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-blue-600 via-blue-500 to-cyan-400 flex items-center justify-center text-white text-3xl shadow-xl shadow-blue-500/25 border border-blue-400/30 shrink-0">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                
                <div class="flex flex-col gap-3 max-w-xl">
                    <h3 class="font-heading font-black text-2xl sm:text-3xl text-white">No Articles Available Yet</h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        We're currently preparing high-quality articles covering software development, web technologies, artificial intelligence, cybersecurity, cloud computing, UI/UX design, SEO, digital marketing, and business innovation.
                    </p>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed mt-1">
                        Our expert team regularly publishes valuable insights, practical tutorials, industry trends, and technology guides to help businesses and developers stay ahead in the digital world. Please check back soon for fresh content and the latest technology updates.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
                    <a href="blog.php" class="btn-gradient-blue px-7 py-3.5 rounded-full font-heading font-bold text-xs text-white uppercase tracking-wider flex items-center justify-center gap-2 group shadow-lg shadow-blue-500/20">
                        <span>Browse All Articles</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="services.php" class="px-7 py-3.5 rounded-full font-heading font-bold text-xs text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all flex items-center justify-center gap-2 backdrop-blur-md">
                        <span>Explore Our Services</span>
                        <i class="fa-solid fa-layer-group text-xs text-blue-400"></i>
                    </a>
                </div>
            </div>

        <?php elseif (empty($blogs)): ?>
            <!-- Scenario 2: Alternative Message (When Search or Filter Returns No Results) -->
            <div class="bg-saas-card bg-saas-card-hover p-10 sm:p-14 rounded-3xl border border-white/10 max-w-2xl mx-auto text-center flex flex-col items-center gap-6 shadow-2xl backdrop-blur-xl reveal-on-scroll">
                <!-- Blue Gradient Icon Container -->
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-cyan-500 via-blue-600 to-indigo-600 flex items-center justify-center text-white text-3xl shadow-xl shadow-cyan-500/25 border border-cyan-400/30 shrink-0">
                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                </div>
                
                <div class="flex flex-col gap-3 max-w-xl">
                    <h3 class="font-heading font-black text-2xl sm:text-3xl text-white">No Matching Articles Found</h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        We couldn't find any articles matching your search or selected category. Try using different keywords, browse another category, or explore all published articles to discover valuable technology insights.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
                    <a href="blog.php" class="btn-gradient-blue px-7 py-3.5 rounded-full font-heading font-bold text-xs text-white uppercase tracking-wider flex items-center justify-center gap-2 group shadow-lg shadow-blue-500/20">
                        <span>Clear Filters</span>
                        <i class="fa-solid fa-rotate-left text-xs group-hover:rotate-180 transition-transform duration-500"></i>
                    </a>
                    <a href="blog.php" class="px-7 py-3.5 rounded-full font-heading font-bold text-xs text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all flex items-center justify-center gap-2 backdrop-blur-md">
                        <span>Browse All Articles</span>
                        <i class="fa-solid fa-newspaper text-xs text-blue-400"></i>
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- Responsive Grid: 3 Columns Desktop, 2 Tablet, 1 Mobile -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php 
                $delay = 0;
                foreach ($blogs as $post): 
                    $readingTime = rand(5, 8) . ' min read';
                ?>
                <!-- Blog Card Item -->
                <div class="bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll <?php echo ($delay > 0) ? 'delay-' . $delay : ''; ?>">
                    <!-- Post Image -->
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="<?php echo htmlspecialchars($post['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80'); ?>" 
                             alt="<?php echo htmlspecialchars($post['title']); ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">
                            <?php echo htmlspecialchars($post['category'] ?: 'Technology'); ?>
                        </span>
                        <span class="absolute bottom-4 right-4 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-950/80 text-slate-300 backdrop-blur-md border border-white/5">
                            <i class="fa-solid fa-clock text-blue-400 mr-1"></i> <?php echo $readingTime; ?>
                        </span>
                    </div>

                    <!-- Post Body -->
                    <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-400 font-medium mb-3">
                                <span><i class="fa-solid fa-user text-blue-400 mr-1"></i> <?php echo htmlspecialchars($post['author'] ?: 'Site And Marketing Technologies'); ?></span>
                                <span>•</span>
                                <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                            </div>

                            <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors leading-snug">
                                <a href="single-blog.php?slug=<?php echo urlencode($post['slug']); ?>">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h3>

                            <p class="text-slate-300 text-sm mt-3 leading-relaxed line-clamp-3">
                                <?php echo htmlspecialchars($post['excerpt']); ?>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-white/5">
                            <a href="single-blog.php?slug=<?php echo urlencode($post['slug']); ?>" class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 hover:text-white uppercase tracking-wider group/btn">
                                <span>Read Full Article</span>
                                <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
                    $delay = ($delay + 100) % 300;
                endforeach; 
                ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- ==================== POPULAR TAGS SECTION ==================== -->
<section class="py-16 bg-[#060c14] border-y border-slate-900/80">
    <div class="max-w-7xl mx-auto px-6 md:px-12 text-center flex flex-col items-center gap-6">
        <h3 class="font-heading font-bold text-xs uppercase tracking-widest text-blue-400">EXPLORE POPULAR TOPICS & TAGS</h3>
        
        <div class="flex flex-wrap justify-center gap-2.5 max-w-4xl">
            <?php 
            $popularTags = [
                'AI', 'Technology', 'Software', 'Development', 'Web Design', 
                'SEO', 'Marketing', 'Business', 'Cloud', 'DevOps', 
                'UI/UX', 'Security', 'Mobile Apps', 'Innovation'
            ];
            foreach ($popularTags as $tag):
            ?>
            <a href="blog.php?q=<?php echo urlencode($tag); ?>" class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900/80 text-slate-300 border border-white/5 hover:border-blue-500/40 hover:text-white hover:scale-105 transition-all">
                #<?php echo htmlspecialchars($tag); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==================== WHY READ OUR BLOG SECTION ==================== -->
<section class="py-24 bg-[#081018] relative overflow-hidden">
    <div class="glow-bg bottom-10 left-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col items-center gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-book-open text-xs"></i> KNOWLEDGE HUB
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                Why Read <span class="text-gradient-blue">Our Blog</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                Stay ahead in the digital era with actionable guides, architectural breakdowns, and business growth methodologies written by industry experts.
            </p>
        </div>

        <!-- 10 Benefit Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            
            <!-- Benefit 1 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Expert Industry Insights</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Written by seasoned engineers and tech architects.</p>
            </div>

            <!-- Benefit 2 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Development Tutorials</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Step-by-step guides for clean code and frameworks.</p>
            </div>

            <!-- Benefit 3 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Latest Tech Trends</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Coverage of emerging tools, AI models, and cloud platforms.</p>
            </div>

            <!-- Benefit 4 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">SEO & Marketing Tips</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Actionable advice to rank higher and scale lead generation.</p>
            </div>

            <!-- Benefit 5 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Cybersecurity Best Practices</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Strategies to protect organizational data from cyber threats.</p>
            </div>

            <!-- Benefit 6 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Business Growth Strategies</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Scalable frameworks tailored for startups and enterprises.</p>
            </div>

            <!-- Benefit 7 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Software Engineering Guides</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Architectural patterns, code refactoring, and API design.</p>
            </div>

            <!-- Benefit 8 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-brain"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">AI & Cloud Computing</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Deep dives into LLM integrations and cloud deployments.</p>
            </div>

            <!-- Benefit 9 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Actionable Advice</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Practical takeaways you can immediately apply to your business.</p>
            </div>

            <!-- Benefit 10 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Regularly Updated Content</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Fresh weekly publications keeping you ahead of market shifts.</p>
            </div>

        </div>

    </div>
</section>

<!-- ==================== NEWSLETTER SECTION ==================== -->
<section class="py-20 bg-[#060c14] border-y border-slate-900/80 relative overflow-hidden">
    <div class="glow-bg-emerald top-0 right-1/4 opacity-30"></div>
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center flex flex-col items-center gap-6">
        
        <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-2xl">
            <i class="fa-solid fa-paper-plane"></i>
        </div>

        <h2 class="font-heading font-black text-3xl sm:text-4xl text-white">
            Stay Updated with the Latest <span class="text-gradient-cyan">Technology Insights</span>
        </h2>

        <p class="text-slate-300 text-base max-w-2xl leading-relaxed">
            Subscribe to receive the latest articles, software development guides, technology news, digital marketing strategies, and business growth tips directly in your inbox.
        </p>

        <!-- Newsletter Form -->
        <form id="blog-newsletter-form" class="flex flex-col sm:flex-row w-full max-w-lg gap-3 bg-slate-900/80 border border-white/10 p-2 rounded-2xl sm:rounded-full backdrop-blur-md">
            <input type="email" placeholder="Enter your business email address" required class="bg-transparent text-sm text-slate-100 placeholder-slate-500 focus:outline-none px-4 py-3 sm:py-2 w-full font-medium">
            <button type="submit" class="btn-gradient-blue px-7 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-heading font-bold text-xs text-white uppercase tracking-wider shrink-0 transition-all">
                Subscribe Now
            </button>
        </form>
        
        <div id="newsletter-success" class="hidden text-xs text-emerald-400 font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i> Thank you! You have successfully subscribed to our newsletter.
        </div>

    </div>
</section>

<!-- ==================== CALL TO ACTION SECTION ==================== -->
<section class="py-24 relative overflow-hidden bg-gradient-to-b from-[#081018] via-[#050a10] to-[#04080e]">
    <div class="glow-bg top-0 right-1/3 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-1/3 opacity-30"></div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center flex flex-col items-center gap-8 reveal-on-scroll">
        
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            TRANSFORM YOUR DIGITAL PRESENCE
        </span>

        <h2 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
            Ready to Transform <span class="text-gradient-blue">Your Business?</span>
        </h2>
        
        <p class="text-slate-300 text-lg sm:text-xl max-w-2xl leading-relaxed">
            Partner with Site And Marketing Technologies to build secure, scalable, and innovative digital solutions that help your business grow faster.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
            <a href="contact.php" class="btn-gradient-blue px-8 py-4 rounded-full font-heading font-bold text-white text-base flex items-center justify-center gap-3 group">
                <span>GET YOUR WEBSITE NOW</span>
                <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <a href="contact.php" class="px-8 py-4 rounded-full font-heading font-bold text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all text-base flex items-center justify-center gap-2 backdrop-blur-md">
                <span>Contact Our Experts</span>
                <i class="fa-solid fa-user-gear text-xs text-blue-400"></i>
            </a>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.getElementById('blog-newsletter-form');
    const successMsg = document.getElementById('newsletter-success');
    if (newsletterForm && successMsg) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            successMsg.classList.remove('hidden');
            newsletterForm.reset();
            setTimeout(() => {
                successMsg.classList.add('hidden');
            }, 5000);
        });
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
