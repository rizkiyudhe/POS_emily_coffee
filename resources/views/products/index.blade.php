<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Produk') }}
            </h2>
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                + Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <!-- Filter & Search dengan Autocomplete -->
        <div class="mb-6 bg-white rounded-2xl shadow-lg p-4">
            <form method="GET" action="{{ route('products.index') }}" id="search-form" class="flex flex-wrap gap-4 items-end">
                <!-- Autocomplete wrapper -->
                <div class="flex-1 min-w-[200px] relative">
                    <label class="block text-gray-700 text-sm font-medium mb-1">Cari Produk (Nama / SKU)</label>
                    <input type="text" name="search" id="search-input" value="{{ request('search') }}" 
                        placeholder="Ketik nama atau SKU..." autocomplete="off"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Autocomplete dropdown -->
                    <div id="autocomplete-results" class="absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                </div>

                <div class="w-48">
                    <label class="block text-gray-700 text-sm font-medium mb-1">Kategori</label>
                    <select name="category_id" id="category-select" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg shadow transition">
                        🔍 Filter
                    </button>
                    <a href="{{ route('products.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg shadow transition ml-2">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->sku }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada produk. Silakan tambah produk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Script Autocomplete -->
    <script>
        (function() {
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('autocomplete-results');
            let currentFocus = -1;
            let items = [];

            // Fungsi debounce
            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(this, args), delay);
                };
            }

            // Fetch dari endpoint /products/search?q=...
            async function fetchProducts(query) {
                if (query.length < 2) {
                    hideAutocomplete();
                    return;
                }
                try {
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

            // Render dropdown
            function renderAutocomplete(products) {
                if (!products.length) {
                    resultsContainer.innerHTML = '<div class="p-2 text-gray-500 text-center">Produk tidak ditemukan</div>';
                    resultsContainer.classList.remove('hidden');
                    return;
                }
                let html = '';
                products.forEach((product, idx) => {
                    html += `
                        <div class="autocomplete-item px-4 py-2 hover:bg-gray-100 cursor-pointer ${idx === currentFocus ? 'bg-gray-100' : ''}" 
                             data-id="${product.id}" data-name="${escapeHtml(product.name)}" data-sku="${escapeHtml(product.sku)}">
                            <div class="font-medium">${escapeHtml(product.name)}</div>
                            <div class="text-sm text-gray-500">SKU: ${escapeHtml(product.sku)} | Harga: Rp ${product.price.toLocaleString()}</div>
                        </div>
                    `;
                });
                resultsContainer.innerHTML = html;
                resultsContainer.classList.remove('hidden');

                // Attach click event to each item
                document.querySelectorAll('.autocomplete-item').forEach((el, idx) => {
                    el.addEventListener('click', () => {
                        const productId = el.getAttribute('data-id');
                        if (productId) window.location.href = `/products/${productId}/edit`;
                    });
                    el.addEventListener('mouseenter', () => setCurrentFocus(idx));
                });
            }

            function setCurrentFocus(index) {
                currentFocus = index;
                const items = document.querySelectorAll('.autocomplete-item');
                items.forEach((el, i) => {
                    if (i === index) el.classList.add('bg-gray-100');
                    else el.classList.remove('bg-gray-100');
                });
                // Scroll aktif ke view
                const active = items[index];
                if (active) active.scrollIntoView({ block: 'nearest' });
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

            // Event listeners
            const debouncedFetch = debounce((q) => fetchProducts(q), 300);
            searchInput.addEventListener('input', function(e) {
                const q = e.target.value;
                if (q.length === 0) hideAutocomplete();
                else debouncedFetch(q);
            });

            searchInput.addEventListener('keydown', function(e) {
                const totalItems = document.querySelectorAll('.autocomplete-item').length;
                if (totalItems === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentFocus < totalItems - 1) setCurrentFocus(currentFocus + 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentFocus > 0) setCurrentFocus(currentFocus - 1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const activeItem = document.querySelector(`.autocomplete-item:nth-child(${currentFocus + 1})`);
                    if (activeItem) {
                        const productId = activeItem.getAttribute('data-id');
                        if (productId) window.location.href = `/products/${productId}/edit`;
                    } else {
                        // Jika tidak ada item terpilih, submit form filter
                        document.getElementById('search-form').submit();
                    }
                } else if (e.key === 'Escape') {
                    hideAutocomplete();
                }
            });

            // Sembunyikan dropdown saat klik di luar
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                    hideAutocomplete();
                }
            });
        })();
    </script>
</x-app-layout>