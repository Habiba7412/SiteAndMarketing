<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/models/Project.php';

// Fetch dynamic projects from DB if available
$dbProjects = [];
try {
    $dbProjects = Project::getAll('published');
} catch (Exception $e) {
    $dbProjects = [];
}

include __DIR__ . '/includes/header.php';
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="relative bg-[#081018] py-24 md:py-32 border-b border-slate-900/80 overflow-hidden">
    <!-- Glowing Background Accents -->
    <div class="glow-bg top-0 right-1/4 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-10 opacity-30"></div>
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center flex flex-col items-center gap-6 mt-8">
        <!-- Small Label Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase reveal-on-scroll">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>OUR PORTFOLIO</span>
        </div>

        <!-- Main Heading -->
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white max-w-4xl leading-[1.15] tracking-tight reveal-on-scroll delay-75">
            Innovative Projects That <span class="text-gradient-blue">Deliver Real Business Results</span>
        </h1>

        <!-- Description -->
        <p class="text-slate-400 text-base sm:text-lg max-w-3xl font-medium leading-relaxed reveal-on-scroll delay-100">
            Explore our portfolio of successful digital solutions built for startups, businesses, and enterprises. Every project reflects our commitment to innovation, quality, performance, and exceptional user experiences.
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
                <li class="text-blue-400 font-bold">Portfolio & Case Studies</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ==================== PORTFOLIO & CATEGORY FILTER SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden">
    <div class="glow-bg bottom-10 right-10 opacity-25"></div>
    
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Category Filter Tabs -->
        <div class="flex items-center justify-center gap-2.5 flex-wrap mb-16 reveal-on-scroll" id="portfolio-tabs">
            <button data-filter="all" class="portfolio-tab-btn active px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/25 border border-blue-400/30 transition-all duration-300">
                All Projects
            </button>
            <button data-filter="web-development" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Web Development
            </button>
            <button data-filter="software-development" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Software Development
            </button>
            <button data-filter="mobile-apps" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Mobile Applications
            </button>
            <button data-filter="ui-ux" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                UI/UX Design
            </button>
            <button data-filter="digital-marketing" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Digital Marketing
            </button>
            <button data-filter="branding" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Branding
            </button>
            <button data-filter="cloud-devops" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                Cloud & DevOps
            </button>
            <button data-filter="ai-solutions" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                AI Solutions
            </button>
            <button data-filter="e-commerce" class="portfolio-tab-btn px-5 py-2.5 rounded-full font-heading text-xs sm:text-sm font-bold bg-slate-900/60 border border-slate-800 text-slate-400 hover:border-blue-500/40 hover:text-white transition-all duration-300">
                E-Commerce
            </button>
        </div>

        <!-- Project Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="projects-grid">
            
            <!-- Project Card 1: Enterprise Business Website -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="web-development">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80" 
                         alt="Enterprise Business Website" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">
                        Web Development
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-building text-blue-400 mr-1"></i> Corporate</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Jan 2026</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors">
                            Enterprise Business Website
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Designed and developed a fully responsive corporate website with modern UI, SEO optimization, high performance, and an easy-to-manage content management system.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">HTML5</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Tailwind CSS</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">JavaScript</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">PHP</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                        </div>

                        <button onclick="openProjectModal('Enterprise Business Website', 'Web Development', 'Corporate Enterprise', 'Jan 2026', 'Designed and developed a fully responsive corporate website with modern UI, SEO optimization, high performance, and an easy-to-manage content management system.', ['HTML5', 'Tailwind CSS', 'JavaScript', 'PHP', 'MySQL'], 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 2: E-Commerce Platform -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-75" data-category="e-commerce">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1556742049-0a6754099a6b?auto=format&fit=crop&w=800&q=80" 
                         alt="E-Commerce Platform" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 backdrop-blur-md">
                        E-Commerce
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-cart-shopping text-cyan-400 mr-1"></i> Retail & E-Commerce</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Nov 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-cyan-400 transition-colors">
                            E-Commerce Platform
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Built a secure online shopping platform featuring product management, payment integration, inventory control, order tracking, customer dashboards, and advanced analytics.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Laravel</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Bootstrap</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Stripe API</span>
                        </div>

                        <button onclick="openProjectModal('E-Commerce Platform', 'E-Commerce', 'Retail & E-Commerce', 'Nov 2025', 'Built a secure online shopping platform featuring product management, payment integration, inventory control, order tracking, customer dashboards, and advanced analytics.', ['Laravel', 'MySQL', 'Bootstrap', 'Stripe API'], 'https://images.unsplash.com/photo-1556742049-0a6754099a6b?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 3: School Management System -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-100" data-category="software-development">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80" 
                         alt="School Management System" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 backdrop-blur-md">
                        Software Development
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-graduation-cap text-emerald-400 mr-1"></i> Education</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Oct 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-emerald-400 transition-colors">
                            School Management System
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Developed a complete school management solution including student records, attendance, online admissions, examinations, fee management, and teacher portals.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">PHP</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">JavaScript</span>
                        </div>

                        <button onclick="openProjectModal('School Management System', 'Software Development', 'Education & EdTech', 'Oct 2025', 'Developed a complete school management solution including student records, attendance, online admissions, examinations, fee management, and teacher portals.', ['PHP', 'MySQL', 'JavaScript'], 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 4: Hospital Management System -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="software-development">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=800&q=80" 
                         alt="Hospital Management System" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">
                        Software Development
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-hospital text-blue-400 mr-1"></i> Healthcare</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Sep 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors">
                            Hospital Management System
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Created a digital healthcare platform for appointment booking, patient records, billing, pharmacy management, laboratory reports, and doctor scheduling.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Laravel</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">REST API</span>
                        </div>

                        <button onclick="openProjectModal('Hospital Management System', 'Software Development', 'Healthcare & Medical', 'Sep 2025', 'Created a digital healthcare platform for appointment booking, patient records, billing, pharmacy management, laboratory reports, and doctor scheduling.', ['Laravel', 'MySQL', 'REST API'], 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 5: Real Estate Platform -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-75" data-category="web-development">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80" 
                         alt="Real Estate Platform" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-cyan-500/20 border border-cyan-500/40 text-cyan-400 backdrop-blur-md">
                        Web Development
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-house-chimney text-cyan-400 mr-1"></i> Real Estate</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Aug 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-cyan-400 transition-colors">
                            Real Estate Platform
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Designed a property listing platform with advanced search filters, Google Maps integration, user dashboards, property management, and lead generation tools.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">PHP</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">JavaScript</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                        </div>

                        <button onclick="openProjectModal('Real Estate Platform', 'Web Development', 'Real Estate & Property', 'Aug 2025', 'Designed a property listing platform with advanced search filters, Google Maps integration, user dashboards, property management, and lead generation tools.', ['PHP', 'JavaScript', 'MySQL'], 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 6: Restaurant Ordering System -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-100" data-category="web-development">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" 
                         alt="Restaurant Ordering System" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 backdrop-blur-md">
                        Web Development
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-utensils text-emerald-400 mr-1"></i> Hospitality</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Jul 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-emerald-400 transition-colors">
                            Restaurant Ordering System
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Built an online food ordering platform with menu management, online payments, order tracking, table reservations, and customer loyalty features.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Laravel</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">MySQL</span>
                        </div>

                        <button onclick="openProjectModal('Restaurant Ordering System', 'Web Development', 'Hospitality & Food Tech', 'Jul 2025', 'Built an online food ordering platform with menu management, online payments, order tracking, table reservations, and customer loyalty features.', ['Laravel', 'MySQL'], 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 7: Digital Marketing Campaign -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="digital-marketing">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=800&q=80" 
                         alt="Digital Marketing Campaign" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-purple-500/20 border border-purple-500/40 text-purple-400 backdrop-blur-md">
                        Digital Marketing
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-bullhorn text-purple-400 mr-1"></i> Tech & SaaS</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Jun 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-purple-400 transition-colors">
                            Digital Marketing Campaign
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Executed a complete digital marketing strategy including SEO, Google Ads, Meta Ads, content marketing, and conversion optimization that significantly increased online visibility and lead generation.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">SEO</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Google Ads</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Facebook Ads</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Instagram Marketing</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Analytics</span>
                        </div>

                        <button onclick="openProjectModal('Digital Marketing Campaign', 'Digital Marketing', 'Tech & SaaS', 'Jun 2025', 'Executed a complete digital marketing strategy including SEO, Google Ads, Meta Ads, content marketing, and conversion optimization that significantly increased online visibility and lead generation.', ['SEO', 'Google Ads', 'Facebook Ads', 'Instagram Marketing', 'Analytics'], 'https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 8: Brand Identity Design -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-75" data-category="branding">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=800&q=80" 
                         alt="Brand Identity Design" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-amber-500/20 border border-amber-500/40 text-amber-400 backdrop-blur-md">
                        Branding
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-pen-nib text-amber-400 mr-1"></i> Creative Corporate</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> May 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-amber-400 transition-colors">
                            Brand Identity Design
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Developed a complete visual identity including logo design, typography, color systems, business stationery, social media branding, and brand guidelines.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Figma</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Illustrator</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Brand Strategy</span>
                        </div>

                        <button onclick="openProjectModal('Brand Identity Design', 'Branding', 'Creative & Corporate', 'May 2025', 'Developed a complete visual identity including logo design, typography, color systems, business stationery, social media branding, and brand guidelines.', ['Figma', 'Adobe Illustrator', 'Brand Strategy'], 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 9: Mobile Business Application -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll delay-100" data-category="mobile-apps">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=800&q=80" 
                         alt="Mobile Business Application" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-indigo-500/20 border border-indigo-500/40 text-indigo-400 backdrop-blur-md">
                        Mobile Applications
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-mobile text-indigo-400 mr-1"></i> Finance & Commerce</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Apr 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-indigo-400 transition-colors">
                            Mobile Business Application
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Created a cross-platform mobile application with secure authentication, push notifications, real-time updates, and cloud synchronization.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">React Native</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Firebase</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Node.js</span>
                        </div>

                        <button onclick="openProjectModal('Mobile Business Application', 'Mobile Applications', 'Finance & Commerce', 'Apr 2025', 'Created a cross-platform mobile application with secure authentication, push notifications, real-time updates, and cloud synchronization.', ['React Native', 'Firebase', 'Node.js'], 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Project Card 10: AI Customer Support Assistant -->
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="ai-solutions">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=800&q=80" 
                         alt="AI Customer Support Assistant" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-sky-500/20 border border-sky-500/40 text-sky-400 backdrop-blur-md">
                        AI Solutions
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-robot text-sky-400 mr-1"></i> AI & SaaS</span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> Mar 2025</span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-sky-400 transition-colors">
                            AI Customer Support Assistant
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            Implemented an AI-powered chatbot capable of handling customer inquiries, automating support, and improving customer satisfaction with intelligent responses.
                        </p>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">OpenAI API</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Python</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">Node.js</span>
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5">WebSockets</span>
                        </div>

                        <button onclick="openProjectModal('AI Customer Support Assistant', 'AI Solutions', 'SaaS & Customer Tech', 'Mar 2025', 'Implemented an AI-powered chatbot capable of handling customer inquiries, automating support, and improving customer satisfaction with intelligent responses.', ['OpenAI API', 'Python', 'Node.js', 'WebSockets'], 'https://images.unsplash.com/photo-1677442136019-21780efad99a?auto=format&fit=crop&w=1200&q=80')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dynamic DB Projects (If any exist) -->
            <?php foreach ($dbProjects as $dbProj): 
                $catLower = strtolower($dbProj['category']);
                $dataCat = 'web-development';
                if (strpos($catLower, 'software') !== false) $dataCat = 'software-development';
                elseif (strpos($catLower, 'mobile') !== false || strpos($catLower, 'app') !== false) $dataCat = 'mobile-apps';
                elseif (strpos($catLower, 'design') !== false || strpos($catLower, 'ui') !== false) $dataCat = 'ui-ux';
                elseif (strpos($catLower, 'market') !== false || strpos($catLower, 'seo') !== false) $dataCat = 'digital-marketing';
                elseif (strpos($catLower, 'brand') !== false) $dataCat = 'branding';
                elseif (strpos($catLower, 'cloud') !== false || strpos($catLower, 'devops') !== false) $dataCat = 'cloud-devops';
                elseif (strpos($catLower, 'ai') !== false) $dataCat = 'ai-solutions';
                elseif (strpos($catLower, 'commerce') !== false) $dataCat = 'e-commerce';
            ?>
            <div class="project-card bg-saas-card bg-saas-card-hover rounded-3xl overflow-hidden border border-white/10 flex flex-col group transition-all duration-500 reveal-on-scroll" data-category="<?php echo $dataCat; ?>">
                <div class="aspect-[16/10] overflow-hidden relative">
                    <img src="<?php echo htmlspecialchars($dbProj['image_url'] ?: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80'); ?>" 
                         alt="<?php echo htmlspecialchars($dbProj['title']); ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-90"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400 backdrop-blur-md">
                        <?php echo htmlspecialchars($dbProj['category']); ?>
                    </span>
                </div>
                <div class="p-7 flex flex-col flex-grow justify-between gap-5">
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                            <span><i class="fa-solid fa-user-check text-blue-400 mr-1"></i> <?php echo htmlspecialchars($dbProj['client'] ?: 'Enterprise Partner'); ?></span>
                            <span><i class="fa-solid fa-calendar-days text-slate-500 mr-1"></i> <?php echo htmlspecialchars($dbProj['year'] ?: date('Y')); ?></span>
                        </div>
                        <h3 class="font-heading font-black text-xl text-white group-hover:text-blue-400 transition-colors">
                            <?php echo htmlspecialchars($dbProj['title']); ?>
                        </h3>
                        <p class="text-slate-300 text-sm mt-3 leading-relaxed">
                            <?php echo htmlspecialchars($dbProj['description']); ?>
                        </p>
                    </div>

                    <div>
                        <?php if (!empty($dbProj['tags'])): ?>
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <?php foreach (explode(',', $dbProj['tags']) as $tag): ?>
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-800/80 text-slate-300 border border-white/5"><?php echo htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <button onclick="openProjectModal('<?php echo addslashes($dbProj['title']); ?>', '<?php echo addslashes($dbProj['category']); ?>', '<?php echo addslashes($dbProj['client'] ?: 'Enterprise Partner'); ?>', '<?php echo addslashes($dbProj['year'] ?: date('Y')); ?>', '<?php echo addslashes($dbProj['description']); ?>', <?php echo json_encode(explode(',', $dbProj['tags'] ?: 'Software,Tech')); ?>, '<?php echo htmlspecialchars($dbProj['image_url'] ?: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80'); ?>')" 
                                class="w-full py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider text-white bg-slate-800/80 hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/20 border border-white/10 transition-all flex items-center justify-center gap-2 group/btn">
                            <span>View Case Study</span>
                            <i class="fa-solid fa-arrow-right text-xs text-blue-400 group-hover/btn:text-white group-hover/btn:translate-x-1 transition-all"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>

