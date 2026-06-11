<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Meja') }}
            </h2>
            <a href="{{ route('tables.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('tables.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nomor Meja <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="table_number" value="{{ old('table_number') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('table_number') border-red-500 @enderror"
                            placeholder="Contoh: 01, A1, 12" required>
                        @error('table_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Kapasitas (orang) <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', 4) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('capacity') border-red-500 @enderror"
                            min="1" required>
                        @error('capacity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Status</label>
                        <select name="status"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Dipesan
                            </option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            Simpan Meja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
