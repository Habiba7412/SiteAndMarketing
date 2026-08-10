// Contact & Messages Inbox View
import { Store } from '../store.js';
import { App } from '../app.js';

let searchQuery = "";
let folderFilter = "All"; // All, unread, archived

export const ContactsView = {
  render(params) {
    const contacts = Store.get("contacts") || [];

    // Filter
    let filtered = contacts.filter(c => {
      const matchSearch = !searchQuery || c.name.toLowerCase().includes(searchQuery) || c.subject.toLowerCase().includes(searchQuery) || c.message.toLowerCase().includes(searchQuery) || c.company.toLowerCase().includes(searchQuery);
      let matchFolder = true;
      if (folderFilter === "unread") matchFolder = c.status === "unread";
      else if (folderFilter === "archived") matchFolder = c.status === "archived";
      else matchFolder = c.status !== "archived"; // Inbox doesn't show archive by default
      return matchSearch && matchFolder;
    });

    // Check if query parameter has id (opens message immediately)
    if (params && params.id) {
      // Handled in init
    }

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Contact Management Inbox</h2>
            <p class="text-xs text-slate-400">Review business consultations, IT audits requests, and export records.</p>
          </div>
          
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <button id="btn-export-csv" class="px-4 py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold rounded-xl flex items-center gap-2">
              <i data-lucide="download" class="w-4 h-4"></i> Export CSV
            </button>
            <button id="btn-export-excel" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2">
              <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
            </button>
          </div>
        </div>

        <!-- Sidebar Inbox Layout split -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Folders Panel (3 Columns) -->
          <div class="lg:col-span-3 space-y-4">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Inbox Folders</span>
            
            <div class="space-y-1.5 font-medium text-xs">
              <button class="folder-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all ${folderFilter === 'All' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500 hover:text-slate-700'}" data-folder="All">
                <span class="flex items-center gap-2.5">
                  <i data-lucide="inbox" class="w-4 h-4"></i> Inbox
                </span>
                <span class="text-[9px] bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded font-bold">${contacts.filter(c => c.status !== 'archived').length}</span>
              </button>

              <button class="folder-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all ${folderFilter === 'unread' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500 hover:text-slate-700'}" data-folder="unread">
                <span class="flex items-center gap-2.5">
                  <i data-lucide="mail" class="w-4 h-4 text-brand-500"></i> Unread Messages
                </span>
                <span class="text-[9px] bg-red-100 dark:bg-red-950/20 text-red-500 px-1.5 py-0.5 rounded font-bold">${contacts.filter(c => c.status === 'unread').length}</span>
              </button>

              <button class="folder-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all ${folderFilter === 'archived' ? 'bg-brand-50 text-brand-600 dark:bg-slate-800 dark:text-brand-500 font-bold' : 'hover:bg-slate-50 dark:hover:bg-slate-800/40 text-slate-500 hover:text-slate-700'}" data-folder="archived">
                <span class="flex items-center gap-2.5">
                  <i data-lucide="archive" class="w-4 h-4"></i> Archived Files
                </span>
                <span class="text-[9px] bg-slate-100 dark:bg-slate-700 px-1.5 py-0.5 rounded font-bold">${contacts.filter(c => c.status === 'archived').length}</span>
              </button>
            </div>
          </div>

          <!-- Messages Table Pane (9 Columns) -->
          <div class="lg:col-span-9 space-y-4">
            
            <!-- Search bar -->
            <div class="relative w-full">
              <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
              <input type="text" id="contact-search" value="${searchQuery}" class="w-full glass-input pl-10 pr-4 py-2 text-xs rounded-xl" placeholder="Search by sender, subject details, content messages...">
            </div>

            <!-- Database Ready Layout Table -->
            <div class="glass-panel rounded-3xl overflow-hidden shadow-premium">
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                  <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px] border-b border-slate-100 dark:border-slate-800">
                    <tr>
                      <th class="p-4">Sender Info</th>
                      <th class="p-4">Inquiry Subject</th>
                      <th class="p-4">Received Date</th>
                      <th class="p-4 text-center">Status</th>
                      <th class="p-4 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    ${filtered.length === 0 ? `
                      <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400 font-semibold">Inbox is clear! No messages match folders.</td>
                      </tr>
                    ` : filtered.map(msg => {
                      const isUnread = msg.status === "unread";
                      let statusBadge = "bg-slate-100 text-slate-500 dark:bg-slate-800";
                      if (isUnread) statusBadge = "bg-red-500/10 text-red-500 font-bold";
                      if (msg.status === "archived") statusBadge = "bg-amber-500/10 text-amber-500";

                      return `
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 cursor-pointer msg-row-element ${isUnread ? 'bg-brand-50/5 font-semibold' : ''}" data-id="${msg.id}">
                          
                          <!-- Sender Details -->
                          <td class="p-4">
                            <div>
                              <span class="font-bold text-slate-800 dark:text-slate-200 block">${msg.name}</span>
                              <span class="text-[10px] text-slate-400 block">${msg.company || 'Private Person'}</span>
                            </div>
                          </td>

                          <!-- Subject / Snippet -->
                          <td class="p-4">
                            <div class="max-w-[200px] sm:max-w-[300px] truncate">
                              <span class="text-slate-700 dark:text-slate-300 block font-semibold truncate">${msg.subject}</span>
                              <span class="text-[10px] text-slate-400 truncate block mt-0.5">${msg.message}</span>
                            </div>
                          </td>

                          <!-- Date -->
                          <td class="p-4 text-slate-400 font-semibold">
                            ${new Date(msg.date).toLocaleDateString()} ${new Date(msg.date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                          </td>

                          <!-- Status -->
                          <td class="p-4 text-center">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[9px] uppercase tracking-wider ${statusBadge}">${msg.status}</span>
                          </td>

                          <!-- Actions -->
                          <td class="p-4 text-right space-x-1 msg-actions-cell" onclick="event.stopPropagation()">
                            <button class="msg-read-btn p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800" data-id="${msg.id}" title="Read message">
                              <i data-lucide="eye" class="w-3.5 h-3.5 inline"></i>
                            </button>
                            <button class="msg-reply-btn p-1.5 rounded-lg text-brand-600 hover:bg-brand-50 dark:hover:bg-brand-500/10" data-id="${msg.id}" title="Compose reply">
                              <i data-lucide="reply" class="w-3.5 h-3.5 inline"></i>
                            </button>
                            <button class="msg-archive-btn p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/20" data-id="${msg.id}" title="Archive">
                              <i data-lucide="archive" class="w-3.5 h-3.5 inline"></i>
                            </button>
                            <button class="msg-delete-btn p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20" data-id="${msg.id}" title="Delete">
                              <i data-lucide="trash-2" class="w-3.5 h-3.5 inline"></i>
                            </button>
                          </td>

                        </tr>
                      `;
                    }).join("")}
                  </tbody>
                </table>
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

    if (params && params.id) {
      setTimeout(() => this.openReadModal(params.id), 200);
      window.history.replaceState(null, null, "#/contacts");
    }
  },

  bindEvents() {
    // Search typing filter
    document.getElementById("contact-search")?.addEventListener("input", (e) => {
      searchQuery = e.target.value.toLowerCase().trim();
      this.refresh();
    });

    // Folder Buttons Filter Navigation
    document.querySelectorAll(".folder-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        folderFilter = btn.getAttribute("data-folder");
        this.refresh();
      });
    });

    // Message Row Click -> Opens Read Modal
    document.querySelectorAll(".msg-row-element").forEach(row => {
      row.addEventListener("click", () => {
        this.openReadModal(row.getAttribute("data-id"));
      });
    });

    // Direct actions inside cell buttons
    document.querySelectorAll(".msg-read-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        this.openReadModal(btn.getAttribute("data-id"));
      });
    });

    document.querySelectorAll(".msg-reply-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        this.openReplyModal(btn.getAttribute("data-id"));
      });
    });

    document.querySelectorAll(".msg-archive-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        this.archiveMessage(id);
      });
    });

    document.querySelectorAll(".msg-delete-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        if (confirm("Delete this submission?")) {
          Store.deleteItem("contacts", id);
          App.showToast("Message deleted from system.", "info");
          this.refresh();
        }
      });
    });

    // Export CSV Mock Tool
    document.getElementById("btn-export-csv")?.addEventListener("click", () => {
      this.exportInboxData("csv");
    });

    // Export Excel Mock Tool
    document.getElementById("btn-export-excel")?.addEventListener("click", () => {
      this.exportInboxData("xlsx");
    });
  },

  // --- Inbox Operations ---

  archiveMessage(id) {
    const contacts = Store.get("contacts") || [];
    const match = contacts.find(c => c.id === id);
    if (match) {
      match.status = "archived";
      Store.set("contacts", contacts);
      App.showToast("Message archived.", "success");
      this.refresh();
    }
  },

  // Read message popup
  openReadModal(id) {
    const contacts = Store.get("contacts") || [];
    const match = contacts.find(c => c.id === id);
    if (!match) return;

    // Mark as read
    if (match.status === "unread") {
      match.status = "read";
      Store.set("contacts", contacts);
      
      // Update sidebar notification unread badge count
      const notifs = Store.get("notifications") || [];
      const relatedNotif = notifs.find(n => n.message.includes(match.name));
      if (relatedNotif) {
        relatedNotif.unread = false;
        Store.set("notifications", notifs);
      }
    }

    const modalHtml = `
      <div class="space-y-4 text-xs text-left">
        <div class="flex justify-between items-start border-b border-slate-100 dark:border-slate-800 pb-3">
          <div>
            <h4 class="font-extrabold text-sm text-slate-800 dark:text-white leading-tight">${match.subject}</h4>
            <p class="text-[10px] text-slate-400 mt-1">From: <b>${match.name}</b> (${match.email}) &bull; ${match.phone}</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Company: <b>${match.company || 'Private'}</b></p>
          </div>
          <span class="text-[9px] text-slate-400 font-bold">${new Date(match.date).toLocaleDateString()}</span>
        </div>
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 leading-relaxed text-slate-600 dark:text-slate-300">
          ${match.message}
        </div>
      </div>
    `;

    App.openModal("Read Contact Message", modalHtml, () => {
      this.refresh();
      return true;
    }, `
      <button id="btn-modal-cancel" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-xs font-semibold rounded-xl text-slate-500">Close</button>
      <button id="btn-read-reply" class="px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white font-semibold text-xs rounded-xl flex items-center gap-1.5">
        <i data-lucide="reply" class="w-3.5 h-3.5"></i> Reply Sender
      </button>
    `);

    // Override read footer action to reply modal
    document.getElementById("btn-read-reply")?.addEventListener("click", () => {
      // Close read modal
      document.getElementById("btn-modal-cancel")?.click();
      // Delay to allow smooth transition animation
      setTimeout(() => this.openReplyModal(match.id), 250);
    });
  },

  // Reply Composer popup
  openReplyModal(id) {
    const contacts = Store.get("contacts") || [];
    const match = contacts.find(c => c.id === id);
    if (!match) return;

    const emailSettings = Store.get("emailSettings") || {};
    
    // Auto populate custom template variables
    let replyBody = emailSettings.autoReplyTemplate || "";
    replyBody = replyBody.replace("[Name]", match.name)
                         .replace("[Subject]", match.subject);

    const composeHtml = `
      <div class="space-y-4 text-xs text-left">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[9px]">To</label>
            <input type="text" value="${match.name} <${match.email}>" class="w-full glass-input p-2 rounded-lg bg-slate-50 dark:bg-slate-800" disabled>
          </div>
          <div class="space-y-1">
            <label class="block font-bold text-slate-400 uppercase tracking-wider text-[9px]">Sender Node (SMTP)</label>
            <input type="text" value="${emailSettings.senderName} <${emailSettings.senderEmail}>" class="w-full glass-input p-2 rounded-lg bg-slate-50 dark:bg-slate-800" disabled>
          </div>
        </div>

        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[9px]">Subject</label>
          <input type="text" id="reply-subject" value="Re: ${match.subject}" class="w-full glass-input p-3 rounded-xl font-bold">
        </div>

        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[9px]">Response Message Payload</label>
          <textarea id="reply-body" rows="6" class="w-full glass-input p-3 rounded-xl font-mono leading-relaxed">${replyBody}</textarea>
        </div>
      </div>
    `;

    App.openModal(`Compose Reply: ${match.name}`, composeHtml, () => {
      const subject = document.getElementById("reply-subject").value.trim();
      const body = document.getElementById("reply-body").value.trim();

      if (!subject || !body) {
        alert("Subject and message payload cannot be empty.");
        return false;
      }

      // Simulate sending via SMTP
      // 1. Log transaction in email log database
      const logs = emailSettings.emailLogs || [];
      logs.unshift({
        id: "log-" + Date.now(),
        to: match.email,
        subject: subject,
        status: "Success",
        date: new Date().toISOString()
      });
      emailSettings.emailLogs = logs;
      Store.set("emailSettings", emailSettings);

      // 2. Set contact message as read
      match.status = "read";
      Store.set("contacts", contacts);
      
      // 3. Trigger success toast
      App.showToast(`Reply sent successfully to ${match.email} via SMTP.`, "success");
      
      this.refresh();
      return true;
    });
  },

  // Export Inbox Data as File downloads
  exportInboxData(format) {
    const contacts = Store.get("contacts") || [];
    
    let fileContent = "";
    let mimeType = "";
    let extension = "";

    if (format === "csv") {
      mimeType = "text/csv;charset=utf-8;";
      extension = "csv";
      
      // headers
      fileContent += "ID,Name,Email,Phone,Company,Subject,Message,Date,Status\n";
      contacts.forEach(c => {
        const cleanMsg = c.message.replace(/"/g, '""');
        fileContent += `"${c.id}","${c.name}","${c.email}","${c.phone}","${c.company || ''}","${c.subject}","${cleanMsg}","${c.date}","${c.status}"\n`;
      });
    } else {
      // Excel XML formatting simulation
      mimeType = "application/vnd.ms-excel";
      extension = "xls";
      
      fileContent += "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>\n";
      fileContent += "<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Contacts</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>\n";
      fileContent += "<body><table><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Company</th><th>Subject</th><th>Message</th><th>Date</th><th>Status</th></tr>\n";
      contacts.forEach(c => {
        fileContent += `<tr><td>${c.id}</td><td>${c.name}</td><td>${c.email}</td><td>${c.phone}</td><td>${c.company || ''}</td><td>${c.subject}</td><td>${c.message}</td><td>${c.date}</td><td>${c.status}</td></tr>\n`;
      });
      fileContent += "</table></body></html>";
    }

    const blob = new Blob([fileContent], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `contacts_export_2026.${extension}`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);

    App.showToast(`Inbox data exported as ${format.toUpperCase()} download.`, "success");
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
