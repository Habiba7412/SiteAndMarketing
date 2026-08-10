// Website General Settings View
import { Store } from '../store.js';
import { App } from '../app.js';

let activeSubTab = "general"; // general, assets, social, location

export const SettingsView = {
  render(params) {
    const s = Store.get("websiteSettings") || {};
    
    if (params && params.tab) {
      // Set by router
    }

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">System Settings & Brand Setup</h2>
            <p class="text-xs text-slate-400">Configure corporate emails, pick primary theme gradients, upload favicon schemas, and update copyrights.</p>
          </div>
          <button id="btn-save-website-settings" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Apply System Settings
          </button>
        </div>

        <!-- Layout Grid split -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Configuration tabs left (8 columns) -->
          <div class="lg:col-span-8 space-y-6">
            
            <!-- Sub tabs Navigation -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6 text-xs font-semibold">
              <button class="settings-tab-btn pb-2.5 relative ${activeSubTab === 'general' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="general">
                General Profile
              </button>
              <button class="settings-tab-btn pb-2.5 relative ${activeSubTab === 'assets' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="assets">
                Brand Logo & Colors
              </button>
              <button class="settings-tab-btn pb-2.5 relative ${activeSubTab === 'social' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="social">
                Social & Footer Text
              </button>
              <button class="settings-tab-btn pb-2.5 relative ${activeSubTab === 'location' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="location">
                Crawl Maps & Local
              </button>
            </div>

            <!-- Tab 1: General Profile -->
            <div class="settings-tab-content space-y-4 ${activeSubTab === 'general' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Corporate Website Identity</h3>
                
                <div class="space-y-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                      <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Website Name</label>
                      <input type="text" id="set-web-name" value="${s.websiteName}" class="w-full glass-input p-3 rounded-xl">
                    </div>
                    <div class="space-y-1">
                      <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Support Email</label>
                      <input type="email" id="set-business-email" value="${s.businessEmail}" class="w-full glass-input p-3 rounded-xl">
                    </div>
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Company Slogan Description</label>
                    <textarea id="set-web-desc" rows="3" class="w-full glass-input p-3 rounded-xl">${s.websiteDescription}</textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 2: Brand Logo & Colors -->
            <div class="settings-tab-content space-y-4 ${activeSubTab === 'assets' ? '' : 'hidden'}">
              
              <!-- Assets upload grid -->
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Visual Identity Assets</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  
                  <!-- Logo Picker -->
                  <div class="space-y-2">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">System Brand Logo</label>
                    <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between bg-slate-50 dark:bg-slate-900/50">
                      <img id="logo-preview-img" src="${s.logoUrl}" class="h-10 w-auto rounded object-contain max-w-[120px]" alt="Logo">
                      <button id="btn-change-logo" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-500 rounded-lg text-xs font-semibold hover:bg-slate-50">Upload Logo</button>
                    </div>
                    <input type="hidden" id="set-logo-url" value="${s.logoUrl}">
                  </div>

                  <!-- Favicon Picker -->
                  <div class="space-y-2">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Website Favicon (Browser Tab)</label>
                    <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-4 flex items-center justify-between bg-slate-50 dark:bg-slate-900/50">
                      <img id="favicon-preview-img" src="${s.faviconUrl}" class="h-8 w-8 rounded object-contain" alt="Favicon">
                      <button id="btn-change-favicon" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-500 rounded-lg text-xs font-semibold hover:bg-slate-50">Upload Favicon</button>
                    </div>
                    <input type="hidden" id="set-favicon-url" value="${s.faviconUrl}">
                  </div>

                </div>
              </div>

              <!-- Color Gradients palette configuration -->
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Theme Design System Palette</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  
                  <div class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 rounded-2xl">
                    <div class="space-y-0.5">
                      <span class="font-bold text-xs block">Primary Theme Color</span>
                      <span class="text-[10px] text-slate-400 font-mono" id="primary-color-text">${s.primaryColor}</span>
                    </div>
                    <input type="color" id="set-color-primary" value="${s.primaryColor}" class="w-10 h-10 rounded-xl cursor-pointer border-none bg-transparent">
                  </div>

                  <div class="flex items-center justify-between p-3.5 border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 rounded-2xl">
                    <div class="space-y-0.5">
                      <span class="font-bold text-xs block">Secondary Accent Color</span>
                      <span class="text-[10px] text-slate-400 font-mono" id="secondary-color-text">${s.secondaryColor}</span>
                    </div>
                    <input type="color" id="set-color-secondary" value="${s.secondaryColor}" class="w-10 h-10 rounded-xl cursor-pointer border-none bg-transparent">
                  </div>

                </div>
              </div>

            </div>

            <!-- Tab 3: Social Links & Footers -->
            <div class="settings-tab-content space-y-4 ${activeSubTab === 'social' ? '' : 'hidden'}">
              
              <!-- Social Links -->
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Social Networking Integrations</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">LinkedIn URL</label>
                    <input type="text" id="set-social-linkedin" value="${s.socialLinks.linkedin}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Twitter / X URL</label>
                    <input type="text" id="set-social-twitter" value="${s.socialLinks.twitter}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">GitHub Repository</label>
                    <input type="text" id="set-social-github" value="${s.socialLinks.github}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Facebook Fan Page</label>
                    <input type="text" id="set-social-facebook" value="${s.socialLinks.facebook}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                </div>
              </div>

              <!-- Footer Settings -->
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Footer Credits Setup</h3>
                
                <div class="space-y-1">
                  <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Footer Copyright Notice</label>
                  <textarea id="set-footer-text" rows="2" class="w-full glass-input p-3 rounded-xl">${s.footerText}</textarea>
                </div>
              </div>

            </div>

            <!-- Tab 4: Maps & Location settings -->
            <div class="settings-tab-content space-y-4 ${activeSubTab === 'location' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">Localizations & Regional Constants</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Phone Number</label>
                    <input type="text" id="set-business-phone" value="${s.businessPhone}" class="w-full glass-input p-3 rounded-xl">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Region Language</label>
                    <select id="set-language" class="w-full glass-input p-3 rounded-xl">
                      <option value="en" ${s.language === 'en' ? 'selected' : ''}>English (US-en)</option>
                      <option value="es" ${s.language === 'es' ? 'selected' : ''}>Español (ES-es)</option>
                      <option value="fr" ${s.language === 'fr' ? 'selected' : ''}>Français (FR-fr)</option>
                    </select>
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Site Timezone</label>
                    <select id="set-timezone" class="w-full glass-input p-3 rounded-xl">
                      <option value="America/Los_Angeles" ${s.timezone === 'America/Los_Angeles' ? 'selected' : ''}>Los Angeles (PST)</option>
                      <option value="America/New_York" ${s.timezone === 'America/New_York' ? 'selected' : ''}>New York (EST)</option>
                      <option value="Europe/London" ${s.timezone === 'Europe/London' ? 'selected' : ''}>London (GMT)</option>
                    </select>
                  </div>
                  <div class="space-y-1 md:col-span-3">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Corporate Office Address</label>
                    <input type="text" id="set-business-address" value="${s.businessAddress}" class="w-full glass-input p-3 rounded-xl">
                  </div>
                  <div class="space-y-1 md:col-span-3">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Google Maps Embed Iframe Src URL</label>
                    <input type="text" id="set-maps-url" value="${s.googleMapsEmbedUrl}" class="w-full glass-input p-3 rounded-xl font-mono text-[10px]">
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- Right info visual preview (4 columns) -->
          <div class="lg:col-span-4 space-y-6">
            
            <!-- Map Preview (WOW) -->
            <div class="glass-panel p-5 rounded-3xl space-y-4 text-left">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block border-b border-slate-100 dark:border-slate-800 pb-1.5 flex items-center gap-1.5">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-brand-500"></i> Headquarters Map Preview
              </span>
              
              <div class="rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800/80 h-44 bg-slate-900 shadow-sm relative">
                <iframe src="${s.googleMapsEmbedUrl}" class="w-full h-full border-none" allowfullscreen="" loading="lazy"></iframe>
              </div>
            </div>

            <!-- Brand Design parameters info -->
            <div class="glass-panel p-5 rounded-3xl text-left space-y-4">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block border-b border-slate-100 dark:border-slate-800 pb-1.5">
                System Diagnostics
              </span>

              <div class="space-y-3 text-xs">
                <div class="flex justify-between items-center">
                  <span class="text-slate-500">Node Environment</span>
                  <span class="font-bold text-slate-700 dark:text-slate-200">Production</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-500">Active Themes</span>
                  <span class="font-bold text-slate-700 dark:text-slate-200">Dual (Dark/Light)</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-500">System Caches</span>
                  <button id="btn-clear-sys-cache" class="px-2 py-1 bg-red-500/10 text-red-500 rounded font-bold text-[9px] hover:bg-red-500/25">Flush Cache</button>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    `;
  },

  // --- Initializer Event Listeners ---
  init(params) {
    this.bindEvents();

    if (params && params.tab) {
      activeSubTab = params.tab;
      this.refresh();
      window.history.replaceState(null, null, "#/settings");
    }
  },

  bindEvents() {
    // Save settings
    document.getElementById("btn-save-website-settings")?.addEventListener("click", () => {
      const s = Store.get("websiteSettings") || {};

      s.websiteName = document.getElementById("set-web-name")?.value.trim() || "";
      s.businessEmail = document.getElementById("set-business-email")?.value.trim() || "";
      s.websiteDescription = document.getElementById("set-web-desc")?.value.trim() || "";
      
      // Brand color variables
      s.logoUrl = document.getElementById("set-logo-url")?.value || "";
      s.faviconUrl = document.getElementById("set-favicon-url")?.value || "";
      s.primaryColor = document.getElementById("set-color-primary")?.value || "";
      s.secondaryColor = document.getElementById("set-color-secondary")?.value || "";

      // Social URLs
      s.socialLinks = {
        linkedin: document.getElementById("set-social-linkedin")?.value.trim() || "",
        twitter: document.getElementById("set-social-twitter")?.value.trim() || "",
        github: document.getElementById("set-social-github")?.value.trim() || "",
        facebook: document.getElementById("set-social-facebook")?.value.trim() || ""
      };

      // Footers
      s.footerText = document.getElementById("set-footer-text")?.value.trim() || "";

      // Locations
      s.businessPhone = document.getElementById("set-business-phone")?.value.trim() || "";
      s.language = document.getElementById("set-language")?.value || "en";
      s.timezone = document.getElementById("set-timezone")?.value || "America/Los_Angeles";
      s.businessAddress = document.getElementById("set-business-address")?.value.trim() || "";
      s.googleMapsEmbedUrl = document.getElementById("set-maps-url")?.value.trim() || "";

      Store.set("websiteSettings", s);
      App.showToast("General system settings applied and persisted.", "success");
      this.refresh();
    });

    // Subtabs Switch Listener
    document.querySelectorAll(".settings-tab-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        activeSubTab = btn.getAttribute("data-tab");
        this.refresh();
      });
    });

    // Color Pickers matching text selectors
    const pickerPrimary = document.getElementById("set-color-primary");
    pickerPrimary?.addEventListener("input", (e) => {
      document.getElementById("primary-color-text").textContent = e.target.value.toUpperCase();
    });

    const pickerSecondary = document.getElementById("set-color-secondary");
    pickerSecondary?.addEventListener("input", (e) => {
      document.getElementById("secondary-color-text").textContent = e.target.value.toUpperCase();
    });

    // Clear system cache diagnostic mock trigger
    document.getElementById("btn-clear-sys-cache")?.addEventListener("click", () => {
      App.showToast("Compiling node structures... System Cache cleared.", "success");
    });

    // Change Logo/Favicon Mock file generators
    const mockImageChange = (previewId, inputId) => {
      const mockPics = [
        "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=150&q=80"
      ];
      const picked = mockPics[Math.floor(Math.random() * mockPics.length)];
      
      const img = document.getElementById(previewId);
      const val = document.getElementById(inputId);
      if (img) img.src = picked;
      if (val) val.value = picked;
      App.showToast("Brand asset updated dynamically.", "info");
    };

    document.getElementById("btn-change-logo")?.addEventListener("click", () => {
      mockImageChange("logo-preview-img", "set-logo-url");
    });

    document.getElementById("btn-change-favicon")?.addEventListener("click", () => {
      mockImageChange("favicon-preview-img", "set-favicon-url");
    });
  },

  // --- View Refresher ---
  refresh() {
    const viewport = document.getElementById("app-view-viewport");
    if (viewport) {
      viewport.innerHTML = this.render();
      this.init();
      lucide.createIcons();
    }
  }
};
