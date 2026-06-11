@php
    $store = \App\Models\StoreSetting::first();
    $storeName = $store->name ?? 'Emily Coffee';
@endphp

<div x-data="{ sidebarOpen: false }" class="relative">
    <!-- HEADER MOBILE -->
    <header
        class="flex items-center justify-between lg:hidden bg-slate-50 border-b border-slate-200 px-6 py-4 shadow-sm z-40 relative">
        <div class="flex items-center gap-2.5">
            @if ($store && $store->logo)
                <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo"
                    class="h-8 w-8 object-cover rounded-lg shadow-sm">
            @else
                <span class="text-xl">☕</span>
            @endif
            <span class="font-bold text-slate-800 tracking-tight">{{ $storeName }}</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-800 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="sidebarOpen" x-cloak stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </header>

    <!-- OVERLAY BLUR MOBILE -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="fixed inset-0 z-40 bg-slate-900/20 backdrop-blur-xs lg:hidden transition-opacity duration-300"></div>

    <!-- SIDEBAR UTAMA -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-50 text-slate-600 border-r border-slate-200 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:h-screen lg:z-auto shrink-0">

        <div>
            <!-- LOGO & BRANDING SIDEBAR -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200 bg-slate-100/60">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    @if ($store && $store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo"
                            class="h-8 w-8 object-cover rounded-lg shadow-sm group-hover:scale-105 transition-transform">
                    @else
                        <span class="text-xl group-hover:scale-105 transition-transform">☕</span>
                    @endif
                    <span class="font-bold text-slate-800 text-base tracking-wide">{{ $storeName }}</span>
                </a>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-slate-600 lg:hidden">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- MENU NAVIGASI -->
            <nav class="px-3 py-6 space-y-6 overflow-y-auto max-h-[calc(100vh-140px)] no-scrollbar">

                <!-- Dashboard -->
                <div class="space-y-0.5">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                <!-- POS KASIR -->
                @if (auth()->user()->can('create transactions') || auth()->user()->can('view transactions'))
                    <div class="space-y-1">
                        <label class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">POS
                            Kasir</label>

                        @can('create transactions')
                            <a href="{{ route('transactions.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('transactions.create') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Transaksi Baru
                            </a>
                        @endcan

                        @can('view transactions')
                            <a href="{{ route('transactions.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.receipt') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Riwayat Transaksi
                            </a>
                        @endcan
                    </div>
                @endif

                <!-- DATA MASTER -->
                @if (auth()->user()->can('manage categories') ||
                        auth()->user()->can('manage products') ||
                        auth()->user()->can('manage tables'))
                    <div class="space-y-1">
                        <label
                            class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Data
                            Master</label>

                        @can('manage categories')
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('categories.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Kategori Menu
                            </a>
                        @endcan

                        @can('manage products')
                            <a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('products.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Katalog Produk
                            </a>
                        @endcan

                        @can('manage tables')
                            <a href="{{ route('tables.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('tables.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Manajemen Meja
                            </a>
                        @endcan
                    </div>
                @endif

                <!-- BACKOFFICE & REPORTS -->
                @if (auth()->user()->can('manage users') ||
                        auth()->user()->can('view reports') ||
                        auth()->user()->can('view logs') ||
                        auth()->user()->can('manage settings'))
                    <div class="space-y-1">
                        <label
                            class="px-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Backoffice</label>

                        @can('manage users')
                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Manajemen Staff
                            </a>
                        @endcan

                        @can('view reports')
                            <!-- Laporan Omzet -->
                            <a href="{{ route('reports.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('reports.index') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                                Laporan Omzet
                            </a>

                            <!-- Laporan Produk -->
                            <a href="{{ route('reports.product') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('reports.product') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .414.336.75.75.75z" />
                                </svg>
                                Laporan Produk
                            </a>

                            <!-- Laporan Void -->
                            <a href="{{ route('reports.void') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('reports.void') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Laporan Void
                            </a>
                        @endcan

                        @can('view logs')
                            <a href="{{ route('activity-logs.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('activity-logs.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Log Aktivitas
                            </a>
                        @endcan

                        @can('manage settings')
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('settings.*') ? 'bg-white text-slate-900 font-bold shadow-xs border border-slate-200/60' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                                <svg class="w-4 h-4 shrink-0 opacity-75" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                                Pengaturan Toko
                            </a>
                        @endcan
                    </div>
                @endif
            </nav>
        </div>

        <!-- FOOTER SIDEBAR (PROFIL USER) -->
        <div class="p-4 border-t border-slate-200 bg-slate-100/60 flex items-center justify-between">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2.5 hover:bg-white p-1.5 rounded-xl transition shadow-xs group min-w-0 flex-1 mr-2 border border-transparent hover:border-slate-200">
                <div
                    class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-600 uppercase tracking-wider group-hover:bg-slate-300 transition-colors">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-slate-700 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-400 font-medium truncate">Akses Aktif</div>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200"
                    title="Keluar">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>
