<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                    {{ __('Kasir Dashboard') }}
                </h2>
                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ now()->format('l, d F Y') }}
                </div>
            </div>
            
            <!-- Tombol Logout Eksplisit -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 border border-slate-200 hover:border-red-200 hover:bg-red-50 text-slate-600 hover:text-red-600 font-medium py-2 px-4 rounded-xl shadow-sm transition duration-200 ease-in-out text-sm w-full sm:w-auto justify-center">
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
            
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <!-- Card Penjualan Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:shadow-md transition duration-300 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Penjualan Hari Ini</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">Rp {{ number_format($totalSalesToday ?? 0, 0, ',', '.') }}</p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Tambahan: Tips & Aktivitas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Banner Ajakan Aksi -->
                <div class="bg-gradient-to-r from-indigo-600 to-slate-950 rounded-2xl shadow-xl p-6 text-white relative overflow-hidden flex flex-col justify-between min-h-[200px]">
                    <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none transform translate-x-6 translate-y-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-48 h-48">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <span class="bg-white/20 text-indigo-100 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Ruang Kerja Kasir</span>
                        <h3 class="text-xl font-bold mt-2">☕ Selamat Bekerja!</h3>
                        <p class="text-slate-300 text-sm mt-1 leading-relaxed">Tetap semangat melayani pelanggan. Siap mencatat lembaran transaksi baru hari ini?</p>
                    </div>
                    <div class="mt-6 relative z-10">
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-indigo-950 rounded-xl font-semibold text-sm hover:bg-slate-50 transition shadow-sm duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Transaksi Baru
                        </a>
                    </div>
                </div>

                <!-- Card Transaksi Terakhir -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-slate-800">🕒 Transaksi Terakhir</h3>
                            <span class="text-xs font-medium text-slate-400">Terbaru di atas</span>
                        </div>
                        
                        @if(isset($recentTransactions) && count($recentTransactions))
                            <ul class="divide-y divide-slate-100">
                                @foreach($recentTransactions as $trx)
                                    <li class="py-3 flex justify-between items-center first:pt-0 last:pb-0">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-slate-50 text-slate-600 text-[11px] font-mono font-bold px-2.5 py-1 rounded-lg border border-slate-100">
                                                {{ $trx->invoice_number }}
                                            </div>
                                        </div>
                                        <span class="font-bold text-slate-800 text-sm">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300 mb-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.008 1.24l.885 1.77a2.25 2.25 0 002.007 1.24h1.98a2.25 2.25 0 002.007-1.24l.885-1.77a2.25 2.25 0 012.007-1.24h3.86m-18 0h18" />
                                </svg>
                                <p class="text-slate-400 text-sm italic">Belum ada aktivitas transaksi hari ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>