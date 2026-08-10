// Blog Posts Management View with Featured Image Upload Component
import { Store } from '../store.js';
import { App } from '../app.js';

let filterCategory = "All";
let filterStatus = "All";
let searchQuery = "";
let currentPage = 1;
const itemsPerPage = 5;

let selectedRows = []; // Keep track of checked blog post IDs

// Helper function to resolve cover image path relative to cms-dashboard directory
function resolveImageUrl(url) {
  if (!url) return '../uploads/blog/placeholder.svg';
  if (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('data:')) {
    return url;
  }
  if (url.startsWith('../')) {
    return url;
  }
  if (url.startsWith('uploads/')) {
    return '../' + url;
  }
  return url;
}

// Helper function to extract basename of file path
function getFilenameFromPath(path) {
  if (!path) return 'No image selected';
  const parts = path.split(/[\/\\]/);
  return parts[parts.length - 1];
}

// Helper to format file size in KB or MB
function formatBytes(bytes, decimals = 1) {
  if (!bytes || bytes === 0) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

export const BlogView = {
  render(params) {
    const blogs = Store.get("blogs") || [];
    const categories = Store.get("categories") || [];
    
    // Filter matching
    let filtered = blogs.filter(b => {
      const matchCat = filterCategory === "All" || b.category === filterCategory;
      const matchStat = filterStatus === "All" || b.status === filterStatus;
      const matchSearch = !searchQuery || b.title.toLowerCase().includes(searchQuery) || b.excerpt.toLowerCase().includes(searchQuery);
      return matchCat && matchStat && matchSearch;
    });

    // Pagination calculations
    const totalItems = filtered.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedItems = filtered.slice(startIndex, startIndex + itemsPerPage);

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Content Management</h2>
            <p class="text-xs text-slate-400">Publish, schedule, edit, or categorize case studies and articles.</p>
          </div>
          <button id="btn-create-post" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2 transition-all">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Blog Post
          </button>
        </div>

        <!-- Filter & Search Panel -->
        <div class="glass-panel p-5 rounded-3xl grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
          
          <!-- Search input -->
          <div class="relative md:col-span-2">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="blog-search-input" value="${searchQuery}" class="w-full glass-input pl-10 pr-4 py-2 text-xs rounded-xl" placeholder="Search blog titles, tags, or content...">
          </div>

          <!-- Category filter -->
          <div>
            <select id="blog-filter-category" class="w-full glass-input p-2 text-xs rounded-xl">
              <option value="All" ${filterCategory === 'All' ? 'selected' : ''}>All Categories</option>
              ${categories.map(c => `<option value="${c.name}" ${filterCategory === c.name ? 'selected' : ''}>${c.name}</option>`).join("")}
            </select>
          </div>

          <!-- Status filter -->
          <div>
            <select id="blog-filter-status" class="w-full glass-input p-2 text-xs rounded-xl">
              <option value="All" ${filterStatus === 'All' ? 'selected' : ''}>All Statuses</option>
              <option value="Published" ${filterStatus === 'Published' ? 'selected' : ''}>Published</option>
              <option value="Draft" ${filterStatus === 'Draft' ? 'selected' : ''}>Draft</option>
              <option value="Scheduled" ${filterStatus === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
            </select>
          </div>

        </div>

        <!-- Bulk Actions Tool Bar -->
        <div id="blog-bulk-toolbar" class="glass-card p-3 px-5 rounded-2xl flex items-center justify-between transition-all duration-300 ${selectedRows.length > 0 ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none h-0 p-0 overflow-hidden'}">
          <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-500"><span id="bulk-selected-count">${selectedRows.length}</span> posts selected</span>
            <div class="h-4 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
            <button id="btn-bulk-publish" class="text-xs font-bold text-brand-600 dark:text-brand-500 hover:underline flex items-center gap-1">
              <i data-lucide="eye" class="w-3.5 h-3.5"></i> Publish Selected
            </button>
            <button id="btn-bulk-delete" class="text-xs font-bold text-red-500 hover:underline flex items-center gap-1">
              <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Selected
            </button>
          </div>
          <button id="btn-clear-bulk" class="text-xs text-slate-400 hover:text-slate-600">Clear</button>
        </div>

        <!-- Data Table Panel -->
        <div class="glass-panel rounded-3xl overflow-hidden shadow-premium">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px] border-b border-slate-100 dark:border-slate-800">
                <tr>
                  <th class="p-4 w-12 text-center">
                    <input type="checkbox" id="check-all-blogs" ${selectedRows.length === paginatedItems.length && paginatedItems.length > 0 ? 'checked' : ''} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                  </th>
                  <th class="p-4">Post Info</th>
                  <th class="p-4">Category</th>
                  <th class="p-4">Author</th>
                  <th class="p-4">Date</th>
                  <th class="p-4 text-center">Featured</th>
                  <th class="p-4 text-center">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                ${paginatedItems.length === 0 ? `
                  <tr>
                    <td colspan="8" class="p-8 text-center text-slate-400 font-semibold">
                      No blog posts match your criteria. Click Add Blog Post to create one.
                    </td>
                  </tr>
                ` : paginatedItems.map(blog => {
                  const isChecked = selectedRows.includes(blog.id);
                  let statusColor = "bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400";
                  if (blog.status === "Published") statusColor = "bg-emerald-500/10 text-emerald-600 dark:text-emerald-500";
                  if (blog.status === "Scheduled") statusColor = "bg-amber-500/10 text-amber-600 dark:text-amber-500";

                  const imageSrc = resolveImageUrl(blog.coverImage);

                  return `
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 ${isChecked ? 'bg-brand-50/10' : ''}">
                      <td class="p-4 text-center">
                        <input type="checkbox" class="blog-row-check rounded border-slate-300 text-brand-600 focus:ring-brand-500" data-id="${blog.id}" ${isChecked ? 'checked' : ''}>
                      </td>
                      <td class="p-4">
                        <div class="flex items-center gap-3">
                          <img src="${imageSrc}" alt="Cover" class="w-12 h-8 rounded-lg object-cover shadow-sm border border-slate-200 dark:border-slate-800" onerror="this.src='../uploads/blog/placeholder.svg';">
                          <div class="min-w-0">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block truncate max-w-[200px] sm:max-w-[300px]">${blog.title}</span>
                            <span class="text-[10px] text-slate-400 font-semibold block truncate max-w-[250px]">${blog.excerpt}</span>
                          </div>
                        </div>
                      </td>
                      <td class="p-4 font-semibold text-slate-500">${blog.category}</td>
                      <td class="p-4 text-slate-600 dark:text-slate-400 font-medium">${blog.author}</td>
                      <td class="p-4 text-slate-400 font-semibold whitespace-nowrap">
                        ${new Date(blog.dateCreated || blog.created_at || Date.now()).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}<br>
                        <span class="text-[10px] text-slate-500 font-mono">${new Date(blog.dateCreated || blog.created_at || Date.now()).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                      </td>
                      <td class="p-4 text-center">
                        <button class="toggle-featured-btn p-1.5 rounded-lg text-slate-300 dark:text-slate-700 hover:text-amber-400 dark:hover:text-amber-400 ${blog.isFeatured ? 'text-amber-400 dark:text-amber-400' : ''}" data-id="${blog.id}">
                          <i data-lucide="star" class="w-4 h-4 inline fill-current"></i>
                        </button>
                      </td>
                      <td class="p-4 text-center">
                        <span class="px-2.5 py-1 rounded-full font-bold text-[9px] uppercase tracking-wider ${statusColor}">${blog.status}</span>
                      </td>
                      <td class="p-4 text-right space-x-1 shrink-0">
                        <button class="edit-blog-btn p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-600 dark:hover:text-slate-200" data-id="${blog.id}">
                          <i data-lucide="edit" class="w-3.5 h-3.5 inline"></i>
                        </button>
                        <button class="delete-blog-btn p-1.5 rounded-lg text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20" data-id="${blog.id}">
                          <i data-lucide="trash-2" class="w-3.5 h-3.5 inline"></i>
                        </button>
                      </td>
                    </tr>
                  `;
                }).join("")}
              </tbody>
            </table>
          </div>

          <!-- Pagination Bar -->
          <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
            <span class="text-slate-400 font-semibold">Showing ${startIndex + 1} to ${Math.min(startIndex + itemsPerPage, totalItems)} of ${totalItems} articles</span>
            <div class="flex items-center gap-2">
              <button id="btn-blog-prev" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 font-semibold disabled:opacity-40">Previous</button>
              
              <div class="flex gap-1">
                ${Array.from({length: totalPages}).map((_, i) => `
                  <button class="btn-blog-page w-7 h-7 rounded-lg font-bold text-xs ${currentPage === i + 1 ? 'bg-brand-600 text-white shadow-md' : 'border border-slate-200 dark:border-slate-800 hover:bg-slate-100'}" data-page="${i + 1}">${i + 1}</button>
                `).join("")}
              </div>

              <button id="btn-blog-next" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 font-semibold disabled:opacity-40">Next</button>
            </div>
          </div>

        </div>

      </div>
    `;
  },

  // --- Initializer Event Listeners ---
  init(params) {
    this.bindTableEvents();
    
    // Automatically trigger "Add New Post" modal if routed via breadcrumb
    if (params && params.action === "new") {
      setTimeout(() => this.openPostFormModal(), 200);
      window.history.replaceState(null, null, "#/blog");
    }
  },

  bindTableEvents() {
    // Search input typing
    const searchInput = document.getElementById("blog-search-input");
    searchInput?.addEventListener("input", (e) => {
      searchQuery = e.target.value.toLowerCase().trim();
      currentPage = 1;
      this.refresh();
    });

    // Category Selector
    const catSelect = document.getElementById("blog-filter-category");
    catSelect?.addEventListener("change", (e) => {
      filterCategory = e.target.value;
      currentPage = 1;
      this.refresh();
    });

    // Status Selector
    const statSelect = document.getElementById("blog-filter-status");
    statSelect?.addEventListener("change", (e) => {
      filterStatus = e.target.value;
      currentPage = 1;
      this.refresh();
    });

    // Toggle Featured star click
    document.querySelectorAll(".toggle-featured-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const blogs = Store.get("blogs") || [];
        const match = blogs.find(b => b.id === id);
        if (match) {
          match.isFeatured = !match.isFeatured;
          Store.set("blogs", blogs);
          App.showToast(match.isFeatured ? "Marked as Featured Post" : "Removed from Featured", "success");
          this.refresh();
        }
      });
    });

    // Row selection checkboxes
    document.querySelectorAll(".blog-row-check").forEach(chk => {
      chk.addEventListener("change", () => {
        const id = chk.getAttribute("data-id");
        if (chk.checked) {
          selectedRows.push(id);
        } else {
          selectedRows = selectedRows.filter(r => r !== id);
        }
        this.refresh();
      });
    });

    // Check All Checkbox
    const checkAll = document.getElementById("check-all-blogs");
    checkAll?.addEventListener("change", (e) => {
      const blogs = Store.get("blogs") || [];
      const currentPaginatedIds = blogs.slice((currentPage - 1) * itemsPerPage, currentPage * itemsPerPage).map(b => b.id);
      
      if (checkAll.checked) {
        selectedRows = Array.from(new Set([...selectedRows, ...currentPaginatedIds]));
      } else {
        selectedRows = selectedRows.filter(r => !currentPaginatedIds.includes(r));
      }
      this.refresh();
    });

    // Clear Bulk button
    document.getElementById("btn-clear-bulk")?.addEventListener("click", () => {
      selectedRows = [];
      this.refresh();
    });

    // Bulk Delete Action
    document.getElementById("btn-bulk-delete")?.addEventListener("click", () => {
      if (confirm(`Are you sure you want to delete the ${selectedRows.length} selected blog posts?`)) {
        let blogs = Store.get("blogs") || [];
        blogs = blogs.filter(b => !selectedRows.includes(b.id));
        Store.set("blogs", blogs);
        App.showToast(`${selectedRows.length} posts deleted from database.`, "info");
        selectedRows = [];
        this.refresh();
      }
    });

    // Bulk Publish Action
    document.getElementById("btn-bulk-publish")?.addEventListener("click", () => {
      const blogs = Store.get("blogs") || [];
      blogs.forEach(b => {
        if (selectedRows.includes(b.id)) {
          b.status = "Published";
        }
      });
      Store.set("blogs", blogs);
      App.showToast(`Selected posts published successfully.`, "success");
      selectedRows = [];
      this.refresh();
    });

    // Edit Blog Click
    document.querySelectorAll(".edit-blog-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        this.openPostFormModal(btn.getAttribute("data-id"));
      });
    });

    // Delete Blog Click
    document.querySelectorAll(".delete-blog-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (confirm("Delete this blog post? This action is permanent.")) {
          Store.deleteItem("blogs", id);
          App.showToast("Blog post deleted.", "info");
          this.refresh();
        }
      });
    });

    // Add Blog Click
    document.getElementById("btn-create-post")?.addEventListener("click", () => {
      this.openPostFormModal();
    });

    // Pagination Click
    document.getElementById("btn-blog-prev")?.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        this.refresh();
      }
    });
    document.getElementById("btn-blog-next")?.addEventListener("click", () => {
      currentPage++;
      this.refresh();
    });
    document.querySelectorAll(".btn-blog-page").forEach(btn => {
      btn.addEventListener("click", () => {
        currentPage = parseInt(btn.getAttribute("data-page"));
        this.refresh();
      });
    });
  },

  // --- Dynamic Post Creator & Rich Editor Modal ---

  openPostFormModal(blogId = null) {
    const blogs = Store.get("blogs") || [];
    const categories = Store.get("categories") || [];
    
    let modalTitle = "Add Blog Post";
    let bTitle = "";
    let bSlug = "";
    let bExcerpt = "";
    let bContent = "";
    let bCategory = categories[0]?.name || "Enterprise IT";
    let bAuthor = "Admin";
    let bCover = "";
    let bSeoTitle = "";
    let bMetaDesc = "";
    let bStatus = "Draft";
    let bFeatured = false;
    let bDateRaw = new Date().toISOString();

    if (blogId) {
      modalTitle = "Edit Blog Post Settings";
      const match = blogs.find(b => b.id === blogId);
      if (match) {
        bTitle = match.title || "";
        bSlug = match.slug || "";
        bExcerpt = match.excerpt || "";
        bContent = match.content || "";
        bCategory = match.category || categories[0]?.name || "Enterprise IT";
        bAuthor = match.author || "Admin";
        bCover = match.coverImage || "";
        bSeoTitle = match.seoTitle || "";
        bMetaDesc = match.metaDescription || "";
        bStatus = match.status || "Draft";
        bFeatured = match.isFeatured || false;
        bDateRaw = match.dateCreated || match.created_at || new Date().toISOString();
      }
    }

    let dateObj = new Date(bDateRaw);
    if (isNaN(dateObj.getTime())) dateObj = new Date();
    const localISO = new Date(dateObj.getTime() - dateObj.getTimezoneOffset() * 60000).toISOString().slice(0, 16);

    const initialPreviewSrc = resolveImageUrl(bCover);
    const initialFilename   = bCover ? getFilenameFromPath(bCover) : '';

    const formHtml = `
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-left" id="blog-edit-form-root">
        
        <!-- Left: Core editor (2 columns) -->
        <div class="md:col-span-2 space-y-4">
          
          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Article Title</label>
            <input type="text" id="edit-post-title" value="${bTitle}" class="w-full glass-input p-3 rounded-xl font-bold text-sm" placeholder="e.g. Scaling enterprise networking architecture">
          </div>

          <div class="space-y-1">
            <div class="flex items-center justify-between">
              <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Clean SEO URL Slug</label>
              <span id="blog-url-preview" class="text-[10px] font-mono text-brand-600 dark:text-brand-400 truncate max-w-[280px]">http://localhost/myitcomapny/blog/${bSlug || 'where-does-it-come-from'}</span>
            </div>
            <input type="text" id="edit-post-slug" value="${bSlug}" class="w-full glass-input p-2.5 rounded-xl font-mono text-[10px]" placeholder="where-does-it-come-from">
          </div>

          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Short Summary / Excerpt</label>
            <textarea id="edit-post-excerpt" rows="2" class="w-full glass-input p-3 rounded-xl" placeholder="A brief summary for cards and search feeds...">${bExcerpt}</textarea>
          </div>

          <!-- Visual Rich Editor Toolbar -->
          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Body Content</label>
            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
              <div class="bg-slate-50 dark:bg-slate-800/80 px-3 py-2 border-b border-slate-200 dark:border-slate-800 flex items-center gap-1">
                <button type="button" class="btn-editor-tool p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-slate-600 dark:text-slate-300 font-bold" data-tag="b" title="Bold">B</button>
                <button type="button" class="btn-editor-tool p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-slate-600 dark:text-slate-300 italic" data-tag="i" title="Italic">I</button>
                <button type="button" class="btn-editor-tool p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-slate-600 dark:text-slate-300 underline" data-tag="u" title="Underline">U</button>
                <div class="h-4 w-[1px] bg-slate-300 dark:bg-slate-700 mx-1"></div>
                <button type="button" class="btn-editor-tool p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-[10px] font-semibold" data-tag="h3">H3</button>
                <button type="button" class="btn-editor-tool p-1 hover:bg-slate-200 dark:hover:bg-slate-700 rounded text-[10px] font-semibold" data-tag="ul">List</button>
              </div>
              <textarea id="edit-post-content" rows="8" class="w-full border-none p-4 bg-transparent outline-none focus:ring-0 text-xs font-mono leading-relaxed" placeholder="Type HTML body content...">${bContent}</textarea>
            </div>
          </div>

        </div>

        <!-- Right: Publishing & Featured Image (1 column) -->
        <div class="space-y-4">
          
          <!-- General Settings -->
          <div class="glass-panel p-4 rounded-2xl space-y-3">
            <span class="block font-bold text-[10px] text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-1.5">Settings</span>
            
            <div class="space-y-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Publish Status</label>
              <select id="edit-post-status" class="w-full glass-input p-2 rounded-lg">
                <option value="Draft" ${bStatus === 'Draft' ? 'selected' : ''}>Draft</option>
                <option value="Published" ${bStatus === 'Published' ? 'selected' : ''}>Published</option>
                <option value="Scheduled" ${bStatus === 'Scheduled' ? 'selected' : ''}>Scheduled</option>
              </select>
            </div>

            <div class="space-y-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Category</label>
              <select id="edit-post-category" class="w-full glass-input p-2 rounded-lg">
                ${categories.map(c => `<option value="${c.name}" ${bCategory === c.name ? 'selected' : ''}>${c.name}</option>`).join("")}
              </select>
            </div>

            <div class="space-y-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Author Name</label>
              <input type="text" id="edit-post-author" value="${bAuthor}" class="w-full glass-input p-2 rounded-lg">
            </div>

            <div class="space-y-1">
              <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Publish Date & Time</label>
              <input type="datetime-local" id="edit-post-date" value="${localISO}" class="w-full glass-input p-2 rounded-lg text-xs font-mono">
            </div>

            <div class="flex items-center justify-between pt-1">
              <span class="text-slate-500 font-bold text-[10px] uppercase">Featured Article</span>
              <input type="checkbox" id="edit-post-featured" ${bFeatured ? 'checked' : ''} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
            </div>
          </div>

          <!-- Featured Image Upload Component -->
          <div class="glass-panel p-4 rounded-2xl space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-1.5">
              <span class="block font-bold text-[10px] text-slate-400 uppercase tracking-wider">Featured Image</span>
              <span class="text-[9px] font-semibold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">JPG, PNG, WEBP, SVG • Max 5MB</span>
            </div>
            
            <!-- Drag & Drop Dropzone Container -->
            <div id="cover-drop-zone" class="relative rounded-2xl overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-700 hover:border-brand-500 bg-slate-50 dark:bg-slate-900/50 transition-all duration-200 p-3 text-center group cursor-pointer">
              
              <!-- Image Preview Wrapper -->
              <div id="cover-preview-wrapper" class="relative ${bCover ? '' : 'hidden'} group/preview">
                <img id="edit-post-cover-preview" src="${initialPreviewSrc}" class="w-full h-36 object-cover rounded-xl shadow-md border border-slate-200 dark:border-slate-800 transition-all" alt="Featured Image Preview" onerror="this.src='../uploads/blog/placeholder.svg';">
                <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover/preview:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                  <button type="button" id="btn-overlay-change-image" class="px-3 py-1.5 bg-brand-600 hover:bg-brand-500 text-white text-[11px] font-bold rounded-lg flex items-center gap-1.5 shadow-md transition-all">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Change Image
                  </button>
                  <button type="button" id="btn-overlay-remove-image" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-[11px] font-bold rounded-lg flex items-center gap-1.5 shadow-md transition-all">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                  </button>
                </div>
              </div>

              <!-- Default Placeholder Dropzone State -->
              <div id="cover-placeholder-wrapper" class="${bCover ? 'hidden' : 'flex'} flex-col items-center justify-center py-5 gap-2 text-slate-400">
                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800/80 flex items-center justify-center text-brand-500 dark:text-brand-400 group-hover:scale-110 transition-transform">
                  <i data-lucide="image-plus" class="w-5 h-5"></i>
                </div>
                <div>
                  <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Drag & drop image here</p>
                  <p class="text-[10px] text-slate-400 mt-0.5">or click Choose Image button below</p>
                </div>
              </div>

              <!-- Progress Overlay -->
              <div id="cover-upload-progress" class="absolute inset-0 bg-slate-950/85 hidden flex-col items-center justify-center p-4 text-white gap-2 rounded-2xl z-20">
                <i data-lucide="loader-2" class="w-6 h-6 animate-spin text-brand-400"></i>
                <span id="cover-upload-percent" class="text-[11px] font-bold">Uploading 0%...</span>
                <div class="w-full bg-slate-800 h-1.5 rounded-full overflow-hidden">
                  <div id="cover-upload-progress-bar" class="bg-brand-500 h-full w-0 transition-all duration-150"></div>
                </div>
              </div>
            </div>

            <!-- Action Toolbar: Choose Image Button & File Details -->
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-2">
                <button type="button" id="btn-trigger-file-pick" class="flex-1 py-2 px-3 bg-brand-600 hover:bg-brand-500 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm">
                  <i data-lucide="folder-open" class="w-4 h-4"></i> Choose Image
                </button>
                <button type="button" id="btn-remove-cover-image-outer" class="${bCover ? 'flex' : 'hidden'} px-3 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 text-xs font-bold rounded-xl items-center gap-1.5 transition-all">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                </button>
              </div>

              <!-- Selected File Metadata Display -->
              <div id="cover-file-info" class="${bCover ? 'flex' : 'hidden'} items-center justify-between px-3 py-2 bg-slate-100 dark:bg-slate-900/60 rounded-xl text-[11px]">
                <div class="flex items-center gap-2 min-w-0 pr-2">
                  <i data-lucide="file-image" class="w-4 h-4 text-brand-500 shrink-0"></i>
                  <span id="cover-file-name" class="font-bold text-slate-700 dark:text-slate-200 truncate">${initialFilename}</span>
                </div>
                <span id="cover-file-size" class="text-[10px] text-slate-400 shrink-0 font-mono"></span>
              </div>
            </div>

            <!-- Hidden File Input & Hidden Cover URL Field -->
            <input type="file" id="edit-post-cover-file" accept="image/jpeg,image/jpg,image/png,image/webp,image/svg+xml,.jpg,.jpeg,.png,.webp,.svg" class="hidden">
            <input type="hidden" id="edit-post-cover" value="${bCover || ''}">

            <!-- URL Fallback -->
            <div class="space-y-1 pt-1">
              <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Or Image URL Path</label>
              <input type="text" id="edit-post-cover-url" value="${bCover || ''}" placeholder="uploads/blog/image.jpg" class="w-full glass-input p-2 rounded-lg text-[10px] font-mono">
            </div>
          </div>

          <!-- SEO Config -->
          <div class="glass-panel p-4 rounded-2xl space-y-3">
            <span class="block font-bold text-[10px] text-slate-400 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-1.5">Search Engine Override</span>
            <div class="space-y-2">
              <div class="space-y-1">
                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">SEO Title</label>
                <input type="text" id="edit-post-seotitle" value="${bSeoTitle}" class="w-full glass-input p-2 rounded-lg" placeholder="Focus search title keywords">
              </div>
              <div class="space-y-1">
                <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider">Meta Description</label>
                <textarea id="edit-post-seodesc" rows="2" class="w-full glass-input p-2 rounded-lg" placeholder="150 character description...">${bMetaDesc}</textarea>
              </div>
            </div>
          </div>

        </div>

      </div>
    `;

    App.openModal(modalTitle, formHtml, () => {
      const editTitle = document.getElementById("edit-post-title").value.trim();
      const editSlug = document.getElementById("edit-post-slug").value.trim();
      const editExcerpt = document.getElementById("edit-post-excerpt").value.trim();
      const editContent = document.getElementById("edit-post-content").value.trim();
      const editCategory = document.getElementById("edit-post-category").value;
      const editAuthor = document.getElementById("edit-post-author").value.trim();
      const editCover = document.getElementById("edit-post-cover").value.trim();
      const editSeoTitle = document.getElementById("edit-post-seotitle").value.trim();
      const editSeoDesc = document.getElementById("edit-post-seodesc").value.trim();
      const editStatus = document.getElementById("edit-post-status").value;
      const editFeatured = document.getElementById("edit-post-featured").checked;
      const editDateInput = document.getElementById("edit-post-date")?.value;
      const editDateObj = editDateInput ? new Date(editDateInput) : new Date();
      const editDateISO = isNaN(editDateObj.getTime()) ? new Date().toISOString() : editDateObj.toISOString();

      if (!editTitle || !editSlug) {
        alert("Title and Slug URL settings are required.");
        return false;
      }

      // Calculate approximate reading time
      const wordCount = editContent.split(/\s+/).length;
      const readingMin = Math.ceil(wordCount / 200) || 1;
      const rTime = `${readingMin} min read`;

      if (blogId) {
        // Update item in store
        Store.updateItem("blogs", blogId, {
          title: editTitle,
          slug: editSlug,
          excerpt: editExcerpt,
          content: editContent,
          category: editCategory,
          author: editAuthor,
          coverImage: editCover,
          seoTitle: editSeoTitle || editTitle,
          metaDescription: editSeoDesc || editExcerpt,
          readingTime: rTime,
          status: editStatus,
          isFeatured: editFeatured,
          dateCreated: editDateISO
        });
        App.showToast("Blog article updated.", "success");
      } else {
        // Insert new item
        Store.insertItem("blogs", {
          title: editTitle,
          slug: editSlug,
          excerpt: editExcerpt,
          content: editContent,
          category: editCategory,
          tags: ["Tech", "IT"],
          author: editAuthor,
          coverImage: editCover,
          seoTitle: editSeoTitle || editTitle,
          metaDescription: editSeoDesc || editExcerpt,
          readingTime: rTime,
          status: editStatus,
          isFeatured: editFeatured,
          dateCreated: editDateISO
        });
        App.showToast("Blog article published to feed.", "success");
      }

      this.refresh();
      return true;
    });

    // Auto-generate Slug & URL preview as title/slug is typed
    const titleInput = document.getElementById("edit-post-title");
    const slugInput = document.getElementById("edit-post-slug");
    const urlPreview = document.getElementById("blog-url-preview");

    const updateUrlPreview = () => {
      if (urlPreview && slugInput) {
        const currentSlug = slugInput.value.trim() || 'where-does-it-come-from';
        urlPreview.textContent = `http://localhost/myitcomapny/blog/${currentSlug}`;
      }
    };

    titleInput?.addEventListener("input", (e) => {
      if (!blogId || !slugInput.value) {
        const val = e.target.value;
        slugInput.value = val.toLowerCase()
                             .trim()
                             .replace(/[^\w\s-]/g, '')
                             .replace(/[\s_]+/g, '-')
                             .replace(/^-+|-+$/g, '');
      }
      updateUrlPreview();
    });

    slugInput?.addEventListener("input", () => {
      updateUrlPreview();
    });

    // Bind Rich Editor Formatting Tools
    document.querySelectorAll(".btn-editor-tool").forEach(tool => {
      tool.addEventListener("click", () => {
        const tag = tool.getAttribute("data-tag");
        const textarea = document.getElementById("edit-post-content");
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selectedText = text.substring(start, end);
        
        let replacement = "";
        if (tag === "ul") {
          replacement = `\n<ul>\n  <li>${selectedText || 'Item 1'}</li>\n  <li>Item 2</li>\n</ul>\n`;
        } else if (tag === "h3") {
          replacement = `<h3>${selectedText || 'Heading'}</h3>`;
        } else {
          replacement = `<${tag}>${selectedText || 'text'}</${tag}>`;
        }

        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        textarea.focus();
        textarea.selectionStart = start;
        textarea.selectionEnd = start + replacement.length;
      });
    });

    // Setup Featured Image Upload Component Logic
    this.setupImageUploadComponent();
  },

  setupImageUploadComponent() {
    const fileInput     = document.getElementById("edit-post-cover-file");
    const hiddenCover   = document.getElementById("edit-post-cover");
    const urlInput      = document.getElementById("edit-post-cover-url");
    const preview       = document.getElementById("edit-post-cover-preview");
    const previewWrap   = document.getElementById("cover-preview-wrapper");
    const placeholderWrap = document.getElementById("cover-placeholder-wrapper");
    const dropZone      = document.getElementById("cover-drop-zone");
    const triggerBtn    = document.getElementById("btn-trigger-file-pick");
    const removeBtnOuter = document.getElementById("btn-remove-cover-image-outer");
    const removeBtnOverlay = document.getElementById("btn-overlay-remove-image");
    const changeBtnOverlay = document.getElementById("btn-overlay-change-image");
    const fileInfo      = document.getElementById("cover-file-info");
    const fileNameEl    = document.getElementById("cover-file-name");
    const fileSizeEl    = document.getElementById("cover-file-size");
    const progressOverlay = document.getElementById("cover-upload-progress");
    const progressBar   = document.getElementById("cover-upload-progress-bar");
    const progressPercent = document.getElementById("cover-upload-percent");

    // Open File Explorer when clicking Choose Image button or dropzone placeholder
    triggerBtn?.addEventListener("click", () => fileInput?.click());
    changeBtnOverlay?.addEventListener("click", () => fileInput?.click());

    // Allow clicking the dropzone placeholder directly to open File Explorer
    placeholderWrap?.addEventListener("click", (e) => {
      e.stopPropagation();
      fileInput?.click();
    });

    // Drag and Drop Event Listeners
    if (dropZone) {
      ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          dropZone.classList.add('border-brand-500', 'bg-brand-500/10', 'scale-[1.01]');
        }, false);
      });

      ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
          e.preventDefault();
          e.stopPropagation();
          dropZone.classList.remove('border-brand-500', 'bg-brand-500/10', 'scale-[1.01]');
        }, false);
      });

      dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt?.files;
        if (files && files.length > 0) {
          this.processSelectedFile(files[0]);
        }
      });
    }

    // Handle File Input Change
    fileInput?.addEventListener("change", (e) => {
      const file = e.target.files?.[0];
      if (file) {
        this.processSelectedFile(file);
      }
    });

    // Remove Image Handlers
    const removeImageAction = () => {
      if (fileInput) fileInput.value = '';
      if (hiddenCover) hiddenCover.value = '';
      if (urlInput) urlInput.value = '';
      if (preview) preview.src = '../uploads/blog/placeholder.svg';
      if (previewWrap) previewWrap.classList.add('hidden');
      if (placeholderWrap) placeholderWrap.classList.remove('hidden');
      if (removeBtnOuter) removeBtnOuter.classList.add('hidden');
      if (removeBtnOuter) removeBtnOuter.classList.remove('flex');
      if (fileInfo) fileInfo.classList.add('hidden');
      if (fileInfo) fileInfo.classList.remove('flex');
      App.showToast("Featured image removed.", "info");
    };

    removeBtnOuter?.addEventListener("click", removeImageAction);
    removeBtnOverlay?.addEventListener("click", removeImageAction);

    // URL Manual Input Event Listener
    urlInput?.addEventListener("input", (e) => {
      const url = e.target.value.trim();
      if (hiddenCover) hiddenCover.value = url;
      if (url) {
        if (preview) preview.src = resolveImageUrl(url);
        if (previewWrap) previewWrap.classList.remove('hidden');
        if (placeholderWrap) placeholderWrap.classList.add('hidden');
        if (removeBtnOuter) { removeBtnOuter.classList.remove('hidden'); removeBtnOuter.classList.add('flex'); }
        if (fileInfo) {
          fileInfo.classList.remove('hidden');
          fileInfo.classList.add('flex');
          if (fileNameEl) fileNameEl.textContent = getFilenameFromPath(url);
          if (fileSizeEl) fileSizeEl.textContent = 'URL path';
        }
      } else {
        removeImageAction();
      }
    });
  },

  // Process and Validate Selected File before Uploading
  processSelectedFile(file) {
    const fileInput     = document.getElementById("edit-post-cover-file");
    const hiddenCover   = document.getElementById("edit-post-cover");
    const urlInput      = document.getElementById("edit-post-cover-url");
    const preview       = document.getElementById("edit-post-cover-preview");
    const previewWrap   = document.getElementById("cover-preview-wrapper");
    const placeholderWrap = document.getElementById("cover-placeholder-wrapper");
    const removeBtnOuter = document.getElementById("btn-remove-cover-image-outer");
    const fileInfo      = document.getElementById("cover-file-info");
    const fileNameEl    = document.getElementById("cover-file-name");
    const fileSizeEl    = document.getElementById("cover-file-size");
    const progressOverlay = document.getElementById("cover-upload-progress");
    const progressBar   = document.getElementById("cover-upload-progress-bar");
    const progressPercent = document.getElementById("cover-upload-percent");

    // 1. Extension & MIME Validation (JPG, JPEG, PNG, WEBP, SVG)
    const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    const fileName = file.name || 'image';
    const ext = fileName.split('.').pop().toLowerCase();

    if (!allowedExts.includes(ext)) {
      App.showToast("Invalid file format. Only JPG, JPEG, PNG, WEBP, and SVG images are allowed.", "error");
      if (fileInput) fileInput.value = '';
      return;
    }

    // 2. File Size Validation (Max 5MB = 5 * 1024 * 1024 bytes)
    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
      App.showToast(`File size (${formatBytes(file.size)}) exceeds the maximum 5 MB limit.`, "error");
      if (fileInput) fileInput.value = '';
      return;
    }

    // 3. Instant Client-Side Image Preview
    const objectUrl = URL.createObjectURL(file);
    if (preview) preview.src = objectUrl;
    if (previewWrap) previewWrap.classList.remove('hidden');
    if (placeholderWrap) placeholderWrap.classList.add('hidden');
    if (removeBtnOuter) { removeBtnOuter.classList.remove('hidden'); removeBtnOuter.classList.add('flex'); }

    // 4. Update File Metadata Display
    if (fileInfo) {
      fileInfo.classList.remove('hidden');
      fileInfo.classList.add('flex');
      if (fileNameEl) fileNameEl.textContent = file.name;
      if (fileSizeEl) fileSizeEl.textContent = formatBytes(file.size);
    }

    // 5. Upload file to PHP backend via XMLHttpRequest with Progress Tracking
    const formData = new FormData();
    formData.append("image", file);

    if (progressOverlay) progressOverlay.classList.remove("hidden");
    if (progressOverlay) progressOverlay.classList.add("flex");
    if (progressBar) progressBar.style.width = "0%";
    if (progressPercent) progressPercent.textContent = "Uploading 0%...";

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "api.php?action=upload_image", true);

    xhr.upload.onprogress = (e) => {
      if (e.lengthComputable) {
        const percent = Math.round((e.loaded / e.total) * 100);
        if (progressBar) progressBar.style.width = percent + "%";
        if (progressPercent) progressPercent.textContent = `Uploading ${percent}%...`;
      }
    };

    xhr.onload = () => {
      if (progressOverlay) progressOverlay.classList.add("hidden");
      if (progressOverlay) progressOverlay.classList.remove("flex");

      if (xhr.status === 200) {
        try {
          const res = JSON.parse(xhr.responseText);
          if (res.error) {
            App.showToast("Upload failed: " + res.error, "error");
            return;
          }
          if (res.url) {
            // Save public URL
            if (hiddenCover) hiddenCover.value = res.url;
            if (urlInput) urlInput.value = res.url;
            if (preview) preview.src = resolveImageUrl(res.url);
            App.showToast("Featured image uploaded successfully!", "success");
          }
        } catch (err) {
          App.showToast("Upload error: Invalid server response.", "error");
        }
      } else {
        App.showToast("Upload failed. Server HTTP status: " + xhr.status, "error");
      }
    };

    xhr.onerror = () => {
      if (progressOverlay) progressOverlay.classList.add("hidden");
      if (progressOverlay) progressOverlay.classList.remove("flex");
      App.showToast("Network error while uploading image.", "error");
    };

    xhr.send(formData);
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
