<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">Laporan Penjualan Produk</h2>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Kotak Filter & Export -->
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <form method="GET" action="{{ route('reports.product') }}"
                    class="flex flex-col lg:flex-row gap-4 items-end justify-between">
                    <div class="flex gap-4 w-full lg:w-auto">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}"
                                class="border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}"
                                class="border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                        </div>
                        <div class="pb-0.5 flex items-end">
                            <button type="submit"
                                class="bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm transition">Filter</button>
                        </div>
                    </div>

                    <div class="flex gap-2 w-full lg:w-auto">
                        <button type="submit" name="export" value="pdf"
                            class="inline-flex items-center gap-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold py-2.5 px-5 rounded-xl border border-rose-200 transition">
                            PDF
                        </button>
                        <button type="submit" name="export" value="excel"
                            class="inline-flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold py-2.5 px-5 rounded-xl border border-emerald-200 transition">
                            Excel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Grid: Best Selling & Least Selling -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Best Selling -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-blue-50/50 flex items-center gap-3">
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">🔥</div>
                        <h3 class="font-bold text-slate-800">10 Produk Terlaris</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Menu
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Terjual
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($bestSellers as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-blue-600 text-center">
                                        {{ $item->total }} Porsi</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada
                                        data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Least Selling -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-100 bg-amber-50/50 flex items-center gap-3">
                        <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">❄️</div>
                        <h3 class="font-bold text-slate-800">10 Produk Kurang Laku</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Nama Menu
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase">Terjual
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leastSellers as $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-amber-600 text-center">
                                        {{ $item->total }} Porsi</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada
                                        data penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
