<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';
$adminId = intval($_SESSION['admin_id'] ?? 0);

if ($adminId > 0) {
    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (!empty($name) && !empty($email) && !empty($username) && !empty($current_password)) {
            try {
                // Verify existing password
                $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ?");
                $stmt->execute([$adminId]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($current_password, $user['password'])) {
                    // Verify username uniqueness
                    $chk = $pdo->prepare("SELECT COUNT(*) FROM `users` WHERE `username` = ? AND `id` != ?");
                    $chk->execute([$username, $adminId]);
                    
                    if ($chk->fetchColumn() == 0) {
                        $query = "UPDATE `users` SET `name` = ?, `email` = ?, `username` = ?";
                        $params = [$name, $email, $username];
                        
                        // Handle changing password if requested
                        if (!empty($new_password)) {
                            if ($new_password === $confirm_password) {
                                if (strlen($new_password) >= 6) {
                                    $query .= ", `password` = ?";
                                    $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                                } else {
                                    $error = "New password must be at least 6 characters long.";
                                }
                            } else {
                                $error = "New password and password confirmation do not match.";
                            }
                        }
                        
                        if (empty($error)) {
                            $query .= " WHERE `id` = ?";
                            $params[] = $adminId;
                            
                            $updStmt = $pdo->prepare($query);
                            $updStmt->execute($params);
                            
                            // Re-bind session
                            $_SESSION['admin_name'] = $name;
                            $_SESSION['admin_username'] = $username;
                            
                            $success = "Profile credentials updated successfully!";
                        }
                    } else {
                        $error = "Username has already been claimed by another administrator.";
                    }
                } else {
                    $error = "Incorrect current password verification.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "All fields except new password are required.";
        }
    }
    
    // Retrieve admin record
    try {
        $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $stmt->execute([$adminId]);
        $adminUser = $stmt->fetch();
    } catch (PDOException $e) {
        $adminUser = [];
    }
} else {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div>
            <h2 class="font-heading font-extrabold text-2xl text-white">Admin Profile settings</h2>
            <p class="text-xs text-slate-400 mt-1">Configure administrator login details, display names, and password credentials.</p>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo $success; ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo $error; ?>", "error"));</script>
        <?php endif; ?>

        <div class="dashboard-card p-8 rounded-3xl max-w-2xl border border-white/5 bg-brand-card">
            <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-xs text-brand-accent"></i>
                <span>Profile Credentials</span>
            </h3>

            <form action="profile.php" method="POST" class="flex flex-col gap-6">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Display Name *</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($adminUser['name'] ?? ''); ?>" required placeholder="e.g. SiteAndMarketing CEO" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Email Address *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($adminUser['email'] ?? ''); ?>" required placeholder="e.g. admin@teckko.com" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Login Username *</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($adminUser['username'] ?? ''); ?>" required placeholder="e.g. admin" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                </div>

                <div class="h-px bg-slate-850/60 my-2"></div>
                <h4 class="font-heading font-bold text-xs text-slate-400 uppercase tracking-wide">Change Password (Leave blank to keep current)</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">New Password</label>
                        <input type="password" name="new_password" placeholder="••••••••" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Confirm New Password</label>
                        <input type="password" name="confirm_password" placeholder="••••••••" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>
                </div>

                <div class="h-px bg-slate-850/60 my-2"></div>
                
                <div>
                    <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-2">Current Password (Required to verify updates) *</label>
                    <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-3 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors">
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-850">
                    <button type="submit" class="px-6 py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-user-check text-[10px]"></i>
                        <span>Save Profile Details</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
