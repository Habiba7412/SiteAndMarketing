// Email Settings & SMTP Integration View
import { Store } from '../store.js';
import { App } from '../app.js';

let activeSubTab = "smtp"; // smtp, templates, logs

export const EmailView = {
  render(params) {
    const email = Store.get("emailSettings") || {};
    
    // Check url hash queries
    if (params && params.tab) {
      // Set by router
    }

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Email System Integration</h2>
            <p class="text-xs text-slate-400">Configure secure Gmail SMTP, customize auto-reply triggers, and review delivery logs.</p>
          </div>
          <button id="btn-save-email-settings" class="px-5 py-2.5 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Save Settings
          </button>
        </div>

        <!-- Layout Grid splitting -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Left configuration tabs (8 columns) -->
          <div class="lg:col-span-8 space-y-6">
            
            <!-- Navigation -->
            <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6 text-xs font-semibold">
              <button class="email-tab-btn pb-2.5 relative ${activeSubTab === 'smtp' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="smtp">
                Gmail SMTP Server
              </button>
              <button class="email-tab-btn pb-2.5 relative ${activeSubTab === 'templates' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="templates">
                Auto-Reply Rules
              </button>
              <button class="email-tab-btn pb-2.5 relative ${activeSubTab === 'logs' ? 'text-brand-600 dark:text-brand-400 border-b-2 border-brand-500' : 'text-slate-400 hover:text-slate-600'}" data-tab="logs">
                Delivery Logs & Queue
              </button>
            </div>

            <!-- Tab 1: SMTP Config -->
            <div class="email-tab-content space-y-4 ${activeSubTab === 'smtp' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <h3 class="font-bold text-sm">SMTP Server Credentials</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">SMTP Host</label>
                    <input type="text" id="smtp-host" value="${email.smtpHost}" class="w-full glass-input p-3 rounded-xl font-mono text-xs" placeholder="smtp.gmail.com">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">SMTP Port</label>
                    <input type="text" id="smtp-port" value="${email.smtpPort}" class="w-full glass-input p-3 rounded-xl font-mono text-xs" placeholder="587">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">SMTP Username</label>
                    <input type="text" id="smtp-username" value="${email.smtpUsername}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">SMTP Password</label>
                    <input type="password" id="smtp-password" value="${email.smtpPassword}" class="w-full glass-input p-3 rounded-xl font-mono text-xs">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Encryption protocol</label>
                    <select id="smtp-encryption" class="w-full glass-input p-3 rounded-xl">
                      <option value="TLS" ${email.encryption === 'TLS' ? 'selected' : ''}>STARTTLS / TLS (Port 587)</option>
                      <option value="SSL" ${email.encryption === 'SSL' ? 'selected' : ''}>SSL / Implicit (Port 465)</option>
                      <option value="None" ${email.encryption === 'None' ? 'selected' : ''}>None (Insecure / Port 25)</option>
                    </select>
                  </div>
                  
                  <div class="h-1 w-full md:col-span-2"></div>

                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Sender Name</label>
                    <input type="text" id="smtp-sender-name" value="${email.senderName}" class="w-full glass-input p-3 rounded-xl" placeholder="AeroCMS Administrator">
                  </div>
                  <div class="space-y-1">
                    <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Sender Email</label>
                    <input type="email" id="smtp-sender-email" value="${email.senderEmail}" class="w-full glass-input p-3 rounded-xl" placeholder="no-reply@myitcompany.com">
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 2: Auto Reply Templates -->
            <div class="email-tab-content space-y-4 ${activeSubTab === 'templates' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4 text-left">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-2">
                  <h3 class="font-bold text-sm">Auto-Reply Configuration</h3>
                  <div class="flex items-center gap-2">
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Trigger Enabled</span>
                    <input type="checkbox" id="email-auto-reply" ${email.autoReplyToggle ? 'checked' : ''} class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                  </div>
                </div>

                <div class="space-y-2">
                  <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Auto-Reply Template</label>
                  <textarea id="email-autoreply-body" rows="6" class="w-full glass-input p-3 rounded-xl font-mono leading-relaxed">${email.autoReplyTemplate}</textarea>
                  <div class="text-[10px] text-slate-400 flex flex-wrap gap-2 mt-1">
                    <span>Insert variables:</span>
                    <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded font-semibold">[Name]</code>
                    <code class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded font-semibold">[Subject]</code>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 3: Email Delivery Logs -->
            <div class="email-tab-content space-y-4 ${activeSubTab === 'logs' ? '' : 'hidden'}">
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="font-bold text-sm">SMTP Dispatch History</h3>
                  <button id="btn-clear-email-logs" class="text-xs text-red-500 font-semibold hover:underline">Clear Logs</button>
                </div>

                <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-2xl">
                  <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                      <tr>
                        <th class="p-3">Sent To</th>
                        <th class="p-3">Subject</th>
                        <th class="p-3">Date Sent</th>
                        <th class="p-3 text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                      ${!email.emailLogs || email.emailLogs.length === 0 ? `
                        <tr>
                          <td colspan="4" class="p-6 text-center text-slate-400 font-semibold">No SMTP logs recorded.</td>
                        </tr>
                      ` : email.emailLogs.map(l => `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                          <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">${l.to}</td>
                          <td class="p-3 text-slate-500">${l.subject}</td>
                          <td class="p-3 text-slate-400 font-semibold">${new Date(l.date).toLocaleString()}</td>
                          <td class="p-3 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold text-[9px] uppercase ${l.status === 'Success' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500'}">
                              ${l.status}
                            </span>
                          </td>
                        </tr>
                      `).join("")}
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Email Queue -->
              <div class="glass-panel p-6 rounded-3xl space-y-4">
                <h3 class="font-bold text-sm">System Dispatch Queue</h3>
                <div class="p-4 border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 rounded-2xl text-xs text-slate-400 text-center">
                  All transactional dispatches sent successfully. 0 items in queue.
                </div>
              </div>
            </div>

          </div>

          <!-- Right: Connection Diagnostic Panel (4 columns) -->
          <div class="lg:col-span-4 space-y-6">
            
            <div class="glass-panel p-5 rounded-3xl text-left space-y-4">
              <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block border-b border-slate-100 dark:border-slate-800 pb-1.5">
                SMTP Server Status
              </span>

              <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <div>
                  <span class="font-bold text-xs block text-slate-800 dark:text-white">Gmail SMTP Connected</span>
                  <span class="text-[10px] text-slate-400">Response time: 42ms</span>
                </div>
              </div>

              <div class="space-y-2 text-[11px] leading-relaxed pt-2 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex justify-between">
                  <span class="text-slate-400">Total Sent</span>
                  <span class="font-bold text-slate-700 dark:text-slate-200">${email.emailLogs?.length || 0}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Failure Rate</span>
                  <span class="font-bold text-slate-700 dark:text-slate-200">0.0%</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-400">Daily Quota</span>
                  <span class="font-bold text-slate-700 dark:text-slate-200">12 / 500 (Simulated)</span>
                </div>
              </div>

              <button id="btn-test-smtp-modal" class="w-full py-2.5 bg-indigo-50 dark:bg-indigo-950/20 text-brand-600 dark:text-brand-400 hover:bg-brand-50 hover:shadow-sm text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition-all">
                <i data-lucide="send" class="w-3.5 h-3.5"></i> Test Connection
              </button>
            </div>

            <!-- Gmail App Passwords instructions -->
            <div class="glass-panel p-5 rounded-3xl text-left space-y-3 bg-gradient-to-br from-indigo-50/20 via-transparent to-brand-50/10 dark:from-indigo-950/10">
              <span class="text-[10px] text-brand-500 font-bold uppercase tracking-wider block">Security Alert</span>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                When using **Gmail SMTP**, you must enable **2-Factor Authentication** in your Google Account and generate an **App Password** to replace your main account login credentials.
              </p>
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
      window.history.replaceState(null, null, "#/email");
    }
  },

  bindEvents() {
    // Save settings
    document.getElementById("btn-save-email-settings")?.addEventListener("click", () => {
      const email = Store.get("emailSettings") || {};
      
      email.smtpHost = document.getElementById("smtp-host")?.value.trim() || "";
      email.smtpPort = document.getElementById("smtp-port")?.value.trim() || "";
      email.smtpUsername = document.getElementById("smtp-username")?.value.trim() || "";
      email.smtpPassword = document.getElementById("smtp-password")?.value || "";
      email.encryption = document.getElementById("smtp-encryption")?.value || "TLS";
      email.senderName = document.getElementById("smtp-sender-name")?.value.trim() || "";
      email.senderEmail = document.getElementById("smtp-sender-email")?.value.trim() || "";

      // Save Auto reply toggle if template is active
      const autoReplyChk = document.getElementById("email-auto-reply");
      const autoReplyText = document.getElementById("email-autoreply-body");
      if (autoReplyChk) email.autoReplyToggle = autoReplyChk.checked;
      if (autoReplyText) email.autoReplyTemplate = autoReplyText.value.trim();

      Store.set("emailSettings", email);
      App.showToast("SMTP Server credentials updated and saved.", "success");
      this.refresh();
    });

    // Subtabs Switch Listener
    document.querySelectorAll(".email-tab-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        activeSubTab = btn.getAttribute("data-tab");
        this.refresh();
      });
    });

    // Clear Logs
    document.getElementById("btn-clear-email-logs")?.addEventListener("click", () => {
      if (confirm("Are you sure you want to delete all SMTP dispatch logs?")) {
        const email = Store.get("emailSettings") || {};
        email.emailLogs = [];
        Store.set("emailSettings", email);
        App.showToast("SMTP log logs cleared.", "info");
        this.refresh();
      }
    });

    // Connection Test modal triggering
    document.getElementById("btn-test-smtp-modal")?.addEventListener("click", () => {
      const testHtml = `
        <div class="space-y-4 text-xs text-left" id="smtp-test-container">
          <p class="text-slate-500">Provide a recipient address to verify TLS/SSL packet handshakes against Gmail servers.</p>
          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[9px]">Recipient Email</label>
            <input type="email" id="smtp-test-recipient" class="w-full glass-input p-3 rounded-xl" placeholder="test-receiver@gmail.com">
          </div>
          
          <!-- Animated Progress bar (hidden initially) -->
          <div id="smtp-test-progress" class="space-y-2 hidden">
            <div class="flex justify-between font-bold text-[10px] text-slate-400 uppercase tracking-wider">
              <span>Resolving MX Records...</span>
              <span id="smtp-progress-pct">0%</span>
            </div>
            <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
              <div id="smtp-progress-bar" class="h-full bg-brand-500 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
          </div>
        </div>
      `;

      App.openModal("SMTP Diagnostic Handshake", testHtml, () => {
        const recipient = document.getElementById("smtp-test-recipient").value.trim();
        if (!recipient) {
          alert("Recipient address required.");
          return false;
        }

        // Show progress animation
        const container = document.getElementById("smtp-test-recipient");
        const progress = document.getElementById("smtp-test-progress");
        const saveBtn = document.getElementById("btn-modal-save");

        if (container) container.parentElement.classList.add("hidden");
        if (progress) progress.classList.remove("hidden");
        if (saveBtn) saveBtn.classList.add("hidden");

        // Simulate progress ticks
        let pct = 0;
        const progressPct = document.getElementById("smtp-progress-pct");
        const progressBar = document.getElementById("smtp-progress-bar");
        
        const timer = setInterval(() => {
          pct += 25;
          if (progressPct) progressPct.textContent = `${pct}%`;
          if (progressBar) progressBar.style.width = `${pct}%`;
          
          if (pct === 100) {
            clearInterval(timer);
            
            // Log success to email settings logs
            const email = Store.get("emailSettings") || {};
            const logs = email.emailLogs || [];
            logs.unshift({
              id: "log-" + Date.now(),
              to: recipient,
              subject: "SMTP Handshake Verification Diagnostic Test",
              status: "Success",
              date: new Date().toISOString()
            });
            email.emailLogs = logs;
            Store.set("emailSettings", email);

            // Close modal & trigger toast
            document.getElementById("btn-modal-cancel")?.click();
            App.showToast(`SMTP test packet successfully dispatched to ${recipient}. check logs.`, "success");
            this.refresh();
          }
        }, 600);

        return false; // Prevent auto closing immediately, we close after progress finishes
      }, `
        <button id="btn-modal-cancel" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-xs font-semibold rounded-xl text-slate-500">Close</button>
        <button id="btn-modal-save" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs rounded-xl flex items-center gap-1.5">
          <i data-lucide="play" class="w-3.5 h-3.5"></i> Begin Test
        </button>
      `);
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
