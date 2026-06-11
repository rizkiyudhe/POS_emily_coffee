<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Struk Transaksi') }}
                <span class="text-sm font-normal text-slate-500">
                    ({{ request('type') ? 'Mode: ' . ucfirst(request('type')) : 'Mode: Konsumen' }})
                </span>
            </h2>
            <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">

                <button onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition duration-200 text-sm w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.82l2.68-2.68m0 0l2.68 2.68M9.4 11.14v5.54m10.46-.38A9.044 9.044 0 0012 3c-2.01 0-3.88.65-5.4 1.75a9.043 9.043 0 00-2.46 13.11m15.46-2.12a9.045 9.045 0 01-15.46 0M10.5 21h3" />
                    </svg>
                    Cetak Struk (Halaman)
                </button>

                @can('reprint receipt')
                    <form action="{{ route('transactions.reprint-customer', $transaction) }}" method="POST"
                        class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition duration-200 text-sm w-full">
                            🧾 Struk Konsumen
                        </button>
                    </form>
                @endcan

                @can('reprint kot')
                    <form action="{{ route('transactions.reprint-checker', $transaction) }}" method="POST"
                        class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition duration-200 text-sm w-full">
                            🔍 Checker
                        </button>
                    </form>
                    <form action="{{ route('transactions.reprint-kitchen', $transaction) }}" method="POST"
                        class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition duration-200 text-sm w-full">
                            🍳 Dapur
                        </button>
                    </form>
                @endcan

                <a href="{{ route('transactions.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm transition duration-200 text-sm w-full sm:w-auto">
                    + POS Baru
                </a>
                <a href="{{ route('transactions.index') }}"
                    class="inline-flex items-center justify-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition duration-200 text-sm w-full sm:w-auto">
                    ← Riwayat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-100 min-h-screen flex items-center justify-center print:bg-white print:py-0">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden print:shadow-none print:border-none p-2 sm:p-6"
            id="receipt-content">
            <div class="bg-white p-4 font-mono text-slate-800 text-xs sm:text-sm leading-relaxed">

                <!-- Header Struk -->
                <div class="text-center border-b-2 border-dashed border-slate-300 pb-4">
                    @if (request('type') == 'checker')
                        <h2 class="font-black text-2xl tracking-tight text-slate-900">STRUK CHECKER</h2>
                        <p class="text-xs text-slate-500 mt-1">Emily Coffee</p>
                    @elseif(request('type') == 'kitchen')
                        <h2 class="font-black text-2xl tracking-tight text-slate-900">ORDER DAPUR</h2>
                        <p class="text-xs text-slate-500 mt-1">Emily Coffee</p>
                    @else
                        <h2 class="font-black text-2xl tracking-tight text-slate-900">Emily Coffee</h2>
                        <p class="text-xs text-slate-500 mt-1">Padang</p>
                        <p class="text-xs text-slate-500">Telp: 08123456789</p>
                    @endif
                </div>

                <!-- Info Transaksi -->
                <div class="mt-4 space-y-1.5 border-b border-dashed border-slate-200 pb-4 text-slate-600">
                    <div class="flex justify-between">
                        <span>No. Invoice</span>
                        <span class="font-bold text-slate-900">{{ $transaction->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>No. Antrean</span>
                        <span class="font-bold text-slate-900">#{{ $transaction->queue_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Meja / Layanan</span>
                        <span
                            class="font-bold text-slate-900">{{ $transaction->table->table_number ?? 'Take Away' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Nama Kasir</span>
                        <span>{{ $transaction->cashier->name }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-400 pt-0.5">
                        <span>Waktu</span>
                        <span>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>

                <!-- Tabel Item Menu -->
                <div class="my-4">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-dashed border-slate-300 text-slate-500 text-xs">
                                <th class="pb-2 font-bold w-1/2">Menu Item</th>
                                <th class="pb-2 text-center font-bold">Qty</th>
                                @if (!in_array(request('type'), ['checker', 'kitchen']))
                                    <th class="pb-2 text-right font-bold">Harga</th>
                                    <th class="pb-2 text-right font-bold">Total</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 divide-dashed">
                            @foreach ($transaction->items as $item)
                                <tr>
                                    <td class="py-2.5 pr-1 text-slate-900 break-words max-w-[140px]">
                                        <div class="font-medium">{{ $item->product->name }}</div>

                                        @if ($item->notes)
                                            <span
                                                class="block text-xs text-rose-600 font-bold mt-0.5 print:text-slate-800 font-sans italic">
                                                * Catatan: {{ $item->notes }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 text-center text-slate-900 font-bold text-base">
                                        {{ $item->quantity }}
                                    </td>

                                    @if (!in_array(request('type'), ['checker', 'kitchen']))
                                        <td class="py-2.5 text-right text-slate-600">
                                            {{ number_format($item->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-2.5 text-right font-semibold text-slate-900">
                                            {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Ringkasan Pembayaran & Diskon (Hanya untuk Mode Konsumen) -->
                @if (!in_array(request('type'), ['checker', 'kitchen']))
                    <div class="border-t-2 border-dashed border-slate-300 pt-4 space-y-2">

                        @if ($transaction->discount_amount > 0)
                            <div class="flex justify-between items-center text-sm text-slate-600">
                                <span>Subtotal</span>
                                <span>Rp
                                    {{ number_format($transaction->total_amount + $transaction->discount_amount, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center text-sm text-rose-600 font-bold border-b border-slate-100 pb-2 mb-2">
                                <span>Diskon
                                    ({{ $transaction->discount_type == 'percentage' ? $transaction->discount_value . '%' : 'Nominal' }})</span>
                                <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div
                            class="flex justify-between items-center text-base font-black border-b border-slate-100 pb-2">
                            <span class="text-slate-900">GRAND TOTAL</span>
                            <span class="text-slate-900 text-lg">Rp
                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-slate-600 pt-1">
                            <span>Jumlah Tunai / Bayar</span>
                            <span class="font-medium">Rp
                                {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center text-emerald-700 font-bold bg-emerald-50/60 px-2 py-1.5 rounded-lg">
                            <span>Kembalian Tunai</span>
                            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-slate-500 pt-1">
                            <span>Metode Pembayaran</span>
                            <span
                                class="uppercase font-bold tracking-wider bg-slate-100 px-2 py-0.5 rounded text-slate-700">
                                {{ $transaction->payment_method }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Footer Struk -->
                <div class="text-center text-slate-400 text-xs mt-8 pt-4 border-t border-dashed border-slate-200">
                    @if (request('type') == 'checker')
                        <p class="font-bold text-slate-600 tracking-wider">[ RE-CHECK SEBELUM SERVED ]</p>
                    @elseif(request('type') == 'kitchen')
                        <p class="font-bold text-slate-600 tracking-wider">[ ANTRIAN ORDER DAPUR ]</p>
                    @else
                        <p class="font-semibold text-slate-500">Terima Kasih!</p>
                        <p class="mt-0.5">Silakan datang kembali & nikmati menu kami.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Script Cetak -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>

    <!-- Style Print -->
    <style media="print">
        body * {
            visibility: hidden;
            background: transparent !important;
        }

        #receipt-content,
        #receipt-content * {
            visibility: visible;
        }

        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
        }

        @page {
            margin: 0;
            size: auto;
        }

        body {
            margin: 0rem;
            padding: 0rem;
        }
    </style>
</x-app-layout>
