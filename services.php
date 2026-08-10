<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/models/Service.php';
require_once __DIR__ . '/models/Setting.php';

$services = [];
try {
    $services = Service::getAll('published');
} catch (Exception $e) {
    $services = [];
}

include __DIR__ . '/includes/header.php';
?>

<!-- ==================== HERO / PAGE BANNER SECTION ==================== -->
<section class="relative bg-[#081018] py-24 md:py-32 border-b border-slate-900/80 overflow-hidden">
    <!-- Glowing Radial Accents -->
    <div class="glow-bg top-0 right-1/4 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-10 opacity-30"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center flex flex-col items-center gap-6 mt-8">
        <!-- Small Label Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase reveal-on-scroll">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>MODERN TECHNOLOGY</span>
        </div>

        <!-- Main Heading -->
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white max-w-4xl leading-[1.15] tracking-tight reveal-on-scroll delay-75">
            Modern Technology & <span class="text-gradient-blue">Digital Innovation Services</span>
        </h1>

        <!-- Introduction Text -->
        <p class="text-slate-400 text-base sm:text-lg max-w-3xl font-medium leading-relaxed reveal-on-scroll delay-100">
            At <strong class="text-white font-semibold">DigiRare Technologies</strong>, we combine innovation, expertise, and the latest technologies to help businesses build secure, scalable, and future-ready digital solutions. From custom software development to cloud infrastructure, AI integration, and cybersecurity, our team delivers high-performance solutions that accelerate growth, improve efficiency, and drive digital transformation.
        </p>

        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="mt-2 reveal-on-scroll delay-150">
            <ol class="inline-flex items-center space-x-2 text-sm font-semibold tracking-wider font-heading uppercase text-slate-400">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-xs text-blue-400"></i> Home
                    </a>
                </li>
                <li><span class="text-slate-600">/</span></li>
                <li class="text-blue-400 font-bold">Services & Innovation</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ==================== MODERN TECHNOLOGY 6 PILLARS SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden border-b border-slate-900/80">
    <div class="glow-bg bottom-10 right-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col items-center gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-microchip text-xs"></i> CORE CAPABILITIES
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                Engineered for <span class="text-gradient-cyan">Future Growth & Speed</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                Discover our specialized technical capabilities designed to scale modern enterprises effortlessly.
            </p>
        </div>

        <!-- 6 Core Technology Pillar Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Pillar 1: Agile Software Development -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-2xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shrink-0">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-blue-400 transition-colors">
                        🚀 Agile Software Development
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        We build custom web applications, enterprise software, and business solutions using Agile methodologies, ensuring faster delivery, continuous improvements, clean code, and outstanding performance.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-blue-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Pillar 2: Cloud Infrastructure & DevOps -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-cyan-500/15 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-2xl group-hover:scale-110 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all shrink-0">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-cyan-400 transition-colors">
                        ☁️ Cloud Infrastructure & DevOps
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Our cloud experts design secure, scalable, and highly available infrastructures using AWS, Microsoft Azure, Google Cloud Platform, Docker, Kubernetes, and automated CI/CD pipelines to maximize efficiency and reliability.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-cyan-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Pillar 3: Cybersecurity & Data Protection -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-400 text-2xl group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all shrink-0">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-indigo-400 transition-colors">
                        🔒 Cybersecurity & Data Protection
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Protect your business with advanced security solutions including threat detection, vulnerability assessments, data encryption, firewall management, secure authentication, and proactive monitoring to keep your systems safe.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Pillar 4: Artificial Intelligence & Automation -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-sky-500/15 border border-sky-500/30 flex items-center justify-center text-sky-400 text-2xl group-hover:scale-110 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all shrink-0">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-sky-400 transition-colors">
                        🤖 Artificial Intelligence & Automation
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Leverage AI-powered technologies to automate workflows, improve customer experiences, analyze business data, and increase operational efficiency through intelligent digital solutions.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-sky-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Pillar 5: UI/UX Design & Digital Experience -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-400 text-2xl group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all shrink-0">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-purple-400 transition-colors">
                        🎨 UI/UX Design & Digital Experience
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Create engaging digital experiences with intuitive interfaces, responsive designs, and user-centered solutions that enhance customer satisfaction and strengthen your brand.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-purple-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Pillar 6: High Performance & Scalability -->
            <div class="bg-saas-card bg-saas-card-hover p-8 rounded-3xl border border-white/10 flex flex-col justify-between gap-6 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="flex flex-col gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-400 text-2xl group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-slate-950 transition-all shrink-0">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="font-heading font-black text-2xl text-white group-hover:text-amber-400 transition-colors">
                        ⚡ High Performance & Scalability
                    </h3>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                        Our applications are optimized for speed, security, and scalability using modern development practices, ensuring reliable performance as your business grows.
                    </p>
                </div>
                <div class="pt-4 border-t border-white/5">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 uppercase tracking-wider group/btn">
                        <span>Get Started</span>
                        <i class="fa-solid fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ==================== ALL CORE DIGITAL SERVICES GRID ==================== -->
