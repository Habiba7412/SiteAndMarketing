// System Management View
import { Store } from '../store.js';
import { App } from '../app.js';

let activeSubTab = "security"; // security, logs, backup, config

export const SystemView = {
  render(params) {
    const logs = Store.get("activityLogs") || [];
    const backups = Store.get("backups") || [];
    
    if (params && params.tab) {
      activeSubTab = params.tab;
    }

    return `
      <div class="space-y-8 animate-fade-in text-left">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">System Administration Console</h2>
            <p class="text-xs text-slate-400">Perform database schema backups, audit system activity logs, restrict login IPs, and verify SSL tokens.</p>
          </div>
          <button id="btn-save-system-policies" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Apply System Policies
          </button>
        </div>

        <!-- System tabs splitting layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Tab Navigation Left (3 columns) -->
          <div class="lg:col-span-3 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Admin Services</span>
            
            <div class="space-y-1.5 text-xs font-semibold">
              <button class="sys-tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all ${activeSubTab === 'security' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500'}" data-tab="security">
                <i data-lucide="shield-alert" class="w-4 h-4"></i> Security Controls
              </button>
              <button class="sys-tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all ${activeSubTab === 'logs' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500'}" data-tab="logs">
                <i data-lucide="file-spreadsheets" class="w-4 h-4"></i> Audit Activity Logs
              </button>
              <button class="sys-tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all ${activeSubTab === 'backup' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500'}" data-tab="backup">
                <i data-lucide="database" class="w-4 h-4"></i> Backup & Restore
              </button>
              <button class="sys-tab-btn w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl transition-all ${activeSubTab === 'config' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500'}" data-tab="config">
                <i data-lucide="sliders" class="w-4 h-4"></i> System Settings
              </button>
            </div>
          </div>

          <!-- Configuration Panel Center-Right (9 columns) -->
          <div class="lg:col-span-9 space-y-6">
            
            <!-- Tab 1: Security Controls -->
            <div class="sys-tab-content space-y-4 ${activeSubTab === 'security' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <h3 class="font-bold text-sm">Staff Authentication Security</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  
                  <!-- 2FA settings -->
                  <div class="flex items-center justify-between p-4 border border-slate-100 dark:border-slate-800/80 rounded-2xl">
                    <div class="space-y-0.5">
                      <span class="font-bold text-xs block">Enforce 2-Factor Authentication</span>
                      <span class="text-[10px] text-slate-400">Force Google Authenticator verification.</span>
                    </div>
                    <input type="checkbox" id="sec-2fa-toggle" checked class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                  </div>

                  <!-- Session Timeouts -->
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Session Idle Timeout (Minutes)</label>
                    <select id="sec-timeout" class="w-full glass-input p-3.5 rounded-xl">
                      <option value="15">15 Minutes</option>
                      <option value="30" selected>30 Minutes</option>
                      <option value="60">60 Minutes</option>
                      <option value="120">2 Hours</option>
                    </select>
                  </div>

                  <!-- IP whitelist -->
                  <div class="space-y-1 md:col-span-2">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Administrator IP Access Whitelist (Comma Separated)</label>
                    <input type="text" id="sec-ip-whitelist" value="127.0.0.1, 192.168.1.*, 10.0.0.1" class="w-full glass-input p-3 rounded-xl font-mono text-xs" placeholder="e.g. 192.168.1.1">
                    <span class="text-[10px] text-slate-400 block mt-1">Leaves empty to allow logins from any network segment node.</span>
                  </div>

                </div>
              </div>
            </div>

            <!-- Tab 2: Audit Logs -->
            <div class="sys-tab-content space-y-4 ${activeSubTab === 'logs' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="font-bold text-sm">System Operations Audit Trail</h3>
                    <p class="text-[10px] text-slate-400">Complete historical index of DB changes, logins, and configurations.</p>
                  </div>
                  <button id="btn-clear-activity-logs" class="text-xs text-red-500 font-semibold hover:underline">Flush Audit Logs</button>
                </div>

                <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-2xl">
                  <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                      <tr>
                        <th class="p-3">Staff Operator</th>
                        <th class="p-3">Operations Action</th>
                        <th class="p-3">Module</th>
                        <th class="p-3">IP Node</th>
                        <th class="p-3 text-right">Timestamp</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                      ${logs.length === 0 ? `
                        <tr>
                          <td colspan="5" class="p-6 text-center text-slate-400 font-semibold">No operations logs registered.</td>
                        </tr>
                      ` : logs.map(l => `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                          <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">${l.user}</td>
                          <td class="p-3 text-slate-500">${l.action}</td>
                          <td class="p-3"><span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px]">${l.module}</span></td>
                          <td class="p-3 font-mono text-slate-400">${l.ipAddress}</td>
                          <td class="p-3 text-right text-slate-400 font-semibold">${new Date(l.date).toLocaleString()}</td>
                        </tr>
                      `).join("")}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tab 3: Backup & Restore -->
            <div class="sys-tab-content space-y-4 ${activeSubTab === 'backup' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                  <div>
                    <h3 class="font-bold text-sm">Database Backups Registry</h3>
                    <p class="text-[10px] text-slate-400">Download SQL schemas or rollback website state instantly.</p>
                  </div>
                  
                  <button id="btn-create-backup" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2">
                    <span id="backup-spinner" class="hidden shrink-0"><i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i></span>
                    <i data-lucide="database-backup" class="w-4 h-4" id="backup-db-icon"></i> <span id="backup-btn-text">Generate DB Backup</span>
                  </button>
                </div>

                <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-2xl">
                  <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                      <tr>
                        <th class="p-3">Snapshot Filename</th>
                        <th class="p-3">File Size</th>
                        <th class="p-3">Date Compiled</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Actions</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                      ${backups.length === 0 ? `
                        <tr>
                          <td colspan="5" class="p-6 text-center text-slate-400 font-semibold">No backup snapshots compiled.</td>
                        </tr>
                      ` : backups.map(b => `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                          <td class="p-3 font-semibold text-slate-700 dark:text-slate-200 font-mono text-[10px]">${b.filename}</td>
                          <td class="p-3 text-slate-500 font-semibold">${b.size}</td>
                          <td class="p-3 text-slate-400 font-semibold">${new Date(b.dateCreated).toLocaleString()}</td>
                          <td class="p-3"><span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 font-bold text-[9px] uppercase">${b.status}</span></td>
                          <td class="p-3 text-right space-x-1.5">
                            <button class="restore-backup-btn text-brand-600 dark:text-brand-500 hover:underline font-bold" data-id="${b.id}">Restore</button>
                            <button class="delete-backup-btn text-red-500 hover:underline font-bold" data-id="${b.id}">Delete</button>
                          </td>
                        </tr>
                      `).join("")}
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Tab 4: System Config -->
            <div class="sys-tab-content space-y-4 ${activeSubTab === 'config' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <h3 class="font-bold text-sm">Global System Settings</h3>
                
                <div class="space-y-4">
                  <!-- Maintenance mode -->
                  <div class="flex items-center justify-between p-4 border border-slate-100 dark:border-slate-800/80 rounded-2xl bg-red-500/5">
                    <div class="space-y-0.5">
                      <span class="font-bold text-xs block text-red-500">Maintenance Mode (Offline Status)</span>
                      <span class="text-[10px] text-slate-400">Instruct clients that server is performing offline audits.</span>
                    </div>
                    <input type="checkbox" id="config-maintenance" class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                  </div>

                  <!-- Caches -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 border border-slate-100 dark:border-slate-800/80 rounded-2xl flex items-center justify-between">
                      <div class="space-y-0.5">
                        <span class="font-bold text-xs block">Flush CDN Indexes</span>
                        <span class="text-[10px] text-slate-400">Prune CDN cache networks.</span>
                      </div>
                      <button id="btn-flush-cdn" class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 font-bold rounded-lg">Prune</button>
                    </div>

                    <div class="p-4 border border-slate-100 dark:border-slate-800/80 rounded-2xl flex items-center justify-between">
                      <div class="space-y-0.5">
                        <span class="font-bold text-xs block">Reset Core Parameters</span>
                        <span class="text-[10px] text-slate-400">Revert core engine settings to seeds.</span>
                      </div>
                      <button id="btn-flush-seeds" class="px-3 py-1.5 bg-red-500/10 text-red-500 hover:bg-red-500/20 font-bold rounded-lg">Factory Reset</button>
                    </div>
                  </div>
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
      window.history.replaceState(null, null, "#/system");
    }
  },

  bindEvents() {
    // Save Settings
    document.getElementById("btn-save-system-policies")?.addEventListener("click", () => {
      App.showToast("System configurations applied successfully.", "success");
    });

    // Subtabs Switch Listener
    document.querySelectorAll(".sys-tab-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        activeSubTab = btn.getAttribute("data-tab");
        this.refresh();
      });
    });

    // Flush Audit logs
    document.getElementById("btn-clear-activity-logs")?.addEventListener("click", () => {
      if (confirm("Permanently wipe all operational audit timeline logs?")) {
        Store.set("activityLogs", []);
        App.showToast("Activity logs database cleared.", "info");
        this.refresh();
      }
    });

    // Generate DB backup schema (Animated WOW)
    const backupBtn = document.getElementById("btn-create-backup");
    backupBtn?.addEventListener("click", () => {
      const spinner = document.getElementById("backup-spinner");
      const icon = document.getElementById("backup-db-icon");
      const text = document.getElementById("backup-btn-text");

      // Set Loading
      if (spinner) spinner.classList.remove("hidden");
      if (icon) icon.classList.add("hidden");
      if (text) text.textContent = "Compiling Database schemas...";
      if (backupBtn) backupBtn.disabled = true;

      setTimeout(() => {
        // Pushes backup file to store database
        const backups = Store.get("backups") || [];
        const dateStr = new Date().toISOString().split('T')[0].replace(/-/g, '');
        const filename = `myitcompany_backup_${dateStr}_${Math.floor(Math.random() * 900 + 100)}.sql`;
        
        backups.unshift({
          id: "bk-" + Date.now(),
          filename: filename,
          size: (Math.random() * 0.5 + 4.5).toFixed(1) + " MB",
          dateCreated: new Date().toISOString(),
          status: "Completed"
        });

        Store.set("backups", backups);
        Store.logActivity(`Compiled database backup snapshot: ${filename}`, "System Control");
        
        App.showToast("Backup snapshot generated and synced in AWS storage.", "success");
        this.refresh();
      }, 1500);
    });

    // Restore backup
    document.querySelectorAll(".restore-backup-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (confirm("Restore snapshot? Web application states will be rolled back. Active sessions might refresh.")) {
          App.showToast("Rollback parameters applied. System reboot completed.", "success");
        }
      });
    });

    // Delete backup
    document.querySelectorAll(".delete-backup-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (confirm("Remove backup snapshot permanently?")) {
          Store.deleteItem("backups", id);
          App.showToast("Backup snapshot removed.", "info");
          this.refresh();
        }
      });
    });

    // Maintenance Toggle change
    const maintenanceChk = document.getElementById("config-maintenance");
    maintenanceChk?.addEventListener("change", (e) => {
      const isChecked = e.target.checked;
      App.showToast(
        isChecked ? "Maintenance mode activated. Website is offline." : "Maintenance mode deactivated. Website is online.", 
        isChecked ? "warning" : "success"
      );
    });

    // Flush CDN
    document.getElementById("btn-flush-cdn")?.addEventListener("click", () => {
      App.showToast("Edge Node invalidations broadcast completed. CDN cache primed.", "success");
    });

    // Factory Reset
    document.getElementById("btn-flush-seeds")?.addEventListener("click", () => {
      if (confirm("Restore website database to primary factory seeds? Custom updates will be lost.")) {
        Store.reset();
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
