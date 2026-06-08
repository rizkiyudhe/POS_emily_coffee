<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Penjualan Hari Ini
                            </p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">
                                Rp {{ number_format($todaySales ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5M4.5 19.5h15M5.25 6h13.5A2.25 2.25 0 0121 8.25v8.25a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 16.5V8.25A2.25 2.25 0 015.25 6zM10.5 11.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Transaksi Hari Ini
                            </p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalTransactionsToday ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 2.24a4.5 4.5 0 112.83 0M12 18.75m-2.25 0a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Produk</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalProducts ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Meja</p>
                            <p class="text-2xl font-bold text-slate-800 mt-2">{{ $totalTables ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6V4.5a1.125 1.125 0 011.125-1.125h14.25A1.125 1.125 0 0120.25 4.5V6m-16.5 0a1.125 1.125 0 00-1.125 1.125v3.5m16.5-4.625v4.625m0 0a1.125 1.125 0 01-1.125 1.125H3.75m16.5-1.125v9a1.125 1.125 0 01-1.125 1.125H3.75a1.125 1.125 0 01-1.125-1.125V10.5m1.125 8.25h14.25" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="p-2 bg-blue-50 text-blue-500 rounded-lg">📈</span>
                        <h3 class="font-bold text-slate-800">Grafik Penjualan 7 Hari Terakhir</h3>
                    </div>
                    <div class="flex-grow w-full relative">
                        <canvas id="salesChart" height="200"></canvas>
                        @if (empty($salesLast7Days) || $salesLast7Days->isEmpty())
                            <div class="absolute inset-0 flex items-center justify-center bg-white/80">
                                <p class="text-slate-400 text-sm font-medium">Belum ada data penjualan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="p-2 bg-amber-50 text-amber-500 rounded-lg">🏆</span>
                        <h3 class="font-bold text-slate-800">Top 5 Produk Terlaris</h3>
                    </div>

                    <ul class="space-y-3">
                        @if (isset($topProducts) && $topProducts->count())
                            @foreach ($topProducts as $index => $item)
                                <li
                                    class="flex justify-between items-center p-3 bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 transition">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <span class="font-medium text-slate-700">{{ $item->product->name }}</span>
                                    </div>
                                    <span
                                        class="px-3 py-1 bg-white border border-slate-200 text-blue-600 font-bold text-sm rounded-lg shadow-sm">
                                        {{ $item->total }} pcs
                                    </span>
                                </li>
                            @endforeach
                        @else
                            <li class="py-8 text-center text-slate-400 text-sm">Belum ada data produk terlaris.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="p-2 bg-rose-50 text-rose-500 rounded-lg">⚠️</span>
                        <h3 class="font-bold text-slate-800">Peringatan Stok Menipis</h3>
                    </div>

                    <ul class="space-y-3 overflow-y-auto max-h-[250px] pr-2">
                        @if (isset($lowStockProducts) && $lowStockProducts->count())
                            @foreach ($lowStockProducts as $product)
                                <li
                                    class="flex items-center justify-between p-3 bg-rose-50/50 border border-rose-100 rounded-xl transition hover:bg-rose-50">
                                    <span class="text-sm font-medium text-slate-700">{{ $product->name }}</span>
                                    <span
                                        class="px-2.5 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg border border-rose-200 shadow-sm">
                                        Sisa: {{ $product->stock }}
                                    </span>
                                </li>
                            @endforeach
                        @else
                            <li class="flex flex-col items-center justify-center py-6 text-center">
                                <span class="text-sm font-medium text-slate-400">Semua produk dalam kondisi stok
                                    aman.</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-100 pb-3">
                        <span class="p-2 bg-indigo-50 text-indigo-500 rounded-lg">📝</span>
                        <h3 class="font-bold text-slate-800">Aktivitas Terakhir</h3>
                    </div>

                    <ul class="space-y-4 overflow-y-auto max-h-[250px] pr-2 relative">
                        @if (isset($recentActivities) && $recentActivities->count())
                            <div class="absolute left-3.5 top-2 bottom-2 w-px bg-slate-200 z-0"></div>
                            @foreach ($recentActivities as $activity)
                                <li class="relative z-10 flex gap-4 items-start">
                                    <div
                                        class="w-7 h-7 rounded-full bg-indigo-100 border-4 border-white flex-shrink-0 mt-1">
                                    </div>
                                    <div class="flex-grow bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-xs text-slate-400 font-mono mb-1">
                                            {{ $activity->created_at->format('H:i') }}</p>
                                        <p class="text-sm text-slate-700">{{ $activity->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="py-6 text-center text-slate-400 text-sm">Belum ada aktivitas tercatat.</li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (isset($salesLast7Days) && $salesLast7Days->count())
                const labels = {!! json_encode($salesLast7Days->keys()) !!};
                const data = {!! json_encode($salesLast7Days->values()) !!};
                const ctx = document.getElementById('salesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: data,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(59, 130, 246)',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.4, // Membuat garis lebih halus/melengkung
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Menyembunyikan legend bawaan agar lebih bersih
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: {
                                    size: 13
                                },
                                bodyFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#94a3b8'
                                }
                            },
                            y: {
                                border: {
                                    display: false
                                },
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        family: "'Inter', sans-serif"
                                    },
                                    color: '#94a3b8',
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                        if (value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                        return 'Rp ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            @endif
        });
    </script>
</x-app-layout>
