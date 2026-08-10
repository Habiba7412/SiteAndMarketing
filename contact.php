<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/models/Setting.php';

$successMsg = '';
$errorMsg = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Contact Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_contact') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errorMsg = "Invalid security token. Please refresh and try again.";
    } else {
        $name    = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

        if (!empty($name) && $email && !empty($subject) && !empty($message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `contact_submissions` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $subject, $message]);
                
                // Dispatch Dynamic Email Alert to Admin Email configured in DB
                $mailSettings = getMailSettings($pdo);
                $adminEmail   = !empty($mailSettings['admin_email']) ? $mailSettings['admin_email'] : 'digiraremarketing@gmail.com';

                $adminSubject = "New Contact Inquiry: {$subject}";
                $adminBody = "
                <div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #0e1a1d; color: #f1f5f9; padding: 24px; border-radius: 12px;\">
                    <h2 style=\"color: #38bdf8; margin-top: 0;\">New Website Contact Lead</h2>
                    <p><strong>Client Name:</strong> {$name}</p>
                    <p><strong>Email Address:</strong> {$email}</p>
                    <p><strong>Subject:</strong> {$subject}</p>
                    <p><strong>Message:</strong></p>
                    <div style=\"background: #070c0e; padding: 16px; border-radius: 8px; border-left: 4px solid #38bdf8;\">
                        " . nl2br($message) . "
                    </div>
                </div>";

                sendDynamicEmail($pdo, $adminEmail, $adminSubject, $adminBody, $email, $name);

                // Dispatch Auto-Thank You Email to Client
                $clientSubject = "Thank You for Contacting DigiRare Technologies";
                $clientBody = "
                <div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #0e1a1d; color: #f1f5f9; padding: 24px; border-radius: 12px;\">
                    <h2 style=\"color: #10b981; margin-top: 0;\">We Have Received Your Message</h2>
                    <p>Dear {$name},</p>
                    <p>Thank you for reaching out to DigiRare Technologies. Our solutions engineering group has received your message and will review it promptly.</p>
                    <p>Expect a response within 24 hours.</p>
                    <p>Best regards,<br><strong>DigiRare Technologies Engineering Team</strong></p>
                </div>";

                sendDynamicEmail($pdo, $email, $clientSubject, $clientBody);

                $successMsg = "Thank you! Your message has been received. We will get back to you within 24 hours.";
            } catch (PDOException $e) {
                $errorMsg = "Database error: " . $e->getMessage();
            }
        } else {
            $errorMsg = "Please fill in all required fields with a valid email address.";
        }
    }
}

// Fetch dynamic contact info from settings
$siteSettings = array_merge([
    'office_address' => 'Islamabad, Pakistan',
    'phone_number'   => '00923199564230',
    'support_phone'  => '00923199564230',
    'support_email'  => 'digiraremarketing@gmail.com',
    'sales_email'    => 'digiraremarketing@gmail.com',
    'map_embed'      => Setting::get('map_embed', ''),
], getSiteSettings($pdo));

