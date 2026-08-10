// User Management View
import { Store } from '../store.js';
import { App } from '../app.js';

let searchQuery = "";
let roleFilter = "All";
let statusFilter = "All";

export const UsersView = {
  render(params) {
    const users = Store.get("users") || [];
    const roles = Store.get("roles") || [];

    // Filters
    const filtered = users.filter(u => {
      const matchSearch = !searchQuery || u.name.toLowerCase().includes(searchQuery) || u.email.toLowerCase().includes(searchQuery) || u.phone.includes(searchQuery);
      const matchRole = roleFilter === "All" || u.role === roleFilter;
      const matchStatus = statusFilter === "All" || u.status === statusFilter;
      return matchSearch && matchRole && matchStatus;
    });

    // Check if direct parameter triggers action
    if (params && params.action === "new") {
      // Handled in init
    }

    return `
      <div class="space-y-8 animate-fade-in">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">User Account Hub</h2>
            <p class="text-xs text-slate-400">Configure administrator logs, edit roles, audit security flags, and reset passwords.</p>
          </div>
          <button id="btn-add-user-modal" class="px-4 py-2 bg-gradient-to-r from-brand-600 to-brand-accent hover:shadow-md text-white text-xs font-semibold rounded-xl flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Add New User
          </button>
        </div>

        <!-- Filter Grid -->
        <div class="glass-panel p-5 rounded-3xl grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
          <!-- Search -->
          <div class="relative">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="user-search" value="${searchQuery}" class="w-full glass-input pl-9 pr-4 py-2 text-xs rounded-xl" placeholder="Search by name, email, phone...">
          </div>
          <!-- Role -->
          <div>
            <select id="user-filter-role" class="w-full glass-input p-2 text-xs rounded-xl">
              <option value="All" ${roleFilter === 'All' ? 'selected' : ''}>All Roles</option>
              ${roles.map(r => `<option value="${r.role}" ${roleFilter === r.role ? 'selected' : ''}>${r.role}</option>`).join("")}
            </select>
          </div>
          <!-- Status -->
          <div>
            <select id="user-filter-status" class="w-full glass-input p-2 text-xs rounded-xl">
              <option value="All" ${statusFilter === 'All' ? 'selected' : ''}>All Statuses</option>
              <option value="Active" ${statusFilter === 'Active' ? 'selected' : ''}>Active</option>
              <option value="Suspended" ${statusFilter === 'Suspended' ? 'selected' : ''}>Suspended</option>
            </select>
          </div>
        </div>

        <!-- User Accounts Table Grid -->
        <div class="glass-panel rounded-3xl overflow-hidden shadow-premium">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
              <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px] border-b border-slate-100 dark:border-slate-800">
                <tr>
                  <th class="p-4">User</th>
                  <th class="p-4">Contact Detail</th>
                  <th class="p-4">Assigned Role</th>
                  <th class="p-4">Last Active</th>
                  <th class="p-4">Registered On</th>
                  <th class="p-4 text-center">Status</th>
                  <th class="p-4 text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                ${filtered.length === 0 ? `
                  <tr>
                    <td colspan="7" class="p-8 text-center text-slate-400 font-semibold">No registered users matched the query.</td>
                  </tr>
                ` : filtered.map(user => {
                  let badgeColor = "bg-emerald-500/10 text-emerald-600 dark:text-emerald-500";
                  if (user.status === "Suspended") badgeColor = "bg-red-500/10 text-red-500";
                  if (user.status === "Inactive") badgeColor = "bg-slate-100 dark:bg-slate-800 text-slate-400";

                  return `
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                      <!-- Avatar & Name -->
                      <td class="p-4">
                        <div class="flex items-center gap-3">
                          <img src="${user.avatar || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=80&q=80'}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-brand-500/10" alt="${user.name}">
                          <div>
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">${user.name}</span>
                            <span class="text-[10px] text-slate-400 font-semibold block uppercase">ID: ${user.id}</span>
                          </div>
                        </div>
                      </td>
                      <!-- Contact -->
                      <td class="p-4 font-medium text-slate-500">
                        <span class="block">${user.email}</span>
                        <span class="block text-[10px] text-slate-400 mt-0.5">${user.phone}</span>
                      </td>
                      <!-- Role -->
                      <td class="p-4">
                        <span class="px-2 py-1 rounded bg-indigo-50 dark:bg-indigo-950/20 text-brand-600 dark:text-brand-400 font-semibold text-[10px]">${user.role}</span>
                      </td>
                      <!-- Last active -->
                      <td class="p-4 text-slate-500 font-semibold">${App.formatRelativeTime(user.lastLogin)}</td>
                      <!-- Registration date -->
                      <td class="p-4 text-slate-400 font-semibold">${new Date(user.registrationDate).toLocaleDateString()}</td>
                      <!-- Status badge -->
                      <td class="p-4 text-center">
                        <span class="px-2.5 py-1 rounded-full font-bold text-[9px] uppercase tracking-wider ${badgeColor}">${user.status}</span>
                      </td>
                      <!-- Actions Panel -->
                      <td class="p-4 text-right space-x-1 shrink-0">
                        <button class="view-user-btn p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800" data-id="${user.id}" title="Audit Timeline">
                          <i data-lucide="eye" class="w-3.5 h-3.5 inline"></i>
                        </button>
                        <button class="edit-user-btn p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800" data-id="${user.id}" title="Edit profile">
                          <i data-lucide="edit-2" class="w-3.5 h-3.5 inline"></i>
                        </button>
                        <button class="suspend-user-btn p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/20" data-id="${user.id}" title="${user.status === 'Active' ? 'Suspend' : 'Activate'} user">
                          <i data-lucide="${user.status === 'Active' ? 'user-x' : 'user-check'}" class="w-3.5 h-3.5 inline"></i>
                        </button>
                        <button class="reset-pwd-btn p-1.5 rounded-lg text-brand-accent hover:bg-purple-50 dark:hover:bg-purple-950/20" data-id="${user.id}" title="Reset Password">
                          <i data-lucide="key" class="w-3.5 h-3.5 inline"></i>
                        </button>
                        <button class="delete-user-btn p-1.5 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20" data-id="${user.id}" title="Remove Account">
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
    `;
  },

  // --- Initializer Event Listeners ---
  init(params) {
    this.bindEvents();

    if (params && params.action === "new") {
      setTimeout(() => this.openUserFormModal(), 200);
      window.history.replaceState(null, null, "#/users");
    }
  },

  bindEvents() {
    // Search typing filter
    document.getElementById("user-search")?.addEventListener("input", (e) => {
      searchQuery = e.target.value.toLowerCase().trim();
      this.refresh();
    });

    // Role filter select
    document.getElementById("user-filter-role")?.addEventListener("change", (e) => {
      roleFilter = e.target.value;
      this.refresh();
    });

    // Status filter select
    document.getElementById("user-filter-status")?.addEventListener("change", (e) => {
      statusFilter = e.target.value;
      this.refresh();
    });

    // Add User Modal Button
    document.getElementById("btn-add-user-modal")?.addEventListener("click", () => {
      this.openUserFormModal();
    });

    // View User (Audit Timeline details)
    document.querySelectorAll(".view-user-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        this.openViewModal(btn.getAttribute("data-id"));
      });
    });

    // Edit User Profile Details
    document.querySelectorAll(".edit-user-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        this.openUserFormModal(btn.getAttribute("data-id"));
      });
    });

    // Suspend Toggle
    document.querySelectorAll(".suspend-user-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const users = Store.get("users") || [];
        const match = users.find(u => u.id === id);
        if (match) {
          if (match.role === "Super Admin") {
            App.showToast("Cannot suspend the Super Admin account.", "error");
            return;
          }
          match.status = match.status === "Active" ? "Suspended" : "Active";
          Store.set("users", users);
          App.showToast(`Account for ${match.name} set to ${match.status}`, "success");
          this.refresh();
        }
      });
    });

    // Reset Password Request Simulator
    document.querySelectorAll(".reset-pwd-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const users = Store.get("users") || [];
        const match = users.find(u => u.id === id);
        if (!match) return;
        
        // Show simulator modal
        const randomTempToken = Math.random().toString(36).substring(2, 8).toUpperCase();
        const infoHtml = `
          <div class="space-y-4 text-xs text-left">
            <p class="text-slate-500">You are generating a recovery passcode for <b>${match.name}</b> (${match.email}).</p>
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 font-mono flex items-center justify-between">
              <div>
                <span class="block text-[9px] font-bold text-slate-400 uppercase">Temporary Recovery Code</span>
                <span class="text-base font-bold text-brand-600 dark:text-brand-accent tracking-widest mt-1 block">${randomTempToken}</span>
              </div>
              <button class="px-2.5 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 rounded-lg font-sans font-bold flex items-center gap-1.5" onclick="navigator.clipboard.writeText('${randomTempToken}'); alert('Copied token to clipboard!');">
                <i data-lucide="copy" class="w-3.5 h-3.5"></i> Copy Code
              </button>
            </div>
            <p class="text-[10px] text-slate-400 italic">This code simulates a system password reset dispatch queue block. A secure email containing this token would automatically be sent to the user.</p>
          </div>
        `;
        App.openModal("Password Recovery Console", infoHtml, () => {
          App.showToast("Recovery token verified. Passcode sent successfully.", "success");
          return true;
        }, `<button id="btn-modal-cancel" class="px-4 py-2 bg-brand-600 text-white rounded-xl text-xs font-semibold">Done</button>`);
      });
    });

    // Delete User
    document.querySelectorAll(".delete-user-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const users = Store.get("users") || [];
        const match = users.find(u => u.id === id);
        if (match) {
          if (match.role === "Super Admin") {
            App.showToast("Cannot remove the core system Super Admin.", "error");
            return;
          }
          if (confirm(`Are you sure you want to delete ${match.name}'s account permanently?`)) {
            Store.deleteItem("users", id);
            App.showToast("User account removed.", "info");
            this.refresh();
          }
        }
      });
    });
  },

  // --- View Detailed Audit Log Modal ---
  openViewModal(userId) {
    const users = Store.get("users") || [];
    const match = users.find(u => u.id === userId);
    if (!match) return;

    // Pull from System log file references
    const logs = Store.get("activityLogs") || [];
    const userLogs = logs.filter(log => log.user === match.name);

    const auditHtml = `
      <div class="space-y-6 text-xs text-left">
        <!-- Profile info -->
        <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
          <img src="${match.avatar}" class="w-12 h-12 rounded-xl object-cover ring-2 ring-brand-500/10">
          <div>
            <h4 class="font-extrabold text-slate-800 dark:text-slate-200 text-sm leading-tight">${match.name}</h4>
            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider block mt-1">${match.role}</span>
            <span class="text-[10px] text-slate-400 mt-0.5 block">${match.email} &bull; ${match.phone}</span>
          </div>
        </div>

        <!-- History dates grid -->
        <div class="grid grid-cols-2 gap-4">
          <div class="glass-panel p-3.5 rounded-xl">
            <span class="text-[10px] text-slate-400 font-bold block uppercase">First Registered On</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 mt-1 block">${new Date(match.registrationDate).toLocaleString()}</span>
          </div>
          <div class="glass-panel p-3.5 rounded-xl">
            <span class="text-[10px] text-slate-400 font-bold block uppercase">Last Activity Seen</span>
            <span class="font-semibold text-slate-700 dark:text-slate-200 mt-1 block">${new Date(match.lastLogin).toLocaleString()}</span>
          </div>
        </div>

        <!-- Actions Audit Trail Timeline -->
        <div class="space-y-3">
          <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Operation Audit Trail</span>
          <div class="space-y-3 border-l border-slate-200 dark:border-slate-800 pl-4 py-1">
            ${userLogs.length === 0 ? `
              <p class="text-slate-400 italic">No operations recorded by this account in the active session.</p>
            ` : userLogs.map(l => `
              <div class="relative">
                <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full border-2 border-white dark:border-slate-900 bg-brand-500"></span>
                <div class="flex justify-between items-center text-[11px]">
                  <span class="font-bold text-slate-700 dark:text-slate-200">${l.action}</span>
                  <span class="text-[9px] text-slate-400 font-bold">${new Date(l.date).toLocaleTimeString()}</span>
                </div>
                <span class="text-[9px] text-slate-400 block mt-0.5">${l.module} &bull; ${l.ipAddress}</span>
              </div>
            `).join("")}
          </div>
        </div>
      </div>
    `;

    App.openModal(`User File Summary: ${match.name}`, auditHtml, () => {}, `
      <button id="btn-modal-cancel" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-xs font-semibold rounded-xl text-slate-600 dark:text-slate-300">Close</button>
    `);
  },

  // --- Add/Edit User Modal Form ---
  openUserFormModal(userId = null) {
    const users = Store.get("users") || [];
    const roles = Store.get("roles") || [];
    
    let title = "Add New User Account";
    let name = "";
    let email = "";
    let phone = "";
    let role = "Author";
    let status = "Active";
    let avatar = "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80";

    if (userId) {
      title = "Edit User Account details";
      const match = users.find(u => u.id === userId);
      if (match) {
        name = match.name;
        email = match.email;
        phone = match.phone;
        role = match.role;
        status = match.status;
        avatar = match.avatar;
      }
    }

    const formHtml = `
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-left">
        <div class="space-y-1 md:col-span-2 flex justify-center pb-2">
          <div class="relative w-16 h-16 rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-800 cursor-pointer group" id="btn-form-select-avatar">
            <img id="form-avatar-preview" src="${avatar}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="Avatar">
            <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-[8px] font-bold transition-opacity">
              Change
            </div>
            <input type="hidden" id="form-user-avatar" value="${avatar}">
          </div>
        </div>
        
        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Full Name</label>
          <input type="text" id="form-user-name" value="${name}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. John Doe">
        </div>
        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Email Address</label>
          <input type="email" id="form-user-email" value="${email}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. j.doe@myitcompany.com">
        </div>
        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Phone Number</label>
          <input type="text" id="form-user-phone" value="${phone}" class="w-full glass-input p-3 rounded-xl" placeholder="e.g. +1 (555) 012-3456">
        </div>
        <div class="space-y-1">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">User Role</label>
          <select id="form-user-role" class="w-full glass-input p-3 rounded-xl">
            ${roles.map(r => `<option value="${r.role}" ${role === r.role ? 'selected' : ''}>${r.role}</option>`).join("")}
          </select>
        </div>
        <div class="space-y-1 md:col-span-2">
          <label class="block font-bold text-slate-400 uppercase tracking-wider text-[10px]">Account Status</label>
          <select id="form-user-status" class="w-full glass-input p-3 rounded-xl">
            <option value="Active" ${status === 'Active' ? 'selected' : ''}>Active</option>
            <option value="Suspended" ${status === 'Suspended' ? 'selected' : ''}>Suspended</option>
            <option value="Inactive" ${status === 'Inactive' ? 'selected' : ''}>Inactive</option>
          </select>
        </div>
      </div>
    `;

    App.openModal(title, formHtml, () => {
      const uName = document.getElementById("form-user-name").value.trim();
      const uEmail = document.getElementById("form-user-email").value.trim();
      const uPhone = document.getElementById("form-user-phone").value.trim();
      const uRole = document.getElementById("form-user-role").value;
      const uStatus = document.getElementById("form-user-status").value;
      const uAvatar = document.getElementById("form-user-avatar").value;

      if (!uName || !uEmail) {
        alert("Name and email are required fields.");
        return false;
      }

      if (userId) {
        // Edit User
        Store.updateItem("users", userId, {
          name: uName,
          email: uEmail,
          phone: uPhone,
          role: uRole,
          status: uStatus,
          avatar: uAvatar
        });
        App.showToast("User details modified.", "success");
      } else {
        // Add User
        Store.insertItem("users", {
          name: uName,
          email: uEmail,
          phone: uPhone,
          role: uRole,
          status: uStatus,
          avatar: uAvatar,
          lastLogin: new Date().toISOString(),
          registrationDate: new Date().toISOString()
        });
        App.showToast("User account registered.", "success");
      }

      this.refresh();
      return true;
    });

    // Handle avatar selection mock click
    document.getElementById("btn-form-select-avatar")?.addEventListener("click", () => {
      // Pick random portrait mock selection
      const index = Math.floor(Math.random() * 5) + 1;
      const portraits = [
        "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=150&q=80",
        "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80"
      ];
      const picked = portraits[index - 1];
      
      const hiddenInput = document.getElementById("form-user-avatar");
      const previewImg = document.getElementById("form-avatar-preview");
      if (hiddenInput) hiddenInput.value = picked;
      if (previewImg) previewImg.src = picked;
      App.showToast("Generated profile avatar.", "info");
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
