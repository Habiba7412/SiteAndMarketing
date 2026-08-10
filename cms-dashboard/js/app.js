// Main Application Controller & Router
import { Store } from './store.js';
import { DashboardView } from './views/dashboard.js';
import { MenuView } from './views/menu.js';
import { BlogView } from './views/blog.js';
import { UsersView } from './views/users.js';
import { RolesView } from './views/roles.js';
import { SEOView } from './views/seo.js';
import { ContactsView } from './views/contacts.js';
import { EmailView } from './views/email.js';
import { SettingsView } from './views/settings.js';
import { AnalyticsView } from './views/analytics.js';
import { MediaView } from './views/media.js';
import { SystemView } from './views/system.js';

// Route Mapping
const VIEWS = {
  'dashboard': DashboardView,
  'menu': MenuView,
  'blog': BlogView,
  'users': UsersView,
  'roles': RolesView,
  'seo': SEOView,
  'contacts': ContactsView,
  'email': EmailView,
  'settings': SettingsView,
  'analytics': AnalyticsView,
  'media': MediaView,
  'system': SystemView,
};

export const App = {
  async init() {
    await Store.init();
    this.setupTheme();
    this.setupSidebar();
    this.setupDropdowns();
    this.setupSearch();
    this.setupNotifications();
    this.setupDatabaseReset();
    
    // Hash Routing Listeners
    window.addEventListener("hashchange", () => this.handleRouting());
    
    // Check if DOM already loaded or wait
    if (document.readyState === "complete" || document.readyState === "interactive") {
      this.handleRouting();
      document.body.classList.add("opacity-100");
    } else {
      window.addEventListener("load", () => {
        this.handleRouting();
        document.body.classList.add("opacity-100");
      });
    }

    // Listen to global store updates to synchronize UI parts
    window.addEventListener("storeUpdated", (e) => {
      if (e.detail.key === "notifications") {
        this.renderNotifications();
      }
      if (e.detail.key === "users") {
        this.syncHeaderUser();
      }
    });

    this.syncHeaderUser();
  },

  // --- Header User Sync ---
  syncHeaderUser() {
    const admin = Store.get("users").find(u => u.role === "Super Admin") || { name: "Jack Devlin", avatar: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=80&q=80" };
    
    const avatarEl = document.getElementById("header-user-avatar");
    const nameEl = document.getElementById("header-user-name");
    const nameExpandedEl = document.getElementById("header-user-name-expanded");
    const roleExpandedEl = document.getElementById("header-user-role-expanded");

    if (avatarEl) avatarEl.src = admin.avatar;
    if (nameEl) nameEl.textContent = admin.name;
    if (nameExpandedEl) nameExpandedEl.textContent = admin.name;
    if (roleExpandedEl) roleExpandedEl.textContent = admin.role;
  },

  // --- Router ---
  handleRouting() {
    const hash = window.location.hash || '#/dashboard';
    const parts = hash.slice(2).split('?');
    const path = parts[0] || 'dashboard';
    const queryStr = parts[1] || '';
    
    // Parse query params
    const params = {};
    if (queryStr) {
      queryStr.split('&').forEach(pair => {
        const [k, v] = pair.split('=');
        params[decodeURIComponent(k)] = decodeURIComponent(v || '');
      });
    }

    const view = VIEWS[path] || DashboardView;
    const viewport = document.getElementById("app-view-viewport");
    
    // Active navigation highlighting
    this.updateSidebarActive(path);

    // Update breadcrumb
    const breadcrumb = document.getElementById("breadcrumb-current-view");
    if (breadcrumb) {
      breadcrumb.textContent = path.charAt(0).toUpperCase() + path.slice(1);
    }

    // Render new view
    if (viewport) {
      viewport.style.opacity = 0;
      setTimeout(() => {
        viewport.innerHTML = view.render(params);
        view.init?.(params);
        lucide.createIcons(); // Re-render Lucide icons dynamically
        viewport.style.opacity = 1;
        viewport.classList.add("animate-fade-in");
      }, 150);
    }

    // Auto-close mobile drawer on route change
    this.toggleMobileSidebar(false);
  },

  updateSidebarActive(path) {
    document.querySelectorAll(".sidebar-item").forEach(item => {
      const route = item.getAttribute("data-route");
      if (route === path) {
        item.classList.add("bg-brand-50", "text-brand-600", "dark:bg-slate-800", "dark:text-brand-500");
      } else {
        item.classList.remove("bg-brand-50", "text-brand-600", "dark:bg-slate-800", "dark:text-brand-500");
      }
    });

    // Expand accordion parent automatically if route matches sub-items
    document.querySelectorAll(".nav-collapsible-content").forEach(content => {
      const matches = Array.from(content.querySelectorAll("a")).some(link => {
        return link.getAttribute("href").split('?')[0] === `#/${path}`;
      });
      const icon = content.previousElementSibling?.querySelector(".lucide-chevron-down");
      if (matches) {
        content.classList.remove("hidden");
        if (icon) icon.style.transform = "rotate(180deg)";
      }
    });
  },

  // --- Dark/Light Mode ---
  setupTheme() {
    const themeToggleBtn = document.getElementById("btn-theme-toggle");
    const currentTheme = localStorage.getItem("theme") || "light";
    
    if (currentTheme === "dark") {
      document.documentElement.classList.add("dark");
      document.documentElement.classList.remove("light");
    } else {
      document.documentElement.classList.add("light");
      document.documentElement.classList.remove("dark");
    }

    themeToggleBtn?.addEventListener("click", () => {
      if (document.documentElement.classList.contains("dark")) {
        document.documentElement.classList.remove("dark");
        document.documentElement.classList.add("light");
        localStorage.setItem("theme", "light");
      } else {
        document.documentElement.classList.remove("light");
        document.documentElement.classList.add("dark");
        localStorage.setItem("theme", "dark");
      }
    });
  },

  // --- Sidebar Collapse (Responsive & Desktop) ---
  setupSidebar() {
    const sidebar = document.getElementById("sidebar");
    const container = document.getElementById("main-view-container");
    const collapseBtn = document.getElementById("btn-collapse-sidebar");
    const collapseIcon = document.getElementById("collapse-icon");
    const mobileToggleBtn = document.getElementById("btn-mobile-sidebar-toggle");
    const backdrop = document.getElementById("sidebar-backdrop");

    // Collapsible sub-navigation elements
    document.querySelectorAll(".nav-collapsible-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const content = btn.nextElementSibling;
        const icon = btn.querySelector(".lucide-chevron-down");
        const isCollapsed = content.classList.contains("hidden");
        
        // Hide all others
        // document.querySelectorAll('.nav-collapsible-content').forEach(c => c.classList.add('hidden'));

        if (isCollapsed) {
          content.classList.remove("hidden");
          if (icon) icon.style.transform = "rotate(180deg)";
        } else {
          content.classList.add("hidden");
          if (icon) icon.style.transform = "rotate(0deg)";
        }
      });
    });

    // Desktop Collapse Toggle
    collapseBtn?.addEventListener("click", () => {
      sidebar.classList.toggle("w-64");
      sidebar.classList.toggle("w-20");
      container.classList.toggle("lg:pl-64");
      container.classList.toggle("lg:pl-20");
      
      const isCompact = sidebar.classList.contains("w-20");
      document.querySelectorAll(".sidebar-text-label").forEach(lbl => {
        lbl.style.display = isCompact ? "none" : "inline";
      });
      document.querySelectorAll(".sidebar-group-title").forEach(title => {
        title.style.display = isCompact ? "none" : "block";
      });
      
      if (collapseIcon) {
        collapseIcon.style.transform = isCompact ? "rotate(180deg)" : "rotate(0deg)";
      }
    });

    // Mobile Navigation Drawer Toggle
    mobileToggleBtn?.addEventListener("click", () => this.toggleMobileSidebar(true));
    backdrop?.addEventListener("click", () => this.toggleMobileSidebar(false));
  },

  toggleMobileSidebar(show) {
    const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");
    if (!sidebar || !backdrop) return;

    if (show) {
      sidebar.classList.remove("-translate-x-full");
      backdrop.classList.remove("hidden");
    } else {
      sidebar.classList.add("-translate-x-full");
      backdrop.classList.add("hidden");
    }
  },

  // --- Dropdowns & Click-Outside Logic ---
  setupDropdowns() {
    const setup = (triggerId, panelId) => {
      const trigger = document.getElementById(triggerId);
      const panel = document.getElementById(panelId);
      
      trigger?.addEventListener("click", (e) => {
        e.stopPropagation();
        // Close other panels
        document.querySelectorAll("[id$='-dropdown']").forEach(p => {
          if (p.id !== panelId) p.classList.add("hidden");
        });
        panel.classList.toggle("hidden");
      });
    };

    setup("btn-notifications-trigger", "notifications-dropdown");
    setup("btn-profile-trigger", "profile-dropdown");

    // Close on click outside
    document.addEventListener("click", () => {
      document.querySelectorAll("[id$='-dropdown']").forEach(p => p.classList.add("hidden"));
    });
  },

  // --- Search Everywhere Modal System ---
  setupSearch() {
    const trigger = document.getElementById("btn-global-search-trigger");
    const modal = document.getElementById("global-search-modal");
    const input = document.getElementById("global-search-input");
    const results = document.getElementById("global-search-results");
    const closeBtn = document.getElementById("btn-close-search");

    const openSearch = () => {
      modal.classList.remove("hidden");
      input.value = "";
      results.innerHTML = `<div class="text-xs text-slate-400 px-3 py-2">Start typing to search databases...</div>`;
      setTimeout(() => input.focus(), 50);
    };

    const closeSearch = () => {
      modal.classList.add("hidden");
    };

    trigger?.addEventListener("click", openSearch);
    closeBtn?.addEventListener("click", closeSearch);
    modal?.addEventListener("click", (e) => {
      if (e.target === modal) closeSearch();
    });

    // Keyboard shortcut (Ctrl+K)
    document.addEventListener("keydown", (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key === "k") {
        e.preventDefault();
        openSearch();
      }
      if (e.key === "Escape") {
        closeSearch();
      }
    });

    input?.addEventListener("input", (e) => {
      const q = e.target.value.toLowerCase().trim();
      if (!q) {
        results.innerHTML = `<div class="text-xs text-slate-400 px-3 py-2">Start typing to search databases...</div>`;
        return;
      }

      // Query Blogs, Menus, Users, Contacts, settings
      const blogs = Store.get("blogs") || [];
      const users = Store.get("users") || [];
      const contacts = Store.get("contacts") || [];
      
      const blogMatches = blogs.filter(b => b.title.toLowerCase().includes(q) || b.tags.some(t => t.toLowerCase().includes(q)));
      const userMatches = users.filter(u => u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.role.toLowerCase().includes(q));
      const contactMatches = contacts.filter(c => c.name.toLowerCase().includes(q) || c.subject.toLowerCase().includes(q) || c.message.toLowerCase().includes(q));

      let html = "";

      if (blogMatches.length === 0 && userMatches.length === 0 && contactMatches.length === 0) {
        results.innerHTML = `<div class="text-xs text-slate-400 px-3 py-4 text-center">No matches found for "${e.target.value}"</div>`;
        return;
      }

      // Render Blog Matches
      if (blogMatches.length > 0) {
        html += `<div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Blogs (${blogMatches.length})</div>`;
        blogMatches.forEach(b => {
          html += `
            <a href="#/blog?action=edit&id=${b.id}" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg group transition-all text-xs">
              <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-400 group-hover:text-brand-500"></i>
              <div class="flex-1 min-w-0">
                <span class="block font-semibold truncate text-slate-700 dark:text-slate-200">${b.title}</span>
                <span class="block text-[10px] text-slate-400 truncate">${b.excerpt}</span>
              </div>
            </a>
          `;
        });
      }

      // Render User Matches
      if (userMatches.length > 0) {
        html += `<div class="px-3 py-1.5 mt-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Users (${userMatches.length})</div>`;
        userMatches.forEach(u => {
          html += `
            <a href="#/users" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg group transition-all text-xs">
              <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400 group-hover:text-brand-500"></i>
              <div class="flex-1 min-w-0">
                <span class="block font-semibold truncate text-slate-700 dark:text-slate-200">${u.name}</span>
                <span class="block text-[10px] text-slate-400 truncate">${u.role} &bull; ${u.email}</span>
              </div>
            </a>
          `;
        });
      }

      // Render Contact Matches
      if (contactMatches.length > 0) {
        html += `<div class="px-3 py-1.5 mt-2 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Contact Messages (${contactMatches.length})</div>`;
        contactMatches.forEach(c => {
          html += `
            <a href="#/contacts?id=${c.id}" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg group transition-all text-xs">
              <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400 group-hover:text-brand-500"></i>
              <div class="flex-1 min-w-0">
                <span class="block font-semibold truncate text-slate-700 dark:text-slate-200">${c.subject}</span>
                <span class="block text-[10px] text-slate-400 truncate">From: ${c.name} - "${c.message}"</span>
              </div>
            </a>
          `;
        });
      }

      results.innerHTML = html;
      lucide.createIcons();

      // Bind search navigation clicks
      results.querySelectorAll("a").forEach(a => {
        a.addEventListener("click", () => closeSearch());
      });
    });
  },

  // --- Notifications Dropdown Render ---
  setupNotifications() {
    this.renderNotifications();
    const markReadBtn = document.getElementById("btn-mark-all-read");
    markReadBtn?.addEventListener("click", (e) => {
      e.stopPropagation();
      const notifs = Store.get("notifications") || [];
      const updated = notifs.map(n => ({ ...n, unread: false }));
      Store.set("notifications", updated);
      this.showToast("All notifications marked as read.", "success");
    });
  },

  renderNotifications() {
    const list = document.getElementById("notification-items-list");
    const badge = document.getElementById("notification-badge");
    const notifs = Store.get("notifications") || [];
    const unreadCount = notifs.filter(n => n.unread).length;

    if (badge) {
      if (unreadCount > 0) {
        badge.classList.remove("hidden");
      } else {
        badge.classList.add("hidden");
      }
    }

    if (!list) return;

    if (notifs.length === 0) {
      list.innerHTML = `
        <div class="py-6 text-center text-xs text-slate-400 flex flex-col items-center gap-2">
          <i data-lucide="info" class="w-5 h-5 opacity-40"></i>
          No system notifications
        </div>
      `;
      lucide.createIcons();
      return;
    }

    list.innerHTML = notifs.map(n => {
      let icon = "bell";
      let iconColor = "text-slate-400 bg-slate-50 dark:bg-slate-800";
      if (n.type === "message") {
        icon = "mail";
        iconColor = "text-brand-500 bg-brand-50 dark:bg-brand-500/10";
      } else if (n.type === "system") {
        icon = "server";
        iconColor = "text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10";
      } else if (n.type === "security") {
        icon = "shield-alert";
        iconColor = "text-red-500 bg-red-50 dark:bg-red-500/10";
      }

      return `
        <div class="px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all flex gap-3 text-xs cursor-pointer ${n.unread ? 'bg-brand-50/20 dark:bg-brand-500/5' : ''}">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${iconColor}">
            <i data-lucide="${icon}" class="w-4 h-4"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start gap-1">
              <span class="font-bold truncate text-slate-800 dark:text-slate-200">${n.title}</span>
              <span class="text-[9px] text-slate-400 font-medium shrink-0">${this.formatRelativeTime(n.date)}</span>
            </div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">${n.message}</p>
          </div>
        </div>
      `;
    }).join("");

    lucide.createIcons();
  },

  // --- Reset Database Button Trigger ---
  setupDatabaseReset() {
    const logoutBtn = document.getElementById("btn-profile-logout");
    logoutBtn?.addEventListener("click", () => {
      if (confirm("Log out of AeroCMS? (This will simulate logout and reset local storage changes if confirmed).")) {
        Store.reset();
      }
    });
  },

  // --- Dynamic Modals System ---
  openModal(title, htmlContent, onSaveCallback, footerHtml = "") {
    const modal = document.getElementById("global-modal");
    const container = document.getElementById("global-modal-content");
    if (!modal || !container) return;

    container.innerHTML = `
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-800">
        <h3 class="font-bold text-base text-slate-800 dark:text-slate-200">${title}</h3>
        <button id="btn-modal-close" class="p-1 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
      <div class="p-6 max-h-[70vh] overflow-y-auto space-y-4">
        ${htmlContent}
      </div>
      <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end gap-3">
        ${footerHtml ? footerHtml : `
          <button id="btn-modal-cancel" class="px-4 py-2 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl transition-all border border-slate-200 dark:border-slate-800">Cancel</button>
          <button id="btn-modal-save" class="px-4 py-2 text-xs font-semibold bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white rounded-xl transition-all">Apply Changes</button>
        `}
      </div>
    `;

    lucide.createIcons();
    modal.classList.remove("hidden");

    // Close callbacks
    const closeModal = () => modal.classList.add("hidden");
    document.getElementById("btn-modal-close")?.addEventListener("click", closeModal);
    document.getElementById("btn-modal-cancel")?.addEventListener("click", closeModal);
    modal.onclick = (e) => { if (e.target === modal) closeModal(); };

    // Save callback
    const saveBtn = document.getElementById("btn-modal-save");
    if (saveBtn && onSaveCallback) {
      saveBtn.addEventListener("click", () => {
        const shouldClose = onSaveCallback();
        if (shouldClose !== false) {
          closeModal();
        }
      });
    }
  },

  // --- Dynamic Toasts System ---
  showToast(message, type = "success") {
    const container = document.getElementById("global-toast-container");
    if (!container) return;

    let icon = "check-circle";
    let typeClass = "bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-800";
    let iconClass = "text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10";
    if (type === "warning") {
      icon = "alert-triangle";
      iconClass = "text-amber-500 bg-amber-50 dark:bg-amber-500/10";
    } else if (type === "error") {
      icon = "alert-circle";
      iconClass = "text-red-500 bg-red-50 dark:bg-red-500/10";
    } else if (type === "info") {
      icon = "info";
      iconClass = "text-brand-500 bg-brand-50 dark:bg-brand-500/10";
    }

    const toast = document.createElement("div");
    toast.className = `flex items-center gap-3 p-4 rounded-2xl border shadow-xl animate-slide-in pointer-events-auto ${typeClass}`;
    toast.innerHTML = `
      <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 ${iconClass}">
        <i data-lucide="${icon}" class="w-4 h-4"></i>
      </div>
      <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 flex-1 leading-snug">${message}</p>
      <button class="p-1 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 select-none">
        <i data-lucide="x" class="w-3.5 h-3.5"></i>
      </button>
    `;

    container.appendChild(toast);
    lucide.createIcons();

    const removeToast = () => {
      toast.style.transform = "translateX(110%)";
      toast.style.opacity = 0;
      setTimeout(() => toast.remove(), 350);
    };

    // Close button
    toast.querySelector("button").onclick = removeToast;

    // Auto-remove after 4 seconds
    setTimeout(removeToast, 4000);
  },

  // --- Helper Date Formatting ---
  formatRelativeTime(dateString) {
    const now = new Date();
    const date = new Date(dateString);
    const diffMs = now - date;
    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHr = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHr / 24);

    if (diffSec < 60) return "Just now";
    if (diffMin < 60) return `${diffMin}m ago`;
    if (diffHr < 24) return `${diffHr}h ago`;
    if (diffDay < 7) return `${diffDay}d ago`;
    return date.toLocaleDateString();
  }
};

App.init();