<!-- ==================== STATISTICS SECTION ==================== -->
<section id="portfolio-stats-section" class="py-20 relative bg-[#060c14] border-y border-slate-900/80 overflow-hidden">
    <div class="glow-bg-emerald top-1/2 left-1/3 opacity-25"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 text-center">
            
            <!-- Stat 1 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-blue portfolio-counter" data-target="150" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Completed Projects</span>
            </div>

            <!-- Stat 2 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-75">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-face-smile"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-cyan portfolio-counter" data-target="80" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Happy Clients</span>
            </div>

            <!-- Stat 3 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-100">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-blue portfolio-counter" data-target="15" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Industries Served</span>
            </div>

            <!-- Stat 4 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-150">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-star text-amber-400"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-cyan portfolio-counter" data-target="99" data-suffix="%">0%</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Client Satisfaction</span>
            </div>

        </div>

    </div>
</section>

<!-- ==================== WHY OUR PROJECTS STAND OUT SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden">
    <div class="glow-bg bottom-0 right-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col items-center gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-award text-xs"></i> ENGINEERING STANDARDS
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                Why Our Projects <span class="text-gradient-cyan">Stand Out</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                Every software solution we deliver is backed by industry best practices, robust security, and obsessive attention to detail.
            </p>
        </div>

        <!-- 10 Feature Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            
            <!-- Feature 1 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Custom Built Solutions</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Tailored architecture specifically crafted for your business goals.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Modern UI/UX Design</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Intuitive, engaging user interfaces that boost conversion rates.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">SEO Optimized</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Semantic HTML & high speed performance for search visibility.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-mobile-screen"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Mobile Responsive</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Fluid layouts seamlessly adapting to all mobile & desktop viewports.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Fast Loading Performance</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Optimized code & assets delivering lightning quick page loads.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Secure Architecture</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Enterprise level encryption and protection against vulnerability.</p>
            </div>

            <!-- Feature 7 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Scalable Development</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Future-ready code bases designed to expand as your business grows.</p>
            </div>

            <!-- Feature 8 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-code-branch"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Clean Code Standards</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Well-documented, modular code strictly adhering to global standards.</p>
            </div>

            <!-- Feature 9 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-rotate"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Ongoing Maintenance</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Continuous updates, security monitoring, and regular enhancements.</p>
            </div>

            <!-- Feature 10 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Dedicated Support</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Direct access to core software engineers and account managers.</p>
            </div>

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
            START YOUR SUCCESS STORY
        </span>

        <h2 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
            Ready to Start <span class="text-gradient-blue">Your Project?</span>
        </h2>
        
        <p class="text-slate-300 text-lg sm:text-xl max-w-2xl leading-relaxed">
            Whether you're launching a startup, scaling your business, or transforming your digital presence, our team is ready to build innovative solutions that deliver measurable results.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
            <a href="contact.php" class="btn-gradient-blue px-8 py-4 rounded-full font-heading font-bold text-white text-base flex items-center justify-center gap-3 group">
                <span>Start Your Project</span>
                <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <a href="contact.php" class="px-8 py-4 rounded-full font-heading font-bold text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all text-base flex items-center justify-center gap-2 backdrop-blur-md">
                <span>GET YOUR WEBSITE NOW</span>
                <i class="fa-solid fa-arrow-right text-xs text-blue-400"></i>
            </a>
        </div>

    </div>
