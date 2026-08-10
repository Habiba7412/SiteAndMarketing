<?php
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';
?>

<!-- ==================== HERO / HEADER BANNER SECTION ==================== -->
<section class="relative bg-[#081018] py-24 md:py-32 border-b border-slate-900/80 overflow-hidden">
    <!-- Glowing Radial Accents -->
    <div class="glow-bg top-0 right-1/4 opacity-40"></div>
    <div class="glow-bg-emerald bottom-0 left-10 opacity-30"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-blue-600/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10 text-center flex flex-col items-center gap-6 mt-8">
        <!-- Small Label badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase reveal-on-scroll">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>OUR STORY</span>
        </div>

        <!-- Main Heading -->
        <h1 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white max-w-4xl leading-[1.15] tracking-tight reveal-on-scroll delay-75">
            Building Innovative Digital Solutions That <span class="text-gradient-blue">Empower Businesses Worldwide</span>
        </h1>

        <!-- Subtitle & Breadcrumbs -->
        <p class="text-slate-400 text-base sm:text-lg max-w-2xl font-medium leading-relaxed reveal-on-scroll delay-100">
            DigiRare Technologies is your trusted partner for custom software engineering, scalable web applications, and transformative digital experiences.
        </p>

        <nav aria-label="Breadcrumb" class="mt-2 reveal-on-scroll delay-150">
            <ol class="inline-flex items-center space-x-2 text-sm font-semibold tracking-wider font-heading uppercase text-slate-400">
                <li class="inline-flex items-center">
                    <a href="index.php" class="hover:text-blue-400 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-house text-xs text-blue-400"></i> Home
                    </a>
                </li>
                <li><span class="text-slate-600">/</span></li>
                <li class="text-blue-400 font-bold">About Us</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ==================== ABOUT CONTENT / OUR STORY SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden">
    <div class="glow-bg bottom-10 right-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <!-- Left Column: Story Text -->
            <div class="lg:col-span-7 flex flex-col gap-6 reveal-on-scroll">
                <div class="inline-flex items-center gap-2 text-xs font-extrabold tracking-widest text-blue-400 uppercase font-heading">
                    <i class="fa-solid fa-layer-group"></i> WHO WE ARE
                </div>
                <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                    Transforming Ideas Into <span class="text-gradient-cyan">Scalable Digital Products</span>
                </h2>
                
                <div class="flex flex-col gap-5 text-slate-300 leading-relaxed text-base sm:text-lg">
                    <p>
                        At <strong class="text-white font-semibold">DigiRare Technologies</strong>, we are passionate about helping businesses grow through innovative technology solutions. Our mission is to transform ideas into powerful digital experiences that increase efficiency, strengthen brands, and drive long-term success.
                    </p>
                    <p>
                        We specialize in custom software development, responsive website design, web applications, mobile solutions, UI/UX design, cloud integration, branding, and digital marketing. Every project is built with a focus on performance, security, scalability, and user experience.
                    </p>
                    <p>
                        Our experienced team works closely with startups, small businesses, and enterprises to understand their goals and deliver solutions tailored to their unique requirements. We believe that every business deserves technology that is reliable, modern, and future-ready.
                    </p>
                    <p>
                        By combining creativity, innovation, and technical expertise, we help our clients stay ahead in today's rapidly evolving digital world. Our commitment to quality, transparency, and customer satisfaction has earned us the trust of businesses across multiple industries.
                    </p>
                    <p class="font-medium text-white border-l-4 border-blue-500 pl-4 py-1 italic bg-blue-950/20 rounded-r-xl">
                        Whether you're launching a new startup, modernizing your existing systems, or scaling your digital presence, DigiRare Technologies is your trusted technology partner.
                    </p>
                </div>
            </div>

            <!-- Right Column: Visual Image & Badges -->
            <div class="lg:col-span-5 relative reveal-on-scroll delay-150">
                <div class="relative w-full aspect-[4/5] max-w-[480px] mx-auto">
                    <!-- Ambient Glow Behind Image -->
                    <div class="absolute -top-6 -right-6 w-full h-full rounded-3xl bg-gradient-to-tr from-blue-600/30 to-cyan-400/20 blur-2xl"></div>
                    
                    <!-- Glass Frame Wrapper -->
                    <div class="relative h-full rounded-3xl overflow-hidden border border-white/10 shadow-2xl shadow-black/90 bg-slate-900/60 backdrop-blur-md group">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1000&q=80" 
                             alt="DigiRare Technologies Software Engineering Team" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-[#081018] via-transparent to-transparent opacity-80"></div>

                        <!-- Floating Stat Badge -->
                        <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl glass-panel border border-white/10 flex items-center gap-4 bg-slate-900/80 backdrop-blur-xl">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 shrink-0 text-xl font-bold">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-white text-base">Enterprise Standards</h3>
                                <p class="text-slate-400 text-xs">High Performance & Security Guaranteed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ==================== MISSION & VISION SECTION ==================== -->
<section class="py-24 relative bg-[#060c14] border-y border-slate-900/80 overflow-hidden">
    <div class="glow-bg-emerald top-1/2 left-10 opacity-20"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            
            <!-- Our Mission Card -->
            <div class="bg-saas-card bg-saas-card-hover p-10 rounded-3xl flex flex-col gap-6 relative group border border-white/10 hover:border-blue-500/40 transition-all duration-300 reveal-on-scroll">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600/20 to-cyan-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 text-3xl group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-blue-600 group-hover:to-cyan-400 group-hover:text-slate-950 transition-all duration-300 shrink-0">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <div class="flex flex-col gap-3">
                    <span class="text-xs font-bold tracking-widest text-blue-400 uppercase font-heading">DRIVING PURPOSE</span>
                    <h3 class="font-heading font-black text-2xl sm:text-3xl text-white">Our Mission</h3>
                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                        Deliver innovative, secure, and scalable digital solutions that help businesses grow faster while providing exceptional customer experiences.
                    </p>
                </div>
            </div>

            <!-- Our Vision Card -->
            <div class="bg-saas-card bg-saas-card-hover p-10 rounded-3xl flex flex-col gap-6 relative group border border-white/10 hover:border-cyan-400/40 transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-cyan-500/20 to-teal-400/20 border border-cyan-400/30 flex items-center justify-center text-cyan-400 text-3xl group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-cyan-400 group-hover:to-emerald-400 group-hover:text-slate-950 transition-all duration-300 shrink-0">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <div class="flex flex-col gap-3">
                    <span class="text-xs font-bold tracking-widest text-cyan-400 uppercase font-heading">FUTURE DIRECTION</span>
                    <h3 class="font-heading font-black text-2xl sm:text-3xl text-white">Our Vision</h3>
                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed">
                        To become a globally trusted technology company recognized for innovation, quality, and long-term client success.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ==================== CORE VALUES SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden">
    <div class="glow-bg top-10 right-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col items-center gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-gem text-xs"></i> CORE VALUES
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                The Principles That <span class="text-gradient-blue">Guide Everything We Build</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                Our culture is grounded in values that inspire excellence, foster collaboration, and guarantee total commitment to client success.
            </p>
        </div>

        <!-- 8 Core Value Grid Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Value 1: Innovation -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Innovation</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Continuously exploring state-of-the-art tech and creative ideas to solve complex problems.
                </p>
            </div>

            <!-- Value 2: Integrity -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Integrity</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Upholding the highest ethical standards, transparency, and honesty in every interaction.
                </p>
            </div>

            <!-- Value 3: Excellence -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Excellence</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Striving for uncompromising quality across software engineering, security, and UI/UX design.
                </p>
            </div>

            <!-- Value 4: Customer First -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Customer First</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Placing client needs, business growth, and user satisfaction at the core of our operations.
                </p>
            </div>

            <!-- Value 5: Transparency -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-eye text-lg"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Transparency</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Maintaining open lines of communication, honest project roadmaps, and straightforward pricing.
                </p>
            </div>

            <!-- Value 6: Continuous Learning -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Continuous Learning</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Fostering a growth mindset to adapt quickly to emerging frameworks and industry benchmarks.
                </p>
            </div>

            <!-- Value 7: Teamwork -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Teamwork</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Uniting cross-functional experts to build cohesive, high-impact digital solutions.
                </p>
            </div>

            <!-- Value 8: Quality Assurance -->
            <div class="bg-saas-card bg-saas-card-hover p-7 rounded-2xl border border-white/5 flex flex-col gap-4 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 text-xl group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-vial-circle-check"></i>
                </div>
                <h3 class="font-heading font-bold text-xl text-white">Quality Assurance</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Executing rigorous testing and code audits to ensure bug-free, resilient deployments.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- ==================== STATISTICS SECTION ==================== -->
<section id="stats-counter-section" class="py-20 relative bg-[#060c14] border-y border-slate-900/80 overflow-hidden">
    <div class="glow-bg-emerald top-1/2 left-1/4 opacity-25"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 text-center">
            
            <!-- Stat 1 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-blue stat-counter" data-target="150" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Projects Completed</span>
            </div>

            <!-- Stat 2 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-75">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-face-smile"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-cyan stat-counter" data-target="80" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Happy Clients</span>
            </div>

            <!-- Stat 3 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-100">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-blue stat-counter" data-target="8" data-suffix="+">0+</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Years Experience</span>
            </div>

            <!-- Stat 4 -->
            <div class="flex flex-col items-center justify-center p-6 rounded-2xl glass-panel border border-white/5 reveal-on-scroll delay-150">
                <div class="w-12 h-12 rounded-xl bg-blue-500/15 flex items-center justify-center text-blue-400 text-2xl mb-3">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <span class="font-heading font-black text-4xl sm:text-5xl text-gradient-cyan stat-counter-custom">24/7</span>
                <span class="text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400 mt-2 font-heading">Technical Support</span>
            </div>

        </div>

    </div>
</section>

<!-- ==================== WHY CHOOSE US SECTION ==================== -->
<section class="py-24 relative bg-[#081018] overflow-hidden">
    <div class="glow-bg bottom-0 left-10 opacity-30"></div>
    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 flex flex-col items-center gap-4 reveal-on-scroll">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full glass-badge text-blue-400 font-bold text-xs tracking-widest uppercase">
                <i class="fa-solid fa-star text-xs"></i> WHY CHOOSE US
            </span>
            <h2 class="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white leading-tight">
                Empowering Your Growth With <span class="text-gradient-cyan">Unmatched Technology Advantages</span>
            </h2>
            <p class="text-slate-400 text-base leading-relaxed">
                We combine technical precision, modern architecture, and customer-centric delivery to help your business excel.
            </p>
        </div>

        <!-- 10 Reasons Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            
            <!-- Reason 1 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-code"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Experienced Software Engineers</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Dedicated engineers with deep expertise in scalable architecture.</p>
            </div>

            <!-- Reason 2 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Modern Technologies</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Utilizing modern stacks, APIs, and cloud services for optimum results.</p>
            </div>

            <!-- Reason 3 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-magnifying-glass font-bold"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">SEO-Friendly Development</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Clean semantic markup built to rank higher on search engines.</p>
            </div>

            <!-- Reason 4 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Secure & Scalable Solutions</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Enterprise-level security, encryption, and high availability.</p>
            </div>

            <!-- Reason 5 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-mobile-screen-button"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Responsive Design</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Flawless user experiences across desktops, tablets, and phones.</p>
            </div>

            <!-- Reason 6 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Fast Project Delivery</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Agile development cycles guaranteeing rapid, on-time launches.</p>
            </div>

            <!-- Reason 7 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-75">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Dedicated Client Support</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Proactive communication and continuous operational assistance.</p>
            </div>

            <!-- Reason 8 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-100">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Transparent Communication</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Regular status reports, clear milestones, and zero surprises.</p>
            </div>

            <!-- Reason 9 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-150">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Affordable Pricing</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Maximum ROI with transparent, competitive investment models.</p>
            </div>

            <!-- Reason 10 -->
            <div class="bg-saas-card bg-saas-card-hover p-6 rounded-2xl border border-white/5 flex flex-col gap-3 group transition-all duration-300 reveal-on-scroll delay-200">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 text-lg group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <h3 class="font-heading font-bold text-base text-white">Long-Term Maintenance</h3>
                <p class="text-slate-400 text-xs leading-relaxed">Ongoing security monitoring, updates, and maintenance support.</p>
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
            GET STARTED TODAY
        </span>

        <h2 class="font-heading font-black text-4xl sm:text-5xl lg:text-6xl text-white leading-tight">
            Ready to Build Your Next <span class="text-gradient-blue">Digital Solution?</span>
        </h2>
        
        <p class="text-slate-300 text-lg sm:text-xl max-w-2xl leading-relaxed">
            Partner with DigiRare Technologies and let our experts turn your ideas into innovative, scalable, and high-performing digital products.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-2 w-full sm:w-auto justify-center">
            <a href="contact.php" class="btn-gradient-blue px-8 py-4 rounded-full font-heading font-bold text-white text-base flex items-center justify-center gap-3 group">
                <span>GET YOUR WEBSITE NOW</span>
                <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </a>
            
            <a href="projects.php" class="px-8 py-4 rounded-full font-heading font-bold text-slate-200 border border-slate-700/80 bg-slate-900/50 hover:bg-slate-800/80 hover:border-slate-500 hover:text-white transition-all text-base flex items-center justify-center gap-2 backdrop-blur-md">
                <span>View Our Portfolio</span>
                <i class="fa-solid fa-briefcase text-xs text-blue-400"></i>
            </a>
        </div>

    </div>
</section>

<!-- ==================== ANIMATED COUNTER SCRIPT ==================== -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const statsSection = document.getElementById('stats-counter-section');
    if (!statsSection) return;

    const counters = document.querySelectorAll('.stat-counter');
    const speed = 1500; // Duration in milliseconds

    const animateCounters = () => {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const suffix = counter.getAttribute('data-suffix') || '';
            const startTime = performance.now();

            const updateCount = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / speed, 1);
                
                // Ease out quad formula for smooth deceleration
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

    // Scroll Intersection Observer to fire counters when visible
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });

    observer.observe(statsSection);
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