include __DIR__ . '/includes/header.php';
?>


    <!-- Breadcrumb / Page Banner Section -->
    <section class="relative bg-brand-darker py-24 border-b border-slate-900 overflow-hidden">
        <div class="glow-bg-emerald top-0 right-10"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center mt-12 flex flex-col gap-4">
            <h1 class="font-heading font-black text-4xl sm:text-5xl text-white">Contact Center</h1>
            <p class="text-brand-accent text-sm font-semibold tracking-wider font-heading uppercase flex items-center justify-center gap-2">
                <a href="index.php" class="hover:underline">Home</a>
                <span>/</span>
                <span class="text-slate-400">Contact Us</span>
            </p>
        </div>
    </section>

    <!-- Contact Cards Grid -->
    <section class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1: Office Address -->
                <div class="glass-panel p-8 rounded-3xl border border-white/5 flex items-start gap-5 hover:border-brand-accent/20 transition-all reveal-on-scroll">
                    <div class="w-12 h-12 rounded-xl bg-brand-accent/15 flex items-center justify-center text-brand-accent text-xl shrink-0">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-lg text-white mb-2">Office Address</h4>
                        <p class="text-sm text-slate-400 leading-relaxed"><?php echo htmlspecialchars($siteSettings['office_address'] ?? 'Islamabad, Pakistan'); ?></p>
                    </div>
                </div>

                <!-- Card 2: Contact Phone -->
                <div class="glass-panel p-8 rounded-3xl border border-white/5 flex items-start gap-5 hover:border-emerald-400/20 transition-all reveal-on-scroll delay-100">
                    <div class="w-12 h-12 rounded-xl bg-emerald-400/15 flex items-center justify-center text-emerald-400 text-xl shrink-0">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-lg text-white mb-2">Call Numbers</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Phone: <a href="tel:00923199564230" class="hover:text-emerald-400 transition-colors"><?php echo htmlspecialchars($siteSettings['phone_number'] ?? '00923199564230'); ?></a> <br>
                            Support: <a href="tel:00923199564230" class="hover:text-emerald-400 transition-colors"><?php echo htmlspecialchars($siteSettings['support_phone'] ?? '00923199564230'); ?></a>
                        </p>
                    </div>
                </div>

                <!-- Card 3: Support Center Email -->
                <div class="glass-panel p-8 rounded-3xl border border-white/5 flex items-start gap-5 hover:border-brand-accent/20 transition-all reveal-on-scroll delay-200">
                    <div class="w-12 h-12 rounded-xl bg-brand-accent/15 flex items-center justify-center text-brand-accent text-xl shrink-0">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-lg text-white mb-2">Electronic Mails</h4>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Support: <a href="mailto:<?php echo htmlspecialchars($siteSettings['support_email'] ?? 'digiraremarketing@gmail.com'); ?>" class="hover:text-brand-accent transition-colors"><?php echo htmlspecialchars($siteSettings['support_email'] ?? 'digiraremarketing@gmail.com'); ?></a> <br>
                            Sales: <a href="mailto:<?php echo htmlspecialchars($siteSettings['sales_email'] ?? 'digiraremarketing@gmail.com'); ?>" class="hover:text-brand-accent transition-colors"><?php echo htmlspecialchars($siteSettings['sales_email'] ?? 'digiraremarketing@gmail.com'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Split Form & Map Layout -->
    <section class="py-20 relative bg-brand-darker/60 border-t border-slate-900">
        <div class="glow-bg top-20 right-20"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Contact Form Column -->
                <div class="lg:col-span-6">
                    <div class="glass-panel rounded-3xl p-8 sm:p-10 border border-white/5 reveal-on-scroll">
                        <h3 class="font-heading font-extrabold text-2xl text-white mb-2">Send Us A Message</h3>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">Have a technical inquiry? Our solution architects are ready to assist you.</p>
                        
                        <form action="contact.php" method="POST" id="contact-form" class="flex flex-col gap-4">
                            <input type="hidden" name="action" value="submit_contact">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="sr-only">Full Name</label>
                                    <input type="text" name="name" id="name" placeholder="Full Name" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                                <div>
                                    <label for="email" class="sr-only">Email Address</label>
                                    <input type="email" name="email" id="email" placeholder="Email Address" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                            </div>
                            <div>
                                <label for="subject" class="sr-only">Subject</label>
                                <input type="text" name="subject" id="subject" placeholder="Subject" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                            </div>
                            <div>
                                <label for="message" class="sr-only">Message Description</label>
                                <textarea name="message" id="message" rows="4" placeholder="Briefly describe your request details..." required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm resize-none"></textarea>
                            </div>
                            
                            <button type="submit" class="mt-2 w-full py-4 rounded-xl font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.01] transition-all">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Simulation Map Column -->
                <div class="lg:col-span-6 relative reveal-on-scroll delay-200">
                    <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl relative w-full aspect-square max-w-[480px] mx-auto bg-slate-900">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.4281313768233!2d-73.98731968459385!3d40.75180127932785!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1668270182449!5m2!1sen!2sus"
                            class="w-full h-full border-none opacity-70 grayscale contrast-125" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-brand-dark via-transparent to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Show alert check
            <?php if (!empty($successMsg)): ?>
                triggerAlert(<?php echo json_encode($successMsg); ?>);
            <?php elseif (!empty($errorMsg)): ?>
                triggerAlert("Error: " + <?php echo json_encode($errorMsg); ?>);
            <?php endif; ?>
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
