<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-sm transition-all duration-200 hover:-translate-y-0.5 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                <form method="GET" action="{{ route('products.index') }}" id="search-form"
                    class="flex flex-col md:flex-row gap-4 items-end">

                    <div class="flex-1 w-full relative">
                        <label for="search-input" class="block text-slate-700 text-sm font-semibold mb-1.5">Cari Produk
                            (Nama / SKU)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}"
                                placeholder="Ketik nama atau SKU..." autocomplete="off"
                                class="pl-10 w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition duration-200 py-2.5">
                        </div>

                        <div id="autocomplete-results"
                            class="absolute z-50 w-full bg-white border border-slate-200 rounded-xl shadow-xl mt-2 max-h-60 overflow-y-auto hidden divide-y divide-slate-100">
                        </div>
                    </div>

                    <div class="w-full md:w-56">
                        <label for="category-select"
                            class="block text-slate-700 text-sm font-semibold mb-1.5">Kategori</label>
                        <select name="category_id" id="category-select"
                            class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit"
                            class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2.5 px-6 rounded-xl shadow-sm transition duration-200">
                            Filter
                        </button>
                        <a href="{{ route('products.index') }}"
                            class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-6 rounded-xl transition duration-200">
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
                                    SKU</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Nama Produk</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Harga</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Stok</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Foto</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($products as $product)
                                <tr class="hover:bg-slate-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm text-slate-500 font-mono">{{ $product->sku }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $product->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-slate-800">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-800 font-medium">{{ $product->stock }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-bold rounded-lg border {{ $product->is_active ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-rose-50 border-rose-200 text-rose-700' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $product->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($product->photo)
                                            <img src="{{ Storage::url($product->photo) }}" alt="{{ $product->name }}"
                                                class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-slate-100 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 text-xs">
                                                N/A
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('products.edit', $product) }}"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST"
                                            class="inline-block ml-1"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
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
                                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                            </svg>
                                            <p class="text-slate-500 font-medium">Belum ada produk yang ditambahkan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($products->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        (function() {
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('autocomplete-results');
            const searchForm = document.getElementById('search-form');
            let currentFocus = -1;
            let items = [];

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            async function fetchProducts(query) {
                if (query.length < 2) {
                    hideAutocomplete();
                    return;
                }
                try {
                    // Pastikan URL API sudah sesuai dengan route kamu
                    const response = await fetch(`/products/search?q=${encodeURIComponent(query)}`);
                    if (!response.ok) throw new Error('Network error');
                    const data = await response.json();
                    items = data;
                    renderAutocomplete(items);
                } catch (err) {
                    console.error(err);
                    hideAutocomplete();
                }
            }

            function renderAutocomplete(products) {
                if (!products.length) {
                    resultsContainer.innerHTML =
                        '<div class="p-4 text-slate-500 text-center text-sm">Produk tidak ditemukan</div>';
                    resultsContainer.classList.remove('hidden');
                    return;
                }

                let html = '';
                products.forEach((product, idx) => {
                    html += `
                        <div class="autocomplete-item flex flex-col px-4 py-3 cursor-pointer transition-colors duration-150 border-l-4 border-transparent hover:bg-slate-50" 
                             data-id="${product.id}" data-index="${idx}">
                            <div class="font-semibold text-slate-800 text-sm">${escapeHtml(product.name)}</div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                <span class="font-mono bg-slate-100 px-1 py-0.5 rounded mr-1">${escapeHtml(product.sku)}</span> 
                                Rp ${product.price.toLocaleString('id-ID')}
                            </div>
                        </div>
                    `;
                });

                resultsContainer.innerHTML = html;
                resultsContainer.classList.remove('hidden');

                // Tambah Event Listeners per item
                document.querySelectorAll('.autocomplete-item').forEach((el) => {
                    el.addEventListener('click', () => {
                        const productId = el.getAttribute('data-id');
                        if (productId) window.location.href = `/products/${productId}/edit`;
                    });
                    el.addEventListener('mouseenter', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        setCurrentFocus(idx);
                    });
                });
            }

            // Fungsi visual untuk highligh item saat digerakkan dengan Arrow Keys
            function setCurrentFocus(index) {
                const itemsList = document.querySelectorAll('.autocomplete-item');
                if (!itemsList.length) return;

                // Hapus aktif dari semua
                itemsList.forEach((el) => {
                    el.classList.remove('bg-blue-50', 'border-blue-500');
                    el.classList.add('border-transparent');
                });

                currentFocus = index;

                // Tambah aktif ke index
                if (currentFocus >= itemsList.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (itemsList.length - 1);

                const active = itemsList[currentFocus];
                if (active) {
                    active.classList.remove('border-transparent', 'hover:bg-slate-50');
                    active.classList.add('bg-blue-50', 'border-blue-500');
                    active.scrollIntoView({
                        block: 'nearest'
                    });
                }
            }

            function hideAutocomplete() {
                resultsContainer.classList.add('hidden');
                currentFocus = -1;
            }

            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, function(m) {
                    if (m === '&') return '&amp;';
                    if (m === '<') return '&lt;';
                    if (m === '>') return '&gt;';
                    return m;
                });
            }

            const debouncedFetch = debounce((q) => fetchProducts(q), 300);

            searchInput.addEventListener('input', function(e) {
                const q = e.target.value;
                if (q.length === 0) hideAutocomplete();
                else debouncedFetch(q);
            });

            // Logika Keyboard: Arrow & Enter
            searchInput.addEventListener('keydown', function(e) {
                const itemsList = document.querySelectorAll('.autocomplete-item');

                if (e.key === 'ArrowDown') {
                    if (resultsContainer.classList.contains('hidden') || itemsList.length === 0) return;
                    e.preventDefault();
                    setCurrentFocus(currentFocus + 1);
                } else if (e.key === 'ArrowUp') {
                    if (resultsContainer.classList.contains('hidden') || itemsList.length === 0) return;
                    e.preventDefault();
                    setCurrentFocus(currentFocus - 1);
                } else if (e.key === 'Enter') {
                    e.preventDefault(); // Cegah default submit dulu

                    if (currentFocus > -1 && itemsList[currentFocus]) {
                        // Jika ada item dropdown yang sedang tersorot, pergi ke edit produk tsb
                        const productId = itemsList[currentFocus].getAttribute('data-id');
                        if (productId) window.location.href = `/products/${productId}/edit`;
                    } else {
                        // Jika TIDAK ADA item dropdown yang disorot, lakukan pencarian (Submit form filter)
                        searchForm.submit();
                    }
                } else if (e.key === 'Escape') {
                    hideAutocomplete();
                }
            });

            // Tutup dropdown jika klik diluar
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    hideAutocomplete();
                }
            });
        })();
    </script>
</x-app-layout>
