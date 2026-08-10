// Roles & Permissions View
import { Store } from '../store.js';
import { App } from '../app.js';

let activeRoleName = "Super Admin"; // Default selection

export const RolesView = {
  render(params) {
    const roles = Store.get("roles") || [];
    const activeRole = roles.find(r => r.role === activeRoleName) || roles[0] || { permissions: {} };

    // All available modules & permissions scope
    const modules = [
      { key: "dashboard", name: "Dashboard Hub" },
      { key: "users", name: "User Settings" },
      { key: "blog", name: "Blog Engine" },
      { key: "menu", name: "Menus Builder" },
      { key: "seo", name: "SEO Management" },
      { key: "media", name: "Media Assets" },
      { key: "contact", name: "Contact Messages" },
      { key: "email", name: "SMTP & Automation" },
      { key: "settings", name: "Website Setup" }
    ];

    const actions = [
      { key: "view", name: "View" },
      { key: "create", name: "Create" },
      { key: "update", name: "Update" },
      { key: "delete", name: "Delete" }
    ];

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Access Control & Permissions</h2>
            <p class="text-xs text-slate-400">Configure role matrices and restrict write/delete credentials across staff accounts.</p>
          </div>
          <button id="btn-create-role" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2">
            <i data-lucide="shield-plus" class="w-4 h-4"></i> Create Custom Role
          </button>
        </div>

        <!-- Double Column Splitting Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Left: Roles Card List (4 Cols) -->
          <div class="lg:col-span-4 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Access Roles</span>
            
            <div class="space-y-3">
              ${roles.map(r => {
                const isActive = r.role === activeRoleName;
                const permissionsCount = Object.values(r.permissions).reduce((acc, curr) => acc + curr.length, 0);

                return `
                  <div class="role-selector-card p-5 rounded-2xl border cursor-pointer transition-all flex items-start justify-between ${isActive ? 'bg-white dark:bg-slate-900 border-brand-500 shadow-premium' : 'bg-white/60 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800 hover:border-slate-300'}" data-role-name="${r.role}">
                    <div class="space-y-1">
                      <h4 class="font-bold text-xs ${isActive ? 'text-brand-600 dark:text-brand-400' : 'text-slate-800 dark:text-slate-200'}">${r.role}</h4>
                      <p class="text-[10px] text-slate-400 max-w-[200px] leading-snug">${r.description || 'Custom security policy role.'}</p>
                      <span class="inline-block text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-400 px-2 py-0.5 rounded font-semibold mt-2">${permissionsCount} permission nodes active</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                      <i data-lucide="${isActive ? 'shield-check' : 'shield'}" class="w-4 h-4 ${isActive ? 'text-brand-500' : ''}"></i>
                    </div>
                  </div>
                `;
              }).join("")}
            </div>
          </div>

          <!-- Right: Fine-Grained Permissions Matrix (8 Cols) -->
          <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
              <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Permission Matrix Setup</span>
                <h3 class="font-extrabold text-base text-slate-800 dark:text-slate-200">Policy Schema: ${activeRole.role}</h3>
              </div>
              <span class="text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 px-2 py-0.5 rounded-full font-bold">Auto-Saving Enabled</span>
            </div>

            <!-- Warning for Super Admin -->
            ${activeRole.role === 'Super Admin' ? `
              <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-500 text-xs flex gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0"></i>
                <p class="leading-relaxed"><b>Notice:</b> Super Admin permissions are hard-locked. Security policies require this role to maintain complete read/write access to restore systems in emergency scenarios.</p>
              </div>
            ` : ''}

            <!-- Checkbox Matrix Table -->
            <div class="glass-panel rounded-3xl overflow-hidden shadow-premium">
              <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px] border-b border-slate-100 dark:border-slate-800">
                  <tr>
                    <th class="p-4">Module Name</th>
                    ${actions.map(a => `<th class="p-4 text-center w-24">${a.name}</th>`).join("")}
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                  ${modules.map(m => {
                    const activePerms = activeRole.permissions[m.key] || [];

                    return `
                      <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                        <td class="p-4 font-bold text-slate-700 dark:text-slate-300">
                          <span class="block">${m.name}</span>
                          <span class="block text-[9px] text-slate-400 font-semibold uppercase">Key: ${m.key}</span>
                        </td>
                        ${actions.map(act => {
                          const isChecked = activePerms.includes(act.key);
                          const isDisabled = activeRole.role === 'Super Admin';

                          return `
                            <td class="p-4 text-center">
                              <input type="checkbox" 
                                     class="permission-matrix-chk rounded border-slate-300 text-brand-600 focus:ring-brand-500 w-4 h-4 disabled:opacity-40" 
                                     data-module="${m.key}" 
                                     data-action="${act.key}" 
                                     ${isChecked ? 'checked' : ''} 
                                     ${isDisabled ? 'disabled' : ''}>
                            </td>
                          `;
                        }).join("")}
                      </tr>
                    `;
                  }).join("")}
                </tbody>
              </table>
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
    // Select Active Role Card Click
    document.querySelectorAll(".role-selector-card").forEach(card => {
      card.addEventListener("click", () => {
        activeRoleName = card.getAttribute("data-role-name");
        this.refresh();
      });
    });

    // Toggle Checkbox matrix update
    document.querySelectorAll(".permission-matrix-chk").forEach(chk => {
      chk.addEventListener("change", () => {
        const modKey = chk.getAttribute("data-module");
        const actKey = chk.getAttribute("data-action");
        const checked = chk.checked;

        const roles = Store.get("roles") || [];
        const match = roles.find(r => r.role === activeRoleName);
        if (!match) return;

        if (!match.permissions[modKey]) {
          match.permissions[modKey] = [];
        }

        if (checked) {
          if (!match.permissions[modKey].includes(actKey)) {
            match.permissions[modKey].push(actKey);
          }
        } else {
          match.permissions[modKey] = match.permissions[modKey].filter(a => a !== actKey);
        }

        // Save
        Store.set("roles", roles);
        App.showToast(`Updated permissions for ${activeRoleName}: ${modKey} &bull; ${actKey}`, "success");
      });
    });

    // Create custom role trigger
    document.getElementById("btn-create-role")?.addEventListener("click", () => {
      const name = prompt("Enter new Custom Role Name (e.g. Marketing Manager):");
      if (name && name.trim()) {
        const desc = prompt("Enter role description:");
        const roles = Store.get("roles") || [];
        
        // Basic initial empty permissions
        const newRole = {
          role: name.trim(),
          description: desc || "Custom security group.",
          permissions: {
            dashboard: ["view"],
            users: [],
            blog: ["view"],
            menu: ["view"],
            seo: ["view"],
            media: ["view"],
            contact: ["view"],
            email: [],
            settings: []
          }
        };

        roles.push(newRole);
        Store.set("roles", roles);
        activeRoleName = newRole.role;
        App.showToast("Custom Security Role registry added.", "success");
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
