<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <!-- Form Filter -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <form method="POST" action="{{ route('reports.generate') }}" id="report-form" class="flex flex-wrap gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Periode</label>
                    <select name="period" id="period" class="border-gray-300 rounded-lg shadow-sm">
                        <option value="daily" {{ ($period ?? 'daily') == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="monthly" {{ ($period ?? '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ ($period ?? '') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ isset($start) ? $start->format('Y-m-d') : '' }}" class="border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ isset($end) ? $end->format('Y-m-d') : '' }}" class="border-gray-300 rounded-lg shadow-sm">
                </div>
                <div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow">
                        Tampilkan Laporan
                    </button>
                    <button type="button" id="export-pdf" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow ml-2">
                        Export PDF
                    </button>
                </div>
            </form>
        </div>

        @if(isset($transactions))
        <!-- Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="text-gray-500 text-sm">Total Penjualan</div>
                <div class="text-3xl font-bold text-green-600">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="text-gray-500 text-sm">Jumlah Transaksi</div>
                <div class="text-3xl font-bold text-blue-600">{{ $totalTransactions }}</div>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="text-gray-500 text-sm">Rata-rata per Transaksi</div>
                <div class="text-3xl font-bold text-purple-600">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Grafik Penjualan ({{ ucfirst($period) }})</h3>
            <canvas id="salesChart" height="100"></canvas>
        </div>

        <!-- Tabel Detail Transaksi -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-3 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-700">Detail Transaksi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Kasir</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($transactions as $trx)
                        <tr>
                            <td class="px-6 py-4 text-sm font-mono">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->table->table_number ?? 'Take Away' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->cashier->name }}</td>
                            <td class="px-6 py-4 text-sm text-right">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Produk Terlaris -->
        @if(isset($bestSellers) && $bestSellers->count())
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Produk Terlaris</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($bestSellers as $item)
                <div class="text-center p-3 bg-gray-50 rounded-lg">
                    <div class="text-sm text-gray-500">{{ $item->name }}</div>
                    <div class="text-xl font-bold text-indigo-600">{{ $item->total_qty }} pcs</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endif
    </div>

    <!-- Script Chart.js dan Export PDF -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if(isset($chartData) && $chartData->count())
        const ctx = document.getElementById('salesChart').getContext('2d');
        const labels = {!! json_encode($chartData->keys()) !!};
        const data = {!! json_encode($chartData->values()) !!};
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
        @endif

        // Export PDF (mengirim form ke route yang sama dengan parameter ?export=pdf)
        document.getElementById('export-pdf')?.addEventListener('click', function() {
            let form = document.getElementById('report-form');
            let action = form.action;
            // Tambahkan parameter export=pdf ke URL
            let url = new URL(action, window.location.origin);
            url.searchParams.set('export', 'pdf');
            form.action = url.toString();
            form.submit();
            // Kembalikan action asli setelah submit (opsional)
            setTimeout(() => { form.action = action; }, 100);
        });
    </script>
</x-app-layout>