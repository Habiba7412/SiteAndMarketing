// Media Library View
import { Store } from '../store.js';
import { App } from '../app.js';

let selectedMediaId = null;

export const MediaView = {
  render(params) {
    const media = Store.get("mediaLibrary") || [];
    const selectedAsset = media.find(m => m.id === selectedMediaId) || null;

    return `
      <div class="space-y-8 animate-fade-in text-left">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Media Asset Library</h2>
            <p class="text-xs text-slate-400">Upload brand logos, case study headers, and audit screenshots. Max file size: 50MB.</p>
          </div>
          
          <button id="btn-upload-media-asset" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2">
            <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload Asset
          </button>
        </div>

        <!-- Media Split columns -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Folder Tree navigation Left (3 columns) -->
          <div class="lg:col-span-3 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Directories</span>
            
            <div class="space-y-1 text-xs font-medium">
              <button class="w-full flex items-center justify-between px-3 py-2 bg-slate-100 dark:bg-slate-800 rounded-xl text-brand-600 dark:text-brand-400 font-bold">
                <span class="flex items-center gap-2"><i data-lucide="folder-open" class="w-4 h-4"></i> Root Assets</span>
                <span class="text-[9px] bg-slate-200 dark:bg-slate-900 px-1.5 py-0.5 rounded font-bold">${media.length}</span>
              </button>
              <button class="w-full flex items-center gap-2 px-3 py-2 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-700">
                <i data-lucide="folder" class="w-4 h-4"></i> Blog Covers
              </button>
              <button class="w-full flex items-center gap-2 px-3 py-2 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-700">
                <i data-lucide="folder" class="w-4 h-4"></i> User Avatars
              </button>
            </div>
          </div>

          <!-- File Grid Center (6 columns) -->
          <div class="lg:col-span-6 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">All Assets</span>
            
            <!-- Asset cards grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
              ${media.map(m => {
                const isSelected = m.id === selectedMediaId;
                return `
                  <div class="media-card-cell relative aspect-square rounded-2xl overflow-hidden border cursor-pointer bg-white dark:bg-slate-900 group ${isSelected ? 'border-brand-500 ring-2 ring-brand-500/10 shadow-premium' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300'}" data-id="${m.id}">
                    <img src="${m.url}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="${m.name}">
                    <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                    <div class="absolute bottom-2 left-2 right-2 bg-white/90 dark:bg-slate-900/90 border border-slate-100 dark:border-slate-800/80 px-2 py-1 rounded-lg text-[9px] font-bold truncate max-w-full block shadow-sm">
                      ${m.name}
                    </div>
                  </div>
                `;
              }).join("")}
            </div>
          </div>

          <!-- Inspector Panel Right (3 columns) -->
          <div class="lg:col-span-3 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Asset Inspector</span>
            
            <div class="glass-panel p-5 rounded-3xl text-left space-y-4">
              ${selectedAsset ? `
                <div class="aspect-video w-full rounded-xl overflow-hidden border border-slate-100 dark:border-slate-800 bg-slate-900">
                  <img src="${selectedAsset.url}" class="w-full h-full object-cover" alt="${selectedAsset.name}">
                </div>
                <div class="space-y-3 text-xs">
                  <div>
                    <span class="font-extrabold block text-slate-700 dark:text-slate-200 truncate">${selectedAsset.name}</span>
                    <span class="text-[9px] text-slate-400 font-mono block truncate">${selectedAsset.url}</span>
                  </div>
                  
                  <div class="space-y-1.5 pt-2 border-t border-slate-100 dark:border-slate-800/80 text-[10px] leading-relaxed">
                    <div class="flex justify-between">
                      <span class="text-slate-400">File Type</span>
                      <span class="font-bold text-slate-700 dark:text-slate-200">${selectedAsset.type}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-slate-400">File Size</span>
                      <span class="font-bold text-slate-700 dark:text-slate-200">${selectedAsset.size}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-slate-400">Dimensions</span>
                      <span class="font-bold text-slate-700 dark:text-slate-200">${selectedAsset.dimensions || 'N/A'}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-slate-400">Uploaded</span>
                      <span class="font-bold text-slate-700 dark:text-slate-200">${new Date(selectedAsset.dateUploaded).toLocaleDateString()}</span>
                    </div>
                  </div>

                  <div class="pt-3 flex gap-2">
                    <button id="btn-copy-media-link" class="flex-1 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 rounded-lg font-bold text-[10px] text-center flex items-center justify-center gap-1">
                      <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copy URL
                    </button>
                    <button id="btn-delete-media-asset" class="py-2 px-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 rounded-lg font-bold text-[10px] text-center flex items-center justify-center">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                  </div>
                </div>
              ` : `
                <div class="py-8 text-center text-slate-400 text-xs flex flex-col items-center gap-2">
                  <i data-lucide="image" class="w-8 h-8 opacity-30"></i>
                  Select an asset card in the center grid to audit dimensions, copy links, or delete schemas.
                </div>
              `}
            </div>
          </div>

        </div>

      </div>
    `;
  },

  // --- Initializer Event Listeners ---
  init() {
    this.bindEvents();
  },

  bindEvents() {
    // Select Card Click
    document.querySelectorAll(".media-card-cell").forEach(card => {
      card.addEventListener("click", () => {
        selectedMediaId = card.getAttribute("data-id");
        this.refresh();
      });
    });

    // Copy asset URL
    document.getElementById("btn-copy-media-link")?.addEventListener("click", () => {
      const media = Store.get("mediaLibrary") || [];
      const asset = media.find(m => m.id === selectedMediaId);
      if (asset) {
        navigator.clipboard.writeText(asset.url);
        App.showToast("Asset URL copied to clipboard!", "success");
      }
    });

    // Delete asset
    document.getElementById("btn-delete-media-asset")?.addEventListener("click", () => {
      if (confirm("Delete this asset permanently from media databases?")) {
        Store.deleteItem("mediaLibrary", selectedMediaId);
        selectedMediaId = null;
        App.showToast("Media asset deleted.", "info");
        this.refresh();
      }
    });

    // Upload asset mock click
    document.getElementById("btn-upload-media-asset")?.addEventListener("click", () => {
      const title = prompt("Enter simulated file name (e.g. cloud_audit_2026.png):");
      if (title && title.trim()) {
        const mockUrls = [
          "https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80",
          "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80"
        ];
        const randomPic = mockUrls[Math.floor(Math.random() * mockUrls.length)];
        
        const newAsset = {
          id: "img-" + Date.now(),
          name: title.trim(),
          url: randomPic,
          size: (Math.random() * 2.5 + 0.1).toFixed(1) + " MB",
          type: "Image",
          dimensions: "1920x1080",
          dateUploaded: new Date().toISOString()
        };

        Store.insertItem("mediaLibrary", newAsset);
        selectedMediaId = newAsset.id;
        App.showToast("Media asset uploaded and indexed.", "success");
        this.refresh();
      }
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
