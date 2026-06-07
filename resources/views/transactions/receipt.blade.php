<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Struk Transaksi') }}
            </h2>
            <div class="flex flex-wrap gap-2">
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                    🖨️ Cetak Halaman
                </button>

                @can('reprint receipt')
                <form action="{{ route('transactions.reprint-customer', $transaction) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        🧾 Cetak Konsumen
                    </button>
                </form>
                @endcan

                @can('reprint kot')
                <form action="{{ route('transactions.reprint-checker', $transaction) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        🔍 Cetak Checker
                    </button>
                </form>
                <form action="{{ route('transactions.reprint-kitchen', $transaction) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                        🍳 Cetak Dapur
                    </button>
                </form>
                @endcan

                <a href="{{ route('transactions.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                    + Transaksi Baru
                </a>
                <a href="{{ route('transactions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                    ← Riwayat
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Struk Paper Style -->
            <div class="p-6" id="receipt-content">
                <div class="text-center border-b pb-4">
                    <h2 class="font-bold text-xl">COFFEE SHOP POS</h2>
                    <p class="text-sm text-gray-600">Jl. Contoh No. 123</p>
                    <p class="text-sm text-gray-600">Telp: 08123456789</p>
                </div>

                <div class="mt-4 space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span>Invoice:</span>
                        <span class="font-mono">{{ $transaction->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Queue:</span>
                        <span>{{ $transaction->queue_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Meja:</span>
                        <span>{{ $transaction->table->table_number ?? 'Take Away' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Kasir:</span>
                        <span>{{ $transaction->cashier->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal:</span>
                        <span>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</span>
                    </div>
                </div>

                <div class="border-t border-b my-4 py-3">
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="text-left">Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Harga</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                            <tr>
                                <td class="py-1">{{ $item->product->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between font-semibold">
                        <span>Total:</span>
                        <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Bayar:</span>
                        <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Kembalian:</span>
                        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Metode:</span>
                        <span class="uppercase">{{ $transaction->payment_method }}</span>
                    </div>
                </div>

                <div class="text-center text-sm text-gray-500 mt-6 pt-4 border-t">
                    Terima Kasih!<br>
                    Silakan datang kembali.
                </div>
            </div>
        </div>
    </div>

    <style media="print">
        /* Sembunyikan semua elemen halaman kecuali area struk */
        body * {
            visibility: hidden;
        }
        #receipt-content, #receipt-content * {
            visibility: visible;
        }
        #receipt-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 10px;
            box-shadow: none;
            background: white;
        }
        .max-w-md {
            max-width: 100%;
        }
        /* Sembunyikan tombol dan navigasi saat print */
        button, .space-x-2 a, .flex-wrap .bg-blue-600, .flex-wrap form, .flex-wrap .bg-green-600, .flex-wrap .bg-gray-600 {
            display: none;
        }
        /* Pastikan tidak ada margin/page break */
        @page {
            margin: 0;
            size: auto;
        }
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</x-app-layout>