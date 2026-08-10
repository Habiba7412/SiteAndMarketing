<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$action = trim($_GET['action'] ?? 'list');
$id = intval($_GET['id'] ?? 0);

// Check URL success redirect triggers
if (isset($_GET['success'])) {
    $success = trim($_GET['success']);
}

// Handle deletions
if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `services` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: services.php?success=" . urlencode("Service deleted successfully."));
        exit();
    } catch (PDOException $e) {
        $error = "Deletion error: " . $e->getMessage();
    }
}

// Handle Add/Edit Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-solid fa-code');
    $description = trim($_POST['description'] ?? '');
    $long_description = trim($_POST['long_description'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    
    if (!empty($title) && !empty($description)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `services` (`title`, `icon`, `description`, `long_description`, `status`) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $icon, $description, $long_description, $status]);
                header("Location: services.php?success=" . urlencode("Service created successfully."));
                exit();
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `services` SET `title` = ?, `icon` = ?, `description` = ?, `long_description` = ?, `status` = ? WHERE `id` = ?");
                $stmt->execute([$title, $icon, $description, $long_description, $status, $id]);
                header("Location: services.php?success=" . urlencode("Service updated successfully."));
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Service Title and Short Description are required.";
    }
}

// Fetch single service for editing
$editSvc = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `services` WHERE `id` = ?");
        $stmt->execute([$id]);
        $editSvc = $stmt->fetch();
        if (!$editSvc) {
            $error = "Selected service record not found.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch all services
try {
    $services = $pdo->query("SELECT * FROM `services` ORDER BY `id` ASC")->fetchAll();
} catch (PDOException $e) {
    $services = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Manage IT Services</h2>
                <p class="text-xs text-slate-400 mt-1">Configure and manage services shown on your homepage and service columns.</p>
            </div>
            
            <?php if ($action === 'list'): ?>
                <a href="services.php?action=add" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Service</span>
                </a>
            <?php else: ?>
                <a href="services.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all font-heading font-bold text-xs flex items-center gap-1.5">
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

        <!-- EDIT or ADD FORM STATE -->
        <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="dashboard-card p-8 rounded-3xl max-w-3xl border border-white/5 bg-brand-card">
                <h3 class="font-heading font-bold text-lg text-white mb-6"><?php echo ($action === 'edit') ? 'Edit Service' : 'Create New Service'; ?></h3>
                
                <form action="services.php?action=<?php echo $action; ?><?php echo ($action === 'edit') ? '&id=' . $id : ''; ?>" method="POST" class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Service Title *</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($editSvc['title'] ?? ''); ?>" required placeholder="e.g. Software Development" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Icon FontAwesome Class</label>
                            <input type="text" name="icon" value="<?php echo htmlspecialchars($editSvc['icon'] ?? 'fa-solid fa-code'); ?>" placeholder="e.g. fa-solid fa-shield-halved" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                            <span class="text-[9px] text-slate-500 mt-1 block">Specify FontAwesome prefix classes. Example: <code class="font-mono text-brand-accent">fa-solid fa-code</code></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Short Description *</label>
                        <textarea name="description" rows="3" required placeholder="A brief summary (1-2 sentences) of this service to display on grids." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editSvc['description'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Long Description / Details</label>
                        <textarea name="long_description" rows="5" placeholder="Deep details shown on hover or dedicated service sub-sections." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editSvc['long_description'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Publishing Status</label>
                            <select name="status" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="published" <?php echo (isset($editSvc['status']) && $editSvc['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($editSvc['status']) && $editSvc['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-850 mt-2">
                        <a href="services.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white transition-all text-xs font-semibold">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span><?php echo ($action === 'edit') ? 'Update Service' : 'Create Service'; ?></span>
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
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4">Icon Class</th>
                                <th class="px-6 py-4">Description</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            <?php if (empty($services)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500 text-xs">
                                        <i class="fa-solid fa-gears text-4xl block mb-2 opacity-30"></i>
                                        <span>No services configured in database. Click Add to insert.</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($services as $svc): ?>
                                    <tr class="hover:bg-brand-darker/5 transition-colors">
                                        <td class="px-6 py-4 font-bold text-white"><?php echo htmlspecialchars($svc['title']); ?></td>
                                        <td class="px-6 py-4 text-xs font-mono text-slate-400">
                                            <i class="<?php echo htmlspecialchars($svc['icon']); ?> mr-2 text-brand-accent text-sm"></i>
                                            <span><?php echo htmlspecialchars($svc['icon']); ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-400 max-w-sm truncate" title="<?php echo htmlspecialchars($svc['description']); ?>">
                                            <?php echo htmlspecialchars($svc['description']); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($svc['status'] === 'published'): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Published</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="services.php?action=edit&id=<?php echo $svc['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Edit Service">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <a href="services.php?action=delete&id=<?php echo $svc['id']; ?>" onclick="return confirm('Are you sure you want to delete this service permanently?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Service">
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
