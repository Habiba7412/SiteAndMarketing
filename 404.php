<?php
header("HTTP/1.0 404 Not Found");
require_once __DIR__ . '/includes/db.php';
$customSeoData = [
    'title' => '404 - Page Not Found | ' . ($webSettings['websiteName'] ?? 'Site And Marketing'),
    'description' => 'The page you are looking for does not exist.',
];
include __DIR__ . '/includes/header.php';
?>

<!-- ==================== 404 SECTION ==================== -->
<section class="relative bg-[#081018] min-h-[70vh] flex items-center justify-center border-b border-slate-900/80 overflow-hidden">
    <!-- Glowing Radial Accents -->
    <div class="glow-bg top-1/4 right-1/4 opacity-40"></div>
    <div class="glow-bg-emerald bottom-1/4 left-1/4 opacity-30"></div>
    
    <div class="max-w-3xl mx-auto px-6 relative z-10 text-center flex flex-col items-center gap-6">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-badge text-rose-400 font-bold text-xs tracking-widest uppercase">
            <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
            <span>ERROR 404</span>
        </div>
        
        <h1 class="font-heading font-black text-6xl md:text-8xl text-white leading-tight tracking-tight">
            Page Not Found
        </h1>
        
        <p class="text-slate-400 text-lg md:text-xl max-w-2xl leading-relaxed">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>
        
        <div class="mt-8 flex flex-col sm:flex-row gap-4 items-center justify-center">
            <a href="<?php echo htmlspecialchars($baseUrl); ?>/" class="bg-brand-accent hover:bg-brand-accent/90 text-white font-semibold px-8 py-3.5 rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.3)] transition-all flex items-center gap-2 hover:-translate-y-1">
                Return to Homepage
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            <a href="<?php echo htmlspecialchars($baseUrl); ?>/contact.php" class="bg-white/5 hover:bg-white/10 text-white border border-white/10 font-semibold px-8 py-3.5 rounded-xl transition-all flex items-center gap-2 hover:-translate-y-1">
                Contact Support
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
