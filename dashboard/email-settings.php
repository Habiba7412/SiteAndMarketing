<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$success = '';
$error = '';
$testSuccess = '';
$testError = '';

// Handle Email Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_email_settings') {
    $mail_engine     = trim($_POST['mail_engine'] ?? 'smtp');
    $smtp_host       = trim($_POST['smtp_host'] ?? '');
    $smtp_port       = (int)($_POST['smtp_port'] ?? 587);
    $smtp_encryption = trim($_POST['smtp_encryption'] ?? 'tls');
    $smtp_auth       = isset($_POST['smtp_auth']) ? 1 : 0;
    $smtp_username   = trim($_POST['smtp_username'] ?? '');
    $smtp_password   = trim($_POST['smtp_password'] ?? '');
    $from_name       = trim($_POST['from_name'] ?? '');
    $from_email      = trim($_POST['from_email'] ?? '');
    $admin_email     = trim($_POST['admin_email'] ?? '');
    $is_enabled      = isset($_POST['is_enabled']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("UPDATE `email_settings` SET 
            `mail_engine` = ?, 
            `smtp_host` = ?, 
            `smtp_port` = ?, 
            `smtp_encryption` = ?, 
            `smtp_auth` = ?, 
            `smtp_username` = ?, 
            `smtp_password` = ?, 
            `from_name` = ?, 
            `from_email` = ?, 
            `admin_email` = ?, 
            `is_enabled` = ? 
            WHERE `id` = 1");

        $stmt->execute([
            $mail_engine,
            $smtp_host,
            $smtp_port,
            $smtp_encryption,
            $smtp_auth,
            $smtp_username,
            $smtp_password,
            $from_name,
            $from_email,
            $admin_email,
            $is_enabled
        ]);

        // Also sync site_settings support/sales emails if updated
        if (!empty($from_email)) {
            $stmtSite = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES ('support_email', ?) ON DUPLICATE KEY UPDATE `setting_value` = ?");
            $stmtSite->execute([$from_email, $from_email]);
        }

        $success = "Dynamic Email & SMTP configurations saved successfully to database!";
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Handle Test Email Dispatch
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'send_test_email') {
    $testRecipient = filter_var(trim($_POST['test_recipient'] ?? ''), FILTER_VALIDATE_EMAIL);
    if ($testRecipient) {
        $result = sendTestEmail($pdo, $testRecipient);
        if ($result['success']) {
            $testSuccess = $result['message'];
        } else {
            $testError = $result['message'];
        }
    } else {
        $testError = "Please enter a valid email address for testing.";
    }
}

// Fetch current Email Settings
$emailSettings = getMailSettings($pdo);

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800/60 pb-5">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Dynamic Email System & SMTP Configuration</h2>
                <p class="text-xs text-slate-400 mt-1">Configure database-driven SMTP servers, authentication, sender identity, and admin lead alerts.</p>
            </div>
            
            <!-- Quick Status Badge -->
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-mono font-bold uppercase tracking-wider flex items-center gap-2 <?php echo !empty($emailSettings['is_enabled']) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20'; ?>">
                    <span class="w-2 h-2 rounded-full <?php echo !empty($emailSettings['is_enabled']) ? 'bg-emerald-400 animate-pulse' : 'bg-rose-400'; ?>"></span>
                    <span><?php echo !empty($emailSettings['is_enabled']) ? 'Mailer Active' : 'Mailer Disabled'; ?></span>
                </span>
            </div>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo addslashes($success); ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo addslashes($error); ?>", "error"));</script>
        <?php endif; ?>

        <?php if (!empty($testSuccess)): ?>
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span><strong>Test Success:</strong> <?php echo htmlspecialchars($testSuccess); ?></span>
            </div>
        <?php elseif (!empty($testError)): ?>
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span><strong>Test Result:</strong> <?php echo htmlspecialchars($testError); ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-6xl">

            <!-- MAIN SMTP SETTINGS FORM (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-6">
                <form action="email-settings.php" method="POST" class="flex flex-col gap-6">
                    <input type="hidden" name="action" value="update_email_settings">

                    <!-- Card 1: Mailer Driver & Status -->
                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-brand-accent uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-server"></i>
                            <span>Mailer Protocol & Master Switch</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Mailer Engine *</label>
                                <select name="mail_engine" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                    <option value="smtp" <?php echo ($emailSettings['mail_engine'] === 'smtp') ? 'selected' : ''; ?>>SMTP Server (Recommended)</option>
                                    <option value="mail" <?php echo ($emailSettings['mail_engine'] === 'mail') ? 'selected' : ''; ?>>PHP Native Mail()</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-6 pt-5">
                                <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                                    <input type="checkbox" name="is_enabled" value="1" <?php echo !empty($emailSettings['is_enabled']) ? 'checked' : ''; ?> class="rounded bg-brand-dark border-slate-700 text-brand-accent focus:ring-0">
                                    <span class="font-bold">Enable Outgoing Emails</span>
                                </label>
                                <label class="flex items-center gap-2 text-xs text-slate-300 cursor-pointer">
                                    <input type="checkbox" name="smtp_auth" value="1" <?php echo !empty($emailSettings['smtp_auth']) ? 'checked' : ''; ?> class="rounded bg-brand-dark border-slate-700 text-brand-accent focus:ring-0">
                                    <span>Require Authentication</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: SMTP Connection Configuration -->
                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-emerald-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-key"></i>
                            <span>SMTP Server Credentials</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">SMTP Host *</label>
                                <input type="text" name="smtp_host" value="<?php echo htmlspecialchars($emailSettings['smtp_host'] ?? ''); ?>" placeholder="e.g. smtp.gmail.com or mail.yourdomain.com" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Port *</label>
                                <input type="number" name="smtp_port" value="<?php echo htmlspecialchars($emailSettings['smtp_port'] ?? 587); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Encryption Protocol</label>
                                <select name="smtp_encryption" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                                    <option value="tls" <?php echo ($emailSettings['smtp_encryption'] === 'tls') ? 'selected' : ''; ?>>TLS (Port 587)</option>
                                    <option value="ssl" <?php echo ($emailSettings['smtp_encryption'] === 'ssl') ? 'selected' : ''; ?>>SSL (Port 465)</option>
                                    <option value="none" <?php echo ($emailSettings['smtp_encryption'] === 'none') ? 'selected' : ''; ?>>None (Port 25)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">SMTP Username / Email</label>
                                <input type="text" name="smtp_username" value="<?php echo htmlspecialchars($emailSettings['smtp_username'] ?? ''); ?>" placeholder="user@domain.com" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>

                            <div class="relative">
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">SMTP Password</label>
                                <div class="relative">
                                    <input type="password" id="smtp_password_input" name="smtp_password" value="<?php echo htmlspecialchars($emailSettings['smtp_password'] ?? ''); ?>" placeholder="••••••••••••" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl pl-4 pr-10 py-2.5 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                                    <button type="button" onclick="togglePass()" class="absolute right-3 top-2.5 text-slate-500 hover:text-slate-300">
                                        <i id="pass_icon" class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Sender Identity & Admin Routing -->
                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-sky-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                            <i class="fa-solid fa-envelope-open-text"></i>
                            <span>Sender Identity & Admin Lead Routing</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">From Display Name *</label>
                                <input type="text" name="from_name" value="<?php echo htmlspecialchars($emailSettings['from_name'] ?? 'DigiRare Technologies'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>

                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">From Sender Email *</label>
                                <input type="email" name="from_email" value="<?php echo htmlspecialchars($emailSettings['from_email'] ?? 'digiraremarketing@gmail.com'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Admin Lead Recipient Email * (Where contact forms send alerts)</label>
                            <input type="email" name="admin_email" value="<?php echo htmlspecialchars($emailSettings['admin_email'] ?? 'digiraremarketing@gmail.com'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-7 py-3 rounded-2xl font-heading font-bold text-xs bg-gradient-to-r from-brand-accent to-emerald-400 text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 transition-all flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk text-sm"></i>
                            <span>Save Email Configurations</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- SIDEBAR: LIVE TEST EMAIL TOOL (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Test Mail Card -->
                <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                    <h3 class="font-heading font-bold text-sm text-amber-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Live SMTP Test Tool</span>
                    </h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Test your database SMTP credentials immediately. Enter your email below to receive a live test message.
                    </p>

                    <form action="email-settings.php" method="POST" class="flex flex-col gap-3">
                        <input type="hidden" name="action" value="send_test_email">
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Recipient Test Email</label>
                            <input type="email" name="test_recipient" value="<?php echo htmlspecialchars($_SESSION['admin_email'] ?? $emailSettings['admin_email'] ?? ''); ?>" required placeholder="youremail@domain.com" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-650 focus:outline-none focus:border-brand-accent transition-colors font-mono">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl font-heading font-bold text-xs bg-gradient-to-r from-amber-400 to-orange-500 text-brand-dark hover:shadow-lg transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            <span>Send Verification Email</span>
                        </button>
                    </form>
                </div>

                <!-- SMTP Help Guide Card -->
                <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-3">
                    <h4 class="font-heading font-bold text-xs text-slate-300 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-brand-accent"></i>
                        <span>Quick Setup Presets</span>
                    </h4>
                    <div class="text-[11px] text-slate-400 space-y-2 leading-relaxed">
                        <p><strong class="text-white">Gmail SMTP:</strong> Host: <code class="text-brand-accent">smtp.gmail.com</code> | Port: <code class="text-brand-accent">587</code> | Enc: <code class="text-brand-accent">TLS</code> (Use App Password)</p>
                        <p><strong class="text-white">Outlook / 365:</strong> Host: <code class="text-brand-accent">smtp.office365.com</code> | Port: <code class="text-brand-accent">587</code> | Enc: <code class="text-brand-accent">TLS</code></p>
                        <p><strong class="text-white">cPanel Mail:</strong> Host: <code class="text-brand-accent">mail.yourdomain.com</code> | Port: <code class="text-brand-accent">465</code> | Enc: <code class="text-brand-accent">SSL</code></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('smtp_password_input');
            const icon = document.getElementById('pass_icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

<?php require_once __DIR__ . '/footer.php'; ?>
