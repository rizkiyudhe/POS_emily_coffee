<div x-data="{ sidebarOpen: false }" class="relative">
    <header class="flex items-center justify-between lg:hidden bg-slate-900 px-6 py-4 shadow-sm z-40 relative">
        <div class="flex items-center gap-2">
            <span class="text-xl">☕</span>
            <span class="font-bold text-white tracking-tight">Emily Coffee</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-400 hover:text-white focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path x-show="!sidebarOpen" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="sidebarOpen" x-cloak stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </header>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 border-r border-slate-800 flex flex-col justify-between transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:h-screen lg:z-auto shrink-0 shadow-xl">

        <div>
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-800 bg-slate-950/40">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                    <span class="text-2xl group-hover:scale-110 transition-transform">☕</span>
                    <span class="font-black text-white text-lg tracking-wider">Emily Coffee</span>
                </a>
                <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="px-4 py-6 space-y-7 overflow-y-auto max-h-[calc(100vh-160px)]">

                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </div>

                @if (auth()->user()->can('create transactions') || auth()->user()->can('view transactions'))
                    <div class="space-y-1.5">
                        <label class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Menu
                            POS Kasir</label>

                        @can('create transactions')
                            <a href="{{ route('transactions.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('transactions.create') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Transaksi Baru
                            </a>
                        @endcan

                        @can('view transactions')
                            <a href="{{ route('transactions.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('transactions.index') || request()->routeIs('transactions.receipt') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Riwayat Transaksi
                            </a>
                        @endcan
                    </div>
                @endif

                @if (auth()->user()->can('manage categories') ||
                        auth()->user()->can('manage products') ||
                        auth()->user()->can('manage tables'))
                    <div class="space-y-1.5">
                        <label class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Data
                            Master</label>

                        @can('manage categories')
                            <a href="{{ route('categories.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Kategori Menu
                            </a>
                        @endcan

                        @can('manage products')
                            <a href="{{ route('products.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Katalog Produk
                            </a>
                        @endcan

                        @can('manage tables')
                            <a href="{{ route('tables.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('tables.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Manajemen Meja
                            </a>
                        @endcan
                    </div>
                @endif

                @if (auth()->user()->can('manage users') ||
                        auth()->user()->can('view reports') ||
                        auth()->user()->can('view logs') ||
                        auth()->user()->can('manage settings'))
                    <div class="space-y-1.5">
                        <label
                            class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest block">Administrator</label>

                        @can('manage users')
                            <a href="{{ route('users.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Manajemen Staff
                            </a>
                        @endcan

                        @can('view reports')
                            <a href="{{ route('reports.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                                </svg>
                                Laporan Omzet
                            </a>
                        @endcan

                        @can('view logs')
                            <a href="{{ route('activity-logs.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('activity-logs.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Log Aktivitas
                            </a>
                        @endcan

                        @can('manage settings')
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 {{ request()->routeIs('settings.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/10' : 'hover:bg-slate-800 text-slate-400 hover:text-slate-200' }}">
                                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Pengaturan Toko
                            </a>
                        @endcan
                    </div>
                @endif
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800 bg-slate-950/30 flex items-center justify-between">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2.5 hover:text-white transition group min-w-0 flex-1 mr-2">
                <div
                    class="h-8 w-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-sm text-slate-200 uppercase tracking-wider group-hover:border-blue-500 transition-colors">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-slate-200 truncate">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] text-slate-500 font-medium truncate">Akses Terbuka</div>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="p-2 text-slate-500 hover:text-rose-400 hover:bg-rose-500/10 rounded-xl transition-all duration-200"
                    title="Keluar">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </aside>

</div>
