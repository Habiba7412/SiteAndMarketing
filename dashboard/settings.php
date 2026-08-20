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
        'consultation_btn_link',
        'hero_sub_heading',
        'hero_headline',
        'hero_description',
        'hero_btn_text_1',
        'hero_btn_url_1',
        'hero_btn_text_2',
        'hero_btn_url_2',
        'hero_supporting_text',
        'what_we_build_heading',
        'what_we_build_desc',
        'what_we_build'
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
            </div>

            <div class="grid grid-cols-1 gap-8 mt-4">
                <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                    <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-home text-xs text-brand-accent"></i>
                        <span>Homepage Hero Configuration</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Hero Eyebrow (Sub-heading)</label>
                            <input type="text" name="hero_sub_heading" value="<?php echo htmlspecialchars($settings['hero_sub_heading'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Hero Headline (H1)</label>
                            <input type="text" name="hero_headline" value="<?php echo htmlspecialchars($settings['hero_headline'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Hero Description</label>
                        <textarea name="hero_description" rows="2" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-none"><?php echo htmlspecialchars($settings['hero_description'] ?? ''); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-4 p-4 border border-slate-800/50 rounded-xl">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-brand-accent">Primary Button</span>
                            <input type="text" name="hero_btn_text_1" value="<?php echo htmlspecialchars($settings['hero_btn_text_1'] ?? ''); ?>" placeholder="Button Text" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            <input type="text" name="hero_btn_url_1" value="<?php echo htmlspecialchars($settings['hero_btn_url_1'] ?? ''); ?>" placeholder="URL Link" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div class="flex flex-col gap-4 p-4 border border-slate-800/50 rounded-xl">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Secondary Button</span>
                            <input type="text" name="hero_btn_text_2" value="<?php echo htmlspecialchars($settings['hero_btn_text_2'] ?? ''); ?>" placeholder="Button Text" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                            <input type="text" name="hero_btn_url_2" value="<?php echo htmlspecialchars($settings['hero_btn_url_2'] ?? ''); ?>" placeholder="URL Link" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Supporting Text (Below Buttons)</label>
                        <input type="text" name="hero_supporting_text" value="<?php echo htmlspecialchars($settings['hero_supporting_text'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                    </div>
                </div>

                <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                    <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-xs text-emerald-400"></i>
                        <span>What We Build Section</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Section Heading</label>
                            <input type="text" name="what_we_build_heading" value="<?php echo htmlspecialchars($settings['what_we_build_heading'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Section Description</label>
                            <input type="text" name="what_we_build_desc" value="<?php echo htmlspecialchars($settings['what_we_build_desc'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Cards Configuration (JSON Array format)</label>
                        <?php
                        $defaultWhatWeBuild = json_encode([
                            ['number' => '01', 'icon' => 'fa-globe', 'title' => 'Business Websites', 'description' => 'Professional websites designed to establish your brand, communicate your value and generate qualified leads.', 'link' => 'services.php'],
                            ['number' => '02', 'icon' => 'fa-laptop-code', 'title' => 'Custom Web Applications', 'description' => 'Tailor-made applications built around your unique business processes, workflows and requirements.', 'link' => 'services.php'],
                            ['number' => '03', 'icon' => 'fa-table-columns', 'title' => 'Admin Dashboards & CMS', 'description' => 'Powerful dashboards and content management systems that give you complete control over your website and business data.', 'link' => 'services.php'],
                            ['number' => '04', 'icon' => 'fa-cart-shopping', 'title' => 'E-Commerce Solutions', 'description' => 'Scalable online stores with product management, secure checkout, payment integration and order management.', 'link' => 'services.php'],
                            ['number' => '05', 'icon' => 'fa-wand-magic-sparkles', 'title' => 'Website Redesign', 'description' => 'Modernize outdated websites with better design, responsiveness, usability, performance and functionality.', 'link' => 'services.php'],
                            ['number' => '06', 'icon' => 'fa-gears', 'title' => 'Business Automation', 'description' => 'Digital tools and integrations that help businesses reduce manual work and improve operational efficiency.', 'link' => 'services.php']
                        ], JSON_PRETTY_PRINT);
                        ?>
                        <textarea name="what_we_build" rows="10" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-300 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-y"><?php echo htmlspecialchars($settings['what_we_build'] ?? $defaultWhatWeBuild); ?></textarea>
                    </div>
                </div>
                
                <div class="dashboard-card p-6 rounded-3xl border border-white/5 bg-brand-card flex flex-col gap-4">
                    <h3 class="font-heading font-bold text-sm text-slate-400 uppercase tracking-wider border-b border-slate-800/40 pb-2 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs text-sky-400"></i>
                        <span>Dynamic Homepage Sections</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Testimonials Heading</label>
                            <input type="text" name="testimonial_heading" value="<?php echo htmlspecialchars($settings['testimonial_heading'] ?? ''); ?>" placeholder="What Our Clients Say" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Client Logos (JSON Array of Objects)</label>
                            <input type="text" name="client_logos" value="<?php echo htmlspecialchars($settings['client_logos'] ?? ''); ?>" placeholder='[{"name":"", "image_url":""}]' class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-300 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Checklist Title</label>
                            <input type="text" name="checklist_title" value="<?php echo htmlspecialchars($settings['checklist_title'] ?? ''); ?>" placeholder="Modern Technology Services" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Checklist Description</label>
                            <input type="text" name="checklist_desc" value="<?php echo htmlspecialchars($settings['checklist_desc'] ?? ''); ?>" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs text-slate-100 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Marquee Texts (JSON Array)</label>
                        <textarea name="marquee_texts" rows="3" placeholder='["Service 1", "Service 2"]' class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-300 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-y"><?php echo htmlspecialchars($settings['marquee_texts'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] uppercase font-bold tracking-wider text-slate-400 mb-1.5">Checklist Items (JSON Array of Objects)</label>
                        <?php
                        $defaultChecklist = json_encode([
                            ['title' => 'Agile Software Development', 'description' => 'Custom web applications and enterprise software built using Agile methodologies.', 'icon' => 'fa-rocket'],
                            ['title' => 'Cloud Infrastructure', 'description' => 'Secure, scalable infrastructures using AWS, Azure, GCP.', 'icon' => 'fa-cloud'],
                            ['title' => 'Cybersecurity', 'description' => 'Threat detection, encryption, and proactive monitoring to keep systems safe.', 'icon' => 'fa-shield-halved']
                        ], JSON_PRETTY_PRINT);
                        ?>
                        <textarea name="checklist_items" rows="6" class="w-full bg-brand-dark/50 border border-slate-800/60 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-300 placeholder-slate-600 focus:outline-none focus:border-brand-accent transition-colors resize-y"><?php echo htmlspecialchars($settings['checklist_items'] ?? $defaultChecklist); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-brand-accent via-blue-500 to-sky-400 rounded-xl font-heading font-bold text-xs text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Save Global Settings
                </button>
            </div>
        </form>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
