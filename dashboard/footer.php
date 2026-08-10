        </main>
        
        <!-- Dashboard Footer -->
        <footer class="h-14 border-t border-slate-800/40 px-6 flex items-center justify-between text-xs text-slate-500 bg-brand-darker/5">
            <span>Copyright &copy; <?php echo date('Y'); ?> Teckko. All Rights Reserved.</span>
            <span>AdminLTE Dynamic Control Platform</span>
        </footer>
    </div>

    <!-- Alert Toast System -->
    <div id="dashboard-alert" class="fixed bottom-6 right-6 z-50 rounded-2xl border bg-brand-card p-6 flex items-center gap-4 shadow-2xl translate-y-24 opacity-0 transition-all duration-300 max-w-sm border-slate-800">
        <div id="dashboard-alert-icon" class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"></div>
        <div>
            <h4 id="dashboard-alert-title" class="font-heading font-bold text-white text-sm"></h4>
            <p id="dashboard-alert-desc" class="text-slate-400 text-xs mt-1"></p>
        </div>
    </div>

    <!-- Scripting for toggles -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openSidebarBtn = document.getElementById('open-sidebar-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const sidebar = document.getElementById('sidebar');

            if (openSidebarBtn && sidebar) {
                openSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                });
            }

            if (closeSidebarBtn && sidebar) {
                closeSidebarBtn.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                });
            }
        });

        // Dashboard Notification Helper
        const showToast = (title, message, type = 'success') => {
            const toast = document.getElementById('dashboard-alert');
            const toastIcon = document.getElementById('dashboard-alert-icon');
            const toastTitle = document.getElementById('dashboard-alert-title');
            const toastDesc = document.getElementById('dashboard-alert-desc');

            if (!toast) return;

            // Reset classes
            toastIcon.className = 'w-10 h-10 rounded-full flex items-center justify-center shrink-0';
            toast.className = 'fixed bottom-6 right-6 z-50 rounded-2xl border bg-brand-card p-6 flex items-center gap-4 shadow-2xl translate-y-24 opacity-0 transition-all duration-300 max-w-sm';

            if (type === 'success') {
                toastIcon.classList.add('bg-emerald-500/10', 'text-emerald-400');
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-check text-lg"></i>';
                toast.classList.add('border-emerald-500/20');
            } else if (type === 'danger' || type === 'error') {
                toastIcon.classList.add('bg-red-500/10', 'text-red-400');
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-xmark text-lg"></i>';
                toast.classList.add('border-red-500/20');
            } else {
                toastIcon.classList.add('bg-brand-accent/10', 'text-brand-accent');
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-info text-lg"></i>';
                toast.classList.add('border-brand-accent/20');
            }

            toastTitle.textContent = title;
            toastDesc.textContent = message;

            // Animate In
            toast.classList.remove('translate-y-24', 'opacity-0');
            
            setTimeout(() => {
                // Animate Out
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 4500);
        };
    </script>
</body>
</html>
