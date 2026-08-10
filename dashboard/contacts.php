<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';

$action = trim($_GET['action'] ?? '');
$id = intval($_GET['id'] ?? 0);

// Process Actions
if ($id > 0 && !empty($action)) {
    try {
        if ($action === 'read') {
            $stmt = $pdo->prepare("UPDATE `contact_submissions` SET `is_read` = 1 WHERE `id` = ?");
            $stmt->execute([$id]);
            $success = "Message marked as read.";
        } elseif ($action === 'unread') {
            $stmt = $pdo->prepare("UPDATE `contact_submissions` SET `is_read` = 0 WHERE `id` = ?");
            $stmt->execute([$id]);
            $success = "Message marked as unread.";
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM `contact_submissions` WHERE `id` = ?");
            $stmt->execute([$id]);
            $success = "Message log deleted successfully.";
        }
    } catch (PDOException $e) {
        $error = "Action error: " . $e->getMessage();
    }
}

// Fetch single message details if requested
$viewMsg = null;
$viewId = intval($_GET['view'] ?? 0);
if ($viewId > 0) {
    try {
        // Auto mark as read when viewed
        $readStmt = $pdo->prepare("UPDATE `contact_submissions` SET `is_read` = 1 WHERE `id` = ?");
        $readStmt->execute([$viewId]);
        
        $stmt = $pdo->prepare("SELECT * FROM `contact_submissions` WHERE `id` = ?");
        $stmt->execute([$viewId]);
        $viewMsg = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Error fetching details: " . $e->getMessage();
    }
}

// Fetch all messages
try {
    $submissions = $pdo->query("SELECT * FROM `contact_submissions` ORDER BY `id` DESC")->fetchAll();
} catch (PDOException $e) {
    $submissions = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div>
            <h2 class="font-heading font-extrabold text-2xl text-white">Contact Submissions</h2>
            <p class="text-xs text-slate-400 mt-1">Review, organize, and manage emails sent by clients via forms.</p>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo $success; ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo $error; ?>", "error"));</script>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Messages Details Viewer (if active) -->
            <?php if ($viewMsg): ?>
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider">Message Details</h3>
                        <a href="contacts.php" class="text-xs text-brand-accent hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Close View</span>
                        </a>
                    </div>
                    
                    <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-6 border-brand-accent/20 bg-brand-card">
                        <!-- Message Info Header -->
                        <div class="border-b border-slate-800 pb-4">
                            <span class="text-[10px] text-slate-500 font-bold block">FROM SENDER</span>
                            <h4 class="font-heading font-bold text-lg text-white mt-1"><?php echo htmlspecialchars($viewMsg['name']); ?></h4>
                            <a href="mailto:<?php echo htmlspecialchars($viewMsg['email']); ?>" class="text-xs text-brand-accent hover:underline flex items-center gap-1.5 mt-1 font-mono">
                                <i class="fa-solid fa-envelope text-[10px]"></i>
                                <span><?php echo htmlspecialchars($viewMsg['email']); ?></span>
                            </a>
                            <span class="text-[10px] text-slate-500 font-semibold block mt-3">Date: <?php echo date('M d, Y H:i:s', strtotime($viewMsg['created_at'])); ?></span>
                        </div>

                        <!-- Subject & Content -->
                        <div>
                            <span class="text-[10px] text-slate-500 font-bold block">SUBJECT</span>
                            <p class="font-heading font-bold text-sm text-white mt-1 leading-relaxed"><?php echo htmlspecialchars($viewMsg['subject']); ?></p>
                            
                            <span class="text-[10px] text-slate-500 font-bold block mt-6">MESSAGE TEXT</span>
                            <div class="p-4 rounded-2xl bg-brand-dark/40 border border-slate-850 mt-1.5 text-xs text-slate-300 leading-relaxed font-mono whitespace-pre-wrap">
                                <?php echo htmlspecialchars($viewMsg['message']); ?>
                            </div>
                        </div>

                        <!-- Quick Control Actions -->
                        <div class="border-t border-slate-800 pt-4 flex flex-wrap gap-2 justify-between">
                            <div class="flex gap-2">
                                <?php if ($viewMsg['is_read'] == 1): ?>
                                    <a href="contacts.php?action=unread&id=<?php echo $viewMsg['id']; ?>" class="px-3 py-1.5 rounded-lg border border-slate-800 text-[10px] font-bold text-slate-400 hover:text-white transition-all flex items-center gap-1">
                                        <i class="fa-solid fa-envelope-open"></i>
                                        <span>Mark Unread</span>
                                    </a>
                                <?php else: ?>
                                    <a href="contacts.php?action=read&id=<?php echo $viewMsg['id']; ?>" class="px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-400 hover:bg-emerald-500/20 transition-all flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Mark Read</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <a href="contacts.php?action=delete&id=<?php echo $viewMsg['id']; ?>" onclick="return confirm('Are you sure you want to delete this message log permanently?')" class="px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-[10px] font-bold text-red-400 hover:bg-red-500/20 transition-all flex items-center gap-1">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Delete Record</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Messages Table Grid -->
            <div class="<?php echo $viewMsg ? 'lg:col-span-8' : 'lg:col-span-12'; ?> flex flex-col gap-4">
                <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider">Inbox Messages</h3>
                
                <div class="dashboard-card rounded-3xl overflow-hidden border border-white/5">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-brand-darker/60 text-slate-400 font-semibold border-b border-slate-800/40 text-xs">
                                    <th class="px-6 py-4">Sender</th>
                                    <th class="px-6 py-4">Subject</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-850">
                                <?php if (empty($submissions)): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-sm">
                                            <i class="fa-solid fa-inbox text-5xl block mb-3 opacity-30"></i>
                                            <span>Your inbox is empty. No messages submitted.</span>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($submissions as $sub): ?>
                                        <tr class="hover:bg-brand-darker/10 transition-colors cursor-pointer <?php echo ($viewId == $sub['id']) ? 'bg-brand-accent/5' : ''; ?>" onclick="window.location.href='contacts.php?view=<?php echo $sub['id']; ?>'">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-white"><?php echo htmlspecialchars($sub['name']); ?></div>
                                                <div class="text-[10px] text-slate-500 font-mono"><?php echo htmlspecialchars($sub['email']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-xs font-semibold text-slate-300 max-w-[200px] truncate">
                                                <?php echo htmlspecialchars($sub['subject']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-400">
                                                <?php echo date('M d, Y H:i', strtotime($sub['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($sub['is_read'] == 0): ?>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-red-500/10 text-red-400 border border-red-500/20">Unread</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Read</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                                <div class="inline-flex gap-2">
                                                    <a href="contacts.php?view=<?php echo $sub['id']; ?>" class="w-8 h-8 rounded-lg bg-slate-800/60 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors" title="View Message">
                                                        <i class="fa-solid fa-eye text-xs"></i>
                                                    </a>
                                                    <a href="contacts.php?action=delete&id=<?php echo $sub['id']; ?>" onclick="return confirm('Are you sure you want to delete this message log?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500/20 flex items-center justify-center text-red-400 transition-colors" title="Delete Message">
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
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