<section id="services-grid" class="py-24 relative bg-[#060c14] overflow-hidden">
    <div class="glow-bg-emerald top-0 left-10 opacity-20"></div>
    
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-layer-group text-xs"></i> END-TO-END SOLUTIONS
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                Our Full Suite of <span class="text-gradient-blue">Digital & Engineering Services</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                Explore our comprehensive offerings tailored for startups, small businesses, and growing enterprises worldwide.
            </p>
        </div>

        <!-- Dynamic Services 2-Column Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <?php 
            $delay = 0;
            foreach ($services as $svc): 
                $svgIcon = getServiceIcon($svc['title']);
            ?>
            <!-- Service Card -->
            <div class="premium-service-card p-8 sm:p-10 flex flex-col justify-between gap-6 group reveal-on-scroll <?php echo ($delay > 0) ? 'delay-' . $delay : ''; ?>">
                <div class="ambient-glow"></div>
                <div class="flex flex-col gap-6 z-10">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 group-hover:bg-gradient-to-tr group-hover:from-blue-600 group-hover:to-cyan-400 group-hover:text-slate-950 transition-all duration-300 relative overflow-hidden shrink-0">
                        <?php echo $svgIcon; ?>
                    </div>
                    <div class="flex flex-col gap-3">
                        <h3 class="font-heading font-black text-2xl text-white group-hover:text-blue-400 transition-colors">
                            <?php echo htmlspecialchars($svc['title']); ?>
                        </h3>
                        <p class="text-slate-300 leading-relaxed text-sm sm:text-base">
                            <?php echo htmlspecialchars($svc['description']); ?>
                        </p>
                        <?php if (!empty($svc['long_description'])): ?>
                        <p class="text-slate-400 leading-relaxed text-xs pt-2 border-t border-slate-800/80">
                            <?php echo htmlspecialchars($svc['long_description']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/5 flex items-center justify-between z-10">
                    <a href="contact.php" class="inline-flex items-center gap-2 text-blue-400 hover:text-white text-xs font-bold uppercase tracking-wider group/btn">
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

    </div>
</section>

<!-- ==================== CALL TO ACTION SECTION ==================== -->
<section class="py-24 relative overflow-hidden bg-gradient-to-b from-[#081018] via-[#050a10] to-[#04080e] border-t border-slate-900">
    <div class="glow-bg top-0 right-1/3 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-1/3 opacity-30"></div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10 text-center flex flex-col items-center gap-8 reveal-on-scroll">
        
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            TRANSFORM YOUR DIGITAL CAPABILITIES
        </span>

        <h2 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
            Ready to Transform Your Business with <span class="text-gradient-blue">Modern Technology?</span>
        </h2>
        
        <p class="text-slate-300 text-lg sm:text-xl max-w-2xl leading-relaxed">
            Partner with <strong class="text-white font-semibold">DigiRare Technologies</strong> to build innovative, secure, and scalable digital solutions that help your business stay competitive in today's digital world.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
            <a href="contact.php" class="btn-gradient-blue px-8 py-4 rounded-full font-heading font-bold text-white text-base flex items-center justify-center gap-3 group shadow-lg shadow-blue-500/25 hover:scale-[1.02] transition-all">
                <span>GET YOUR WEBSITE NOW</span>
                <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <a href="#services-grid" class="px-8 py-4 rounded-full font-heading font-bold text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all text-base flex items-center justify-center gap-2 backdrop-blur-md">
                <span>Explore Our Services</span>
                <i class="fa-solid fa-layer-group text-xs text-blue-400"></i>
            </a>
        </div>

    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
