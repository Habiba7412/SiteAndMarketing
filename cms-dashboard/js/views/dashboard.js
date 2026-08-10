// Dashboard Home View
import { Store } from '../store.js';

export const DashboardView = {
  render(params) {
    const blogs = Store.get("blogs") || [];
    const users = Store.get("users") || [];
    const activeUsersCount = users.filter(u => u.status === "Active").length;
    const contacts = Store.get("contacts") || [];
    const unreadMsgs = contacts.filter(c => c.status === "unread").length;
    const menus = Store.get("menus") || [];
    const seoSettings = Store.get("seoSettings") || {};
    const emailSettings = Store.get("emailSettings") || {};
    
    // Quick calculations
    const blogCount = blogs.length;
    const userCount = users.length;
    const menuCount = menus.length;
    const seoScore = 94; // Mock SEO calculated value

    return `
      <div class="space-y-8">
        
        <!-- Welcome Jumbotron (Glassmorphic) -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-tr from-slate-900 via-slate-800 to-indigo-950 p-8 text-white border border-white/5 shadow-premium-dark flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(99,102,241,0.15),transparent_60%)] pointer-events-none"></div>
          <div class="relative z-10 space-y-2">
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-500/20 text-indigo-300 border border-brand-500/30">System Hub</span>
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Welcome back, Jack Devlin!</h2>
            <p class="text-xs text-slate-300 max-w-xl">AeroCMS is monitoring the website. Server load is stable, all DNS endpoints are verified, and caching is primed at 99.8% hit rate.</p>
          </div>
          <div class="relative z-10 flex gap-3 shrink-0">
            <a href="#/blog?action=new" class="px-5 py-2.5 rounded-xl font-bold text-xs bg-brand-600 hover:bg-brand-500 hover:shadow-lg transition-all flex items-center gap-2">
              <i data-lucide="plus-circle" class="w-4 h-4"></i> Create Blog
            </a>
            <a href="#/seo" class="px-5 py-2.5 rounded-xl font-bold text-xs bg-white/10 hover:bg-white/15 border border-white/10 hover:border-white/20 transition-all flex items-center gap-2">
              <i data-lucide="shield-check" class="w-4 h-4"></i> SEO Checkup
            </a>
          </div>
        </div>

        <!-- 4 Stat Counters (Glassmorphic Glow Grid) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          
          <!-- Card 1: Users -->
          <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-100/40 dark:text-slate-800/10 group-hover:scale-110 transition-transform">
              <i data-lucide="users" class="w-24 h-24 stroke-[1px]"></i>
            </div>
            <div class="flex justify-between items-start">
              <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 dark:bg-indigo-500/20 flex items-center justify-center text-indigo-500 dark:text-indigo-400">
                <i data-lucide="users" class="w-5 h-5"></i>
              </div>
              <span class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full flex items-center gap-1">
                <i data-lucide="arrow-up-right" class="w-3 h-3"></i> +12%
              </span>
            </div>
            <div class="mt-4">
              <span class="text-xs text-slate-400 font-semibold tracking-wider block uppercase">Total & Active Users</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-extrabold tracking-tight">${userCount}</span>
                <span class="text-xs text-slate-400">/ ${activeUsersCount} active</span>
              </div>
            </div>
          </div>

          <!-- Card 2: Blogs -->
          <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-100/40 dark:text-slate-800/10 group-hover:scale-110 transition-transform">
              <i data-lucide="file-text" class="w-24 h-24 stroke-[1px]"></i>
            </div>
            <div class="flex justify-between items-start">
              <div class="w-12 h-12 rounded-2xl bg-brand-accent/10 dark:bg-brand-accent/20 flex items-center justify-center text-brand-accent">
                <i data-lucide="file-text" class="w-5 h-5"></i>
              </div>
              <span class="text-[10px] font-bold text-brand-accent bg-brand-accent/10 px-2 py-0.5 rounded-full">Articles</span>
            </div>
            <div class="mt-4">
              <span class="text-xs text-slate-400 font-semibold tracking-wider block uppercase">Blog Posts</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-extrabold tracking-tight">${blogCount}</span>
                <span class="text-xs text-slate-400">Published & Drafts</span>
              </div>
            </div>
          </div>

          <!-- Card 3: SEO Score -->
          <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-100/40 dark:text-slate-800/10 group-hover:scale-110 transition-transform">
              <i data-lucide="globe" class="w-24 h-24 stroke-[1px]"></i>
            </div>
            <div class="flex justify-between items-start">
              <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-500 dark:text-emerald-400">
                <i data-lucide="gauge" class="w-5 h-5"></i>
              </div>
              <span class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full">Premium</span>
            </div>
            <div class="mt-4">
              <span class="text-xs text-slate-400 font-semibold tracking-wider block uppercase">SEO Global Score</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-3xl font-extrabold tracking-tight">${seoScore}%</span>
                <span class="text-xs text-slate-400">Optimal indexing</span>
              </div>
            </div>
          </div>

          <!-- Card 4: Mail SMTP -->
          <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-100/40 dark:text-slate-800/10 group-hover:scale-110 transition-transform">
              <i data-lucide="mail" class="w-24 h-24 stroke-[1px]"></i>
            </div>
            <div class="flex justify-between items-start">
              <div class="w-12 h-12 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 flex items-center justify-center text-amber-500 dark:text-amber-400">
                <i data-lucide="send" class="w-5 h-5"></i>
              </div>
              <span class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Connected
              </span>
            </div>
            <div class="mt-4">
              <span class="text-xs text-slate-400 font-semibold tracking-wider block uppercase">SMTP Status</span>
              <div class="flex items-baseline gap-2 mt-1">
                <span class="text-lg font-bold truncate tracking-tight text-slate-700 dark:text-slate-200">${emailSettings.smtpHost}</span>
              </div>
            </div>
          </div>

        </div>

        <!-- 4 Secondary Stat Cards (Compact Horizontal List) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Menu Bars</span>
              <span class="text-xl font-extrabold mt-1 block">${menuCount} Menus</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
              <i data-lucide="menu" class="w-4 h-4"></i>
            </div>
          </div>
          <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Unread Inbox</span>
              <span class="text-xl font-extrabold mt-1 block">${unreadMsgs} Messages</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-950/20 flex items-center justify-center text-red-500">
              <i data-lucide="mail-open" class="w-4 h-4"></i>
            </div>
          </div>
          <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Visitor Stats</span>
              <span class="text-xl font-extrabold mt-1 block">4,812 / hr</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/20 flex items-center justify-center text-emerald-500">
              <i data-lucide="trending-up" class="w-4 h-4"></i>
            </div>
          </div>
          <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
            <div>
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Sitemap status</span>
              <span class="text-xl font-extrabold mt-1 block text-emerald-500">Crawled</span>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-950/20 flex items-center justify-center text-blue-500">
              <i data-lucide="link" class="w-4 h-4"></i>
            </div>
          </div>
        </div>

        <!-- Charts & Analytics Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- Traffic Analytics Graph Card -->
          <div class="lg:col-span-2 glass-panel p-6 rounded-3xl flex flex-col space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-base">Website Visitors</h3>
                <p class="text-[11px] text-slate-400">Monthly visitor telemetry and traffic nodes.</p>
              </div>
              <select class="glass-input text-xs px-2.5 py-1.5 rounded-lg">
                <option>Last 30 Days</option>
                <option>Last Quarter</option>
                <option>All Time</option>
              </select>
            </div>
            
            <!-- Dynamic Vector Line Chart -->
            <div class="flex-1 min-h-[250px] relative flex items-end">
              <svg class="w-full h-full" viewBox="0 0 500 200" preserveAspectRatio="none">
                <!-- Gradients -->
                <defs>
                  <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#6366f1" stop-opacity="0.3"/>
                    <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0"/>
                  </linearGradient>
                </defs>
                <!-- Grid Lines -->
                <line x1="0" y1="50" x2="500" y2="50" stroke="rgba(156,163,175,0.06)" stroke-dasharray="4"/>
                <line x1="0" y1="100" x2="500" y2="100" stroke="rgba(156,163,175,0.06)" stroke-dasharray="4"/>
                <line x1="0" y1="150" x2="500" y2="150" stroke="rgba(156,163,175,0.06)" stroke-dasharray="4"/>
                <!-- Area Path -->
                <path d="M 0,200 L 0,160 Q 60,110 120,130 T 240,60 T 360,120 T 480,40 L 500,30 L 500,200 Z" fill="url(#chartGrad)"/>
                <!-- Stroke Path -->
                <path d="M 0,160 Q 60,110 120,130 T 240,60 T 360,120 T 480,40 L 500,30" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round"/>
                <!-- Glowing Points -->
                <circle cx="240" cy="60" r="4" fill="#8b5cf6" stroke="#fff" stroke-width="2" class="animate-pulse"/>
                <circle cx="480" cy="40" r="4" fill="#6366f1" stroke="#fff" stroke-width="2" class="animate-pulse"/>
              </svg>
              <!-- Overlay stats floating -->
              <div class="absolute top-2 left-6 bg-white/80 dark:bg-slate-800/80 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-700 text-[10px] font-bold shadow-sm">
                Peak: 12.4K Visits
              </div>
            </div>
            
            <div class="flex justify-between items-center text-[10px] text-slate-400 font-semibold pt-2 border-t border-slate-100 dark:border-slate-800/50">
              <span>Jul 01</span>
              <span>Jul 07</span>
              <span>Jul 14</span>
              <span>Jul 21</span>
              <span>Jul 28</span>
            </div>
          </div>

          <!-- Quick Actions & System Health -->
          <div class="glass-panel p-6 rounded-3xl flex flex-col justify-between space-y-6">
            <div>
              <h3 class="font-bold text-base">Quick Actions</h3>
              <p class="text-[11px] text-slate-400">Command operations console shortcuts.</p>
              
              <div class="grid grid-cols-2 gap-3 mt-4">
                <a href="#/blog?action=new" class="flex flex-col items-center gap-2 p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-brand-500/30 transition-all group">
                  <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">Add Post</span>
                </a>
                <a href="#/menu" class="flex flex-col items-center gap-2 p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-brand-500/30 transition-all group">
                  <div class="w-8 h-8 rounded-lg bg-brand-accent/10 flex items-center justify-center text-brand-accent group-hover:scale-105 transition-transform">
                    <i data-lucide="git-merge" class="w-4 h-4"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">Menu Tree</span>
                </a>
                <a href="#/seo" class="flex flex-col items-center gap-2 p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-brand-500/30 transition-all group">
                  <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="target" class="w-4 h-4"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">SEO Config</span>
                </a>
                <a href="#/email" class="flex flex-col items-center gap-2 p-3 text-center rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 hover:border-brand-500/30 transition-all group">
                  <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-105 transition-transform">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                  </div>
                  <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">SMTP Host</span>
                </a>
              </div>
            </div>

            <!-- Health Status -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 space-y-3">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">System Health</span>
              <div class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-slate-500">CPU Usage</span>
                  <span class="font-semibold text-slate-700 dark:text-slate-200">18%</span>
                </div>
                <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                  <div class="h-full bg-emerald-500 rounded-full" style="width: 18%"></div>
                </div>
              </div>
              <div class="space-y-2">
                <div class="flex items-center justify-between text-xs">
                  <span class="text-slate-500">Memory Usage</span>
                  <span class="font-semibold text-slate-700 dark:text-slate-200">42%</span>
                </div>
                <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                  <div class="h-full bg-indigo-500 rounded-full" style="width: 42%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activities Timeline -->
        <div class="glass-panel p-6 rounded-3xl">
          <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800/50">
            <div>
              <h3 class="font-bold text-base">Recent Activity Logs</h3>
              <p class="text-[11px] text-slate-400">Live feed of content actions, security logins, and SMTP transactions.</p>
            </div>
            <a href="#/system?tab=logs" class="text-xs text-brand-600 dark:text-brand-500 font-semibold hover:underline">View All Logs</a>
          </div>

          <div class="mt-6 space-y-6">
            ${this.renderActivityLogs()}
          </div>
        </div>

      </div>
    `;
  },

  renderActivityLogs() {
    const logs = Store.get("activityLogs") || [];
    if (logs.length === 0) {
      return `<div class="text-xs text-slate-400 text-center py-4">No recent database operations registered.</div>`;
    }

    // Return the latest 4 logs formatted beautifully
    return logs.slice(0, 4).map(log => {
      let icon = "activity";
      let iconColor = "text-slate-400 bg-slate-100 dark:bg-slate-800";
      
      if (log.module === "Email Settings") {
        icon = "mail";
        iconColor = "text-amber-500 bg-amber-500/10";
      } else if (log.module === "Blog Posts") {
        icon = "file-text";
        iconColor = "text-indigo-500 bg-indigo-500/10";
      } else if (log.module === "SEO Management") {
        icon = "globe";
        iconColor = "text-emerald-500 bg-emerald-500/10";
      }

      return `
        <div class="flex gap-4 items-start text-xs">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${iconColor}">
            <i data-lucide="${icon}" class="w-4 h-4"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-center">
              <span class="font-bold text-slate-800 dark:text-slate-200">${log.user} <span class="font-normal text-slate-500">${log.action}</span></span>
              <span class="text-[10px] text-slate-400 font-semibold">${new Date(log.date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
            </div>
            <div class="flex items-center gap-2 mt-1 text-[10px] text-slate-400 font-semibold">
              <span class="bg-slate-100 dark:bg-slate-800/80 px-2 py-0.5 rounded">${log.module}</span>
              <span>&bull;</span>
              <span>IP: ${log.ipAddress}</span>
            </div>
          </div>
        </div>
      `;
    }).join("");
  }
};
