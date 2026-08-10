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
        $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        $subject = "Estimate Request: " . ($service ? ucfirst($service) : "General");

        if (!empty($name) && $email && !empty($message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO `contact_submissions` (`name`, `email`, `subject`, `message`) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $email, $subject, $message]);
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
 
                    <!-- Stats Grid (2x2 Layout) - Dynamic from DB -->
                    <div class="grid grid-cols-2 gap-6 my-4 max-w-md">
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

    <!-- Endless Scrolling Marquee Banner -->
    <section class="border-y border-slate-800 bg-brand-darker py-6 overflow-hidden select-none">
        <div class="animate-marquee flex gap-8 whitespace-nowrap text-3xl sm:text-5xl font-heading font-black text-stroke uppercase">
            <span>Software Development <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Cloud Architecture <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Cyber Security <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>AI Integrations <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Data Analytics <span class="text-brand-accent font-normal mx-6">•</span></span>
            <!-- Duplicated for smooth loop -->
            <span>Software Development <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Cloud Architecture <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Cyber Security <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>AI Integrations <span class="text-brand-accent font-normal mx-6">•</span></span>
            <span>Data Analytics <span class="text-brand-accent font-normal mx-6">•</span></span>
        </div>
    </section>

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
                        
                        <!-- Floating Glass Stats Panel -->
                        <div class="col-span-6 lg:col-span-5 absolute -right-2 -bottom-6 glass-panel rounded-2xl p-6 border border-white/10 shadow-2xl z-20 flex flex-col gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-brand-accent/15 flex items-center justify-center text-brand-accent">
                                    <i class="fa-solid fa-users text-lg"></i>
                                </div>
                                <div>
                                    <div class="font-heading font-black text-xl text-white">36k+</div>
                                    <div class="text-[10px] uppercase text-slate-400 tracking-wider">Happy Users</div>
                                </div>
                            </div>
                            <div class="h-px bg-slate-800"></div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-400/15 flex items-center justify-center text-emerald-400">
                                    <i class="fa-solid fa-award text-lg"></i>
                                </div>
                                <div>
                                    <div class="font-heading font-black text-xl text-white">850+</div>
                                    <div class="text-[10px] uppercase text-slate-400 tracking-wider">Expert Engineers</div>
                                </div>
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
                        Modern Technology & <span class="text-gradient-blue">Digital Innovation Services</span>
                    </h2>
                    
                    <p class="text-slate-300 text-base leading-relaxed">
                        At <strong class="text-white font-semibold">DigiRare Technologies</strong>, we combine innovation, expertise, and the latest technologies to help businesses build secure, scalable, and future-ready digital solutions. From custom software development to cloud infrastructure, AI integration, and cybersecurity, our team delivers high-performance solutions that accelerate growth, improve efficiency, and drive digital transformation.
                    </p>

                    <!-- Interactive Checklist -->
                    <ul class="flex flex-col gap-4 mt-2">
                        <li class="flex items-start gap-4 group">
                            <span class="w-7 h-7 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 mt-1 shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-rocket text-xs"></i>
                            </span>
                            <div>
                                <h4 class="font-heading font-bold text-lg text-white">🚀 Agile Software Development</h4>
                                <p class="text-sm text-slate-400 mt-1 leading-relaxed">Custom web applications and enterprise software built using Agile methodologies for faster delivery, clean code, and outstanding performance.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4 group">
                            <span class="w-7 h-7 rounded-lg bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center text-cyan-400 mt-1 shrink-0 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                                <i class="fa-solid fa-cloud text-xs"></i>
                            </span>
                            <div>
                                <h4 class="font-heading font-bold text-lg text-white">☁️ Cloud Infrastructure & DevOps</h4>
                                <p class="text-sm text-slate-400 mt-1 leading-relaxed">Secure, scalable infrastructures using AWS, Azure, GCP, Docker, Kubernetes, and automated CI/CD pipelines to maximize efficiency.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4 group">
                            <span class="w-7 h-7 rounded-lg bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mt-1 shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                            </span>
                            <div>
                                <h4 class="font-heading font-bold text-lg text-white">🔒 Cybersecurity & Data Protection</h4>
                                <p class="text-sm text-slate-400 mt-1 leading-relaxed">Threat detection, vulnerability assessments, encryption, firewall management, and proactive monitoring to keep systems safe.</p>
                            </div>
                        </li>
                    </ul>

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
                Ready to Transform <br class="hidden sm:inline"> Your Business?
            </h2>
            <p class="text-slate-300 text-lg md:text-xl max-w-2xl leading-relaxed">
                Let's build innovative digital solutions that help your business grow faster, reach more customers, and stay ahead of the competition.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 mt-4 w-full sm:w-auto justify-center">
                <a href="contact.php" class="px-8 py-4 rounded-full font-heading font-bold text-center text-brand-dark bg-gradient-to-r from-brand-accent via-cyan-400 to-emerald-400 hover:shadow-xl hover:shadow-brand-accent/20 hover:scale-[1.02] transition-all">
                    GET YOUR WEBSITE NOW
                </a>
                <a href="contact.php" class="px-8 py-4 rounded-full font-heading font-bold text-center border border-slate-700 hover:border-slate-500 hover:bg-white/5 transition-all text-white flex items-center justify-center gap-2">
                    <span>GET YOUR WEBSITE NOW</span>
                    <i class="fa-solid fa-arrow-right text-xs text-brand-accent"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Client / Partner Logo Grid -->
    <section class="py-12 bg-brand-darker border-y border-slate-900">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-wrap items-center justify-between gap-8 text-slate-500">
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <i class="fa-brands fa-google text-2xl"></i>
                <span class="font-heading font-black tracking-wider uppercase text-lg">Google</span>
            </div>
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <i class="fa-brands fa-microsoft text-2xl"></i>
                <span class="font-heading font-black tracking-wider uppercase text-lg">Microsoft</span>
            </div>
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <i class="fa-brands fa-aws text-2xl"></i>
                <span class="font-heading font-black tracking-wider uppercase text-lg">Amazon Web</span>
            </div>
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <i class="fa-brands fa-digital-ocean text-2xl"></i>
                <span class="font-heading font-black tracking-wider uppercase text-lg">DigitalOcean</span>
            </div>
            <div class="flex items-center gap-2 hover:text-white transition-colors duration-300 select-none">
                <i class="fa-brands fa-salesforce text-2xl"></i>
                <span class="font-heading font-black tracking-wider uppercase text-lg">Salesforce</span>
            </div>
        </div>
    </section>

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
                
                <!-- Project 1: Enterprise Business Website -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="web-development">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80" alt="Enterprise Business Website" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">Web Development</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-building text-blue-400 mr-1"></i> Corporate Enterprise</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors">Enterprise Business Website</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">Designed and developed a fully responsive corporate website with modern UI, SEO optimization, and an easy CMS.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">HTML5</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Tailwind</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">PHP</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 2: E-Commerce Platform -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-75" data-category="e-commerce">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1556742049-0a6754099a6b?auto=format&fit=crop&w=800&q=80" alt="E-Commerce Platform" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 backdrop-blur-md">E-Commerce</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-cart-shopping text-cyan-400 mr-1"></i> Retail & E-Commerce</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-cyan-400 transition-colors">E-Commerce Platform</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">Built a secure shopping platform with payment integration, inventory control, and customer analytics dashboards.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Laravel</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">MySQL</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Stripe API</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-cyan-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 3: School Management System -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-100" data-category="software-development">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80" alt="School Management System" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 backdrop-blur-md">Software Dev</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-graduation-cap text-emerald-400 mr-1"></i> Education</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-emerald-400 transition-colors">School Management System</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">Complete solution for student records, attendance, online admissions, exams, fee management, and portals.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">PHP</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">MySQL</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">JavaScript</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 4: Mobile Business Application -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="mobile-apps">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=800&q=80" alt="Mobile Business Application" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-indigo-500/20 border border-indigo-500/40 text-indigo-400 backdrop-blur-md">Mobile Applications</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-mobile text-indigo-400 mr-1"></i> Finance & Commerce</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-indigo-400 transition-colors">Mobile Business Application</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">Cross-platform mobile application with secure authentication, push notifications, and cloud sync.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">React Native</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Firebase</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Node.js</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 5: AI Customer Support Assistant -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-75" data-category="ai-solutions">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=800&q=80" alt="AI Customer Support Assistant" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-sky-500/20 border border-sky-500/40 text-sky-400 backdrop-blur-md">AI Solutions</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-robot text-sky-400 mr-1"></i> AI & SaaS</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-sky-400 transition-colors">AI Customer Support Assistant</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">AI-powered chatbot capable of handling inquiries, automating support, and boosting user satisfaction.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">OpenAI API</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Python</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">WebSockets</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-sky-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 6: Hospital Management System -->
                <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-100" data-category="software-development">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80" alt="Hospital Management System" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">Software Dev</span>
                    </div>
                    <div class="p-7 flex flex-col flex-grow justify-between gap-4">
                        <div>
                            <span class="text-xs text-slate-400 font-medium block mb-1"><i class="fa-solid fa-hospital text-blue-400 mr-1"></i> Healthcare</span>
                            <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors">Hospital Management System</h3>
                            <p class="text-slate-300 text-sm mt-2 leading-relaxed">Healthcare platform for appointment booking, patient records, billing, pharmacy, and doctor scheduling.</p>
                        </div>
                        <div>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">Laravel</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">MySQL</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-white/5">REST API</span>
                            </div>
                            <a href="projects.php" class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 hover:text-white group/btn">
                                <span>Read Full Case Study</span>
                                <i class="fa-solid fa-arrow-right text-[10px] group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>

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
                        1250+ People Say About Us
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

    <!-- Estimate & Free Project Analysis Form Section -->
    <section class="py-24 relative overflow-hidden bg-brand-darker">
        <div class="glow-bg top-20 right-20"></div>
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left Details Column -->
                <div class="lg:col-span-6 flex flex-col gap-6">
                    <span class="text-sm font-bold tracking-wider text-brand-accent uppercase font-heading font-medium">Start Collaborating</span>
                    <h2 class="font-heading font-black text-4xl sm:text-5xl text-white leading-tight">
                        Let's Work For Your Next Projects!
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        Ready to scale your business infrastructure? Reach out to our specialist engineering teams. We analyze your targets and create modular blueprints built for high loads.
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
                            <div>
                                <label for="form-name" class="sr-only">Full Name</label>
                                <input type="text" name="name" id="form-name" placeholder="Full Name" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                            </div>
                            <div>
                                <label for="form-email" class="sr-only">Email Address</label>
                                <input type="email" name="email" id="form-email" placeholder="Email Address" required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm">
                            </div>
                            <div>
                                <label for="form-service" class="sr-only">Select Service</label>
                                <select name="service" id="form-service" class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 focus:outline-none focus:border-brand-accent text-sm">
                                    <option value="" disabled selected class="text-slate-600">Select Service Required</option>
                                    <option value="consulting">IT Consultation</option>
                                    <option value="cybersecurity">Cyber Security</option>
                                    <option value="software">Software Engineering</option>
                                    <option value="cloud">Cloud Integration</option>
                                </select>
                            </div>
                            <div>
                                <label for="form-message" class="sr-only">Brief Description</label>
                                <textarea name="message" id="form-message" rows="3" placeholder="Briefly describe your project details..." required class="w-full bg-brand-dark/50 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-brand-accent text-sm resize-none"></textarea>
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
        });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>
