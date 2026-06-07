<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk') }}
            </h2>
            <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- SKU -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku') }}" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('sku') border-red-500 @enderror"
                                placeholder="Contoh: COF-001" required>
                            @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                                required>
                            @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Stok -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Stok Awal</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label class="ml-2 text-gray-700">Aktif</label>
                        </div>

                        <!-- Deskripsi (full width) -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-medium mb-2">Deskripsi (Opsional)</label>
                            <textarea name="description" rows="3" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>