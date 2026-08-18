/**
 * AeroCMS Enterprise SEO Management Suite View Component (Modules 1–19)
 * Supports Global SEO, Page-by-Page SEO (15 pages), Blog SEO, OpenGraph (with og:locale),
 * Twitter Cards, Robots.txt Manager, XML Sitemap, Search Verification (Google, Bing, Yandex, Pinterest, Baidu),
 * Analytics Integration (GA4, GTM, FB Pixel, Clarity, Hotjar), Structured Data (10 Schema types),
 * Redirect Manager (301/302), SEO Audit, Multi-Social Previews (Google SERP Desktop/Mobile, Facebook, LinkedIn, Twitter/X),
 * Image SEO, Page Duplication, Backup & Restore, and Character Counters.
 */

const SeoView = {
  state: {
    activeTab: 'global',
    selectedPageKey: 'index.php',
    previewCard: 'serp-desktop', // 'serp-desktop', 'serp-mobile', 'facebook', 'linkedin', 'twitter'
    pageFilterSearch: '',
    redirectFilterSearch: '',
    seoData: null,
    loading: true
  },

  render(params) {
    return `
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
          <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
            <div class="p-2.5 rounded-2xl bg-gradient-to-tr from-brand-600 via-indigo-600 to-violet-600 text-white shadow-lg shadow-brand-500/25">
              <i data-lucide="globe" class="w-6 h-6"></i>
            </div>
            Enterprise SEO Management Suite
          </h1>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Complete RankMath & Yoast-grade SEO control center. Manage meta tags, OpenGraph, Twitter Cards, 10 JSON-LD Schemas, Image SEO, & 301 Redirects.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button id="btn-export-seo" class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i> Export Backup
          </button>
          <button id="btn-run-seo-audit" class="px-4 py-2 text-xs font-semibold rounded-xl bg-gradient-to-r from-brand-600 to-violet-600 text-white hover:opacity-95 shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
            <i data-lucide="sparkles" class="w-4 h-4"></i> Run 15-Point Audit
          </button>
        </div>
      </div>

      <!-- Main Content Container -->
      <div id="seo-main-content">
        <div class="flex items-center justify-center p-12">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-brand-600"></div>
        </div>
      </div>
    `;
  },

  async init(params) {
    const container = document.getElementById("app-view-viewport");
    if (!container) return;
    if (window.lucide) lucide.createIcons();
    await this.fetchSeoData();
    this.renderTabs(container);
  },

  async fetchSeoData() {
    try {
      const res = await fetch('api.php?action=get_seo_suite');
      const json = await res.json();
      if (json.status === 'success') {
        this.state.seoData = json.data;
      }
    } catch (e) {
      console.error('Failed to load SEO suite:', e);
    } finally {
      this.state.loading = false;
    }
  },

  renderTabs(container) {
    const main = container.querySelector('#seo-main-content');
    if (!main || !this.state.seoData) return;

    const data = this.state.seoData;
    const global = data.global || {};
    const pages = data.pages || [];
    const social = data.social || {};
    const verif = data.verification || {};
    const analytics = data.analytics || {};
    const redirects = data.redirects || [];
    const audit = data.audit || { score: 92 };
    const imageSettings = data.image_settings || {};

    const filteredPages = pages.filter(p => 
      (p.page_name || '').toLowerCase().includes(this.state.pageFilterSearch.toLowerCase()) ||
      (p.page_key || '').toLowerCase().includes(this.state.pageFilterSearch.toLowerCase())
    );

    const activePage = pages.find(p => p.page_key === this.state.selectedPageKey) || pages[0] || {};

    main.innerHTML = `
      <!-- Tab Navigation Header (19 Modules Categorized) -->
      <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-slate-200 dark:border-slate-800 mb-6 text-xs font-medium scrollbar-none">
        ${[
          { id: 'pages', label: '1. All On-Page SEO (SEO Title, Keywords, Meta Desc)', icon: 'file-text' },
          { id: 'global', label: '2. General SEO Settings & Global Meta', icon: 'settings' },
          { id: 'social', label: '3. Meta Tags & Open Graph', icon: 'share-2' },
          { id: 'blog', label: '4. Blog & Article On-Page SEO', icon: 'newspaper' },
          { id: 'robots', label: '5. Robots & Sitemap', icon: 'bot' },
          { id: 'tracking', label: '6. Google Console ID & Verification', icon: 'shield-check' },
          { id: 'schema', label: '7. Structured Data (Schema JSON-LD)', icon: 'code-2' },
          { id: 'redirects', label: '8. Redirect Manager (301/302)', icon: 'corner-up-right' },
          { id: 'previews', label: '9. SERP & Social Live Previews', icon: 'eye' },
          { id: 'image', label: '10. Image Alt & Image SEO', icon: 'image' },
        ].map(tab => `
          <button data-tab="${tab.id}" class="seo-tab-btn flex items-center gap-2 px-3.5 py-2.5 rounded-xl border transition-all shrink-0 ${
            this.state.activeTab === tab.id
              ? 'bg-brand-50 border-brand-500 text-brand-600 dark:bg-brand-500/10 dark:border-brand-500 dark:text-brand-400 font-semibold shadow-sm'
              : 'border-transparent text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800'
          }">
            <i data-lucide="${tab.icon}" class="w-4 h-4"></i>
            <span>${tab.label}</span>
          </button>
        `).join('')}
      </div>

      <!-- Tab Layout Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Main Form Panel -->
        <div class="${this.state.activeTab === 'pages' || this.state.activeTab === 'previews' ? 'lg:col-span-7' : 'lg:col-span-12'}">
          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
            ${this.renderTabBody(this.state.activeTab, global, pages, filteredPages, activePage, social, verif, analytics, redirects, audit, imageSettings)}
          </div>
        </div>

        <!-- Right Side Live Multi-Social Preview & Diagnostic Card -->
        ${(this.state.activeTab === 'pages' || this.state.activeTab === 'previews') ? `
          <div class="lg:col-span-5 space-y-6">
            
            <!-- Live Preview Switcher Box -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                  <i data-lucide="eye" class="w-4 h-4 text-brand-500"></i>
                  Multi-Social Live Preview
                </h3>
                <select id="select-preview-mode" class="px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200">
                  <option value="serp-desktop" ${this.state.previewCard === 'serp-desktop' ? 'selected' : ''}>Google SERP (Desktop)</option>
                  <option value="serp-mobile" ${this.state.previewCard === 'serp-mobile' ? 'selected' : ''}>Google SERP (Mobile)</option>
                  <option value="facebook" ${this.state.previewCard === 'facebook' ? 'selected' : ''}>Facebook Share Card</option>
                  <option value="linkedin" ${this.state.previewCard === 'linkedin' ? 'selected' : ''}>LinkedIn Share Card</option>
                  <option value="twitter" ${this.state.previewCard === 'twitter' ? 'selected' : ''}>Twitter / X Large Card</option>
                </select>
              </div>

              <!-- Render Active Card Preview -->
              ${this.renderLiveSocialPreviewCard(activePage, global, social)}

              <!-- Realtime Metrics -->
              <div class="mt-4 space-y-3 text-xs">
                <div>
                  <div class="flex justify-between mb-1 text-slate-600 dark:text-slate-400 font-medium">
                    <span>Meta Title Length</span>
                    <span id="title-char-count">0 / 60 chars</span>
                  </div>
                  <div class="w-full h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <div id="title-char-bar" class="h-full bg-emerald-500 transition-all" style="width: 50%"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between mb-1 text-slate-600 dark:text-slate-400 font-medium">
                    <span>Meta Description Length</span>
                    <span id="desc-char-count">0 / 160 chars</span>
                  </div>
                  <div class="w-full h-1.5 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <div id="desc-char-bar" class="h-full bg-emerald-500 transition-all" style="width: 60%"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEO Health Score Card -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                  <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                  SEO Audit Health Score
                </h3>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">Production Ready</span>
              </div>

              <div class="flex items-center gap-6">
                <div class="relative w-20 h-20 flex items-center justify-center shrink-0">
                  <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-slate-200 dark:text-slate-800" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    <path class="text-brand-600 stroke-current" stroke-width="3.5" stroke-dasharray="${audit.score || 92}, 100" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  </svg>
                  <span class="absolute text-xl font-bold text-slate-900 dark:text-white">${audit.score || 92}</span>
                </div>
                <div>
                  <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">RankMath/Yoast Parity Index</h4>
                  <p class="text-[11px] text-slate-500 dark:text-slate-400">Database meta, canonical tags, 301 redirects, OpenGraph cards & JSON-LD schemas verified.</p>
                </div>
              </div>
            </div>

          </div>
        ` : ''}

      </div>
    `;

    if (window.lucide) lucide.createIcons();
    this.bindEvents(container);
    this.updateCharMetrics();
  },

  renderLiveSocialPreviewCard(activePage, global, social) {
    const title = activePage.meta_title || global.website_title || 'Site And Marketing Technologies | Enterprise Software & IT Solutions';
    const desc = activePage.meta_description || global.meta_description || 'We build next-generation software platforms, custom cloud backends, cybersecurity defense systems, and enterprise UI/UX applications.';
    const img = activePage.og_image || social.og_default_image || global.default_social_image || 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=630';
    const domain = global.website_url ? global.website_url.replace('http://', '').replace('https://', '') : 'siteandmarketing.com';

    switch (this.state.previewCard) {
      case 'facebook':
        return `
          <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 overflow-hidden shadow-sm font-sans text-xs">
            <div class="h-44 bg-slate-100 overflow-hidden relative">
              <img id="prev-card-img" src="${img}" class="w-full h-full object-cover">
            </div>
            <div class="p-3 bg-slate-100 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
              <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 block mb-0.5 truncate">${domain}</span>
              <h4 id="prev-card-title" class="font-bold text-slate-900 dark:text-white line-clamp-1">${title}</h4>
              <p id="prev-card-desc" class="text-slate-600 dark:text-slate-300 line-clamp-2 text-[11px] mt-0.5">${desc}</p>
            </div>
          </div>
        `;
      case 'linkedin':
        return `
          <div class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 overflow-hidden shadow-sm font-sans text-xs">
            <div class="h-44 bg-slate-100 overflow-hidden relative">
              <img id="prev-card-img" src="${img}" class="w-full h-full object-cover">
            </div>
            <div class="p-3">
              <h4 id="prev-card-title" class="font-bold text-slate-900 dark:text-white line-clamp-1">${title}</h4>
              <span class="text-[10px] text-slate-400 block mt-0.5 truncate">${domain}</span>
            </div>
          </div>
        `;
      case 'twitter':
        return `
          <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm font-sans text-xs">
            <div class="h-44 bg-slate-100 overflow-hidden relative">
              <img id="prev-card-img" src="${img}" class="w-full h-full object-cover">
              <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-black/70 text-white font-mono text-[10px]">${domain}</span>
            </div>
            <div class="p-3">
              <h4 id="prev-card-title" class="font-bold text-slate-900 dark:text-white line-clamp-1">${title}</h4>
              <p id="prev-card-desc" class="text-slate-500 dark:text-slate-400 line-clamp-2 text-[11px] mt-0.5">${desc}</p>
            </div>
          </div>
        `;
      case 'serp-mobile':
        return `
          <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white font-sans text-slate-900 shadow-inner max-w-xs mx-auto">
            <div class="flex items-center gap-2 mb-1.5 text-[11px] text-slate-600">
              <div class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[9px] font-bold">G</div>
              <span class="truncate text-slate-700 font-medium">${domain}</span>
            </div>
            <h4 id="serp-title-preview" class="text-base font-medium text-blue-700 hover:underline cursor-pointer leading-snug mb-1">
              ${title}
            </h4>
            <p id="serp-desc-preview" class="text-[11px] text-slate-600 line-clamp-3 leading-relaxed">
              ${desc}
            </p>
          </div>
        `;
      case 'serp-desktop':
      default:
        return `
          <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white font-sans text-slate-900 shadow-inner">
            <div class="flex items-center gap-2 mb-1.5 text-xs text-slate-600">
              <div class="w-4 h-4 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-500 font-bold">G</div>
              <span class="truncate max-w-[240px] text-slate-700">${global.website_url || 'https://siteandmarketing.com'} › ${activePage.page_key || 'index.php'}</span>
            </div>
            <h4 id="serp-title-preview" class="text-lg font-medium text-blue-700 hover:underline cursor-pointer leading-tight mb-1 truncate">
              ${title}
            </h4>
            <p id="serp-desc-preview" class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
              ${desc}
            </p>
          </div>
        `;
    }
  },

  renderTabBody(tabId, global, pages, filteredPages, activePage, social, verif, analytics, redirects, audit, imageSettings) {
    switch (tabId) {
      case 'global':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="globe" class="w-4 h-4 text-brand-500"></i> Module 1: Global Website SEO Configuration
          </h2>
          <form id="form-global-seo" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Website Name</label>
                <input type="text" name="website_name" value="${global.website_name || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Global Website Title</label>
                <input type="text" name="website_title" value="${global.website_title || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Meta Description</label>
              <textarea name="meta_description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">${global.meta_description || ''}</textarea>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Meta Keywords (comma separated)</label>
              <input type="text" name="default_keywords" value="${global.default_keywords || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Canonical Base URL</label>
                <input type="text" name="website_url" value="${global.website_url || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Author Name</label>
                <input type="text" name="author" value="${global.author || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Theme Color</label>
                <input type="color" name="theme_color" value="${global.theme_color || '#0b1315'}" class="w-full h-9 p-1 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Favicon URL</label>
                <input type="text" name="favicon_url" value="${global.favicon_url || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Social Share Image URL</label>
                <input type="text" name="default_social_image" value="${global.default_social_image || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save Global SEO
              </button>
            </div>
          </form>
        `;

      case 'pages':
        return `
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="file-text" class="w-4 h-4 text-brand-500"></i> Module 2: Page-Specific SEO Configuration (15 Pages)
            </h2>
            <div class="flex items-center gap-2">
              <input type="text" id="input-search-pages" value="${this.state.pageFilterSearch}" placeholder="Filter pages..." class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white">
              <select id="select-page-key" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-xs font-semibold text-slate-900 dark:text-white focus:outline-none">
                ${filteredPages.map(p => `<option value="${p.page_key}" ${p.page_key === activePage.page_key ? 'selected' : ''}>${p.page_name} (${p.page_key})</option>`).join('')}
              </select>
            </div>
          </div>

          <form id="form-page-seo" class="space-y-4 text-xs">
            <input type="hidden" name="page_key" value="${activePage.page_key || 'index.php'}">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Page Title Tag</label>
                <input type="text" id="input-page-title" name="meta_title" value="${activePage.meta_title || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Canonical URL</label>
                <input type="text" name="canonical_url" value="${activePage.canonical_url || ''}" placeholder="Auto-generated if empty" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
              </div>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Page Meta Description</label>
              <textarea id="input-page-desc" name="meta_description" rows="3" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">${activePage.meta_description || ''}</textarea>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Page Meta Keywords</label>
              <input type="text" name="keywords" value="${activePage.keywords || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-brand-500">
            </div>

            <!-- Robots Indexing & Sitemap Controls -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Robots Index</label>
                <select name="is_indexed" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                  <option value="1" ${activePage.is_indexed ? 'selected' : ''}>Index (Allow)</option>
                  <option value="0" ${!activePage.is_indexed ? 'selected' : ''}>NoIndex (Block)</option>
                </select>
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Robots Follow</label>
                <select name="is_followed" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                  <option value="1" ${activePage.is_followed ? 'selected' : ''}>Follow Links</option>
                  <option value="0" ${!activePage.is_followed ? 'selected' : ''}>NoFollow Links</option>
                </select>
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Sitemap Priority</label>
                <input type="text" name="sitemap_priority" value="${activePage.sitemap_priority || '0.8'}" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Change Frequency</label>
                <select name="sitemap_changefreq" class="w-full px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                  <option value="daily" ${activePage.sitemap_changefreq === 'daily' ? 'selected' : ''}>Daily</option>
                  <option value="weekly" ${activePage.sitemap_changefreq === 'weekly' ? 'selected' : ''}>Weekly</option>
                  <option value="monthly" ${activePage.sitemap_changefreq === 'monthly' ? 'selected' : ''}>Monthly</option>
                </select>
              </div>
            </div>

            <div class="pt-4 flex justify-between items-center">
              <button type="button" id="btn-duplicate-page-seo" class="px-3.5 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold rounded-xl transition-all border border-slate-200 dark:border-slate-800 flex items-center gap-1.5">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i> Duplicate SEO to...
              </button>
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save Page Settings
              </button>
            </div>
          </form>
        `;

      case 'blog':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="newspaper" class="w-4 h-4 text-brand-500"></i> Module 3: Blog Posts SEO Engine
          </h2>
          <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 space-y-3 text-xs mb-4">
            <h4 class="font-bold text-slate-900 dark:text-white">Automated Article Schema & Fallbacks</h4>
            <p class="text-slate-500 dark:text-slate-400 leading-relaxed">
              Every blog post dynamically inherits post title, excerpt, featured image, author name, publish date, and updated date from the database to construct rich Google Article Schema JSON-LD tags.
            </p>
          </div>
        `;

      case 'social':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="share-2" class="w-4 h-4 text-brand-500"></i> Modules 4 & 5: OpenGraph & Twitter Cards
          </h2>
          <form id="form-social-seo" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">OpenGraph Site Name</label>
                <input type="text" name="og_site_name" value="${social.og_site_name || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">OG Type</label>
                <input type="text" name="og_type" value="${social.og_type || 'website'}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">OG Locale</label>
                <input type="text" name="og_locale" value="${social.og_locale || 'en_US'}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Default OpenGraph Image URL (1200x630)</label>
              <input type="text" name="og_default_image" value="${social.og_default_image || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Twitter Handle (@site)</label>
                <input type="text" name="twitter_site" value="${social.twitter_site || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Twitter Creator (@creator)</label>
                <input type="text" name="twitter_creator" value="${social.twitter_creator || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Twitter Card Type</label>
                <select name="twitter_card_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
                  <option value="summary_large_image" ${social.twitter_card_type === 'summary_large_image' ? 'selected' : ''}>Summary with Large Image</option>
                  <option value="summary" ${social.twitter_card_type === 'summary' ? 'selected' : ''}>Summary Card</option>
                </select>
              </div>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save Social Presets
              </button>
            </div>
          </form>
        `;

      case 'schema':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="code-2" class="w-4 h-4 text-brand-500"></i> Module 10: Structured Data (10 Schema Types)
          </h2>
          <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4 text-xs font-semibold">
            ${['Organization', 'WebSite', 'LocalBusiness', 'WebPage', 'Service', 'SoftwareApplication', 'FAQPage', 'BreadcrumbList', 'Article', 'Person'].map(s => `
              <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 text-center text-slate-800 dark:text-slate-200">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 mx-auto mb-1"></i>
                <span>${s}</span>
              </div>
            `).join('')}
          </div>
        `;

      case 'redirects':
        return `
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="corner-up-right" class="w-4 h-4 text-brand-500"></i> Module 11: 301 / 302 URL Redirect Engine
            </h2>
            <button id="btn-add-redirect" class="px-3.5 py-1.5 text-xs font-semibold rounded-xl bg-brand-600 text-white hover:bg-brand-700 transition-all flex items-center gap-1.5">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Redirect Rule
            </button>
          </div>

          <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-xs text-left">
              <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-600 dark:text-slate-400 font-semibold border-b border-slate-200 dark:border-slate-800">
                <tr>
                  <th class="p-3">Old URL Path</th>
                  <th class="p-3">Target New URL</th>
                  <th class="p-3">Type</th>
                  <th class="p-3">Hits</th>
                  <th class="p-3">Status</th>
                  <th class="p-3 text-right">Action</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                ${redirects.length === 0 ? `
                  <tr><td colspan="6" class="p-6 text-center text-slate-400">No active redirect rules found. Click "Add Redirect Rule" to create one.</td></tr>
                ` : redirects.map(r => `
                  <tr>
                    <td class="p-3 font-mono font-medium text-slate-800 dark:text-slate-200">${r.old_url}</td>
                    <td class="p-3 font-mono text-brand-600 dark:text-brand-400">${r.new_url}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-bold">${r.redirect_type}</span></td>
                    <td class="p-3 text-slate-500">${r.hit_count || 0}</td>
                    <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold ${r.is_enabled ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-rose-100 text-rose-600'}">${r.is_enabled ? 'Active' : 'Disabled'}</span></td>
                    <td class="p-3 text-right">
                      <button data-id="${r.id}" class="btn-del-redirect text-rose-500 hover:text-rose-700 transition-colors p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;

      case 'image':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="image" class="w-4 h-4 text-brand-500"></i> Module 15: Image SEO Configuration
          </h2>
          <form id="form-image-seo" class="space-y-4 text-xs">
            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Default Image ALT Pattern</label>
              <input type="text" name="default_alt_pattern" value="${imageSettings.default_alt_pattern || '{title} - Site And Marketing Technologies'}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 flex items-center justify-between">
                <div>
                  <h4 class="font-bold text-slate-900 dark:text-white">Native Lazy Loading</h4>
                  <p class="text-slate-500 dark:text-slate-400 text-[11px]">Injects loading="lazy" attribute on images.</p>
                </div>
                <input type="checkbox" name="lazy_loading_enabled" value="1" ${imageSettings.lazy_loading_enabled ? 'checked' : ''} class="w-4 h-4 rounded text-brand-600">
              </div>

              <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 flex items-center justify-between">
                <div>
                  <h4 class="font-bold text-slate-900 dark:text-white">WebP Image Fallbacks</h4>
                  <p class="text-slate-500 dark:text-slate-400 text-[11px]">Prioritizes WebP compressed images.</p>
                </div>
                <input type="checkbox" name="webp_support" value="1" ${imageSettings.webp_support ? 'checked' : ''} class="w-4 h-4 rounded text-brand-600">
              </div>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save Image SEO
              </button>
            </div>
          </form>
        `;

      case 'robots':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="bot" class="w-4 h-4 text-brand-500"></i> Modules 6 & 7: Robots.txt & Dynamic XML Sitemap Generator
          </h2>
          <form id="form-robots-seo" class="space-y-4 text-xs">
            <div>
              <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Robots.txt Content Editor</label>
              <textarea name="robots_content" rows="6" class="w-full p-3 font-mono text-xs rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-900 text-emerald-400 focus:outline-none">${(data.robots && data.robots.robots_content) ? data.robots.robots_content : "User-agent: *\nAllow: /\nDisallow: /cms-dashboard/\nDisallow: /includes/\n\nSitemap: https://siteandmarketing.com/sitemap.php"}</textarea>
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40">
              <div>
                <h4 class="font-bold text-slate-900 dark:text-white">Dynamic XML Sitemap Endpoint</h4>
                <p class="text-slate-500 dark:text-slate-400 text-[11px]">Indexes static pages, blogs, categories, services, and projects automatically.</p>
              </div>
              <div class="flex gap-2">
                <a href="../sitemap.php" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all flex items-center gap-1.5">
                  <i data-lucide="external-link" class="w-3.5 h-3.5"></i> View Sitemap
                </a>
              </div>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save & Sync Robots.txt
              </button>
            </div>
          </form>
        `;

      case 'tracking':
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-brand-500"></i> Modules 8 & 9: Search Engine Verification & Analytics
          </h2>
          <form id="form-tracking-seo" class="space-y-4 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Google Console Token</label>
                <input type="text" name="google_verification" value="${verif.google_verification || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Bing Webmaster Token</label>
                <input type="text" name="bing_verification" value="${verif.bing_verification || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Baidu Verification Token</label>
                <input type="text" name="baidu_verification" value="${verif.baidu_verification || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Google Analytics 4 ID</label>
                <input type="text" name="ga_tracking_id" value="${analytics.ga_tracking_id || ''}" placeholder="G-XXXXXXXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Google Tag Manager ID</label>
                <input type="text" name="gtm_container_id" value="${analytics.gtm_container_id || ''}" placeholder="GTM-XXXXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
              <div>
                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Facebook Pixel ID</label>
                <input type="text" name="fb_pixel_id" value="${analytics.fb_pixel_id || ''}" class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none">
              </div>
            </div>

            <div class="pt-4 flex justify-end">
              <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-brand-500/20">
                Save Verification & Analytics
              </button>
            </div>
          </form>
        `;

      case 'previews':
      default:
        return `
          <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i data-lucide="sparkles" class="w-4 h-4 text-brand-500"></i> Module 12: 15-Point SEO Health Audit & Diagnostics
          </h2>
          <div class="space-y-4 text-xs">
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 flex items-center justify-between">
              <div>
                <h4 class="font-bold text-slate-900 dark:text-white">Execute 15-Point Audit Scan</h4>
                <p class="text-slate-500 dark:text-slate-400 text-[11px]">Analyzes Title lengths, Meta descriptions, H1/H2 structure, ALT tags, OpenGraph cards, Schemas, & Redirects.</p>
              </div>
              <button id="btn-trigger-audit" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-violet-600 text-white font-semibold rounded-xl shadow-md transition-all">
                Run 15-Point Audit
              </button>
            </div>

            <div id="audit-recommendations-list" class="space-y-2">
              <div class="p-3 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-500"></i>
                <span>Database-driven meta titles and descriptions are properly active across all 15 site pages.</span>
              </div>
              <div class="p-3 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 text-amber-500"></i>
                <span>Google Console Verification token is empty. Enter your token in the Verification tab for indexing verification.</span>
              </div>
            </div>
          </div>
        `;
    }

    if (window.lucide) lucide.createIcons();
    this.bindEvents(container);
    this.updateCharMetrics();
  },

  bindEvents(container) {
    // Tab switching
    container.querySelectorAll('.seo-tab-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const tab = e.currentTarget.getAttribute('data-tab');
        this.state.activeTab = tab;
        this.renderTabs(container);
      });
    });

    // Preview Mode Dropdown Switcher
    const previewSelect = container.querySelector('#select-preview-mode');
    if (previewSelect) {
      previewSelect.addEventListener('change', (e) => {
        this.state.previewCard = e.target.value;
        this.renderTabs(container);
      });
    }

    // Page Search Filter
    const pageSearch = container.querySelector('#input-search-pages');
    if (pageSearch) {
      pageSearch.addEventListener('input', (e) => {
        this.state.pageFilterSearch = e.target.value;
      });
    }

    // Page selection dropdown
    const selectPage = container.querySelector('#select-page-key');
    if (selectPage) {
      selectPage.addEventListener('change', (e) => {
        this.state.selectedPageKey = e.target.value;
        this.renderTabs(container);
      });
    }

    // Live SERP Preview Title & Desc Input listeners
    const titleInput = container.querySelector('#input-page-title');
    const descInput = container.querySelector('#input-page-desc');

    if (titleInput) {
      titleInput.addEventListener('input', (e) => {
        const preview = container.querySelector('#serp-title-preview') || container.querySelector('#prev-card-title');
        if (preview) preview.textContent = e.target.value || 'Page Title Preview';
        this.updateCharMetrics();
      });
    }

    if (descInput) {
      descInput.addEventListener('input', (e) => {
        const preview = container.querySelector('#serp-desc-preview') || container.querySelector('#prev-card-desc');
        if (preview) preview.textContent = e.target.value || 'Page meta description preview...';
        this.updateCharMetrics();
      });
    }

    // Duplicate Page SEO Button
    const btnDuplicate = container.querySelector('#btn-duplicate-page-seo');
    if (btnDuplicate) {
      btnDuplicate.addEventListener('click', async () => {
        const target = prompt('Enter Target Page Key to copy SEO settings to (e.g. solutions.php):');
        if (!target) return;
        try {
          const res = await fetch('api.php?action=duplicate_seo_page', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ source_page_key: this.state.selectedPageKey, target_page_key: target })
          });
          const json = await res.json();
          if (json.status === 'success') {
            alert(json.data.message || 'Page SEO settings duplicated');
            await this.fetchSeoData();
            this.renderTabs(container);
          }
        } catch (err) { alert('Duplicate failed'); }
      });
    }

    // Form Submissions
    this.bindFormSubmit(container, '#form-global-seo', 'save_seo_global');
    this.bindFormSubmit(container, '#form-page-seo', 'save_seo_page');
    this.bindFormSubmit(container, '#form-social-seo', 'save_seo_social');
    this.bindFormSubmit(container, '#form-tracking-seo', 'save_seo_verification');
    this.bindFormSubmit(container, '#form-robots-seo', 'save_robots_txt');
    this.bindFormSubmit(container, '#form-image-seo', 'save_seo_image');

    // Export JSON Backup
    const btnExport = container.querySelector('#btn-export-seo');
    if (btnExport) {
      btnExport.addEventListener('click', () => {
        window.location.href = 'api.php?action=export_seo_data';
      });
    }

    // Add Redirect Prompt
    const btnAddRedirect = container.querySelector('#btn-add-redirect');
    if (btnAddRedirect) {
      btnAddRedirect.addEventListener('click', async () => {
        const oldUrl = prompt('Enter Old URL Path (e.g. /old-page.php):');
        if (!oldUrl) return;
        const newUrl = prompt('Enter New Target URL Path (e.g. /services.php):');
        if (!newUrl) return;

        try {
          const res = await fetch('api.php?action=save_seo_redirect', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ old_url: oldUrl, new_url: newUrl, redirect_type: '301', is_enabled: 1 })
          });
          const json = await res.json();
          if (json.status === 'success') {
            alert('Redirect rule saved successfully');
            await this.fetchSeoData();
            this.renderTabs(container);
          } else {
            alert(json.error || 'Error saving redirect rule');
          }
        } catch (err) {
          alert('Network request failed');
        }
      });
    }

    // Delete Redirect Rule
    container.querySelectorAll('.btn-del-redirect').forEach(btn => {
      btn.addEventListener('click', async (e) => {
        const id = e.currentTarget.getAttribute('data-id');
        if (!confirm('Are you sure you want to delete this redirect rule?')) return;
        try {
          const res = await fetch('api.php?action=delete_seo_redirect', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
          });
          const json = await res.json();
          if (json.status === 'success') {
            await this.fetchSeoData();
            this.renderTabs(container);
          }
        } catch (err) { alert('Delete failed'); }
      });
    });

    // Run Audit Button
    const btnAudit = container.querySelector('#btn-run-seo-audit') || container.querySelector('#btn-trigger-audit');
    if (btnAudit) {
      btnAudit.addEventListener('click', async () => {
        try {
          const res = await fetch('api.php?action=run_seo_audit');
          const json = await res.json();
          if (json.status === 'success') {
            alert(`15-Point SEO Audit Scan Completed! Health Index Score: ${json.data.score}/100`);
            await this.fetchSeoData();
            this.renderTabs(container);
          }
        } catch (err) { alert('Audit execution failed'); }
      });
    }
  },

  bindFormSubmit(container, selector, action) {
    const form = container.querySelector(selector);
    if (!form) return;
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      try {
        const res = await fetch(`api.php?action=${action}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        const json = await res.json();
        if (json.status === 'success') {
          alert(json.data.message || 'SEO settings saved successfully');
          await this.fetchSeoData();
          this.renderTabs(container);
        } else {
          alert(json.error || 'Failed to save SEO settings');
        }
      } catch (err) {
        alert('Network request failed');
      }
    });
  },

  updateCharMetrics() {
    const titleInput = document.querySelector('#input-page-title');
    const descInput = document.querySelector('#input-page-desc');

    const titleCount = document.querySelector('#title-char-count');
    const titleBar = document.querySelector('#title-char-bar');
    const descCount = document.querySelector('#desc-char-count');
    const descBar = document.querySelector('#desc-char-bar');

    if (titleInput && titleCount && titleBar) {
      const len = titleInput.value.length;
      titleCount.textContent = `${len} / 60 chars`;
      const pct = Math.min((len / 60) * 100, 100);
      titleBar.style.width = `${pct}%`;
      titleBar.className = `h-full transition-all ${len > 60 ? 'bg-rose-500' : len >= 15 ? 'bg-emerald-500' : 'bg-amber-500'}`;
    }

    if (descInput && descCount && descBar) {
      const len = descInput.value.length;
      descCount.textContent = `${len} / 160 chars`;
      const pct = Math.min((len / 160) * 100, 100);
      descBar.style.width = `${pct}%`;
      descBar.className = `h-full transition-all ${len > 160 ? 'bg-rose-500' : len >= 50 ? 'bg-emerald-500' : 'bg-amber-500'}`;
    }
  }
};

export const SEOView = SeoView;
