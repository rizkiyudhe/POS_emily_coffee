<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Riwayat Transaksi') }}
            </h2>
            @can('create transactions')
                <a href="{{ route('transactions.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5 w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Transaksi Baru
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <form method="GET" action="{{ route('transactions.index') }}"
                    class="flex flex-col lg:flex-row gap-4 items-end">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full lg:w-auto">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                        </div>
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-slate-700 text-sm font-semibold mb-1.5">Cari (Invoice / Queue)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="INV-... atau A001..."
                                class="pl-10 w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                        </div>
                    </div>

                    <div class="flex gap-3 w-full lg:w-auto">
                        <button type="submit"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm transition duration-200">
                            Filter
                        </button>
                        <a href="{{ route('transactions.index') }}"
                            class="flex-1 lg:flex-none inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-6 rounded-xl transition duration-200">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Invoice</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Queue</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Meja</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Kasir</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Total</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700 font-mono">
                                        {{ $trx->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-600">
                                        {{ $trx->queue_number }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $trx->table_id ? 'bg-slate-100 text-slate-700' : 'bg-amber-50 border border-amber-200 text-amber-700' }}">
                                            {{ $trx->table->table_number ?? 'Take Away' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                        {{ $trx->cashier->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($trx->status == 'void')
                                            <div class="flex flex-col gap-1.5">
                                                <span
                                                    class="inline-flex items-center w-max px-2.5 py-1 text-xs font-bold rounded-lg border bg-rose-50 border-rose-200 text-rose-700"
                                                    title="Alasan: {{ $trx->void_reason }}">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full mr-1.5 bg-rose-500"></span>Void
                                                </span>
                                                @if ($trx->voided_at)
                                                    <div
                                                        class="text-[10px] text-slate-500 leading-tight bg-slate-50 p-1.5 rounded-md border border-slate-100">
                                                        Oleh: <span
                                                            class="font-semibold text-slate-700">{{ $trx->voider->name ?? 'Admin (ID: ' . $trx->voided_by . ')' }}</span><br>
                                                        Tgl:
                                                        {{ \Carbon\Carbon::parse($trx->voided_at)->format('d/m/Y H:i') }}
                                                        @if ($trx->void_reason)
                                                            <div class="mt-1 pt-1 border-t border-slate-200 italic text-rose-600 line-clamp-2"
                                                                title="{{ $trx->void_reason }}">
                                                                "{{ $trx->void_reason }}"
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center w-max px-2.5 py-1 text-xs font-bold rounded-lg border bg-emerald-50 border-emerald-200 text-emerald-700">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full mr-1.5 bg-emerald-500"></span>Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                        {{ $trx->transaction_date->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap space-x-1.5">

                                        <!-- TOMBOL DETAIL TRANSAKSI -->
                                        <button type="button" onclick="openDetailModal({{ $trx->id }})"
                                            class="inline-flex items-center justify-center px-2.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 text-xs font-bold rounded-xl transition"
                                            title="Detail Transaksi">
                                            Detail
                                        </button>

                                        <!-- TOMBOL STRUK -->
                                        <a href="{{ route('transactions.receipt', $trx) }}"
                                            class="inline-flex items-center justify-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold rounded-xl transition"
                                            title="Lihat Struk">
                                            Struk
                                        </a>

                                        @can('reprint receipt')
                                            <form action="{{ route('transactions.reprint-customer', $trx) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl transition">
                                                    Konsumen
                                                </button>
                                            </form>
                                        @endcan

                                        @can('reprint kot')
                                            <form action="{{ route('transactions.reprint-checker', $trx) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-2.5 py-1.5 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 text-xs font-bold rounded-xl transition">
                                                    Checker
                                                </button>
                                            </form>
                                            <form action="{{ route('transactions.reprint-kitchen', $trx) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-2.5 py-1.5 bg-orange-50 hover:bg-orange-100 border border-orange-200 text-orange-700 text-xs font-bold rounded-xl transition">
                                                    Dapur
                                                </button>
                                            </form>
                                        @endcan

                                        @can('void transactions')
                                            @if ($trx->status != 'void')
                                                <button type="button" onclick="openVoidModal({{ $trx->id }})"
                                                    class="inline-flex items-center justify-center px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 text-xs font-bold rounded-xl transition">
                                                    Void
                                                </button>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-slate-300 mb-3"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            <p class="text-slate-500 font-medium">Belum ada transaksi tercatat pada
                                                sistem.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODAL VOID -->
    <div id="voidModal"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-[60] transition-all duration-300">
        <div
            class="bg-white rounded-2xl border border-slate-100 p-6 w-full max-w-md shadow-2xl mx-4 transform transition-all">
            <div class="flex items-center gap-3 mb-3 border-b border-slate-100 pb-3">
                <div class="p-2 bg-rose-50 text-rose-600 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Void Transaksi</h3>
            </div>

            <form id="voidForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-slate-600 text-xs font-semibold uppercase tracking-wider mb-2">Alasan
                        Pembatalan (Void)</label>
                    <textarea name="reason" rows="3"
                        class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-rose-500 focus:ring-2 focus:ring-rose-100 transition p-3 text-sm"
                        placeholder="Ketik alasan pembatalan transaksi secara detail..." required></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeVoidModal()"
                        class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-xl transition text-sm">Batal</button>
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2 px-4 rounded-xl shadow-sm transition text-sm">Void
                        Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DETAIL TRANSAKSI (Di-generate untuk setiap baris) -->
    @foreach ($transactions as $trx)
        <div id="detailModal-{{ $trx->id }}"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition-all duration-300">
            <div
                class="bg-white rounded-2xl border border-slate-100 w-full max-w-2xl shadow-2xl mx-4 flex flex-col max-h-[90vh]">

                <!-- Modal Header -->
                <div
                    class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Detail Transaksi</h3>
                        <p class="text-sm text-slate-500 font-mono mt-0.5">{{ $trx->invoice_number }}</p>
                    </div>
                    <button type="button" onclick="closeDetailModal({{ $trx->id }})"
                        class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-5 overflow-y-auto space-y-6">
                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-slate-400 text-xs mb-1 uppercase tracking-wider font-bold">Waktu</p>
                            <p class="font-semibold text-slate-800">{{ $trx->transaction_date->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs mb-1 uppercase tracking-wider font-bold">Kasir</p>
                            <p class="font-semibold text-slate-800">{{ $trx->cashier->name }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs mb-1 uppercase tracking-wider font-bold">Layanan</p>
                            <p class="font-semibold text-slate-800">{{ $trx->table->table_number ?? 'Take Away' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs mb-1 uppercase tracking-wider font-bold">Status</p>
                            @if ($trx->status == 'void')
                                <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded">Void</span>
                            @else
                                <span
                                    class="text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded">Selesai</span>
                            @endif
                        </div>
                    </div>

                    <!-- Daftar Pesanan (Item) -->
                    <div>
                        <h4 class="font-bold text-slate-700 mb-3 flex items-center gap-2">
                            <span>🛒</span> Daftar Pesanan
                        </h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-2.5 font-bold text-slate-600">Menu</th>
                                        <th class="px-4 py-2.5 font-bold text-slate-600 text-center">Qty</th>
                                        <th class="px-4 py-2.5 font-bold text-slate-600 text-right">Harga</th>
                                        <th class="px-4 py-2.5 font-bold text-slate-600 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($trx->items as $item)
                                        <tr class="hover:bg-slate-50/50">
                                            <td class="px-4 py-3">
                                                <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                                @if ($item->notes)
                                                    <div class="text-xs text-rose-500 italic mt-0.5 font-medium">*
                                                        {{ $item->notes }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-center font-bold text-slate-700">
                                                {{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right text-slate-500">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-slate-800">Rp
                                                {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Ringkasan Keuangan -->
                    <div class="bg-slate-50/80 rounded-xl p-4 md:p-5 border border-slate-100">
                        <div class="space-y-2.5 text-sm">
                            <!-- Hitung Manual Subtotal Kotor -->
                            <div class="flex justify-between text-slate-600">
                                <span class="font-medium">Subtotal</span>
                                <span class="font-semibold">Rp
                                    {{ number_format($trx->total_amount + $trx->discount_amount - $trx->tax_amount - $trx->service_amount, 0, ',', '.') }}</span>
                            </div>

                            @if ($trx->discount_amount > 0)
                                <div
                                    class="flex justify-between text-rose-600 font-bold bg-rose-50 px-2 py-1 -mx-2 rounded">
                                    <span>Potongan Diskon
                                        ({{ $trx->discount_type == 'percentage' ? $trx->discount_value . '%' : 'Nominal' }})</span>
                                    <span>- Rp {{ number_format($trx->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if ($trx->tax_amount > 0)
                                <div class="flex justify-between text-slate-600">
                                    <span>Pajak ({{ $trx->tax_percentage ?? 0 }}%)</span>
                                    <span>Rp {{ number_format($trx->tax_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            @if ($trx->service_amount > 0)
                                <div class="flex justify-between text-slate-600">
                                    <span>Service Charge ({{ $trx->service_percentage ?? 0 }}%)</span>
                                    <span>Rp {{ number_format($trx->service_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div
                                class="border-t border-slate-200 pt-3 mt-3 flex justify-between font-black text-lg text-slate-800">
                                <span>GRAND TOTAL</span>
                                <span>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center text-slate-600 pt-2">
                                <span>Dibayar <span
                                        class="uppercase font-bold text-xs bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded ml-1">{{ $trx->payment_method }}</span></span>
                                <span class="font-bold">Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</span>
                            </div>

                            <div
                                class="flex justify-between items-center text-emerald-600 font-bold bg-emerald-50 px-2 py-1.5 -mx-2 rounded">
                                <span>Uang Kembali</span>
                                <span>Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="p-5 border-t border-slate-100 flex justify-end bg-slate-50/50 rounded-b-2xl">
                    <button type="button" onclick="closeDetailModal({{ $trx->id }})"
                        class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl transition shadow-sm">
                        Tutup Detail
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        // Script Modal Void
        function openVoidModal(transactionId) {
            const modal = document.getElementById('voidModal');
            const form = document.getElementById('voidForm');
            form.action = `/transactions/${transactionId}/void`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVoidModal() {
            const modal = document.getElementById('voidModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Script Modal Detail Transaksi
        function openDetailModal(id) {
            const modal = document.getElementById('detailModal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeDetailModal(id) {
            const modal = document.getElementById('detailModal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>
