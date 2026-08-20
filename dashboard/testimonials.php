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
        $stmt = $pdo->prepare("DELETE FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
        header("Location: testimonials.php?success=" . urlencode("Testimonial deleted successfully."));
        exit();
    } catch (PDOException $e) {
        $error = "Deletion error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);
    $review = trim($_POST['review'] ?? '');
    $status = trim($_POST['status'] ?? 'published');
    
    if (!empty($client_name) && !empty($review)) {
        try {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO `testimonials` (`client_name`, `company`, `image_url`, `rating`, `review`, `status`) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$client_name, $company, $image_url, $rating, $review, $status]);
                header("Location: testimonials.php?success=" . urlencode("Testimonial added successfully."));
                exit();
            } elseif ($action === 'edit' && $id > 0) {
                $stmt = $pdo->prepare("UPDATE `testimonials` SET `client_name`=?, `company`=?, `image_url`=?, `rating`=?, `review`=?, `status`=? WHERE `id`=?");
                $stmt->execute([$client_name, $company, $image_url, $rating, $review, $status, $id]);
                header("Location: testimonials.php?success=" . urlencode("Testimonial updated successfully."));
                exit();
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Client Name and Review are required.";
    }
}

$editTest = null;
if ($action === 'edit' && $id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `testimonials` WHERE `id` = ?");
        $stmt->execute([$id]);
        $editTest = $stmt->fetch();
        if (!$editTest) {
            $error = "Testimonial not found.";
            $action = 'list';
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

try {
    $testimonials = $pdo->query("SELECT * FROM `testimonials` ORDER BY `id` DESC")->fetchAll();
} catch (PDOException $e) {
    $testimonials = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Testimonials</h2>
                <p class="text-xs text-slate-400 mt-1">Manage client testimonials and reviews.</p>
            </div>
            
            <?php if ($action === 'list'): ?>
                <a href="testimonials.php?action=add" class="px-5 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-sky-500 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Add New Testimonial</span>
                </a>
            <?php else: ?>
                <a href="testimonials.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all font-heading font-bold text-xs flex items-center gap-1.5">
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
                <h3 class="font-heading font-bold text-lg text-white mb-6"><?php echo ($action === 'edit') ? 'Edit Testimonial' : 'Add New Testimonial'; ?></h3>
                
                <form action="testimonials.php?action=<?php echo $action; ?><?php echo ($action === 'edit') ? '&id=' . $id : ''; ?>" method="POST" class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Client Name *</label>
                            <input type="text" name="client_name" value="<?php echo htmlspecialchars($editTest['client_name'] ?? ''); ?>" required placeholder="e.g. John Doe" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Company / Position</label>
                            <input type="text" name="company" value="<?php echo htmlspecialchars($editTest['company'] ?? ''); ?>" placeholder="e.g. CEO at Acme Corp" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Profile Image URL</label>
                            <input type="text" name="image_url" value="<?php echo htmlspecialchars($editTest['image_url'] ?? ''); ?>" placeholder="e.g. https://images.unsplash.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" value="<?php echo htmlspecialchars($editTest['rating'] ?? '5'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Review *</label>
                        <textarea name="review" rows="4" required placeholder="Write the testimonial here..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($editTest['review'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Publishing Status</label>
                            <select name="status" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                <option value="published" <?php echo (isset($editTest['status']) && $editTest['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                                <option value="draft" <?php echo (isset($editTest['status']) && $editTest['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-850 mt-2">
                        <a href="testimonials.php" class="px-5 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white transition-all text-xs font-semibold">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check text-[10px]"></i>
                            <span><?php echo ($action === 'edit') ? 'Update Testimonial' : 'Publish Testimonial'; ?></span>
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
                                <th class="px-6 py-4">Client</th>
                                <th class="px-6 py-4">Rating</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-850">
                            <?php if (empty($testimonials)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500 text-xs">
                                        <i class="fa-solid fa-comments text-4xl block mb-2 opacity-30"></i>
                                        <span>No testimonials added yet.</span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($testimonials as $test): ?>
                                    <tr class="hover:bg-brand-darker/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-slate-800">
                                                <img src="<?php echo htmlspecialchars($test['image_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($test['client_name']).'&background=0D8ABC&color=fff'); ?>" alt="" class="w-full h-full object-cover">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white"><?php echo htmlspecialchars($test['client_name']); ?></div>
                                            <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($test['company']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-300">
                                            <?php for($i=0; $i<$test['rating']; $i++) echo '<i class="fa-solid fa-star text-yellow-500 text-[10px]"></i>'; ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if ($test['status'] === 'published'): ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Published</span>
                                            <?php else: ?>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="testimonials.php?action=edit&id=<?php echo $test['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="Edit Testimonial">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <a href="testimonials.php?action=delete&id=<?php echo $test['id']; ?>" onclick="return confirm('Are you sure you want to delete this testimonial?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Testimonial">
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
