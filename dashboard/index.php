<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/header.php';

// Query statistical metrics
try {
    $countBlogs = $pdo->query("SELECT COUNT(*) FROM `blogs`")->fetchColumn();
    $countProjects = $pdo->query("SELECT COUNT(*) FROM `projects`")->fetchColumn();
    $countServices = $pdo->query("SELECT COUNT(*) FROM `services`")->fetchColumn();
    $countUnreadMsgs = $pdo->query("SELECT COUNT(*) FROM `contact_submissions` WHERE `is_read` = 0")->fetchColumn();
    $countTotalMsgs = $pdo->query("SELECT COUNT(*) FROM `contact_submissions`")->fetchColumn();

    // Fetch 5 latest contact submissions
    $recentStmt = $pdo->query("SELECT * FROM `contact_submissions` ORDER BY `id` DESC LIMIT 5");
    $recentMsgs = $recentStmt->fetchAll();
} catch (PDOException $e) {
    $countBlogs = $countProjects = $countServices = $countUnreadMsgs = $countTotalMsgs = 0;
    $recentMsgs = [];
}
?>

    <div class="flex flex-col gap-8">
        <!-- Dashboard Welcome Banner -->
        <div class="dashboard-card p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-brand-card to-brand-dark flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div>
                <h2 class="font-heading font-extrabold text-2xl text-white">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>!</h2>
                <p class="text-xs text-slate-400 mt-1">Here is a quick overview of your website state and user interactions.</p>
            </div>
            <a href="../index.php" target="_blank" class="px-5 py-2.5 rounded-full font-heading font-semibold text-xs bg-brand-accent text-brand-dark hover:shadow-lg transition-all flex items-center gap-2 shrink-0">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>View Live Site</span>
            </a>
        </div>

        <!-- Stats Counter Panel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1: Unread Messages -->
            <div class="dashboard-card p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-7xl text-red-500/5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-400">
                        <i class="fa-regular fa-envelope text-lg"></i>
                    </div>
                    <?php if ($countUnreadMsgs > 0): ?>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-500 text-white animate-pulse">New Alerts</span>
                    <?php endif; ?>
                </div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Unread Messages</span>
                <span class="font-heading font-black text-3xl text-white mt-1 block"><?php echo $countUnreadMsgs; ?> <span class="text-xs text-slate-500 font-normal">/ <?php echo $countTotalMsgs; ?> total</span></span>
            </div>

            <!-- Card 2: Services -->
            <div class="dashboard-card p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-7xl text-brand-accent/5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 flex items-center justify-center text-brand-accent">
                        <i class="fa-solid fa-gears text-lg"></i>
                    </div>
                </div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">IT Services</span>
                <span class="font-heading font-black text-3xl text-white mt-1 block"><?php echo $countServices; ?></span>
            </div>

            <!-- Card 3: Projects -->
            <div class="dashboard-card p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-7xl text-emerald-400/5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-400/10 flex items-center justify-center text-emerald-400">
                        <i class="fa-solid fa-laptop-code text-lg"></i>
                    </div>
                </div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Case Studies</span>
                <span class="font-heading font-black text-3xl text-white mt-1 block"><?php echo $countProjects; ?></span>
            </div>

            <!-- Card 4: Blogs -->
            <div class="dashboard-card p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 text-7xl text-purple-400/5 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-blog"></i>
                </div>
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                        <i class="fa-solid fa-blog text-lg"></i>
                    </div>
                </div>
                <span class="text-xs text-slate-400 block font-semibold uppercase tracking-wider">Blog Posts</span>
                <span class="font-heading font-black text-3xl text-white mt-1 block"><?php echo $countBlogs; ?></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Recent Messages -->
            <div class="lg:col-span-2 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-lg text-white">Recent Client Communications</h3>
                    <a href="contacts.php" class="text-xs text-brand-accent hover:underline flex items-center gap-1">
                        <span>See All Message Logs</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                
                <div class="dashboard-card rounded-3xl overflow-hidden border border-white/5">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-brand-darker/50 text-slate-400 font-semibold border-b border-slate-800/40 text-xs">
                                    <th class="px-6 py-4">Sender</th>
                                    <th class="px-6 py-4">Subject</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-850">
                                <?php if (empty($recentMsgs)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-xs">
                                            <i class="fa-solid fa-folder-open text-3xl block mb-2 opacity-50"></i>
                                            <span>No messages received yet.</span>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentMsgs as $msg): ?>
                                        <tr class="hover:bg-brand-darker/10 transition-colors cursor-pointer" onclick="window.location.href='contacts.php?id=<?php echo $msg['id']; ?>'">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-white"><?php echo htmlspecialchars($msg['name']); ?></div>
                                                <div class="text-[10px] text-slate-500"><?php echo htmlspecialchars($msg['email']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 text-xs font-medium text-slate-300 max-w-[200px] truncate">
                                                <?php echo htmlspecialchars($msg['subject']); ?>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-slate-400">
                                                <?php echo date('M d, Y H:i', strtotime($msg['created_at'])); ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($msg['is_read'] == 0): ?>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-red-500/10 text-red-400 border border-red-500/20">Unread</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wider uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Read</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Quick Setup & Info -->
            <div class="flex flex-col gap-6">
                <h3 class="font-heading font-bold text-lg text-white">Quick Actions</h3>
                
                <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-4">
                    <a href="blogs.php?action=add" class="flex items-center gap-4 p-3 rounded-2xl bg-brand-darker/40 hover:bg-brand-accent/5 hover:text-brand-accent border border-slate-800/60 hover:border-brand-accent/20 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 shrink-0">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Add New Blog Post</h4>
                            <span class="text-[10px] text-slate-500 block">Write tech articles and updates</span>
                        </div>
                    </a>

                    <a href="projects.php?action=add" class="flex items-center gap-4 p-3 rounded-2xl bg-brand-darker/40 hover:bg-brand-accent/5 hover:text-brand-accent border border-slate-800/60 hover:border-brand-accent/20 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-emerald-400/10 flex items-center justify-center text-emerald-400 shrink-0">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Publish Portfolio Project</h4>
                            <span class="text-[10px] text-slate-500 block">Upload recent enterprise case studies</span>
                        </div>
                    </a>

                    <a href="seo.php" class="flex items-center gap-4 p-3 rounded-2xl bg-brand-darker/40 hover:bg-brand-accent/5 hover:text-brand-accent border border-slate-800/60 hover:border-brand-accent/20 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-brand-accent/10 flex items-center justify-center text-brand-accent shrink-0">
                            <i class="fa-solid fa-magnifying-glass-chart text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">Configure Meta SEO Tags</h4>
                            <span class="text-[10px] text-slate-500 block">Manage page search rankings metadata</span>
                        </div>
                    </a>
                </div>

                <!-- Database status widget -->
                <div class="dashboard-card p-6 rounded-3xl flex flex-col gap-4">
                    <h4 class="font-heading font-bold text-sm text-white">System Status</h4>
                    <div class="flex items-center justify-between text-xs border-b border-slate-800/60 pb-3">
                        <span class="text-slate-400">Database Connection</span>
                        <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            <span>Connected</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs border-b border-slate-800/60 pb-3">
                        <span class="text-slate-400">Default Auth Account</span>
                        <span class="text-slate-400 font-semibold">Active</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">CMS Script Status</span>
                        <span class="text-brand-accent font-bold">Stable v1.0.0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/footer.php'; ?>
