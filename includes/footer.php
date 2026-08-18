<?php
// Ensure $siteSettings is available
if (!isset($siteSettings)) {
    require_once __DIR__ . '/db.php';
    $siteSettings = getSiteSettings($pdo);
}
?>
    <!-- Footer Area (Site And Marketing Technologies) -->
    <footer class="bg-brand-darker border-t border-slate-900 pt-20 pb-8 relative overflow-hidden">
        <!-- Background Glowing Blobs -->
        <div class="absolute w-[400px] h-[400px] rounded-full bg-brand-accent/5 filter blur-3xl -top-20 -left-20 pointer-events-none"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full bg-indigo-500/5 filter blur-3xl -bottom-20 -right-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            
            <!-- Large Call-To-Action / Newsletter Section at the Top -->
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between pb-12 border-b border-slate-900/60 gap-8 mb-16 reveal-on-scroll">
                <div class="flex flex-col gap-3 max-w-2xl">
                    <h3 class="font-heading font-black text-3xl sm:text-4xl text-white uppercase tracking-tight leading-tight">
                        Let's Build Your Next Digital <br class="hidden sm:inline"> Success Story
                    </h3>
                    <p class="text-slate-400 text-sm sm:text-base leading-relaxed">
                        Have an idea or a project in mind? Partner with our experienced team to create innovative software solutions, high-performing websites, and digital experiences that help your business grow.
                    </p>
                </div>
                
                <form id="newsletter-form" class="flex flex-col sm:flex-row w-full lg:w-auto max-w-md gap-3 bg-brand-card/50 border border-white/5 p-2 rounded-2xl sm:rounded-full backdrop-blur-md">
                    <label for="newsletter-email" class="sr-only">Email Address</label>
                    <input type="email" id="newsletter-email" placeholder="Enter your business email" required class="bg-transparent text-sm text-slate-100 placeholder-slate-650 focus:outline-none px-4 py-3 sm:py-2 w-full">
                    <button type="submit" class="px-6 py-3 sm:py-2 bg-gradient-to-r from-brand-accent via-blue-500 to-sky-400 rounded-xl sm:rounded-full font-heading font-bold text-xs text-brand-dark hover:shadow-lg hover:shadow-brand-accent/20 hover:scale-[1.02] active:scale-[0.98] transition-all shrink-0">
                        GET YOUR WEBSITE NOW
                    </button>
                </form>
            </div>

            <!-- Footer Columns Grid (4 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 mb-16">
                
                <!-- Column 1: Company Profile (lg:col-span-4) -->
                <div class="lg:col-span-4 flex flex-col gap-6 reveal-on-scroll">
                    <a href="index.php" class="flex items-center gap-3 group">
                        <?php if (!empty($siteLogo)): ?>
                            <img src="<?php echo htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($companyName); ?>" class="h-10 sm:h-12 w-auto object-contain max-w-[200px] group-hover:scale-105 transition-transform" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden'); this.nextElementSibling.classList.add('flex');">
                            <div class="hidden items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-accent/20 group-hover:scale-105 transition-transform">
                                    <i class="fa-solid fa-cubes text-brand-dark text-lg"></i>
                                </div>
                                <span class="font-heading font-extrabold text-2xl tracking-tight text-white group-hover:text-brand-accent transition-colors">
                                    Site And Marketing<span class="text-brand-accent">.</span>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg shadow-brand-accent/20 group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-cubes text-brand-dark text-lg"></i>
                            </div>
                            <span class="font-heading font-extrabold text-2xl tracking-tight text-white group-hover:text-brand-accent transition-colors">
                                Site And Marketing<span class="text-brand-accent">.</span>
                            </span>
                        <?php endif; ?>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Site And Marketing Technologies is a trusted software development and digital solutions company delivering custom websites, web applications, branding, digital marketing, and innovative technology services to businesses worldwide. We focus on quality, creativity, performance, and long-term client success.
                    </p>
                    <!-- Social Media Links with animated hovers -->
                    <div class="flex flex-wrap gap-2.5">
                        <a href="https://facebook.com" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="Facebook"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="https://linkedin.com" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in text-sm"></i></a>
                        <a href="https://instagram.com" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="Instagram"><i class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="https://twitter.com" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="Twitter"><i class="fa-brands fa-x-twitter text-sm"></i></a>
                        <a href="https://github.com" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="GitHub"><i class="fa-brands fa-github text-sm"></i></a>
                        <a href="https://behance.net" target="_blank" class="w-9 h-9 rounded-xl bg-brand-card border border-white/5 hover:border-brand-accent/30 hover:bg-brand-accent hover:text-brand-dark flex items-center justify-center text-slate-400 transition-all duration-300 hover:scale-110" aria-label="Behance"><i class="fa-brands fa-behance text-sm"></i></a>
                    </div>
                </div>

                <!-- Spacer for lg -->
                <div class="hidden lg:block lg:col-span-1"></div>

                <!-- Column 2: Quick Links (lg:col-span-2) -->
                <div class="lg:col-span-2 flex flex-col gap-6 reveal-on-scroll delay-75">
                    <h4 class="font-heading font-bold text-sm text-white uppercase tracking-wider border-l-2 border-brand-accent pl-3">Quick Links</h4>
                    <ul class="flex flex-col gap-3 text-sm text-slate-400 font-medium">
                        <li><a href="index.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Home</a></li>
                        <li><a href="about.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> About Us</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Services</a></li>
                        <li><a href="projects.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Portfolio</a></li>
                        <li><a href="contact.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Pricing</a></li>
                        <li><a href="blog.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Blog</a></li>
                        <li><a href="contact.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Careers</a></li>
                        <li><a href="contact.php" class="hover:text-brand-accent transition-colors flex items-center gap-1.5 group"><i class="fa-solid fa-angle-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i> Contact Us</a></li>
                    </ul>
                </div>

                <!-- Column 3: Our Services (lg:col-span-2) -->
                <div class="lg:col-span-2 flex flex-col gap-6 reveal-on-scroll delay-100">
                    <h4 class="font-heading font-bold text-sm text-white uppercase tracking-wider border-l-2 border-brand-accent pl-3">Our Services</h4>
                    <ul class="flex flex-col gap-3 text-sm text-slate-400 font-medium">
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Custom Website Dev</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Web App Development</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">WordPress Dev</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">E-Commerce Solutions</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">UI/UX Design</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Graphic Design</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Digital Marketing</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">SEO Optimization</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Business Branding</a></li>
                        <li><a href="services.php" class="hover:text-brand-accent transition-colors">Website Support</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact & Trust Indicators (lg:col-span-3) -->
                <div class="lg:col-span-3 flex flex-col gap-6 reveal-on-scroll delay-150">
                    <h4 class="font-heading font-bold text-sm text-white uppercase tracking-wider border-l-2 border-brand-accent pl-3">Contact Info</h4>
                    <ul class="flex flex-col gap-3.5 text-sm text-slate-400">
                        <li class="flex items-start gap-3">
                            <span class="text-brand-accent shrink-0">📍</span>
                            <span>Islamabad, Pakistan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-brand-accent shrink-0">📧</span>
                            <a href="mailto:<?php echo htmlspecialchars($siteSettings['support_email'] ?? 'info@siteandmarketing.com'); ?>" class="hover:text-brand-accent"><?php echo htmlspecialchars($siteSettings['support_email'] ?? 'info@siteandmarketing.com'); ?></a>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-brand-accent shrink-0">📞</span>
                            <a href="tel:00923199564230" class="hover:text-brand-accent">00923199564230</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-brand-accent shrink-0">🌐</span>
                            <a href="http://www.siteandmarketing.com" target="_blank" class="hover:text-brand-accent">www.siteandmarketing.com</a>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-brand-accent shrink-0">🕒</span>
                            <span>Monday – Saturday <br> 9:00 AM – 7:00 PM</span>
                        </li>
                    </ul>

                    <!-- Why Clients Trust Us -->
                    <div class="border-t border-slate-900/60 pt-4 flex flex-col gap-2">
                        <span class="text-xs font-bold text-white uppercase tracking-widest">Why Clients Trust Us</span>
                        <ul class="flex flex-col gap-1.5 text-xs text-slate-400">
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-450">Custom Digital Solutions</span></li>
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-455">Experienced Team</span></li>
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-450">Affordable Pricing</span></li>
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-455">Fast Project Delivery</span></li>
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-450">Secure & Scalable Apps</span></li>
                            <li class="flex items-center gap-1.5 text-brand-accent font-medium"><span>✔</span> <span class="text-slate-455">24/7 Technical Support</span></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Footer Bottom -->
            <div class="pt-8 border-t border-slate-900 flex flex-col lg:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <div class="flex flex-col sm:flex-row items-center gap-2 text-center sm:text-left">
                    <span>© 2026 Site And Marketing Technologies. All Rights Reserved.</span>
                    <span class="hidden sm:inline text-slate-800">|</span>
                    <span class="text-slate-400 font-medium">Designed & Developed by Site And Marketing Technologies.</span>
                </div>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-brand-accent transition-colors">Privacy Policy</a>
                    <span class="text-slate-800">•</span>
                    <a href="#" class="hover:text-brand-accent transition-colors">Terms & Conditions</a>
                    <span class="text-slate-800">•</span>
                    <a href="#" class="hover:text-brand-accent transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Glassmorphic Success Alert -->
    <div id="global-alert" class="fixed bottom-6 right-6 z-50 glass-panel rounded-2xl border border-emerald-500/30 p-6 flex items-center gap-4 shadow-2xl translate-y-24 opacity-0 transition-all duration-300 max-w-sm">
        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400 shrink-0">
            <i class="fa-solid fa-check text-lg"></i>
        </div>
        <div>
            <h4 class="font-heading font-bold text-white text-sm">Action Completed!</h4>
            <p class="text-slate-400 text-xs mt-1" id="global-alert-text">Process executed successfully.</p>
        </div>
    </div>

    <!-- Scripting for Mobile Nav & Forms -->
    <script>
        // Sticky Header shadows
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (header) {
                if (window.scrollY > 20) {
                    header.classList.add('bg-brand-dark/75', 'backdrop-blur-md', 'shadow-lg', 'border-b', 'border-white/5', 'py-4');
                    header.classList.remove('py-6');
                } else {
                    header.classList.remove('bg-brand-dark/75', 'backdrop-blur-md', 'shadow-lg', 'border-b', 'border-white/5', 'py-4');
                    header.classList.add('py-6');
                }
            }
        });

        // Mobile drawer toggles
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn = document.getElementById('close-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-x-full');
            });
        }

        if (closeMenuBtn && mobileMenu) {
            closeMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.add('translate-x-full');
            });
        }

        const triggerAlert = (message) => {
            const alertBox = document.getElementById('global-alert');
            const alertText = document.getElementById('global-alert-text');
            if (alertBox && alertText) {
                alertText.textContent = message;
                alertBox.style.opacity = '1';
                alertBox.style.transform = 'translateY(0)';
                
                setTimeout(() => {
                    alertBox.style.opacity = '0';
                    alertBox.style.transform = 'translateY(1.5rem)';
                }, 4000);
            }
        };

        // Form handler validations
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', (e) => {
                // If submitting via native POST, let the action run; 
                // if it's AJAX we will handle it, but here we can let the PHP form action run and output success messages.
                // We'll write contact.php to display success alerts using PHP triggers.
            });
        }

        const newsletterForm = document.getElementById('newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                triggerAlert("You have successfully subscribed to our tech newsletter!");
                newsletterForm.reset();
            });
        }

        // Scroll reveal logic using IntersectionObserver
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: "0px 0px -50px 0px"
        });
        revealElements.forEach(el => observer.observe(el));
    </script>

    <!-- Floating WhatsApp Button with Hover Tooltip -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end group">
        <!-- "Chat with us" Tooltip (Appears smoothly on Hover) -->
        <span class="opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 pointer-events-none mb-2 px-3.5 py-1.5 bg-brand-dark/95 text-emerald-400 border border-emerald-500/40 text-xs font-bold rounded-xl shadow-2xl backdrop-blur-md whitespace-nowrap flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Chat with us
        </span>
        <!-- Circular WhatsApp Icon Button -->
        <a href="https://wa.me/923199564230" target="_blank" rel="noopener noreferrer" 
           class="relative w-14 h-14 rounded-full bg-gradient-to-tr from-emerald-600 via-emerald-500 to-green-400 text-white flex items-center justify-center shadow-2xl shadow-emerald-500/40 hover:shadow-emerald-500/60 hover:scale-110 active:scale-95 transition-all duration-300 border border-emerald-400/40"
           aria-label="Chat on WhatsApp">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-40"></span>
            <i class="fa-brands fa-whatsapp text-3xl relative z-10 text-white"></i>
        </a>
    </div>
</body>
</html>
