<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$action = trim($_GET['action'] ?? 'list');
$id = intval($_GET['id'] ?? 0);

// Check success parameters
if (isset($_GET['success'])) {
    $success = trim($_GET['success']);
}

// Handle deletions
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `projects` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: projects.php?success=" . urlencode("Project deleted successfully."));
        exit();
    } catch (PDOException $e) {
        $error = "Deletion error: " . $e->getMessage();
    }
}

// Handle Add/Edit Form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $project_type = trim($_POST['project_type'] ?? 'Client Project');
    $category = trim($_POST['category'] ?? '');
    $industry = trim($_POST['industry'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $client = trim($_POST['client'] ?? '');
    $project_date = trim($_POST['project_date'] ?? '');
    $link = trim($_POST['link'] ?? '#');
    $description = trim($_POST['description'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    
    if (empty($project_date)) {
        $project_date = null;
    }
    
    if (!empty($title) && !empty($category) && !empty($image_url) && !empty($description)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `projects` (`title`, `project_type`, `category`, `industry`, `image_url`, `client`, `project_date`, `link`, `description`, `features`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $project_type, $category, $industry, $image_url, $client, $project_date, $link, $description, $features, $status]);
                header("Location: projects.php?success=" . urlencode("Project published successfully."));
                exit();
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `projects` SET `title` = ?, `project_type` = ?, `category` = ?, `industry` = ?, `image_url` = ?, `client` = ?, `project_date` = ?, `link` = ?, `description` = ?, `features` = ?, `status` = ? WHERE `id` = ?");
                $stmt->execute([$title, $project_type, $category, $industry, $image_url, $client, $project_date, $link, $description, $features, $status, $id]);
                header("Location: projects.php?success=" . urlencode("Project updated successfully."));
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Project Title, Category, Image URL, and Description are required.";
    }
}

// Fetch single project for edit view
$editProj = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `projects` WHERE `id` = ?");
        $stmt->execute([$id]);
        $editProj = $stmt->fetch();
        if (!$editProj) {
            $error = "Project record not found.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch all projects
try {
    $projects = $pdo->query("SELECT * FROM `projects` ORDER BY `project_date` DESC, `id` DESC")->fetchAll();
} catch (PDOException $e) {
    $projects = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Case Studies Portfolio</h2>
                <p class="text-xs text-slate-400 mt-1">Configure and manage client project portfolios and case studies.</p>
            </div>
            
            <?php if ($action === 'list'): ?>
                <a href="projects.php?action=add" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Project</span>
                </a>
            <?php else: ?>
                <a href="projects.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all font-heading font-bold text-xs flex items-center gap-1.5">
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
                <h3 class="font-heading font-bold text-lg text-white mb-6"><?php echo ($action === 'edit') ? 'Edit Project' : 'Publish New Project'; ?></h3>
                
                <form action="projects.php?action=<?php echo $action; ?><?php echo ($action === 'edit') ? '&id=' . $id : ''; ?>" method="POST" class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Project Title *</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($editProj['title'] ?? ''); ?>" required placeholder="e.g. ERP Cloud Sync System" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Project Type</label>
                            <select name="project_type" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="Client Project" <?php echo (isset($editProj['project_type']) && $editProj['project_type'] === 'Client Project') ? 'selected' : ''; ?>>Client Project</option>
                                <option value="Featured Demo Project" <?php echo (isset($editProj['project_type']) && $editProj['project_type'] === 'Featured Demo Project') ? 'selected' : ''; ?>>Featured Demo Project</option>
                                <option value="Concept Project" <?php echo (isset($editProj['project_type']) && $editProj['project_type'] === 'Concept Project') ? 'selected' : ''; ?>>Concept Project</option>
                                <option value="Internal Project" <?php echo (isset($editProj['project_type']) && $editProj['project_type'] === 'Internal Project') ? 'selected' : ''; ?>>Internal Project</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Category *</label>
                            <select name="category" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="Software Engineering" <?php echo (isset($editProj['category']) && $editProj['category'] === 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                <option value="UI / UX Design" <?php echo (isset($editProj['category']) && $editProj['category'] === 'UI / UX Design') ? 'selected' : ''; ?>>UI / UX Design</option>
                                <option value="Cloud / DevOps" <?php echo (isset($editProj['category']) && $editProj['category'] === 'Cloud / DevOps') ? 'selected' : ''; ?>>Cloud / DevOps</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Industry</label>
                            <input type="text" name="industry" value="<?php echo htmlspecialchars($editProj['industry'] ?? ''); ?>" placeholder="e.g. Healthcare, E-Commerce" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Client Name</label>
                            <input type="text" name="client" value="<?php echo htmlspecialchars($editProj['client'] ?? ''); ?>" placeholder="e.g. Fintech Ltd" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Project Date</label>
                            <input type="date" name="project_date" value="<?php echo htmlspecialchars($editProj['project_date'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Project Showcase Image URL *</label>
                            <input type="text" name="image_url" value="<?php echo htmlspecialchars($editProj['image_url'] ?? ''); ?>" required placeholder="e.g. https://images.unsplash.com/photo-..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                            <span class="text-[9px] text-slate-500 mt-1 block">Specify an Unsplash address or local path: <code class="font-mono text-brand-accent">assets/images/portfolio1.jpg</code></span>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">External Link URL</label>
                            <input type="text" name="link" value="<?php echo htmlspecialchars($editProj['link'] ?? '#'); ?>" placeholder="e.g. https://github.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Case Study Description *</label>
                        <textarea name="description" rows="4" required placeholder="Write a short summary detailed about this project scope..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editProj['description'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Key Features & Technology (Line separated)</label>
                        <textarea name="features" rows="4" placeholder="Built with React&#10;Stripe Payment Gateway&#10;Real-time dashboard" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editProj['features'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Publishing Status</label>
                            <select name="status" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="published" <?php echo (isset($editProj['status']) && $editProj['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($editProj['status']) && $editProj['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-850 mt-2">
                        <a href="projects.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white transition-all text-xs font-semibold">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span><?php echo ($action === 'edit') ? 'Update Case Study' : 'Publish Case Study'; ?></span>
                        </button>
                    </div>
                </form>
            </div>
            
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
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-500 text-xs">
                                        <i class="fa-solid fa-laptop-code text-4xl block mb-2 opacity-30"></i>
                                        <span>No projects posted in database yet. Click Add to insert.</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($projects as $proj): ?>
                                    <tr class="hover:bg-brand-darker/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="w-12 h-10 rounded-lg overflow-hidden shrink-0 border border-slate-800">
                                                <img src="<?php echo htmlspecialchars($proj['image_url']); ?>" alt="" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-white"><?php echo htmlspecialchars($proj['title']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 rounded bg-slate-800 text-[10px] text-slate-300 font-semibold"><?php echo htmlspecialchars($proj['category']); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400"><?php echo htmlspecialchars($proj['client'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-xs text-slate-400">
                                            <?php echo $proj['project_date'] ? date('M Y', strtotime($proj['project_date'])) : 'N/A'; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($proj['status'] === 'published'): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Published</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="projects.php?action=edit&id=<?php echo $proj['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Edit Project">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <a href="projects.php?action=delete&id=<?php echo $proj['id']; ?>" onclick="return confirm('Are you sure you want to delete this case study permanently?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Project">
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
