<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Manajemen Meja') }}
            </h2>
            <a href="{{ route('tables.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Meja
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50/80 border-b border-slate-200">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">
                                    No. Meja</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">
                                    Kapasitas</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-1/4">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($tables as $table)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 bg-slate-100 text-slate-500 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 6V4.5a1.125 1.125 0 011.125-1.125h14.25A1.125 1.125 0 0120.25 4.5V6m-16.5 0a1.125 1.125 0 00-1.125 1.125v3.5m16.5-4.625v4.625m0 0a1.125 1.125 0 01-1.125 1.125H3.75m16.5-1.125v9a1.125 1.125 0 01-1.125 1.125H3.75a1.125 1.125 0 01-1.125-1.125V10.5m1.125 8.25h14.25" />
                                                </svg>
                                            </div>
                                            Meja {{ $table->table_number }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                            {{ $table->capacity }} Orang
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            // Mapping style status meja
                                            $statusStyles = [
                                                'available' => [
                                                    'bg' => 'bg-emerald-50',
                                                    'border' => 'border-emerald-200',
                                                    'text' => 'text-emerald-700',
                                                    'dot' => 'bg-emerald-500',
                                                ],
                                                'occupied' => [
                                                    'bg' => 'bg-rose-50',
                                                    'border' => 'border-rose-200',
                                                    'text' => 'text-rose-700',
                                                    'dot' => 'bg-rose-500',
                                                ],
                                                'reserved' => [
                                                    'bg' => 'bg-amber-50',
                                                    'border' => 'border-amber-200',
                                                    'text' => 'text-amber-700',
                                                    'dot' => 'bg-amber-500',
                                                ],
                                            ];
                                            $statusLabels = [
                                                'available' => 'Tersedia',
                                                'occupied' => 'Terisi',
                                                'reserved' => 'Dipesan',
                                            ];
                                            // Fallback jika status tidak dikenal
                                            $style = $statusStyles[$table->status] ?? [
                                                'bg' => 'bg-slate-50',
                                                'border' => 'border-slate-200',
                                                'text' => 'text-slate-700',
                                                'dot' => 'bg-slate-500',
                                            ];
                                            $label = $statusLabels[$table->status] ?? ucfirst($table->status);
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg border {{ $style['bg'] }} {{ $style['border'] }} {{ $style['text'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $style['dot'] }}"></span>
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap space-x-1">
                                        <a href="{{ route('tables.edit', $table) }}"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors group"
                                            title="Edit Meja">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor"
                                                class="w-5 h-5 group-hover:scale-105 transition-transform">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('tables.destroy', $table) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus meja ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition-colors group"
                                                title="Hapus Meja">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    class="w-5 h-5 group-hover:scale-105 transition-transform">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="p-4 bg-slate-50 text-slate-400 rounded-2xl border border-slate-100 mb-3">
                                                <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 6V4.5a1.125 1.125 0 011.125-1.125h14.25A1.125 1.125 0 0120.25 4.5V6m-16.5 0a1.125 1.125 0 00-1.125 1.125v3.5m16.5-4.625v4.625m0 0a1.125 1.125 0 01-1.125 1.125H3.75m16.5-1.125v9a1.125 1.125 0 01-1.125 1.125H3.75a1.125 1.125 0 01-1.125-1.125V10.5m1.125 8.25h14.25" />
                                                </svg>
                                            </div>
                                            <p class="text-slate-500 font-medium">Belum ada meja yang ditambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($tables->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $tables->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
