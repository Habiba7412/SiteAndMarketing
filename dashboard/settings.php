<?php
require_once __DIR__ . '/../includes/db.php';

$success = '';
$error = '';

// Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_settings') {
    $keys = [
        'company_name',
        'site_logo',
        'office_address',
        'phone_number',
        'support_phone',
        'support_email',
        'sales_email',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'github_url',
        'consultation_btn_link'
    ];
    
    // File upload for website logo
    if (isset($_FILES['site_logo_file']) && $_FILES['site_logo_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['site_logo_file']['tmp_name'];
        $fileName = $_FILES['site_logo_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = __DIR__ . '/../uploads/logo/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFileName = 'site_logo_' . time() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $_POST['site_logo'] = 'uploads/logo/' . $newFileName;
            }
        }
    }

    try {
        $pdo->beginTransaction();
        
        // Upsert database values
        $stmt = $pdo->prepare("INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `setting_value` = ?");
        foreach ($keys as $key) {
            $value = trim($_POST[$key] ?? '');
            $stmt->execute([$key, $value, $value]);
        }
        
        $pdo->commit();
        $success = "Site settings configured successfully!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error updating configurations: " . $e->getMessage();
    }
}

// Fetch current site settings
try {
    $settings = getSiteSettings($pdo);
} catch (PDOException $e) {
    $settings = [];
}

require_once __DIR__ . '/header.php';
?>

    <div class="flex flex-col gap-6">
        <div>
            <h2 class="font-heading font-extrabold text-2xl text-white">Global Site Settings</h2>
            <p class="text-xs text-slate-400 mt-1">Configure company logo, identifiers, contact links, address items, and social network handles.</p>
        </div>

        <?php if (!empty($success)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Success", "<?php echo $success; ?>", "success"));</script>
        <?php elseif (!empty($error)): ?>
            <script>document.addEventListener('DOMContentLoaded', () => showToast("Error", "<?php echo $error; ?>", "error"));</script>
        <?php endif; ?>

        <form action="settings.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8 max-w-4xl">
            <input type="hidden" name="action" value="update_settings">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Column 1: Brand Logo & Contact Info -->
                <div class="flex flex-col gap-6">
                    <!-- Website Brand Logo Setup Card -->
                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-image text-xs text-brand-accent"></i>
                            <span>Website Logo Setup</span>
                        </h3>

                        <!-- Current Logo Preview -->
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-brand-dark/50 border border-slate-800/60">
                            <div class="w-24 h-14 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-center p-2 overflow-hidden shrink-0">
                                <?php if (!empty($settings['site_logo'])): ?>
                                    <img id="logo-preview-img" src="../<?php echo htmlspecialchars($settings['site_logo']); ?>" alt="Current Logo" class="max-h-full w-auto object-contain">
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-500 font-bold uppercase">No Logo</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white block">Header & Footer Logo</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Upload a PNG, SVG, JPG or WebP image.</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Upload Logo Image File</label>
                            <input type="file" name="site_logo_file" accept="image/*" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2 text-xs text-slate-300 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-accent file:text-brand-dark hover:file:bg-cyan-400 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">OR Image URL (Optional)</label>
                            <input type="text" name="site_logo" value="<?php echo htmlspecialchars($settings['site_logo'] ?? ''); ?>" placeholder="e.g. uploads/logo/mylogo.png or https://..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-briefcase text-xs text-brand-accent"></i>
                            <span>General Information</span>
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Company Name *</label>
                            <input type="text" name="company_name" value="<?php echo htmlspecialchars($settings['company_name'] ?? 'Site And Marketing Technologies'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Free Consultation Redirect URI</label>
                            <input type="text" name="consultation_btn_link" value="<?php echo htmlspecialchars($settings['consultation_btn_link'] ?? 'contact.php'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-xs text-emerald-400"></i>
                            <span>Contact Desk</span>
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Office Address *</label>
                            <textarea name="office_address" rows="2" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($settings['office_address'] ?? 'Islamabad, Pakistan'); ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Primary Phone *</label>
                                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($settings['phone_number'] ?? '00923199564230'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Support Phone</label>
                                <input type="text" name="support_phone" value="<?php echo htmlspecialchars($settings['support_phone'] ?? '00923199564230'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Support Email *</label>
                                <input type="email" name="support_email" value="<?php echo htmlspecialchars($settings['support_email'] ?? 'info@siteandmarketing.com'); ?>" required class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Sales Email</label>
                                <input type="email" name="sales_email" value="<?php echo htmlspecialchars($settings['sales_email'] ?? 'info@siteandmarketing.com'); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Social Media Links -->
                <div class="flex flex-col gap-6">
                    <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                        <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-share-nodes text-xs text-sky-400"></i>
                            <span>Social Media Integrations</span>
                        </h3>
                        
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Facebook Page URL</label>
                            <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" placeholder="https://facebook.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Twitter / X Handle URL</label>
                            <input type="url" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" placeholder="https://twitter.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">LinkedIn Profile / Page URL</label>
                            <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">GitHub Repository URL</label>
                            <input type="url" name="github_url" value="<?php echo htmlspecialchars($settings['github_url'] ?? ''); ?>" placeholder="https://github.com/..." class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-brand-accent via-blue-500 to-sky-400 rounded-xl font-heading font-bold text-xs text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Save Global Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
