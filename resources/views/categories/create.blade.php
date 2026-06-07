<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Kategori') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Makanan, Minuman, Snack" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Deskripsi (Opsional)</label>
                        <textarea name="description" rows="3" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>