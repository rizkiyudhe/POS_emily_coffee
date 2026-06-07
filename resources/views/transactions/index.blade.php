<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            @can('create transactions')
            <a href="{{ route('transactions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                + Transaksi Baru
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Filter Form -->
        <div class="mb-6 bg-white rounded-2xl shadow-lg p-4">
            <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-gray-700 text-sm font-medium mb-1">Cari (Invoice / Queue)</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="INV-... atau A001..." 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                        🔍 Filter
                    </button>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg shadow transition ml-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Queue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-mono">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->queue_number }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->table->table_number ?? 'Take Away' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $trx->cashier->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Selesai</span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <!-- Lihat Detail / Struk -->
                                <a href="{{ route('transactions.receipt', $trx) }}" class="text-blue-600 hover:text-blue-800">Struk</a>

                                <!-- Cetak Ulang (Hanya jika punya permission) -->
                                @can('reprint receipt')
                                <form action="{{ route('transactions.reprint-customer', $trx) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800">Cetak Konsumen</button>
                                </form>
                                @endcan

                                @can('reprint kot')
                                <form action="{{ route('transactions.reprint-checker', $trx) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-800">Cetak Checker</button>
                                </form>
                                <form action="{{ route('transactions.reprint-kitchen', $trx) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-orange-600 hover:text-orange-800">Cetak Dapur</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi. Silakan buat transaksi baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>