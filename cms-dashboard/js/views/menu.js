// Menu Builder & Management View
import { Store } from '../store.js';
import { App } from '../app.js';

let activeMenuId = "menu-main"; // Default to Main Navigation
let dragSrcEl = null;

export const MenuView = {
  render(params) {
    const menus = Store.get("menus") || [];
    const activeMenu = menus.find(m => m.id === activeMenuId) || menus[0] || { items: [] };
    
    return `
      <div class="space-y-8">
        
        <!-- View Title & Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Menu Navigation Builder</h2>
            <p class="text-xs text-slate-400">Design nested header layouts, dropdown levels, and responsive mega-menus.</p>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <select id="menu-select-dropdown" class="glass-input text-xs px-3 py-2 rounded-xl flex-1 sm:flex-none">
              ${menus.map(m => `<option value="${m.id}" ${m.id === activeMenuId ? 'selected' : ''}>${m.name} (${m.status})</option>`).join("")}
            </select>
            <button id="btn-create-new-menu" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2 shrink-0">
              <i data-lucide="plus" class="w-4 h-4"></i> Create Menu
            </button>
          </div>
        </div>

        <!-- Live Website Preview Simulation (WOW Factor) -->
        <div class="glass-panel p-6 rounded-3xl space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 pb-3">
            <div class="flex items-center gap-2">
              <i data-lucide="monitor" class="w-4 h-4 text-brand-500"></i>
              <h3 class="font-bold text-xs uppercase tracking-wider text-slate-400">Website Header Menu Preview (Live)</h3>
            </div>
            <span class="text-[9px] bg-indigo-500/10 text-brand-600 font-bold px-2 py-0.5 rounded-full uppercase">Desktop HUD</span>
          </div>

          <!-- Preview Header Bar -->
          <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between shadow-lg relative border border-white/5">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 rounded bg-brand-500 flex items-center justify-center font-bold text-xs text-white">M</div>
              <span class="font-extrabold text-sm tracking-tight">MyITCompany</span>
            </div>
            
            <!-- Dynamic Preview List -->
            <div class="flex items-center gap-6 text-xs font-semibold">
              ${this.renderLiveMenuPreview(activeMenu.items)}
            </div>

            <div class="px-3 py-1.5 rounded-lg bg-brand-600 text-[10px] font-bold cursor-pointer">
              Get Started
            </div>
          </div>
        </div>

        <!-- Double Column Configurator -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Left Column: Navigation Tree Editor (7 Cols) -->
          <div class="lg:col-span-7 space-y-6">
            <div class="glass-panel p-6 rounded-3xl space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-bold text-base">Menu Structure</h3>
                  <p class="text-[11px] text-slate-400">Drag items to sort. Use Indent/Outdent to configure sub-levels.</p>
                </div>
                <button id="btn-add-menu-item" class="px-3 py-1.5 border border-brand-500/20 hover:border-brand-500/40 text-brand-600 dark:text-brand-500 bg-brand-50/10 hover:bg-brand-50/20 text-xs font-semibold rounded-xl flex items-center gap-1.5">
                  <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Add Item
                </button>
              </div>

              <!-- Menu Items Tree -->
              <div id="menu-tree-container" class="space-y-2 py-2">
                ${this.renderMenuTree(activeMenu.items)}
              </div>

              <div class="pt-4 border-t border-slate-100 dark:border-slate-800/50 flex justify-end gap-3">
                <button id="btn-save-menu-tree" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-bold rounded-xl flex items-center gap-2">
                  <i data-lucide="save" class="w-4 h-4"></i> Save Menu Layout
                </button>
              </div>

            </div>
          </div>

          <!-- Right Column: Database Table Registry (5 Cols) -->
          <div class="lg:col-span-5 space-y-6">
            <div class="glass-panel p-6 rounded-3xl space-y-4">
              <div>
                <h3 class="font-bold text-base">All Website Menus</h3>
                <p class="text-[11px] text-slate-400">Switch active statuses or delete menu schemas.</p>
              </div>

              <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-2xl">
                <table class="w-full text-left border-collapse text-xs">
                  <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                    <tr>
                      <th class="p-3">Menu Name</th>
                      <th class="p-3 text-center">Status</th>
                      <th class="p-3 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    ${menus.map(m => `
                      <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <td class="p-3 font-semibold text-slate-800 dark:text-slate-200">
                          <button class="menu-select-btn font-bold hover:underline" data-menu-id="${m.id}">${m.name}</button>
                        </td>
                        <td class="p-3 text-center">
                          <button class="toggle-menu-status-btn px-2 py-0.5 rounded font-bold text-[9px] ${m.status === 'Active' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-200 dark:bg-slate-700 text-slate-500'}" data-menu-id="${m.id}">
                            ${m.status}
                          </button>
                        </td>
                        <td class="p-3 text-right space-x-1">
                          <button class="edit-menu-meta-btn p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" data-menu-id="${m.id}" title="Rename Menu">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5 inline"></i>
                          </button>
                          <button class="delete-menu-btn p-1 text-red-400 hover:text-red-600" data-menu-id="${m.id}">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5 inline"></i>
                          </button>
                        </td>
                      </tr>
                    `).join("")}
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

      </div>
    `;
  },

  // --- HTML Trees & Previews Helpers ---

  renderLiveMenuPreview(items, depth = 0) {
    if (!items || items.length === 0) return `<span class="text-slate-500 text-[10px]">No active items</span>`;
    
    return items.map(item => {
      const hasChildren = item.children && item.children.length > 0;
      const icon = item.icon ? `<i data-lucide="${item.icon}" class="w-3.5 h-3.5 inline mr-1"></i>` : "";
      
      if (item.type === "megamenu") {
        return `
          <div class="group relative py-2 cursor-pointer">
            <span class="hover:text-brand-400 flex items-center gap-1">
              ${icon} ${item.name} <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
            </span>
            <!-- Mega Dropdown Grid -->
            <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[400px] bg-slate-800 border border-slate-700 rounded-xl shadow-2xl p-4 grid grid-cols-2 gap-4 opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all z-50">
              ${hasChildren ? item.children.map(sub => `
                <div>
                  <span class="font-bold text-xs text-brand-400 block border-b border-slate-700 pb-1 mb-1">${sub.name}</span>
                  <a href="#/menu" class="text-[10px] text-slate-300 hover:text-white block py-0.5">Explore page</a>
                </div>
              `).join("") : '<span class="text-[10px] text-slate-500">Configure subitems</span>'}
            </div>
          </div>
        `;
      }

      if (hasChildren) {
        return `
          <div class="group relative py-2 cursor-pointer">
            <span class="hover:text-brand-400 flex items-center gap-1">
              ${icon} ${item.name} <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
            </span>
            <!-- Dropdown Standard List -->
            <div class="absolute top-full left-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-2xl py-2 opacity-0 scale-95 pointer-events-none group-hover:opacity-100 group-hover:scale-100 group-hover:pointer-events-auto transition-all z-50">
              ${item.children.map(sub => `
                <a href="#/menu" class="block px-4 py-1.5 text-[11px] text-slate-300 hover:text-white hover:bg-slate-700">
                  ${sub.icon ? `<i data-lucide="${sub.icon}" class="w-3 h-3 inline mr-1"></i>` : ""} ${sub.name}
                </a>
              `).join("")}
            </div>
          </div>
        `;
      }

      return `<a href="#/menu" class="hover:text-brand-400 py-2">${icon}${item.name}</a>`;
    }).join("");
  },

  renderMenuTree(items, depth = 0) {
    if (!items || items.length === 0) return `
      <div class="p-6 text-center text-xs text-slate-400 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
        No links in this list. Click Add Item to begin structure building.
      </div>
    `;

    let html = "";
    items.forEach((item, index) => {
      const icon = item.icon || "link";
      const target = item.target || "_self";
      const hasChildren = item.children && item.children.length > 0;
      
      html += `
        <div class="menu-item-node group/node" data-id="${item.id}" data-depth="${depth}" draggable="true" style="margin-left: ${depth * 28}px">
          <div class="flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-brand-500/40 dark:hover:border-brand-500/40 transition-all select-none">
            
            <div class="flex items-center gap-3">
              <!-- Drag Handle Icon -->
              <div class="drag-handle text-slate-300 dark:text-slate-700 hover:text-slate-600 dark:hover:text-slate-300 cursor-grab active:cursor-grabbing p-1 rounded-lg">
                <i data-lucide="grip-vertical" class="w-4 h-4"></i>
              </div>
              
              <!-- Icon Indicator -->
              <div class="w-7 h-7 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 shrink-0">
                <i data-lucide="${icon}" class="w-3.5 h-3.5"></i>
              </div>

              <div>
                <span class="font-bold text-xs block text-slate-700 dark:text-slate-200">${item.name}</span>
                <span class="text-[10px] text-slate-400 block font-semibold truncate max-w-[150px] sm:max-w-[250px]">${item.url} &bull; ${item.type}</span>
              </div>
            </div>

            <!-- Actions panel -->
            <div class="flex items-center gap-2 opacity-100 lg:opacity-0 group-hover/node:opacity-100 transition-opacity">
              
              <!-- Indent Buttons -->
              <button class="btn-outdent-item p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600" title="Outdent (Out)" data-id="${item.id}">
                <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
              </button>
              <button class="btn-indent-item p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600" title="Indent (Sub-level)" data-id="${item.id}">
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
              </button>

              <!-- Node edit/delete -->
              <button class="btn-edit-tree-node p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/20 text-indigo-500" data-id="${item.id}" title="Edit Link Settings">
                <i data-lucide="settings" class="w-3.5 h-3.5"></i>
              </button>
              <button class="btn-delete-tree-node p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500" data-id="${item.id}" title="Remove">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
              </button>
            </div>
          </div>
        </div>
      `;

      if (hasChildren) {
        html += this.renderMenuTree(item.children, depth + 1);
      }
    });

    return html;
  },

  // --- Initializer Event Listeners ---

  init() {
    this.bindEvents();
    this.setupDragAndDrop();
  },

  bindEvents() {
    // Menu Dropdown Switcher
    const select = document.getElementById("menu-select-dropdown");
    select?.addEventListener("change", (e) => {
      activeMenuId = e.target.value;
      this.refresh();
    });

    // Database Switch Menu Click
    document.querySelectorAll(".menu-select-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        activeMenuId = btn.getAttribute("data-menu-id");
        this.refresh();
      });
    });

    // Toggle Menu Status Active/Inactive
    document.querySelectorAll(".toggle-menu-status-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-menu-id");
        const menus = Store.get("menus");
        const match = menus.find(m => m.id === id);
        if (match) {
          match.status = match.status === "Active" ? "Inactive" : "Active";
          Store.set("menus", menus);
          App.showToast(`Menu status updated to ${match.status}`, "success");
          this.refresh();
        }
      });
    });

    // Rename Menu Meta
    document.querySelectorAll(".edit-menu-meta-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-menu-id");
        const menus = Store.get("menus");
        const match = menus.find(m => m.id === id);
        if (!match) return;

        const newName = prompt("Enter new Menu Name:", match.name);
        if (newName && newName.trim()) {
          match.name = newName.trim();
          Store.set("menus", menus);
          App.showToast("Menu renamed successfully", "success");
          this.refresh();
        }
      });
    });

    // Delete Menu
    document.querySelectorAll(".delete-menu-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-menu-id");
        const menus = Store.get("menus");
        if (menus.length <= 1) {
          App.showToast("Cannot delete the last menu registry.", "warning");
          return;
        }
        if (confirm("Are you sure you want to delete this menu hierarchy?")) {
          const filtered = menus.filter(m => m.id !== id);
          Store.set("menus", filtered);
          activeMenuId = filtered[0].id;
          App.showToast("Menu template deleted.", "info");
          this.refresh();
        }
      });
    });

    // Create New Menu Registry
    document.getElementById("btn-create-new-menu")?.addEventListener("click", () => {
      const name = prompt("Enter new menu configuration name (e.g. Sidebar Links):");
      if (name && name.trim()) {
        const newMenu = {
          id: "menu-" + Math.random().toString(36).substr(2, 5),
          name: name.trim(),
          status: "Inactive",
          items: []
        };
        const menus = Store.get("menus");
        menus.push(newMenu);
        Store.set("menus", menus);
        activeMenuId = newMenu.id;
        App.showToast("Menu registry created.", "success");
        this.refresh();
      }
    });

    // Save tree back to LocalStorage
    document.getElementById("btn-save-menu-tree")?.addEventListener("click", () => {
      // Tree state saved automatically.
      App.showToast("Navigation hierarchy saved to database schemas.", "success");
    });

    // Add Menu Item Click (Modal editor)
    document.getElementById("btn-add-menu-item")?.addEventListener("click", () => {
      this.openItemModal();
    });

    // Delete Node item from tree
    document.querySelectorAll(".btn-delete-tree-node").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (confirm("Are you sure you want to delete this menu node? (Sub-nodes will be orphaned/deleted too).")) {
          this.deleteNode(id);
        }
      });
    });

    // Edit Node item from tree
    document.querySelectorAll(".btn-edit-tree-node").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        this.openItemModal(id);
      });
    });

    // Indent / Outdent Event Triggers
    document.querySelectorAll(".btn-indent-item").forEach(btn => {
      btn.addEventListener("click", () => {
        this.shiftNodeDepth(btn.getAttribute("data-id"), 1);
      });
    });
    document.querySelectorAll(".btn-outdent-item").forEach(btn => {
      btn.addEventListener("click", () => {
        this.shiftNodeDepth(btn.getAttribute("data-id"), -1);
      });
    });
  },

  // --- Tree Nodes Actions ---

  // Outdent or Indent levels
  shiftNodeDepth(itemId, shift) {
    const menus = Store.get("menus");
    const activeMenu = menus.find(m => m.id === activeMenuId);
    if (!activeMenu) return;

    // Helper flat array walker
    const list = this.flattenItems(activeMenu.items);
    const index = list.findIndex(item => item.id === itemId);
    if (index === -1) return;

    const node = list[index];
    
    // We adjust placement in flat structure
    if (shift > 0 && index > 0) {
      // Indent: Make child of previous item if parent depth matches
      const prev = list[index - 1];
      this.removeNodeFromTree(activeMenu.items, itemId);
      
      // Find prev and push
      this.addNodeAsChild(activeMenu.items, prev.id, node);
    } else if (shift < 0) {
      // Outdent: Move up a level in parent
      const parentNode = this.findParentNode(activeMenu.items, itemId);
      if (parentNode) {
        const grandParentNode = this.findParentNode(activeMenu.items, parentNode.id);
        this.removeNodeFromTree(activeMenu.items, itemId);
        
        if (grandParentNode) {
          grandParentNode.children.push(node);
        } else {
          activeMenu.items.push(node);
        }
      }
    }

    Store.set("menus", menus);
    this.refresh();
  },

  // Flatten nested items for array manipulation
  flattenItems(items, res = []) {
    items.forEach(item => {
      res.push(item);
      if (item.children && item.children.length > 0) {
        this.flattenItems(item.children, res);
      }
    });
    return res;
  },

  removeNodeFromTree(items, id) {
    const index = items.findIndex(item => item.id === id);
    if (index !== -1) {
      items.splice(index, 1);
      return true;
    }
    for (let item of items) {
      if (item.children && item.children.length > 0) {
        const found = this.removeNodeFromTree(item.children, id);
        if (found) return true;
      }
    }
    return false;
  },

  addNodeAsChild(items, parentId, childNode) {
    for (let item of items) {
      if (item.id === parentId) {
        if (!item.children) item.children = [];
        item.children.push(childNode);
        return true;
      }
      if (item.children && item.children.length > 0) {
        const found = this.addNodeAsChild(item.children, parentId, childNode);
        if (found) return true;
      }
    }
    return false;
  },

  findParentNode(items, childId, parent = null) {
    for (let item of items) {
      if (item.id === childId) return parent;
      if (item.children && item.children.length > 0) {
        const res = this.findParentNode(item.children, childId, item);
        if (res) return res;
      }
    }
    return null;
  },

  findNodeById(items, id) {
    for (let item of items) {
      if (item.id === id) return item;
      if (item.children && item.children.length > 0) {
        const res = this.findNodeById(item.children, id);
        if (res) return res;
      }
    }
    return null;
  },

  deleteNode(id) {
    const menus = Store.get("menus");
    const activeMenu = menus.find(m => m.id === activeMenuId);
    if (activeMenu) {
      this.removeNodeFromTree(activeMenu.items, id);
      Store.set("menus", menus);
      App.showToast("Menu node and its children deleted.", "info");
      this.refresh();
    }
  },

  // Open Modal Form for Adding/Editing Item
  openItemModal(itemId = null) {
    const menus = Store.get("menus");
    const activeMenu = menus.find(m => m.id === activeMenuId);
    if (!activeMenu) return;

    let title = "Add Menu Item";
    let name = "";
    let url = "";
    let type = "internal";
    let target = "_self";
    let icon = "link";

    if (itemId) {
      title = "Edit Menu Item settings";
      const node = this.findNodeById(activeMenu.items, itemId);
      if (node) {
        name = node.name;
        url = node.url;
        type = node.type;
        target = node.target || "_self";
        icon = node.icon || "link";
      }
    }

    const modalHtml = `
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
        <div class="space-y-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Link Title</label>
          <input type="text" id="modal-menu-name" value="${name}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. Cloud Security">
        </div>
        <div class="space-y-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Lucide Icon String</label>
          <input type="text" id="modal-menu-icon" value="${icon}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. home, shield, layers">
        </div>
        <div class="space-y-2 md:col-span-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">URL Link / Slug path</label>
          <input type="text" id="modal-menu-url" value="${url}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. /services/security or https://google.com">
          <div class="flex items-center gap-2 mt-1">
            <span class="text-[10px] text-slate-400">Quick page presets:</span>
            <button class="btn-preset-url px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[9px] hover:bg-brand-50 hover:text-brand-600" data-val="/services">Services</button>
            <button class="btn-preset-url px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[9px] hover:bg-brand-50 hover:text-brand-600" data-val="/blog">Blog</button>
            <button class="btn-preset-url px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[9px] hover:bg-brand-50 hover:text-brand-600" data-val="/contact">Contact</button>
          </div>
        </div>
        <div class="space-y-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Target Type</label>
          <select id="modal-menu-target" class="w-full glass-input p-3 rounded-xl">
            <option value="_self" ${target === '_self' ? 'selected' : ''}>Same Window (_self)</option>
            <option value="_blank" ${target === '_blank' ? 'selected' : ''}>New Tab (_blank)</option>
          </select>
        </div>
        <div class="space-y-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Display Type</label>
          <select id="modal-menu-type" class="w-full glass-input p-3 rounded-xl">
            <option value="internal" ${type === 'internal' ? 'selected' : ''}>Standard Link</option>
            <option value="dropdown" ${type === 'dropdown' ? 'selected' : ''}>Standard Dropdown Node</option>
            <option value="megamenu" ${type === 'megamenu' ? 'selected' : ''}>Mega Menu (Gridded Category)</option>
          </select>
        </div>
      </div>
    `;

    App.openModal(title, modalHtml, () => {
      const nameVal = document.getElementById("modal-menu-name").value.trim();
      const urlVal = document.getElementById("modal-menu-url").value.trim();
      const iconVal = document.getElementById("modal-menu-icon").value.trim();
      const targetVal = document.getElementById("modal-menu-target").value;
      const typeVal = document.getElementById("modal-menu-type").value;

      if (!nameVal || !urlVal) {
        alert("Title and URL cannot be blank.");
        return false;
      }

      if (itemId) {
        // Edit Mode
        const node = this.findNodeById(activeMenu.items, itemId);
        if (node) {
          node.name = nameVal;
          node.url = urlVal;
          node.icon = iconVal;
          node.target = targetVal;
          node.type = typeVal;
        }
        App.showToast("Menu item updated.", "success");
      } else {
        // Add Mode
        const newItem = {
          id: "m-" + Math.random().toString(36).substr(2, 5),
          name: nameVal,
          url: urlVal,
          icon: iconVal,
          target: targetVal,
          type: typeVal,
          children: []
        };
        activeMenu.items.push(newItem);
        App.showToast("Menu item added to tree.", "success");
      }

      Store.set("menus", menus);
      this.refresh();
      return true;
    });

    // Handle quick preset buttons inside modal
    document.querySelectorAll(".btn-preset-url").forEach(btn => {
      btn.addEventListener("click", () => {
        const input = document.getElementById("modal-menu-url");
        if (input) input.value = btn.getAttribute("data-val");
      });
    });
  },

  // --- HTML5 Drag and Drop ---
  setupDragAndDrop() {
    const nodes = document.querySelectorAll(".menu-item-node");
    nodes.forEach(node => {
      node.addEventListener("dragstart", (e) => {
        dragSrcEl = node;
        e.dataTransfer.effectAllowed = "move";
        node.classList.add("opacity-50");
      });

      node.addEventListener("dragend", () => {
        node.classList.remove("opacity-50");
        document.querySelectorAll(".menu-item-node").forEach(n => n.classList.remove("drag-over"));
      });

      node.addEventListener("dragover", (e) => {
        e.preventDefault();
        node.classList.add("drag-over");
      });

      node.addEventListener("dragleave", () => {
        node.classList.remove("drag-over");
      });

      node.addEventListener("drop", (e) => {
        e.stopPropagation();
        e.preventDefault();
        
        const targetId = node.getAttribute("data-id");
        const sourceId = dragSrcEl?.getAttribute("data-id");
        
        if (sourceId && targetId && sourceId !== targetId) {
          this.reorderNodes(sourceId, targetId);
        }
      });
    });
  },

  reorderNodes(sourceId, targetId) {
    const menus = Store.get("menus");
    const activeMenu = menus.find(m => m.id === activeMenuId);
    if (!activeMenu) return;

    const sourceNode = this.findNodeById(activeMenu.items, sourceId);
    if (!sourceNode) return;

    // Remove source node
    this.removeNodeFromTree(activeMenu.items, sourceId);

    // Reinsert before or after target node in flat array structure
    const insertIntoList = (items) => {
      const idx = items.findIndex(n => n.id === targetId);
      if (idx !== -1) {
        // Insert right after target
        items.splice(idx + 1, 0, sourceNode);
        return true;
      }
      for (let item of items) {
        if (item.children && item.children.length > 0) {
          const inserted = insertIntoList(item.children);
          if (inserted) return true;
        }
      }
      return false;
    };

    insertIntoList(activeMenu.items);
    Store.set("menus", menus);
    App.showToast("Tree reordered.", "success");
    this.refresh();
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
