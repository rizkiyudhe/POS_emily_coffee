<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Meja') }}
            </h2>
            <a href="{{ route('tables.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                + Tambah Meja
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tables as $table)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                Meja {{ $table->table_number }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $table->capacity }} orang
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'occupied' => 'bg-red-100 text-red-800',
                                        'reserved' => 'bg-yellow-100 text-yellow-800',
                                    ];
                                    $statusLabels = [
                                        'available' => 'Tersedia',
                                        'occupied' => 'Terisi',
                                        'reserved' => 'Dipesan',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$table->status] }}">
                                    {{ $statusLabels[$table->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('tables.edit', $table) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form action="{{ route('tables.destroy', $table) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus meja ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada meja. Silakan tambah meja.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tables->links() }}
            </div>
        </div>
    </div>
</x-app-layout>