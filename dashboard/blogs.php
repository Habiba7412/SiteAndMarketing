<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$action = trim($_GET['action'] ?? 'list');
$id = intval($_GET['id'] ?? 0);

// Check success triggers
if (isset($_GET['success'])) {
    $success = trim($_GET['success']);
}

// Handle deletions
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `blogs` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: blogs.php?success=" . urlencode("Blog post deleted successfully."));
        exit();
    } catch (PDOException $e) {
        $error = "Deletion error: " . $e->getMessage();
    }
}

// Helper function to create slugs in PHP
function createSlug($string) {
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return strtolower(trim($slug, '-'));
}

// Handle Add/Edit Form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $author = trim($_POST['author'] ?? 'Admin');
    $status = trim($_POST['status'] ?? 'published');
    $seo_title = trim($_POST['seo_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    
    if (empty($slug)) {
        $slug = createSlug($title);
    } else {
        $slug = createSlug($slug);
    }
    
    if (empty($excerpt)) {
        $excerpt = substr(strip_tags($content), 0, 150) . '...';
    }

    if (empty($seo_title)) {
        $seo_title = $title;
    }
    if (empty($meta_description)) {
        $meta_description = $excerpt;
    }
    
    if (!empty($title) && !empty($category) && !empty($image_url) && !empty($content)) {
        try {
            if ($action === 'add') {
                // Ensure slug is unique
                $chk = $pdo->prepare("SELECT COUNT(*) FROM `blogs` WHERE `slug` = ?");
                $chk->execute([$slug]);
                if ($chk->fetchColumn() > 0) {
                    $slug .= '-' . time();
                }
                
                $stmt = $pdo->prepare("INSERT INTO `blogs` (`title`, `slug`, `category`, `image_url`, `excerpt`, `content`, `author`, `status`, `seo_title`, `meta_description`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $category, $image_url, $excerpt, $content, $author, $status, $seo_title, $meta_description]);
                header("Location: blogs.php?success=" . urlencode("Blog post published successfully."));
                exit();
            } elseif ($action === 'edit' && $id > 0) {
                // Ensure slug is unique for other posts
                $chk = $pdo->prepare("SELECT COUNT(*) FROM `blogs` WHERE `slug` = ? AND `id` != ?");
                $chk->execute([$slug, $id]);
                if ($chk->fetchColumn() > 0) {
                    $slug .= '-' . time();
                }
                
                $stmt = $pdo->prepare("UPDATE `blogs` SET `title` = ?, `slug` = ?, `category` = ?, `image_url` = ?, `excerpt` = ?, `content` = ?, `author` = ?, `status` = ?, `seo_title` = ?, `meta_description` = ? WHERE `id` = ?");
                $stmt->execute([$title, $slug, $category, $image_url, $excerpt, $content, $author, $status, $seo_title, $meta_description, $id]);
                header("Location: blogs.php?success=" . urlencode("Blog post updated successfully."));
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Blog Title, Category, Image URL, and Content are required.";
    }
}

// Fetch single blog for edit view
$editBlog = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `blogs` WHERE `id` = ?");
        $stmt->execute([$id]);
        $editBlog = $stmt->fetch();
        if (!$editBlog) {
            $error = "Blog record not found.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch all blogs
try {
    $blogs = $pdo->query("SELECT * FROM `blogs` ORDER BY `created_at` DESC, `id` DESC")->fetchAll();
} catch (PDOException $e) {
    $blogs = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Manage Blog Posts</h2>
                <p class="text-xs text-slate-400 mt-1">Configure and manage articles displayed on your dynamic blog listing page.</p>
            </div>
            
            <?php if ($action === 'list'): ?>
                <a href="blogs.php?action=add" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Post</span>
                </a>
            <?php else: ?>
                <a href="blogs.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all font-heading font-bold text-xs flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>Back to List</span>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo $success; ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo $error; ?>", "error"));</script>
        <?php endif; ?>

        <!-- ADD/EDIT FORM STATE -->
        <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="dashboard-card p-8 rounded-3xl max-w-3xl border border-white/5 bg-brand-card">
                <h3 class="font-heading font-bold text-lg text-white mb-6"><?php echo ($action === 'edit') ? 'Edit Blog Post' : 'Write New Blog Post'; ?></h3>
                
                <form action="blogs.php?action=<?php echo $action; ?><?php echo ($action === 'edit') ? '&id=' . $id : ''; ?>" method="POST" class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Blog Title *</label>
                            <input type="text" name="title" id="blog-title" value="<?php echo htmlspecialchars($editBlog['title'] ?? ''); ?>" required placeholder="e.g. The Future of Kubernetes in 2026" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Slug URL (Optional)</label>
                            <input type="text" name="slug" id="blog-slug" value="<?php echo htmlspecialchars($editBlog['slug'] ?? ''); ?>" placeholder="Auto-generated if left blank" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                            <span class="text-[9px] text-slate-500 mt-1 block">Friendly URL format: <code class="font-mono text-brand-accent">future-of-kubernetes-2026</code></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Category *</label>
                            <input type="text" name="category" value="<?php echo htmlspecialchars($editBlog['category'] ?? ''); ?>" required placeholder="e.g. Technology" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Author *</label>
                            <input type="text" name="author" value="<?php echo htmlspecialchars($editBlog['author'] ?? 'Admin'); ?>" required placeholder="e.g. Sarah Jenkins" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Publishing Status</label>
                            <select name="status" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="published" <?php echo (isset($editBlog['status']) && $editBlog['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($editBlog['status']) && $editBlog['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Showcase Image URL *</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($editBlog['image_url'] ?? ''); ?>" required placeholder="e.g. https://images.unsplash.com/photo-..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">SEO Meta Title (Optional)</label>
                            <input type="text" name="seo_title" value="<?php echo htmlspecialchars($editBlog['seo_title'] ?? ''); ?>" placeholder="Custom title for Google search results" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">SEO Meta Description (Optional)</label>
                            <input type="text" name="meta_description" value="<?php echo htmlspecialchars($editBlog['meta_description'] ?? ''); ?>" placeholder="Search engines snippet summary" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Excerpt (Brief Summary)</label>
                        <textarea name="excerpt" rows="2" placeholder="Brief tagline shown on blog listing summaries. If left blank, it clips the main content." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editBlog['excerpt'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Main Blog Content *</label>
                        <textarea name="content" rows="8" required placeholder="Write the full body content here..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editBlog['content'] ?? ''); ?></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-850 mt-2">
                        <a href="blogs.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white transition-all text-xs font-semibold">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span><?php echo ($action === 'edit') ? 'Update Post' : 'Publish Post'; ?></span>
                        </button>
                    </div>
                </form>
            </div>
            
            <script>
                // Interactive JavaScript Slug generator
                const titleInput = document.getElementById('blog-title');
                const slugInput = document.getElementById('blog-slug');
                
                if (titleInput && slugInput) {
                    titleInput.addEventListener('input', () => {
                        if (slugInput.value === '' || titleInput.dataset.slugEdited === 'false') {
                            const titleValue = titleInput.value;
                            const slugValue = titleValue
                                .toLowerCase()
                                .replace(/[^a-z0-9\s-]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/-+/g, '-');
                            slugInput.value = slugValue;
                        }
                    });
                }
            </script>
            
        <!-- LISTING STATE -->
        <?php else: ?>
            <div class="dashboard-card rounded-3xl overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-brand-darker/60 text-slate-400 font-semibold border-b border-slate-800/40 text-xs">
                                <th class="px-6 py-4">Preview</th>
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Author</th>
                                <th class="px-6 py-4">Publish Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            <?php if (empty($blogs)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-500 text-xs">
                                        <i class="fa-solid fa-blog text-4xl block mb-2 opacity-30"></i>
                                        <span>No blog posts in database yet. Click Add to write one.</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($blogs as $b): ?>
                                    <tr class="hover:bg-brand-darker/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="w-12 h-10 rounded-lg overflow-hidden shrink-0 border border-slate-800">
                                                <img src="<?php echo htmlspecialchars($b['image_url']); ?>" alt="" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-white"><?php echo htmlspecialchars($b['title']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded bg-slate-800 text-[10px] text-slate-300 font-semibold"><?php echo htmlspecialchars($b['category']); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400"><?php echo htmlspecialchars($b['author']); ?></td>
                                        <td class="px-6 py-4 text-xs text-slate-400">
                                            <?php echo date('M d, Y', strtotime($b['created_at'])); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($b['status'] === 'published'): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Published</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="blogs.php?action=edit&id=<?php echo $b['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Edit Post">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <a href="blogs.php?action=delete&id=<?php echo $b['id']; ?>" onclick="return confirm('Are you sure you want to delete this blog post permanently?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Post">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
