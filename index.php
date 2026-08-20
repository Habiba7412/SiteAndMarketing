<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/models/Setting.php';
require_once __DIR__ . '/models/Blog.php';
require_once __DIR__ . '/models/Service.php';
require_once __DIR__ . '/models/Project.php';
require_once __DIR__ . '/models/Team.php';
require_once __DIR__ . '/models/Testimonial.php';
require_once __DIR__ . '/models/Faq.php';

$successMsg = '';
$errorMsg = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle Estimate Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_estimate') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errorMsg = "Invalid security token. Please refresh and try again.";
    } else {
        $name    = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $service = htmlspecialchars(trim($_POST['service'] ?? ''), ENT_QUOTES, 'UTF-8');
        $businessName = htmlspecialchars(trim($_POST['business_name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $budgetRange = htmlspecialchars(trim($_POST['budget_range'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        $subject = "Estimate Request: " . ($service ? ucfirst($service) : "General");

        if (!empty($name) && $email && !empty($message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `contact_submissions` (`name`, `email`, `subject`, `message`, `business_name`, `phone`, `service`, `budget_range`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $subject, $message, $businessName, $phone, $service, $budgetRange]);
                $successMsg = "Your estimate request has been submitted! We will get back to you shortly.";
            } catch (PDOException $e) {
                $errorMsg = "Error saving submission: " . $e->getMessage();
            }
        } else {
            $errorMsg = "Please fill in all required fields with a valid email address.";
        }
    }
}

// Fetch all dynamic content from MySQL using OOP models
$servicesList  = Service::getAll('published');
$projectsList  = Project::getAll('published');
$blogsList     = Blog::getPublished(3);
$teamList      = Team::getAll();
$testimonials  = Testimonial::getAll('published');
$faqs          = Faq::getAll('published');

// Fetch hero & about content from settings
$heroSubHeading = Setting::get('hero_sub_heading', 'NextGen Software Innovators');
$heroHeadline   = Setting::get('hero_headline', 'Transforming Ideas Into Powerful Digital Solutions');
$heroDesc       = Setting::get('hero_description', 'We empower startups and enterprises with innovative software development.');
$heroBtnText1   = Setting::get('hero_btn_text_1', 'Get Started Today');
$heroBtnUrl1    = Setting::get('hero_btn_url_1', 'contact.php');
$heroBtnText2   = Setting::get('hero_btn_text_2', 'Explore Our Services');
$heroBtnUrl2    = Setting::get('hero_btn_url_2', 'services.php');
$heroImage      = Setting::get('hero_image', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=800&q=80');

$aboutSubTitle  = Setting::get('about_sub_title', 'Innovate & Grow');
$aboutTitle     = Setting::get('about_title', 'Innovate Soft Solutions to Grow Tech Business');
$aboutDesc      = Setting::get('about_description', 'Our customized software frameworks are designed to resolve real-world limitations.');
$aboutImage     = Setting::get('about_image', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=700&q=80');
$aboutCounters  = json_decode(Setting::get('about_counters', '[]'), true) ?: [];
$aboutFeatures  = json_decode(Setting::get('about_features', '[]'), true) ?: [];

$heroSupportingText = Setting::get('hero_supporting_text', 'Custom Development • Responsive Design • SEO-Ready • Scalable Solutions');
$whatWeBuild = json_decode(Setting::get('what_we_build', '[]'), true) ?: [];
$whatWeBuildHeading = Setting::get('what_we_build_heading', 'Digital Solutions Built Around Your Business');
$whatWeBuildDesc = Setting::get('what_we_build_desc', 'From business websites to custom web applications, we build practical digital solutions designed around your requirements, customers and business goals.');

$clientLogos = json_decode(Setting::get('client_logos', '[]'), true) ?: [];
$testimonialHeading = Setting::get('testimonial_heading', 'What Our Clients Say');
$marqueeTexts = json_decode(Setting::get('marquee_texts', '["Software Development", "Cloud Architecture", "Cyber Security", "AI Integrations", "Data Analytics"]'), true) ?: [];
$checklistTitle = Setting::get('checklist_title', 'Modern Technology & Digital Innovation Services');
$checklistDesc = Setting::get('checklist_desc', 'At Site And Marketing Technologies, we combine innovation, expertise, and the latest technologies to help businesses build secure, scalable, and future-ready digital solutions.');
$checklistItems = json_decode(Setting::get('checklist_items', '[]'), true) ?: [];

$whyChooseUsHeading = Setting::get('why_choose_us_heading', 'Why Choose Site And Marketing');
$whyChooseUsDesc = Setting::get('why_choose_us_desc', '');
$whyChooseUs = json_decode(Setting::get('why_choose_us', '[]'), true) ?: [];

$processHeading = Setting::get('process_heading', 'Our Development Process');
$processDesc = Setting::get('process_desc', '');
$process = json_decode(Setting::get('process', '[]'), true) ?: [];

$techHeading = Setting::get('tech_heading', 'Technologies We Use');
$techDesc = Setting::get('tech_desc', '');
$technologies = json_decode(Setting::get('technologies', '[]'), true) ?: [];

$contactHeading = Setting::get('contact_heading', 'Let\'s Talk About Your Project');
$contactDesc = Setting::get('contact_desc', '');

$ctaHeading = Setting::get('cta_heading', 'Have a Project in Mind? Let\'s Build It.');
$ctaDesc = Setting::get('cta_desc', '');
$ctaPrimaryText = Setting::get('cta_primary_text', 'Start Your Project');
$ctaPrimaryUrl = Setting::get('cta_primary_url', '#contact');
$ctaSecondaryText = Setting::get('cta_secondary_text', 'Talk to Our Team');
$ctaSecondaryUrl = Setting::get('cta_secondary_url', '#contact');

include __DIR__ . '/includes/header.php';
?>

    <!-- Hero Section -->
    <section class="relative min-h-screen pt-32 pb-20 flex items-center overflow-hidden">
        <!-- Glowing background blobs -->
        <div class="glow-bg top-20 right-10"></div>
        <div class="glow-bg-emerald bottom-10 left-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-12 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Hero Text -->
                <div class="lg:col-span-7 flex flex-col gap-6 text-left reveal-on-scroll">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-brand-accent/20 bg-brand-accent/5 w-fit">
                        <span class="w-2 h-2 rounded-full bg-brand-accent pulse-dot"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-accent font-heading"><?php echo htmlspecialchars($heroSubHeading); ?></span>
                    </div>

                    <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
                        <?php echo htmlspecialchars($heroHeadline); ?>
                    </h1>
 
                    <p class="text-slate-300 text-lg md:text-xl max-w-xl leading-relaxed">
                        <?php echo htmlspecialchars($heroDesc); ?>
                    </p>
 
                    <div class="grid grid-cols-2 gap-6 my-4 max-w-md hidden">
                        <?php foreach ($aboutCounters as $counter): ?>
                        <div class="flex flex-col gap-1">
                            <span class="font-heading font-black text-3xl sm:text-4xl text-white"><?php echo htmlspecialchars($counter['value']); ?></span>
                            <div class="text-xs uppercase tracking-wider text-slate-450 font-medium leading-snug">
                                <?php echo htmlspecialchars($counter['label']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
 
                    <!-- Call To Action Buttons - Dynamic from DB -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-2">
                        <a href="<?php echo htmlspecialchars($heroBtnUrl1); ?>" class="px-8 py-4 rounded-full font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-xl hover:shadow-brand-accent/20 hover:scale-[1.02] transition-all">
                            <?php echo htmlspecialchars($heroBtnText1); ?>
                        </a>
                        <a href="<?php echo htmlspecialchars($heroBtnUrl2); ?>" class="px-8 py-4 rounded-full font-heading font-bold text-center border border-slate-700 hover:border-slate-500 hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                            <span><?php echo htmlspecialchars($heroBtnText2); ?></span>
                            <i class="fa-solid fa-arrow-right text-xs text-brand-accent"></i>
                        </a>
                    </div>

                    <!-- Supporting Text -->
                    <div class="mt-6 text-sm font-semibold text-slate-400 tracking-wide">
                        <?php echo htmlspecialchars($heroSupportingText); ?>
                    </div>
                </div>

                <!-- Hero Image Column -->
                <div class="lg:col-span-5 relative mt-6 lg:mt-0 reveal-on-scroll delay-200">
                    <div class="relative w-full aspect-square max-w-[480px] mx-auto">
                        <!-- Glass frame wrapper -->
                        <div class="absolute inset-4 rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/50 z-10 bg-brand-dark/45 backdrop-blur-md">
                            <img src="<?php echo htmlspecialchars($heroImage); ?>" alt="Hero Image" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700">
                        </div>
                        <!-- Background glowing elements -->
                        <div class="absolute -top-4 -right-4 w-1/2 h-1/2 rounded-full bg-gradient-to-br from-brand-accent to-emerald-400 opacity-20 filter blur-3xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-1/2 h-1/2 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 opacity-30 filter blur-3xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Build Section -->
    <?php if (!empty($whatWeBuild)): ?>
    <section class="py-24 relative overflow-hidden bg-brand-dark">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Heading Container -->
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    <?php echo htmlspecialchars($whatWeBuildHeading); ?>
                </h2>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    <?php echo htmlspecialchars($whatWeBuildDesc); ?>
                </p>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $delay = 0; foreach ($whatWeBuild as $card): ?>
                <div class="glass-panel rounded-3xl p-8 border border-white/5 flex flex-col gap-5 hover:border-brand-accent/30 transition-all duration-300 reveal-on-scroll <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-brand-accent/10 flex items-center justify-center text-brand-accent text-xl">
                            <i class="fa-solid <?php echo htmlspecialchars($card['icon']); ?>"></i>
                        </div>
                        <span class="text-3xl font-heading font-black text-white/10"><?php echo htmlspecialchars($card['number']); ?></span>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-white mt-2"><?php echo htmlspecialchars($card['title']); ?></h3>
                    <p class="text-slate-400 text-sm leading-relaxed flex-grow">
                        <?php echo htmlspecialchars($card['description']); ?>
                    </p>
                    <?php if (!empty($card['link'])): ?>
                    <div class="mt-4 pt-4 border-t border-slate-800/50">
                        <a href="<?php echo htmlspecialchars($card['link']); ?>" class="inline-flex items-center gap-2 text-brand-accent hover:text-white text-xs font-bold uppercase tracking-wider group/link">
                            <span>Learn More</span>
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover/link:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $delay = ($delay + 100) % 300; endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Endless Scrolling Marquee Banner -->
    <?php if (!empty($marqueeTexts)): ?>
    <section class="border-y border-slate-800 bg-brand-darker py-6 overflow-hidden select-none">
        <div class="animate-marquee flex gap-8 whitespace-nowrap text-3xl sm:text-5xl font-heading font-black text-stroke uppercase">
            <?php foreach (array_merge($marqueeTexts, $marqueeTexts) as $text): ?>
            <span><?php echo htmlspecialchars($text); ?> <span class="text-brand-accent font-normal mx-6">•</span></span>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Software Development Intro Section -->
    <section class="relative py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left side content -->
                <div class="lg:col-span-6 flex flex-col gap-6 reveal-on-scroll">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Innovate & Grow</span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                        Innovate Soft Solutions to Grow Tech Business
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        Our customized software frameworks are designed to resolve real-world operations limitations. We collaborate with you to build scalable platforms that increase productivity, eliminate overheads, and secure user data.
                    </p>
                    
                    <div class="mt-4">
                        <a href="about.php" class="inline-flex items-center gap-3 text-brand-accent font-bold group">
                            <span>Read More About Us</span>
                            <span class="w-8 h-8 rounded-full border border-brand-accent/30 flex items-center justify-center group-hover:bg-brand-accent group-hover:text-brand-dark transition-all">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Right side layout with photo and stats badge -->
                <div class="lg:col-span-6 relative reveal-on-scroll delay-200">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-8 rounded-2xl overflow-hidden border border-white/5 shadow-2xl relative z-10">
                            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=700&q=80" alt="Tech Collaboration" class="w-full object-cover aspect-[4/3] grayscale hover:grayscale-0 transition-all duration-500">
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Technology Services Checklists -->
    <section class="py-20 relative bg-brand-darker/40 border-y border-slate-900/60">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <!-- Image Side -->
                <div class="order-2 lg:order-1 relative reveal-on-scroll">
                    <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/80 relative group">
                        <img src="https://images.unsplash.com/photo-1542744094-3a31f103e35f?auto=format&fit=crop&w=800&q=80" alt="Modern Technology & Digital Innovation" class="w-full aspect-[16/11] object-cover opacity-85 group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-transparent"></div>
                    </div>
                </div>

                <!-- Text & Checklist Side -->
                <div class="order-1 lg:order-2 flex flex-col gap-6 reveal-on-scroll delay-200">
                    <span class="text-xs font-bold tracking-widest text-brand-accent uppercase font-heading">MODERN TECHNOLOGY</span>
                    <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                        <?php echo htmlspecialchars($checklistTitle ?? 'Modern Technology & Digital Innovation Services'); ?>
                    </h2>
                    
                    <p class="text-slate-300 text-base leading-relaxed">
                        <?php echo htmlspecialchars($checklistDesc ?? 'At Site And Marketing Technologies, we combine innovation, expertise, and the latest technologies to help businesses build secure, scalable, and future-ready digital solutions.'); ?>
                    </p>

                    <!-- Interactive Checklist -->
                    <?php if (!empty($checklistItems)): ?>
                    <ul class="flex flex-col gap-4 mt-2">
                        <?php foreach ($checklistItems as $item): ?>
                        <li class="flex items-start gap-4 group">
                            <span class="w-7 h-7 rounded-lg bg-brand-accent/15 border border-brand-accent/30 flex items-center justify-center text-brand-accent mt-1 shrink-0 group-hover:bg-brand-accent group-hover:text-brand-dark transition-all">
                                <i class="fa-solid <?php echo htmlspecialchars($item['icon'] ?? 'fa-check'); ?> text-xs"></i>
                            </span>
                            <div>
                                <h4 class="font-heading font-bold text-lg text-white"><?php echo htmlspecialchars($item['title']); ?></h4>
                                <p class="text-sm text-slate-400 mt-1 leading-relaxed"><?php echo htmlspecialchars($item['description']); ?></p>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <div class="mt-2">
                        <a href="services.php" class="inline-flex items-center gap-2 text-sm font-bold text-brand-accent hover:text-white uppercase tracking-wider group">
                            <span>Explore All Modern Tech Pillars</span>
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <?php if (!empty($whyChooseUs)): ?>
    <section class="py-24 relative overflow-hidden bg-brand-dark">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Our Advantage</span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    <?php echo htmlspecialchars($whyChooseUsHeading); ?>
                </h2>
                <?php if (!empty($whyChooseUsDesc)): ?>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    <?php echo htmlspecialchars($whyChooseUsDesc); ?>
                </p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $delay = 0; foreach ($whyChooseUs as $item): ?>
                <div class="glass-panel rounded-3xl p-8 border border-white/5 flex flex-col gap-5 hover:border-brand-accent/30 transition-all duration-300 reveal-on-scroll <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
                    <div class="w-12 h-12 rounded-xl bg-brand-accent/10 flex items-center justify-center text-brand-accent text-xl">
                        <i class="fa-solid <?php echo htmlspecialchars($item['icon'] ?? 'fa-check'); ?>"></i>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-white mt-2"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h3>
                    <p class="text-slate-400 text-sm leading-relaxed flex-grow">
                        <?php echo htmlspecialchars($item['description'] ?? ''); ?>
                    </p>
                </div>
                <?php $delay = ($delay + 100) % 300; endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Our Process Section -->
    <?php if (!empty($process)): ?>
    <section class="py-24 relative overflow-hidden bg-brand-darker border-t border-slate-900/80">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">How We Work</span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    <?php echo htmlspecialchars($processHeading); ?>
                </h2>
                <?php if (!empty($processDesc)): ?>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    <?php echo htmlspecialchars($processDesc); ?>
                </p>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                <div class="hidden lg:block absolute top-1/2 left-0 w-full h-0.5 bg-slate-800 -translate-y-1/2 z-0"></div>
                <?php $delay = 0; foreach ($process as $step): ?>
                <div class="relative z-10 flex flex-col items-center text-center gap-4 reveal-on-scroll <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
                    <div class="w-16 h-16 rounded-full bg-brand-darker border border-brand-accent/50 flex items-center justify-center text-brand-accent text-2xl shadow-xl shadow-brand-accent/10 relative">
                        <i class="fa-solid <?php echo htmlspecialchars($step['icon'] ?? 'fa-gear'); ?>"></i>
                        <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-brand-accent text-brand-dark text-xs font-bold flex items-center justify-center"><?php echo htmlspecialchars($step['step'] ?? ''); ?></span>
                    </div>
                    <h3 class="font-heading font-bold text-lg text-white mt-4"><?php echo htmlspecialchars($step['title'] ?? ''); ?></h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        <?php echo htmlspecialchars($step['description'] ?? ''); ?>
                    </p>
                </div>
                <?php $delay = ($delay + 100) % 400; endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Technologies Section -->
    <?php if (!empty($technologies)): ?>
    <section class="py-24 relative overflow-hidden bg-brand-dark">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Tech Stack</span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    <?php echo htmlspecialchars($techHeading); ?>
                </h2>
                <?php if (!empty($techDesc)): ?>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    <?php echo htmlspecialchars($techDesc); ?>
                </p>
                <?php endif; ?>
            </div>

            <div class="flex flex-wrap justify-center gap-4 reveal-on-scroll">
                <?php foreach ($technologies as $tech): ?>
                <div class="flex items-center gap-3 px-6 py-3 rounded-xl border border-slate-800 bg-brand-darker hover:border-brand-accent/50 hover:bg-slate-800/50 transition-all duration-300">
                    <?php if (!empty($tech['logo'])): ?>
                    <img src="<?php echo htmlspecialchars($tech['logo']); ?>" alt="<?php echo htmlspecialchars($tech['name'] ?? ''); ?>" class="w-6 h-6 object-contain">
                    <?php else: ?>
                    <i class="fa-solid fa-code text-brand-accent"></i>
                    <?php endif; ?>
                    <div>
                        <span class="font-heading font-bold text-sm text-white block"><?php echo htmlspecialchars($tech['name'] ?? ''); ?></span>
                        <span class="text-[10px] text-slate-500 uppercase tracking-wider block"><?php echo htmlspecialchars($tech['category'] ?? 'Technology'); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Premium Digital Services Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="glow-bg top-0 left-10"></div>
        <div class="glow-bg-emerald bottom-10 right-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Heading Container -->
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Our Expertise</span>
                <h2 class="font-heading font-black text-4xl sm:text-5xl text-white leading-tight">
                    WE PROVIDE HIGH-QUALITY <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400">DIGITAL SERVICES</span>
                </h2>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    We help businesses grow through custom software development, creative design, branding, and digital solutions.
                </p>
            </div>

            <!-- Responsive 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                <?php 
                $delay = 0;
                foreach ($servicesList as $svc): 
                    $svgIcon = getServiceIcon($svc['title']);
                ?>
                <!-- Service Card -->
                <div class="premium-service-card p-8 sm:p-10 flex flex-col gap-6 group reveal-on-scroll <?php echo ($delay > 0) ? 'delay-' . $delay : ''; ?>">
                    <div class="ambient-glow"></div>
                    <div class="w-14 h-14 rounded-2xl bg-brand-accent/10 border border-brand-accent/20 flex items-center justify-center text-brand-accent group-hover:bg-gradient-to-tr group-hover:from-brand-accent group-hover:to-emerald-400 group-hover:text-brand-dark transition-all duration-300 relative overflow-hidden z-10 shrink-0">
                        <?php echo $svgIcon; ?>
                    </div>
                    <div class="flex flex-col gap-3 flex-grow z-10">
                        <h3 class="font-heading font-extrabold text-2xl text-white group-hover:text-brand-accent transition-colors">
                            <?php echo htmlspecialchars($svc['title']); ?>
                        </h3>
                        <p class="text-slate-400 leading-relaxed text-sm">
                            <?php echo htmlspecialchars($svc['description']); ?>
                        </p>
                        <?php if (!empty($svc['long_description'])): ?>
                        <p class="text-slate-550 leading-relaxed text-xs mt-2 border-t border-slate-800/50 pt-2 hidden group-hover:block transition-all duration-500">
                            <?php echo htmlspecialchars($svc['long_description']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="pt-4 mt-auto border-t border-white/5 flex items-center justify-between z-10">
                        <a href="contact.php" class="inline-flex items-center gap-2 text-brand-accent hover:text-white text-sm font-semibold group/btn">
                            <span>Get Started</span>
                            <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
                <?php 
                    $delay = ($delay + 100) % 400;
                endforeach; 
                ?>
            </div>
            
            <!-- Call To Actions -->
            <div class="flex flex-col sm:flex-row gap-4 mt-16 justify-center items-center w-full reveal-on-scroll">
                <a href="contact.php" class="px-8 py-4 rounded-full font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-xl hover:shadow-brand-accent/20 hover:scale-[1.02] transition-all w-full sm:w-auto">
                    GET YOUR WEBSITE NOW
                </a>
                <a href="services.php" class="px-8 py-4 rounded-full font-heading font-bold text-center text-white bg-transparent border border-slate-700 hover:border-slate-500 hover:bg-white/5 transition-all w-full sm:w-auto flex items-center justify-center gap-2">
                    <span>View All Services</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden bg-brand-darker border-t border-slate-900">
        <!-- Background Glowing Blobs -->
        <div class="glow-bg top-0 right-1/4"></div>
        <div class="glow-bg-emerald bottom-0 left-1/4"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center flex flex-col items-center gap-8 reveal-on-scroll">
            <h2 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
                <?php echo htmlspecialchars($ctaHeading); ?>
            </h2>
            <?php if (!empty($ctaDesc)): ?>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl leading-relaxed">
                <?php echo htmlspecialchars($ctaDesc); ?>
            </p>
            <?php endif; ?>
            <div class="flex flex-col sm:flex-row gap-4 mt-4 w-full sm:w-auto justify-center">
                <a href="<?php echo htmlspecialchars($ctaPrimaryUrl); ?>" class="px-8 py-4 rounded-full font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-xl hover:shadow-brand-accent/20 hover:scale-[1.02] transition-all">
                    <?php echo htmlspecialchars($ctaPrimaryText); ?>
                </a>
                <a href="<?php echo htmlspecialchars($ctaSecondaryUrl); ?>" class="px-8 py-4 rounded-full font-heading font-bold text-center border border-slate-700 hover:border-slate-500 hover:bg-white/5 transition-all text-white flex items-center justify-center gap-2">
                    <span><?php echo htmlspecialchars($ctaSecondaryText); ?></span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-accent"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Client / Partner Logo Grid -->
    <?php if (!empty($clientLogos)): ?>
    <section class="py-12 bg-brand-darker border-y border-slate-900">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-wrap items-center justify-between gap-8 text-slate-500">
            <?php foreach ($clientLogos as $logo): ?>
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <img src="<?php echo htmlspecialchars($logo['image_url']); ?>" alt="<?php echo htmlspecialchars($logo['name']); ?>" class="h-8 object-contain grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100">
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Meet Our Experienced Team Section -->
    <section class="py-24 relative overflow-hidden bg-brand-dark/30">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="flex flex-col gap-4">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading font-medium">Expert Members</span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-white leading-tight">
                        Meet Our Experienced <br class="hidden sm:inline"> Team Members
                    </h2>
                </div>
                <div>
                    <a href="about.php" class="px-6 py-3 rounded-full border border-slate-700 hover:border-brand-accent hover:text-brand-accent transition-all inline-flex items-center gap-2">
                        <span>View All Team</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Team Grid - Dynamic from MySQL team_members table -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php $tDelay = 0; foreach ($teamList as $member): ?>
                <div class="glass-panel rounded-3xl overflow-hidden border border-white/5 relative group hover:border-brand-accent/20 transition-all duration-300 reveal-on-scroll <?php echo $tDelay > 0 ? 'delay-' . $tDelay : ''; ?>">
                    <div class="aspect-[4/5] overflow-hidden relative">
                        <img src="<?php echo htmlspecialchars($member['image_url'] ?: 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80'); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-105">
                        <!-- Floating Social Media Links -->
                        <div class="absolute bottom-6 left-6 flex gap-2 translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 z-20">
                            <?php if (!empty($member['linkedin_url'])): ?>
                            <a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-brand-dark/95 flex items-center justify-center text-slate-300 hover:bg-brand-accent hover:text-brand-dark transition-colors">
                                <i class="fa-brands fa-linkedin-in text-sm"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['github_url'])): ?>
                            <a href="<?php echo htmlspecialchars($member['github_url']); ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-brand-dark/95 flex items-center justify-center text-slate-300 hover:bg-brand-accent hover:text-brand-dark transition-colors">
                                <i class="fa-brands fa-github text-sm"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (!empty($member['twitter_url'])): ?>
                            <a href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-brand-dark/95 flex items-center justify-center text-slate-300 hover:bg-brand-accent hover:text-brand-dark transition-colors">
                                <i class="fa-brands fa-twitter text-sm"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-6 relative bg-gradient-to-t from-brand-darker to-brand-card">
                        <h4 class="font-heading font-bold text-xl text-white"><?php echo htmlspecialchars($member['name']); ?></h4>
                        <span class="text-sm text-brand-accent font-medium mt-1 block"><?php echo htmlspecialchars($member['designation']); ?></span>
                    </div>
                </div>
                <?php $tDelay = ($tDelay + 100) % 300; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Case Studies & Portfolio Showcase -->
    <section class="py-24 relative bg-[#081018] border-t border-slate-900/80 overflow-hidden">
        <div class="glow-bg top-10 left-10 opacity-30"></div>
        <div class="glow-bg-emerald bottom-10 right-10 opacity-25"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-12 flex flex-col items-center gap-4 reveal-on-scroll">
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    OUR PORTFOLIO
                </span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    Innovative Projects That <span class="text-gradient-blue">Deliver Real Results</span>
                </h2>
                <p class="text-slate-400 text-base leading-relaxed">
                    Explore featured case studies of high-performance software engineering, e-commerce, web applications, and AI integrations built for our global clients.
                </p>
            </div>

            <!-- Categories Filter Tabs -->
            <div class="flex items-center justify-center gap-2 flex-wrap mb-12 reveal-on-scroll" id="portfolio-tabs">
                <button data-filter="all" class="portfolio-tab-btn active px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/25 border border-blue-400/30 transition-all duration-300">All Projects</button>
                <button data-filter="web-development" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">Web Development</button>
                <button data-filter="software-development" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">Software Dev</button>
                <button data-filter="mobile-apps" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">Mobile Apps</button>
                <button data-filter="e-commerce" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">E-Commerce</button>
                <button data-filter="ai-solutions" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">AI Solutions</button>
            </div>

            <!-- Portfolio Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="portfolio-grid">
                <?php $delay = 0; foreach ($projectsList as $project): ?>
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>" data-category="<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $project['category'] ?? 'web-development'))); ?>">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="<?php echo htmlspecialchars($project['image_url'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80'); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md"><?php echo htmlspecialchars($project['category'] ?? 'Project'); ?></span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-folder-open text-blue-400 mr-1"></i> <?php echo htmlspecialchars($project['client_name'] ?? 'Client'); ?></span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors"><?php echo htmlspecialchars($project['title']); ?></h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed"><?php echo htmlspecialchars($project['description']); ?></p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <?php 
                                $techs = array_map('trim', explode(',', $project['technologies'] ?? ''));
                                foreach (array_slice($techs, 0, 3) as $tech): 
                                    if(empty($tech)) continue;
                                ?>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5"><?php echo htmlspecialchars($tech); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="project-details.php?id=<?php echo $project['id']; ?>" class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php $delay = ($delay + 75) % 300; endforeach; ?>
            </div>
            
            <div class="text-center mt-14 reveal-on-scroll">
                <a href="projects.php" class="btn-gradient-blue px-8 py-4 rounded-full font-heading font-bold text-white text-base inline-flex items-center justify-center gap-3 shadow-lg shadow-blue-500/20 hover:scale-[1.02] transition-all">
                    <span>Explore All 10+ Case Studies</span>
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-24 relative overflow-hidden bg-brand-dark">
        <div class="glow-bg-emerald bottom-10 right-10"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Testimonial Content Box -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Testimonials</span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-white leading-tight">
                        <?php echo htmlspecialchars($testimonialHeading); ?>
                    </h2>
                    
                    <!-- Carousel Slider Outer -->
                    <div class="relative glass-panel rounded-3xl p-8 sm:p-10 border border-white/5 mt-4">
                        <div class="absolute -top-6 -left-4 text-6xl text-brand-accent opacity-20">
                            <i class="fa-solid fa-quote-left"></i>
                        </div>
                        
                        <!-- Testimonial Items - Dynamic from MySQL testimonials table -->
                        <div id="testimonial-carousel">
                            <?php $tFirst = true; foreach ($testimonials as $t): ?>
                            <div class="testimonial-slide <?php echo $tFirst ? 'active' : 'hidden'; ?> transition-all duration-300">
                                <p class="text-lg leading-relaxed text-slate-200">
                                    "<?php echo htmlspecialchars($t['review']); ?>"
                                </p>
                                <div class="flex items-center gap-4 mt-8">
                                    <?php if (!empty($t['image_url'])): ?>
                                    <div class="w-12 h-12 rounded-full overflow-hidden shrink-0">
                                        <img src="<?php echo htmlspecialchars($t['image_url']); ?>" alt="<?php echo htmlspecialchars($t['client_name']); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <h5 class="font-heading font-bold text-base text-white"><?php echo htmlspecialchars($t['client_name']); ?></h5>
                                        <?php if (!empty($t['company'])): ?>
                                        <span class="text-xs text-brand-accent"><?php echo htmlspecialchars($t['company']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $tFirst = false; endforeach; ?>
                        </div>

                        <!-- Carousel Controls Buttons -->
                        <div class="flex gap-3 justify-end mt-4">
                            <button id="prev-testimonial-btn" class="w-10 h-10 rounded-full border border-slate-800 hover:border-brand-accent flex items-center justify-center text-slate-400 hover:text-brand-accent transition-all" aria-label="Previous Slide">
                                <i class="fa-solid fa-arrow-left text-xs"></i>
                            </button>
                            <button id="next-testimonial-btn" class="w-10 h-10 rounded-full border border-slate-800 hover:border-brand-accent flex items-center justify-center text-slate-400 hover:text-brand-accent transition-all" aria-label="Next Slide">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Avatars Collage Grid -->
                <div class="lg:col-span-5 relative flex justify-center items-center mt-8 lg:mt-0">
                    <div class="grid grid-cols-2 gap-4 max-w-[360px]">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80" alt="avatar" class="rounded-3xl border border-white/10 hover:border-brand-accent shadow-xl grayscale hover:grayscale-0 transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80" alt="avatar" class="rounded-3xl border border-white/10 hover:border-emerald-400 shadow-xl grayscale hover:grayscale-0 transition-all duration-300 mt-6">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&h=150&q=80" alt="avatar" class="rounded-3xl border border-white/10 hover:border-brand-accent shadow-xl grayscale hover:grayscale-0 transition-all duration-300 -mt-6">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&h=150&q=80" alt="avatar" class="rounded-3xl border border-white/10 hover:border-emerald-400 shadow-xl grayscale hover:grayscale-0 transition-all duration-300">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQs Section -->
    <?php if (!empty($faqs)): ?>
    <section class="py-24 relative overflow-hidden bg-brand-darker border-t border-slate-900/80">
        <div class="glow-bg top-20 left-20"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
                <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Got Questions?</span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    Frequently Asked Questions
                </h2>
                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    Find answers to some of our most common questions about our services, process, and pricing.
                </p>
            </div>

            <div class="max-w-4xl mx-auto flex flex-col gap-4">
                <?php $delay = 0; foreach ($faqs as $index => $faq): ?>
                <div class="faq-item glass-panel rounded-2xl border border-white/5 overflow-hidden reveal-on-scroll <?php echo $delay > 0 ? 'delay-' . $delay : ''; ?>">
                    <button class="faq-toggle w-full px-6 py-5 text-left flex items-center justify-between gap-4 group">
                        <span class="font-heading font-bold text-lg text-white group-hover:text-brand-accent transition-colors"><?php echo htmlspecialchars($faq['question']); ?></span>
                        <span class="w-8 h-8 rounded-full bg-brand-darker border border-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-brand-accent/10 group-hover:text-brand-accent group-hover:border-brand-accent/30 transition-all shrink-0 faq-icon">
                            <i class="fa-solid fa-plus text-xs transition-transform duration-300"></i>
                        </span>
                    </button>
                    <div class="faq-content hidden px-6 pb-6 pt-0">
                        <p class="text-slate-400 leading-relaxed text-sm">
                            <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                        </p>
                    </div>
                </div>
                <?php $delay = ($delay + 100) % 500; endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Estimate & Free Project Analysis Form Section -->
    <section class="py-24 relative overflow-hidden bg-brand-darker">
        <div class="glow-bg top-20 right-20"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left Details Column -->
                <div class="lg:col-span-6 flex flex-col gap-6">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading font-medium">Start Collaborating</span>
                    <h2 class="font-heading font-black text-4xl sm:text-5xl text-white leading-tight">
                        <?php echo htmlspecialchars($contactHeading); ?>
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        <?php echo htmlspecialchars($contactDesc); ?>
                    </p>
                    <div class="flex items-center gap-4 mt-2">
                        <div class="w-12 h-12 rounded-full bg-brand-accent/15 flex items-center justify-center text-brand-accent">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-slate-400 block tracking-wider font-semibold">Immediate Assistance</span>
                            <span class="font-heading font-bold text-lg text-white"><?php echo htmlspecialchars($siteSettings['phone_number'] ?? '+1 (800) 456-7890'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Right Form Column -->
                <div class="lg:col-span-6">
                    <div class="glass-panel rounded-3xl p-8 sm:p-10 border border-white/5 relative">
                        <h3 class="font-heading font-extrabold text-2xl text-white mb-6">Free Estimate Request</h3>
                        
                        <form action="index.php" method="POST" id="estimate-form" class="flex flex-col gap-4">
                            <input type="hidden" name="action" value="submit_estimate">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="form-name" class="sr-only">Full Name</label>
                                    <input type="text" name="name" id="form-name" placeholder="Full Name *" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                                <div>
                                    <label for="form-email" class="sr-only">Email Address</label>
                                    <input type="email" name="email" id="form-email" placeholder="Email Address *" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="form-business" class="sr-only">Business Name</label>
                                    <input type="text" name="business_name" id="form-business" placeholder="Business / Company" class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                                <div>
                                    <label for="form-phone" class="sr-only">Phone Number</label>
                                    <input type="tel" name="phone" id="form-phone" placeholder="Phone Number" class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="form-service" class="sr-only">Select Service</label>
                                    <select name="service" id="form-service" class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 focus:outline-none focus:border-brand-accent text-sm">
                                        <option value="" disabled selected class="text-slate-600">Service Required</option>
                                        <?php foreach ($servicesList as $svc): ?>
                                            <option value="<?php echo htmlspecialchars($svc['title']); ?>"><?php echo htmlspecialchars($svc['title']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="form-budget" class="sr-only">Budget Range</label>
                                    <select name="budget_range" id="form-budget" class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 focus:outline-none focus:border-brand-accent text-sm">
                                        <option value="" disabled selected class="text-slate-600">Budget Range</option>
                                        <option value="<$5k">Less than $5,000</option>
                                        <option value="$5k-$10k">$5,000 - $10,000</option>
                                        <option value="$10k-$25k">$10,000 - $25,000</option>
                                        <option value="$25k+">$25,000+</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="form-message" class="sr-only">Brief Description</label>
                                <textarea name="message" id="form-message" rows="3" placeholder="Briefly describe your project details... *" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm resize-none"></textarea>
                            </div>
                            
                            <button type="submit" class="mt-2 w-full py-4 rounded-xl font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.01] transition-all">
                                Request Estimate
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News & Blog Section -->
    <section class="py-24 relative overflow-hidden bg-brand-dark">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="flex flex-col gap-4">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading">Updates & Insights</span>
                    <h2 class="font-heading font-extrabold text-3xl sm:text-4xl text-white leading-tight">
                        Read Our Latest News & Blog
                    </h2>
                </div>
                <div>
                    <a href="blog.php" class="px-6 py-3 rounded-full border border-slate-700 hover:border-brand-accent hover:text-brand-accent transition-all inline-flex items-center gap-2">
                        <span>Read All Articles</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($blogsList as $b): ?>
                <!-- Blog Card - Dynamic from MySQL blogs table -->
                <div class="glass-panel rounded-3xl overflow-hidden border border-white/5 group hover:border-brand-accent/20 transition-all duration-300">
                    <div class="aspect-[16/9] overflow-hidden relative">
                        <img src="<?php echo htmlspecialchars($b['image_url'] ?: 'uploads/blog/placeholder.svg'); ?>" alt="<?php echo htmlspecialchars($b['title']); ?>" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700" onerror="this.src='uploads/blog/placeholder.svg';">
                        <span class="absolute top-6 left-6 px-3 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider bg-brand-accent text-brand-dark z-20"><?php echo htmlspecialchars($b['category']); ?></span>
                    </div>
                    <div class="p-8 bg-brand-card">
                        <span class="text-xs text-slate-400 font-medium"><?php echo date('M d, Y • h:i A', strtotime($b['created_at'])); ?></span>
                        <h4 class="font-heading font-extrabold text-2xl text-white mt-3 group-hover:text-brand-accent transition-colors leading-tight">
                            <a href="blog/<?php echo urlencode($b['slug']); ?>"><?php echo htmlspecialchars($b['title']); ?></a>
                        </h4>
                        <p class="text-slate-400 text-sm mt-3 leading-relaxed">
                            <?php echo htmlspecialchars($b['excerpt']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Testimonial Slider JS Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Slider scripts
            const slides = document.querySelectorAll('.testimonial-slide');
            const prevBtn = document.getElementById('prev-testimonial-btn');
            const nextBtn = document.getElementById('next-testimonial-btn');
            let currentSlide = 0;

            if (slides.length > 0) {
                const showSlide = (index) => {
                    slides.forEach((slide, idx) => {
                        if (idx === index) {
                            slide.classList.remove('hidden');
                            slide.classList.add('active');
                        } else {
                            slide.classList.add('hidden');
                            slide.classList.remove('active');
                        }
                    });
                };

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                        showSlide(currentSlide);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        currentSlide = (currentSlide + 1) % slides.length;
                        showSlide(currentSlide);
                    });
                }
            }

            // Show estimate/contact message alert notifications if PHP variable set
            <?php if (!empty($successMsg)): ?>
                triggerAlert(<?php echo json_encode($successMsg); ?>);
            <?php elseif (!empty($errorMsg)): ?>
                triggerAlert("Error: " + <?php echo json_encode($errorMsg); ?>);
            <?php endif; ?>

            // Filter Tabs for Portfolio cases
            const tabs = document.querySelectorAll('.portfolio-tab-btn');
            const cards = document.querySelectorAll('.portfolio-card');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active from all tabs
                    tabs.forEach(t => t.classList.remove('active', 'bg-brand-accent', 'text-brand-dark'));
                    tabs.forEach(t => t.classList.add('border', 'border-slate-800', 'text-slate-400'));
                    
                    // Add active to current
                    tab.classList.add('active', 'bg-brand-accent', 'text-brand-dark');
                    tab.classList.remove('border', 'border-slate-800', 'text-slate-400');
                    
                    const filter = tab.getAttribute('data-filter');
                    cards.forEach(card => {
                        const cat = card.getAttribute('data-category');
                        if (filter === 'all' || cat === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // FAQ Accordion
            const faqToggles = document.querySelectorAll('.faq-toggle');
            faqToggles.forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const content = toggle.nextElementSibling;
                    const icon = toggle.querySelector('i');
                    
                    // Close all other FAQs
                    document.querySelectorAll('.faq-content').forEach(c => {
                        if (c !== content && !c.classList.contains('hidden')) {
                            c.classList.add('hidden');
                            c.previousElementSibling.querySelector('i').classList.replace('fa-minus', 'fa-plus');
                        }
                    });

                    // Toggle current FAQ
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        icon.classList.replace('fa-plus', 'fa-minus');
                    } else {
                        content.classList.add('hidden');
                        icon.classList.replace('fa-minus', 'fa-plus');
                    }
                });
            });
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
