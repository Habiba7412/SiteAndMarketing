<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$action = trim($_GET['action'] ?? 'list');
$id = intval($_GET['id'] ?? 0);

if (isset($_GET['success'])) {
    $success = trim($_GET['success']);
}

if ($action === 'delete' && $id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM `team_members` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: team.php?success=" . urlencode("Team member deleted successfully."));
        exit();
    } catch (PDOException $e) {
        $error = "Deletion error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $designation = trim($_POST['designation'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $github_url = trim($_POST['github_url'] ?? '');
    $sort_order = intval($_POST['sort_order'] ?? 0);
    $status = trim($_POST['status'] ?? 'published');
    
    if (!empty($name) && !empty($designation)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `team_members` (`name`, `designation`, `bio`, `image_url`, `linkedin_url`, `twitter_url`, `github_url`, `sort_order`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $designation, $bio, $image_url, $linkedin_url, $twitter_url, $github_url, $sort_order, $status]);
                header("Location: team.php?success=" . urlencode("Team member added successfully."));
                exit();
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `team_members` SET `name`=?, `designation`=?, `bio`=?, `image_url`=?, `linkedin_url`=?, `twitter_url`=?, `github_url`=?, `sort_order`=?, `status`=? WHERE `id`=?");
                $stmt->execute([$name, $designation, $bio, $image_url, $linkedin_url, $twitter_url, $github_url, $sort_order, $status, $id]);
                header("Location: team.php?success=" . urlencode("Team member updated successfully."));
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Name and Designation are required.";
    }
}

$editTeam = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `team_members` WHERE `id` = ?");
        $stmt->execute([$id]);
        $editTeam = $stmt->fetch();
        if (!$editTeam) {
            $error = "Team member not found.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

try {
    $teamMembers = $pdo->query("SELECT * FROM `team_members` ORDER BY `sort_order` ASC, `id` DESC")->fetchAll();
} catch (PDOException $e) {
    $teamMembers = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Team Members</h2>
                <p class="text-xs text-slate-400 mt-1">Manage team members and employee profiles.</p>
            </div>
            
            <?php if ($action === 'list'): ?>
                <a href="team.php?action=add" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Team Member</span>
                </a>
            <?php else: ?>
                <a href="team.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all font-heading font-bold text-xs flex items-center gap-1.5">
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

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <div class="dashboard-card p-8 rounded-3xl max-w-3xl border border-white/5 bg-brand-card">
                <h3 class="font-heading font-bold text-lg text-white mb-6"><?php echo ($action === 'edit') ? 'Edit Team Member' : 'Add New Team Member'; ?></h3>
                
                <form action="team.php?action=<?php echo $action; ?><?php echo ($action === 'edit') ? '&id=' . $id : ''; ?>" method="POST" class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Name *</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($editTeam['name'] ?? ''); ?>" required placeholder="e.g. Jane Doe" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Designation *</label>
                            <input type="text" name="designation" value="<?php echo htmlspecialchars($editTeam['designation'] ?? ''); ?>" required placeholder="e.g. Lead Developer" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Profile Image URL</label>
                        <input type="text" name="image_url" value="<?php echo htmlspecialchars($editTeam['image_url'] ?? ''); ?>" placeholder="e.g. https://images.unsplash.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Short Bio</label>
                        <textarea name="bio" rows="3" placeholder="Brief description of the team member..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editTeam['bio'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">LinkedIn URL</label>
                            <input type="text" name="linkedin_url" value="<?php echo htmlspecialchars($editTeam['linkedin_url'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Twitter URL</label>
                            <input type="text" name="twitter_url" value="<?php echo htmlspecialchars($editTeam['twitter_url'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">GitHub URL</label>
                            <input type="text" name="github_url" value="<?php echo htmlspecialchars($editTeam['github_url'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Sort Order</label>
                            <input type="number" name="sort_order" value="<?php echo htmlspecialchars($editTeam['sort_order'] ?? '0'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Publishing Status</label>
                            <select name="status" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="published" <?php echo (isset($editTeam['status']) && $editTeam['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($editTeam['status']) && $editTeam['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-850 mt-2">
                        <a href="team.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white transition-all text-xs font-semibold">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span><?php echo ($action === 'edit') ? 'Update Team Member' : 'Publish Team Member'; ?></span>
                        </button>
                    </div>
                </form>
            </div>
            
        <?php else: ?>
            <div class="dashboard-card rounded-3xl overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-brand-darker/60 text-slate-400 font-semibold border-b border-slate-800/40 text-xs">
                                <th class="px-6 py-4">Image</th>
                                <th class="px-6 py-4">Name & Designation</th>
                                <th class="px-6 py-4">Sort Order</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            <?php if (empty($teamMembers)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500 text-xs">
                                        <i class="fa-solid fa-users text-4xl block mb-2 opacity-30"></i>
                                        <span>No team members added yet.</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($teamMembers as $member): ?>
                                    <tr class="hover:bg-brand-darker/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-slate-800">
                                                <img src="<?php echo htmlspecialchars($member['image_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($member['name']).'&background=random'); ?>" alt="" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white"><?php echo htmlspecialchars($member['name']); ?></div>
                                            <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($member['designation']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-300"><?php echo htmlspecialchars($member['sort_order']); ?></td>
                                        <td class="px-6 py-4">
                                            <?php if ($member['status'] === 'published'): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Published</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="team.php?action=edit&id=<?php echo $member['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Edit Team Member">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <a href="team.php?action=delete&id=<?php echo $member['id']; ?>" onclick="return confirm('Are you sure you want to delete this team member?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Team Member">
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
</div>
<!-- / Content Area -->

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
