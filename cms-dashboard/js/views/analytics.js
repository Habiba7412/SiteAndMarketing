// Analytics Dashboard View
import { Store } from '../store.js';

export const AnalyticsView = {
  render(params) {
    return `
      <div class="space-y-8 animate-fade-in text-left">
        
        <!-- View Headers -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-xl font-extrabold tracking-tight">Analytics telemetry Control</h2>
            <p class="text-xs text-slate-400">Review server loads, website visitors, top page index bounce rates, and organic conversions.</p>
          </div>
          <select class="glass-input text-xs px-3 py-2 rounded-xl">
            <option>Last 30 Days</option>
            <option>Last 90 Days</option>
            <option>All Time</option>
          </select>
        </div>

        <!-- Metric Widgets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Widget 1: Sessions -->
          <div class="glass-card p-5 rounded-3xl relative overflow-hidden group">
            <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Total Traffic Sessions</span>
            <span class="text-3xl font-black block mt-2 tracking-tight">142.8K</span>
            <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-0.5 mt-2">
              <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +18.4% <span class="text-slate-400 font-normal">vs last month</span>
            </span>
          </div>

          <!-- Widget 2: Page Views -->
          <div class="glass-card p-5 rounded-3xl relative overflow-hidden group">
            <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Pages Audited</span>
            <span class="text-3xl font-black block mt-2 tracking-tight">418.9K</span>
            <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-0.5 mt-2">
              <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> +12.1% <span class="text-slate-400 font-normal">avg. session</span>
            </span>
          </div>

          <!-- Widget 3: Bounce Rate -->
          <div class="glass-card p-5 rounded-3xl relative overflow-hidden group">
            <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Bounce Rate Index</span>
            <span class="text-3xl font-black block mt-2 tracking-tight">38.4%</span>
            <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-0.5 mt-2">
              <i data-lucide="trending-down" class="w-3.5 h-3.5"></i> -2.4% <span class="text-slate-400 font-normal">better retention</span>
            </span>
          </div>

          <!-- Widget 4: Avg Session Time -->
          <div class="glass-card p-5 rounded-3xl relative overflow-hidden group">
            <span class="text-[10px] text-slate-400 font-bold block uppercase tracking-wider">Avg Session Time</span>
            <span class="text-3xl font-black block mt-2 tracking-tight">4m 12s</span>
            <span class="text-[10px] text-emerald-500 font-semibold flex items-center gap-0.5 mt-2">
              <i data-lucide="clock" class="w-3.5 h-3.5"></i> +8.2% <span class="text-slate-400 font-normal">user engagement</span>
            </span>
          </div>
        </div>

        <!-- Double Column Charts splitting -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
          
          <!-- Visitor & Pageviews double layer line chart (8 Columns) -->
          <div class="lg:col-span-8 glass-panel p-6 rounded-3xl flex flex-col space-y-6">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-base">Visitor Telemetry & Load Charts</h3>
                <p class="text-[11px] text-slate-400">Plotting monthly unique sessions against server hit thresholds.</p>
              </div>
              <div class="flex items-center gap-4 text-[10px] font-semibold">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-brand-500 rounded-full"></span> Visits</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-brand-accent rounded-full"></span> Page Views</span>
              </div>
            </div>

            <!-- SVG Dual chart -->
            <div class="h-64 flex items-end relative w-full pt-4">
              <svg class="w-full h-full" viewBox="0 0 500 200" preserveAspectRatio="none">
                <defs>
                  <linearGradient id="visGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#6366f1" stop-opacity="0.25"/>
                    <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0"/>
                  </linearGradient>
                  <linearGradient id="viewGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#8b5cf6" stop-opacity="0.15"/>
                    <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0.0"/>
                  </linearGradient>
                </defs>
                <!-- Grid Lines -->
                <line x1="0" y1="50" x2="500" y2="50" stroke="rgba(156,163,175,0.05)" stroke-dasharray="4"/>
                <line x1="0" y1="100" x2="500" y2="100" stroke="rgba(156,163,175,0.05)" stroke-dasharray="4"/>
                <line x1="0" y1="150" x2="500" y2="150" stroke="rgba(156,163,175,0.05)" stroke-dasharray="4"/>

                <!-- Layer 1: Page views (Purple) -->
                <path d="M 0,200 L 0,180 Q 80,140 160,150 T 320,100 T 480,50 L 500,45 L 500,200 Z" fill="url(#viewGrad)"/>
                <path d="M 0,180 Q 80,140 160,150 T 320,100 T 480,50 L 500,45" fill="none" stroke="#8b5cf6" stroke-width="2" stroke-dasharray="2"/>

                <!-- Layer 2: Visits (Indigo) -->
                <path d="M 0,200 L 0,150 Q 80,90 160,110 T 320,50 T 480,20 L 500,10 L 500,200 Z" fill="url(#visGrad)"/>
                <path d="M 0,150 Q 80,90 160,110 T 320,50 T 480,20 L 500,10" fill="none" stroke="#6366f1" stroke-width="2.5" stroke-linecap="round"/>
              </svg>
            </div>
            
            <div class="flex justify-between items-center text-[9px] text-slate-400 font-bold border-t border-slate-100 dark:border-slate-800/80 pt-3">
              <span>Week 1</span>
              <span>Week 2</span>
              <span>Week 3</span>
              <span>Week 4</span>
            </div>
          </div>

          <!-- Traffic Sources Circular Chart (4 Columns) -->
          <div class="lg:col-span-4 glass-panel p-6 rounded-3xl flex flex-col justify-between space-y-6">
            <div>
              <h3 class="font-bold text-base">Traffic Channels</h3>
              <p class="text-[11px] text-slate-400">Telemetry of referral domains.</p>
            </div>

            <!-- Concentric Circular SVG -->
            <div class="flex justify-center py-4">
              <div class="relative w-36 h-36 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90">
                  <!-- Ring 1: Organic (Outer) -->
                  <circle cx="72" cy="72" r="60" stroke="rgba(156,163,175,0.06)" stroke-width="8" fill="transparent" />
                  <circle cx="72" cy="72" r="60" stroke="#6366f1" stroke-width="8" fill="transparent" 
                          stroke-dasharray="377" stroke-dashoffset="150" stroke-linecap="round" />
                  
                  <!-- Ring 2: Direct (Middle) -->
                  <circle cx="72" cy="72" r="46" stroke="rgba(156,163,175,0.06)" stroke-width="8" fill="transparent" />
                  <circle cx="72" cy="72" r="46" stroke="#8b5cf6" stroke-width="8" fill="transparent" 
                          stroke-dasharray="289" stroke-dashoffset="100" stroke-linecap="round" />

                  <!-- Ring 3: Referral (Inner) -->
                  <circle cx="72" cy="72" r="32" stroke="rgba(156,163,175,0.06)" stroke-width="8" fill="transparent" />
                  <circle cx="72" cy="72" r="32" stroke="#10b981" stroke-width="8" fill="transparent" 
                          stroke-dasharray="201" stroke-dashoffset="60" stroke-linecap="round" />
                </svg>
                <div class="absolute text-center flex flex-col">
                  <span class="text-xs text-slate-400 font-bold uppercase">Main</span>
                  <span class="text-sm font-extrabold">60% Org</span>
                </div>
              </div>
            </div>

            <!-- Legend values -->
            <div class="space-y-2 text-[10px] font-semibold pt-2 border-t border-slate-100 dark:border-slate-800/80">
              <div class="flex justify-between items-center">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-brand-500 rounded-full"></span> Organic Search</span>
                <span>60%</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-brand-accent rounded-full"></span> Direct Traversal</span>
                <span>35%</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span> Referrals / Social</span>
                <span>5%</span>
              </div>
            </div>
          </div>

        </div>

        <!-- Bottom Rows: Device split & Geography traffic locations -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          
          <!-- Left: Top Audited URLs list (2 Columns) -->
          <div class="lg:col-span-2 glass-panel p-6 rounded-3xl space-y-4">
            <div>
              <h3 class="font-bold text-base">Top Performing Pages</h3>
              <p class="text-[11px] text-slate-400">High-volume URL paths indexed by search console engines.</p>
            </div>

            <div class="overflow-hidden border border-slate-100 dark:border-slate-800 rounded-2xl">
              <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 dark:bg-slate-800 text-slate-400 uppercase font-bold tracking-wider text-[10px]">
                  <tr>
                    <th class="p-3">Page Route</th>
                    <th class="p-3 text-center">Sessions</th>
                    <th class="p-3 text-center">Avg Duration</th>
                    <th class="p-3 text-right">Bounce Rate</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                  <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">/services/cloud-migrations</td>
                    <td class="p-3 text-center">45,812</td>
                    <td class="p-3 text-center">5m 10s</td>
                    <td class="p-3 text-right text-emerald-500 font-bold">32.1%</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">/blog/how-ai-revolutionizing-enterprise-it</td>
                    <td class="p-3 text-center">31,044</td>
                    <td class="p-3 text-center">4m 45s</td>
                    <td class="p-3 text-right text-emerald-500 font-bold">28.4%</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">/</td>
                    <td class="p-3 text-center">28,910</td>
                    <td class="p-3 text-center">2m 12s</td>
                    <td class="p-3 text-right text-amber-500 font-bold">48.2%</td>
                  </tr>
                  <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                    <td class="p-3 font-semibold text-slate-700 dark:text-slate-200">/services/managed-it</td>
                    <td class="p-3 text-center">21,080</td>
                    <td class="p-3 text-center">3m 30s</td>
                    <td class="p-3 text-right text-emerald-500 font-bold">36.8%</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Right: Geo & Devices Breakdowns (1 Column) -->
          <div class="glass-panel p-6 rounded-3xl space-y-6 flex flex-col justify-between">
            <!-- Devices Layout progress indicators -->
            <div class="space-y-3">
              <div>
                <h3 class="font-bold text-xs uppercase tracking-wider text-slate-400">Device Telemetry</h3>
                <span class="text-[10px] text-slate-400">Mobile vs desktop browser nodes.</span>
              </div>
              <div class="space-y-2.5 text-xs">
                <div class="space-y-1">
                  <div class="flex justify-between font-semibold">
                    <span>Desktop Browser</span>
                    <span>58%</span>
                  </div>
                  <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-500" style="width: 58%"></div>
                  </div>
                </div>
                <div class="space-y-1">
                  <div class="flex justify-between font-semibold">
                    <span>Mobile Safaris</span>
                    <span>38%</span>
                  </div>
                  <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-brand-accent" style="width: 38%"></div>
                  </div>
                </div>
                <div class="space-y-1">
                  <div class="flex justify-between font-semibold">
                    <span>iPad / Tablet nodes</span>
                    <span>4%</span>
                  </div>
                  <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full bg-slate-400" style="width: 4%"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Country list -->
            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800/80">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-400 block">Top Traffic Regions</span>
              <div class="space-y-2 text-xs">
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-700 dark:text-slate-200">United States</span>
                  <span class="text-slate-400">62,801 Visits</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-700 dark:text-slate-200">United Kingdom</span>
                  <span class="text-slate-400">22,044 Visits</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="font-bold text-slate-700 dark:text-slate-200">Canada</span>
                  <span class="text-slate-400">14,990 Visits</span>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    `;
  }
};
