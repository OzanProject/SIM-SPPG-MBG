<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard - {{ tenant('id') ?? 'Dapur MBG' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary-container": "#247caa",
              "outline-variant": "#bfc7d0",
              "surface-container-high": "#e6e8eb",
              "on-background": "#191c1e",
              "surface-container": "#eceef1",
              "on-tertiary-container": "#fefcff",
              "inverse-primary": "#87ceff",
              "tertiary": "#455d80",
              "on-tertiary": "#ffffff",
              "primary": "#00628c",
              "outline": "#70787f",
              "inverse-surface": "#2d3133",
              "surface-dim": "#d8dadd",
              "secondary-fixed-dim": "#c1c7ce",
              "surface-container-highest": "#e0e3e6",
              "on-secondary-container": "#5f656c",
              "secondary": "#595f65",
              "on-secondary": "#ffffff",
              "on-surface": "#191c1e",
              "surface-container-lowest": "#ffffff",
              "background": "#f7f9fc",
              "tertiary-fixed": "#d4e3ff",
              "surface-tint": "#00658f",
              "on-error-container": "#93000a",
              "primary-fixed-dim": "#87ceff",
              "primary-fixed": "#c8e6ff",
              "on-tertiary-fixed": "#001c3a",
              "inverse-on-surface": "#eff1f4",
              "tertiary-fixed-dim": "#afc8f0",
              "on-primary": "#ffffff",
              "on-surface-variant": "#40484e",
              "surface": "#f7f9fc",
              "secondary-fixed": "#dde3eb",
              "surface-bright": "#f7f9fc",
              "tertiary-container": "#5d769a",
              "on-primary-fixed": "#001e2e",
              "on-primary-container": "#fcfcff",
              "surface-container-low": "#f2f4f7",
              "error": "#ba1a1a",
              "secondary-container": "#dde3eb",
              "surface-variant": "#e0e3e6",
              "on-tertiary-fixed-variant": "#2f486a",
              "on-primary-fixed-variant": "#004c6d",
              "on-secondary-fixed-variant": "#41474e",
              "error-container": "#ffdad6",
              "on-secondary-fixed": "#161c22",
              "on-error": "#ffffff"
            },
            fontFamily: {
              "headline": ["Public Sans"],
              "body": ["Public Sans"],
              "label": ["Public Sans"]
            },
            borderRadius: {"DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem"},
          },
        },
      }
    </script>
</head>
<body class="bg-surface text-on-surface antialiased flex">

<!-- SideNavBar Shell -->
<aside class="h-screen w-64 fixed left-0 top-0 bg-[#001e2e] flex flex-col h-full py-4 shadow-2xl shadow-[#001e2e]/20 z-50">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center text-white">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_balance</span>
            </div>
            <div>
                <h1 class="text-xl font-bold text-white tracking-widest uppercase">Dapur MBG</h1>
                <p class="text-[0.6875rem] text-sky-500 font-medium tracking-wider">ENTERPRISE FINANCE</p>
            </div>
        </div>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="bg-[#247caa] text-white rounded-md mx-2 px-4 py-2 flex items-center gap-3 group transition-all duration-200" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined text-sky-200" style="font-variation-settings: 'FILL' 1;">dashboard</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Dashboard</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">account_balance</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Accounting</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">inventory_2</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Inventory</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">shopping_cart</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Procurement</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">payments</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Budgeting</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">insert_chart</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Reporting</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">group</span>
            <span class="font-sans text-[0.875rem] tracking-tight">User Management</span>
        </a>
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="#">
            <span class="material-symbols-outlined">history</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Audit Trail</span>
        </a>
    </nav>
    <div class="mt-auto pt-4 border-t border-slate-800 space-y-1">
        <a class="text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200" href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-sans text-[0.875rem] tracking-tight">Settings</span>
        </a>
        <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="cursor-pointer text-slate-400 hover:text-white mx-2 px-4 py-2 flex items-center gap-3 group hover:bg-slate-800/50 transition-all duration-200">
            <span class="material-symbols-outlined text-error">logout</span>
            <span class="font-sans text-[0.875rem] tracking-tight text-error">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>

