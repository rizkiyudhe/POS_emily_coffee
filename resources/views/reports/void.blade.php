<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">Laporan Transaksi Batal (Void)</h2>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Kotak Filter & Export -->
            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <form method="GET" action="{{ route('reports.void') }}"
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

            <!-- Tabel Data Void -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Waktu
                                    Pembatalan</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Invoice</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Nominal</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Kasir Awal
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Dibatalkan
                                    Oleh</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Alasan
                                    (Reason)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($voids as $void)
                                <tr class="hover:bg-rose-50/30 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($void->voided_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800 font-mono">
                                        {{ $void->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-rose-600">
                                        Rp {{ number_format($void->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $void->cashier->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                        {{ $void->voider->name ?? 'Admin' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 italic">
                                        "{{ $void->void_reason }}"
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Tidak ada transaksi yang dibatalkan pada periode ini.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
