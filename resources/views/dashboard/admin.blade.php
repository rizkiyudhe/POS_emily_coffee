<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <!-- Tombol Logout Eksplisit -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 border border-slate-200 hover:border-red-200 hover:bg-red-50 text-slate-600 hover:text-red-600 font-medium py-2 px-4 rounded-xl shadow-sm transition duration-200 ease-in-out text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Banner Selamat Datang -->
            <div class="mb-8 bg-gradient-to-r from-slate-900 to-indigo-950 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none transform translate-x-10 translate-y-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-64 h-64">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <span class="bg-indigo-500/30 text-indigo-300 text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">Ringkasan Cepat</span>
                    <h3 class="text-xl font-bold mt-2">Selamat datang kembali, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-slate-300 text-sm mt-1">Pantau performa dan kelola operasional kafe Anda hari ini dari satu dasbor terpusat.</p>
                </div>
            </div>

            <!-- Statistik Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Card Penjualan Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Penjualan Hari Ini</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M4.5 19.5h15M5.25 6h13.5A2.25 2.25 0 0121 8.25v8.25a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 16.5V8.25A2.25 2.25 0 015.25 6zM10.5 11.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card Transaksi Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Transaksi Hari Ini</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalTransactionsToday ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 2.24a4.5 4.5 0 112.83 0M12 18.75m-2.25 0a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card Total Produk -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Produk</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalProducts ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card Total Meja -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Meja</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalTables ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6V4.5a1.125 1.125 0 011.125-1.125h14.25A1.125 1.125 0 0120.25 4.5V6m-16.5 0a1.125 1.125 0 00-1.125 1.125v3.5m16.5-4.625v4.625m0 0a1.125 1.125 0 01-1.125 1.125H3.75m16.5-1.125v9a1.125 1.125 0 01-1.125 1.125H3.75a1.125 1.125 0 01-1.125-1.125V10.5m1.125 8.25h14.25" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Baris kedua -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Total User -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pengguna</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card Produk Terlaris -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Produk Terlaris</p>
                            @if(isset($bestSeller) && $bestSeller)
                                <p class="text-xl font-bold text-slate-800 mt-2">{{ $bestSeller->product->name ?? '-' }}</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    🔥 Terjual {{ $bestSeller->total ?? 0 }} pcs
                                </span>
                            @else
                                <p class="text-slate-400 text-sm italic mt-2">Belum ada data penjualan</p>
                            @endif
                        </div>
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-xl self-start">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.105-.21-.334-.34-.567-.34s-.462.13-.567.34l-2.032 4.116-4.542.66c-.234.033-.42.21-.465.441-.044.232.046.465.216.634l3.287 3.204-.775 4.523c-.04.233.054.467.243.605.19.137.444.148.643.029L10.5 15.21l4.043 2.126c.2.119.453.108.643-.029.19-.138.283-.372.243-.605l-.775-4.523 3.287-3.204c.17-.169.26-.402.216-.634a.517.517 0 00-.465-.441l-4.542-.66L11.48 3.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>