<!-- Main Canvas -->
<main class="flex-1 ml-64 min-h-screen bg-surface">
    <!-- TopAppBar Shell -->
    <header class="flex items-center justify-between px-6 w-full sticky top-0 z-40 bg-white shadow-sm h-14">
        <div class="flex items-center gap-4">
            <button class="p-2 text-slate-600 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">
                    <span class="material-symbols-outlined text-sm">search</span>
                </span>
                <input class="pl-10 pr-4 py-1.5 bg-slate-50 border-none rounded text-sm w-64 focus:ring-2 focus:ring-primary/20" placeholder="Global Ledger Search..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button class="p-2 text-slate-600 hover:bg-slate-100 transition-colors rounded-full">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="p-2 text-slate-600 hover:bg-slate-100 transition-colors rounded-full">
                <span class="material-symbols-outlined">mail</span>
            </button>
            <button class="p-2 text-slate-600 hover:bg-slate-100 transition-colors rounded-full">
                <span class="material-symbols-outlined">grid_view</span>
            </button>
            <div class="h-8 w-px bg-slate-200 mx-2"></div>
            <div class="flex items-center gap-3 pl-2">
                <img alt="User profile avatar" class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0D8ABC&color=fff"/>
                <span class="text-sm font-semibold text-slate-900">{{ Auth::user()->name ?? 'Admin User' }}</span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="p-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-on-surface headline-sm">Dashboard - Cabang {{ tenant('id') ?? 'Dapur MBG' }}</h2>
                <p class="text-secondary text-sm">Enterprise overview and financial performance metrics</p>
            </div>
            <div class="flex gap-2">
                <button class="bg-surface-container-high px-4 py-2 text-sm font-semibold rounded hover:bg-slate-200 transition-all">
                    Generate Report
                </button>
                <button class="bg-primary px-4 py-2 text-white text-sm font-semibold rounded hover:bg-primary-container transition-all flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-sm">add</span>
                    New Transaction
                </button>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total Journals -->
            <div class="bg-surface-container-lowest rounded-lg p-7 border-l-4 border-primary relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-secondary font-label text-[0.6875rem] uppercase tracking-widest mb-2">Total Journals</p>
                    <h3 class="text-3xl font-bold text-on-surface">1,284</h3>
                    <p class="text-xs text-primary mt-2 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">trending_up</span>
                        +12% this month
                    </p>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-primary opacity-5 text-8xl group-hover:scale-110 transition-transform">account_balance</span>
            </div>
            <!-- Total Inventory Items -->
            <div class="bg-surface-container-lowest rounded-lg p-7 border-l-4 border-tertiary relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-secondary font-label text-[0.6875rem] uppercase tracking-widest mb-2">Inventory Items</p>
                    <h3 class="text-3xl font-bold text-on-surface">4,592</h3>
                    <p class="text-xs text-tertiary mt-2 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">sync</span>
                        84 Low stock alerts
                    </p>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-tertiary opacity-5 text-8xl group-hover:scale-110 transition-transform">inventory_2</span>
            </div>
            <!-- Active Purchase Orders -->
            <div class="bg-surface-container-lowest rounded-lg p-7 border-l-4 border-secondary relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-secondary font-label text-[0.6875rem] uppercase tracking-widest mb-2">Active POs</p>
                    <h3 class="text-3xl font-bold text-on-surface">32</h3>
                    <p class="text-xs text-secondary mt-2 font-medium flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs">pending_actions</span>
                        12 Pending approval
                    </p>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-secondary opacity-5 text-8xl group-hover:scale-110 transition-transform">shopping_cart</span>
            </div>
            <!-- Budget Remaining -->
            <div class="bg-surface-container-lowest rounded-lg p-7 border-l-4 border-primary-container relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-secondary font-label text-[0.6875rem] uppercase tracking-widest mb-2">Budget Remaining</p>
                    <h3 class="text-3xl font-bold text-on-surface">$248.5k</h3>
                    <div class="w-full bg-surface-container-high h-1.5 rounded-full mt-4 overflow-hidden">
                        <div class="bg-primary-container h-full w-[65%]"></div>
                    </div>
                </div>
                <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-primary-container opacity-5 text-8xl group-hover:scale-110 transition-transform">payments</span>
            </div>
        </div>

        <!-- Dashboard Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
            <!-- Main Chart -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h4 class="text-lg font-bold text-on-surface">Monthly Expenditure vs Budget</h4>
                        <p class="text-sm text-secondary">Fiscal Year 2024</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="text-xs font-semibold text-secondary uppercase tracking-tight">Expenditure</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-surface-container-high"></span>
                            <span class="text-xs font-semibold text-secondary uppercase tracking-tight">Budget</span>
                        </div>
                    </div>
                </div>
                <!-- Visual Chart Representation (Bar) -->
                <div class="h-64 flex items-end justify-between gap-4 pt-4">
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[60%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[90%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">JAN</span>
                    </div>
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[75%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[85%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">FEB</span>
                    </div>
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[45%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[95%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">MAR</span>
                    </div>
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[90%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[80%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">APR</span>
                    </div>
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[55%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[85%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">MAY</span>
                    </div>
                    <div class="flex flex-col items-center flex-1 h-full justify-end group">
                        <div class="w-full flex justify-center gap-1 items-end h-[70%]">
                            <div class="w-4 bg-primary rounded-t-sm"></div>
                            <div class="w-4 bg-surface-container-high rounded-t-sm h-[75%]"></div>
                        </div>
                        <span class="text-[0.6875rem] mt-3 font-label text-secondary">JUN</span>
                    </div>
                </div>
            </div>

            <!-- High-level Stats (Info boxes) -->
            <div class="space-y-6">
                <div class="bg-surface-container-lowest p-6 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <div>
                        <p class="text-secondary font-label text-[0.6875rem] uppercase">Operating Margin</p>
                        <h5 class="text-xl font-bold">24.8%</h5>
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded bg-tertiary-fixed flex items-center justify-center text-tertiary">
                        <span class="material-symbols-outlined">savings</span>
                    </div>
                    <div>
                        <p class="text-secondary font-label text-[0.6875rem] uppercase">Total Assets</p>
                        <h5 class="text-xl font-bold">$1.42M</h5>
                    </div>
                </div>
                <div class="bg-surface-container-lowest p-6 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined">credit_card_off</span>
                    </div>
                    <div>
                        <p class="text-secondary font-label text-[0.6875rem] uppercase">Pending Payables</p>
                        <h5 class="text-xl font-bold">$18.2k</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Table -->
        <div class="bg-surface-container-lowest rounded-lg overflow-hidden">
            <div class="p-6 border-b border-surface-container flex items-center justify-between">
                <h4 class="text-lg font-bold text-on-surface">Recent Activities</h4>
                <button class="text-primary text-xs font-bold uppercase tracking-widest hover:underline">View All Activities</button>
            </div>
            <table class="w-full text-left">
                <thead class="bg-surface-container-high">
                    <tr>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider">Entity</th>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-secondary font-label text-[0.6875rem] uppercase tracking-wider text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    <tr class="hover:bg-primary-fixed/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-on-surface">#TRX-8902</td>
                        <td class="px-6 py-4 text-sm text-on-surface">Global Produce Suppliers</td>
                        <td class="px-6 py-4 text-sm text-secondary">Oct 24, 2024</td>
                        <td class="px-6 py-4 text-sm font-bold text-on-surface">$2,450.00</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[0.6875rem] font-bold uppercase tracking-tight bg-green-100 text-green-700">Completed</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">more_vert</span>
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-primary-fixed/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-on-surface">#TRX-8891</td>
                        <td class="px-6 py-4 text-sm text-on-surface">Kitchen Express Inc.</td>
                        <td class="px-6 py-4 text-sm text-secondary">Oct 23, 2024</td>
                        <td class="px-6 py-4 text-sm font-bold text-on-surface">$1,280.50</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-[0.6875rem] font-bold uppercase tracking-tight bg-yellow-100 text-yellow-700">Pending</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="text-slate-400 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-lg">more_vert</span>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <footer class="px-8 py-6 text-center text-xs text-secondary border-t border-surface-container bg-white/50">
        <p>© 2024 Dapur MBG SaaS - Enterprise Finance. All Rights Reserved.</p>
    </footer>
</main>

<!-- Contextual FAB -->
<button class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-white rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center justify-center z-50">
    <span class="material-symbols-outlined text-2xl">support_agent</span>
</button>

</body>
</html>
