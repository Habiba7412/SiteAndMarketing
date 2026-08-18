<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/db.php';

$currentPage = basename($_SERVER['SCRIPT_NAME']);

// Get count of unread contact submissions
try {
    $unreadStmt = $pdo->query("SELECT COUNT(*) FROM `contact_submissions` WHERE `is_read` = 0");
    $unreadCount = $unreadStmt->fetchColumn();
} catch (PDOException $e) {
    $unreadCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiteAndMarketing | Admin Control Panel</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0b1315',
                            darker: '#070c0e',
                            card: '#0e1a1d',
                            accent: '#38bdf8',
                            emerald: '#10b981',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Outfit & Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 2px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .glass-sidebar {
            background: rgba(14, 26, 29, 0.95);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.04);
        }
        .dashboard-card {
            background: rgba(14, 26, 29, 0.4);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.2s ease;
        }
        .dashboard-card:hover {
            border-color: rgba(56, 189, 248, 0.15);
            box-shadow: 0 10px 30px -15px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body class="bg-brand-dark text-slate-200 font-sans min-h-screen flex custom-scrollbar">

    <!-- Sidebar Layout -->
    <aside id="sidebar" class="w-64 glass-sidebar fixed inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col justify-between">
        <div class="flex flex-col h-full">
            <!-- Sidebar Brand Header -->
            <div class="px-6 py-6 border-b border-slate-800/40 flex items-center justify-between">
                <a href="index.php" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-accent to-emerald-400 flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-cubes text-brand-dark text-sm"></i>
                    </div>
                    <span class="font-heading font-extrabold text-xl tracking-tight text-white">
                        SiteAndMarketing<span class="text-brand-accent">.</span> <span class="text-[9px] uppercase tracking-widest text-slate-500 font-bold block -mt-1">Admin</span>
                    </span>
                </a>
                
                <!-- Close Button (Mobile only) -->
                <button id="close-sidebar-btn" class="lg:hidden text-slate-400 hover:text-white" aria-label="Close Sidebar">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Profile Widget -->
            <div class="px-6 py-5 border-b border-slate-800/40 flex items-center gap-3 bg-brand-darker/20">
                <div class="w-10 h-10 rounded-full bg-slate-800 border border-slate-700/60 flex items-center justify-center text-slate-400 font-bold uppercase text-sm">
                    <?php echo isset($_SESSION['admin_username']) ? substr($_SESSION['admin_username'], 0, 2) : 'AD'; ?>
                </div>
                <div class="min-w-0">
                    <span class="text-xs text-slate-500 block font-medium">Logged in as</span>
                    <span class="text-sm font-bold text-slate-200 block truncate"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?></span>
                </div>
            </div>

            <!-- Navigation Menu Items -->
            <nav class="flex-grow px-4 py-6 overflow-y-auto space-y-1 custom-scrollbar text-sm font-medium">
                <a href="index.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'index.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gauge text-base"></i>
                        <span>Dashboard</span>
                    </div>
                </a>

                <a href="seo.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'seo.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-magnifying-glass-chart text-base"></i>
                        <span>SEO Settings</span>
                    </div>
                </a>

                <a href="contacts.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'contacts.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-base"></i>
                        <span>Contact Messages</span>
                    </div>
                    <?php if ($unreadCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white shrink-0"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>

                <div class="h-px bg-slate-800/40 my-3 mx-4"></div>
                <span class="px-4 text-[10px] uppercase font-bold tracking-wider text-slate-500 block mb-2">Content Manager</span>

                <a href="services.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'services.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gears text-base"></i>
                        <span>IT Services</span>
                    </div>
                </a>

                <a href="projects.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'projects.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-laptop-code text-base"></i>
                        <span>Projects Portfolio</span>
                    </div>
                </a>

                <a href="blogs.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'blogs.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-blog text-base"></i>
                        <span>Blog Posts</span>
                    </div>
                </a>

                <div class="h-px bg-slate-800/40 my-3 mx-4"></div>
                <span class="px-4 text-[10px] uppercase font-bold tracking-wider text-slate-500 block mb-2">Configurations</span>

                <a href="settings.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'settings.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-sliders text-base"></i>
                        <span>Site Settings</span>
                    </div>
                </a>

                <a href="email-settings.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'email-settings.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-paper-plane text-base"></i>
                        <span>Email & SMTP Settings</span>
                    </div>
                </a>

                <a href="profile.php" class="flex items-center justify-between px-4 py-3 rounded-xl transition-all <?php echo ($currentPage == 'profile.php') ? 'bg-brand-accent/10 text-brand-accent border border-brand-accent/20' : 'text-slate-400 hover:bg-slate-800/30 hover:text-slate-200 border border-transparent'; ?>">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-user-gear text-base"></i>
                        <span>Admin Profile</span>
                    </div>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer Action (Logout & View Site) -->
        <div class="p-4 border-t border-slate-800/40 bg-brand-darker/10 space-y-2">
            <a href="../index.php" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white hover:border-slate-700 transition-all text-xs font-semibold">
                <i class="fa-solid fa-globe"></i>
                <span>View Website</span>
            </a>
            <a href="logout.php" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 transition-all text-xs font-semibold">
                <i class="fa-solid fa-power-off"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Workspace Container -->
    <div class="flex-grow lg:pl-64 min-h-screen flex flex-col">
        <!-- Top Navbar -->
        <header class="h-16 border-b border-slate-800/40 bg-brand-dark/95 backdrop-blur-md px-6 flex items-center justify-between sticky top-0 z-30">
            <!-- Sidebar toggle button -->
            <button id="open-sidebar-btn" class="lg:hidden text-slate-400 hover:text-white" aria-label="Open Sidebar">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <div class="hidden lg:block">
                <h1 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Control Panel</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- Mini Notifications -->
                <a href="contacts.php" class="relative text-slate-400 hover:text-white transition-colors" title="Messages">
                    <i class="fa-regular fa-envelope text-xl"></i>
                    <?php if ($unreadCount > 0): ?>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse border border-brand-dark"></span>
                    <?php endif; ?>
                </a>

                <!-- Mini Logged In Label -->
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs text-slate-400 font-semibold select-none"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'admin'); ?></span>
                </div>
            </div>
        </header>

        <!-- Dynamic Main Contents Wrapper -->
        <main class="flex-grow p-6 md:p-8">