</section>

<!-- ==================== CASE STUDY MODAL OVERLAY ==================== -->
<div id="project-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-slate-900 border border-white/10 rounded-3xl max-w-3xl w-full overflow-hidden shadow-2xl relative flex flex-col max-h-[90vh] scale-95 transition-transform duration-300" id="project-modal-container">
        
        <!-- Modal Close Button -->
        <button onclick="closeProjectModal()" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-slate-800/80 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-600 transition-all flex items-center justify-center z-20">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>

        <!-- Modal Content Container -->
        <div class="overflow-y-auto p-6 md:p-8 space-y-6">
            <div class="aspect-video w-full rounded-2xl overflow-hidden relative border border-white/5">
                <img id="modal-image" src="" alt="Project Preview" class="w-full h-full object-cover">
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <span id="modal-category" class="px-3 py-1 rounded-full text-xs font-bold font-heading bg-blue-500/20 border border-blue-500/40 text-blue-400">
                        Category
                    </span>
                    <span class="text-xs text-slate-400 font-medium" id="modal-industry">Industry</span>
                    <span class="text-xs text-slate-500">•</span>
                    <span class="text-xs text-slate-400 font-medium" id="modal-date">Date</span>
                </div>

                <h3 id="modal-title" class="font-heading font-black text-2xl sm:text-3xl text-white">Project Title</h3>
                
                <p id="modal-description" class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Description...
                </p>

                <div>
                    <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-slate-400 mb-2">Technologies Used</h4>
                    <div id="modal-tech-stack" class="flex flex-wrap gap-2">
                        <!-- Badges injected dynamically -->
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <span class="text-xs text-slate-400">Built with enterprise standards by Site And Marketing Technologies</span>
                    <a href="contact.php" class="btn-gradient-blue px-6 py-2.5 rounded-full font-heading font-bold text-xs text-white uppercase tracking-wider flex items-center gap-2">
                        <span>Request Similar Project</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ==================== JAVASCRIPT FILTER & INTERACTION LOGIC ==================== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Portfolio Category Filtering
    const tabs = document.querySelectorAll('.portfolio-tab-btn');
    const cards = document.querySelectorAll('.project-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-gradient-to-r', 'from-blue-600', 'to-cyan-500', 'text-white', 'shadow-lg', 'shadow-blue-500/25', 'border-blue-400/30');
                t.classList.add('bg-slate-900/60', 'border-slate-800', 'text-slate-400');
            });

            tab.classList.add('active', 'bg-gradient-to-r', 'from-blue-600', 'to-cyan-500', 'text-white', 'shadow-lg', 'shadow-blue-500/25', 'border-blue-400/30');
            tab.classList.remove('bg-slate-900/60', 'border-slate-800', 'text-slate-400');

            const filter = tab.getAttribute('data-filter');

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Stats Counter Animation
    const statsSection = document.getElementById('portfolio-stats-section');
    if (statsSection) {
        const counters = document.querySelectorAll('.portfolio-counter');
        const speed = 1500;

        const animateCounters = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const suffix = counter.getAttribute('data-suffix') || '';
                const startTime = performance.now();

                const updateCount = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / speed, 1);
                    const easeValue = progress * (2 - progress);
                    const currentCount = Math.floor(easeValue * target);

                    counter.innerText = currentCount + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.innerText = target + suffix;
                    }
                };
                requestAnimationFrame(updateCount);
            });
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        observer.observe(statsSection);
    }
});

// Modal Logic
function openProjectModal(title, category, industry, date, description, techStack, imageUrl) {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-category').innerText = category;
    document.getElementById('modal-industry').innerText = industry;
    document.getElementById('modal-date').innerText = date;
    document.getElementById('modal-description').innerText = description;
    document.getElementById('modal-image').src = imageUrl;

    const techContainer = document.getElementById('modal-tech-stack');
    techContainer.innerHTML = '';
    techStack.forEach(tech => {
        const span = document.createElement('span');
        span.className = 'px-3 py-1 rounded-md text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20';
        span.innerText = tech;
        techContainer.appendChild(span);
    });

    const modal = document.getElementById('project-modal');
    const container = document.getElementById('project-modal-container');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    container.classList.remove('scale-95');
    container.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}

function closeProjectModal() {
    const modal = document.getElementById('project-modal');
    const container = document.getElementById('project-modal-container');
    modal.classList.add('opacity-0', 'pointer-events-none');
    container.classList.remove('scale-100');
    container.classList.add('scale-95');
    document.body.style.overflow = 'auto';
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
