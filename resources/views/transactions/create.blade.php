<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transaksi Baru') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                ← Batal
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-lg p-4">
                    <label class="block text-gray-700 font-medium mb-2">Pilih Meja</label>
                    <select id="table_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Take Away (Tanpa Meja)</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table->id }}">Meja {{ $table->table_number }} (Kapasitas
                                {{ $table->capacity }} orang)</option>
                        @endforeach
                    </select>
                    <!-- Order Type -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Tipe Pesanan</label>
                        <div class="flex gap-4">
                            <label><input type="radio" name="order_type" value="dine_in" checked> Dine In</label>
                            <label><input type="radio" name="order_type" value="takeaway"> Take Away</label>
                        </div>
                    </div>

                    <!-- Diskon -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Diskon</label>
                        <div class="flex gap-2">
                            <select id="discount_type" class="border rounded w-32">
                                <option value="">Tanpa Diskon</option>
                                <option value="nominal">Rp (Nominal)</option>
                                <option value="percentage">% (Persen)</option>
                            </select>
                            <input type="number" id="discount_value" placeholder="Nilai" class="border rounded w-32"
                                disabled>
                        </div>
                    </div>

                    <!-- Catatan Global -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Catatan Transaksi</label>
                        <textarea id="global_notes" rows="2" class="w-full border rounded"></textarea>
                    </div>

                </div>


                <div class="bg-white rounded-2xl shadow-lg p-5 space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1.5">Cari Produk</label>
                        <div class="relative">
                            <input type="text" id="search-product"
                                placeholder="Ketik nama atau SKU... (Gunakan ↑↓ dan Enter)" autocomplete="off"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div id="autocomplete-results"
                                class="absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Filter Kategori</label>
                        <div id="category-filters" class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-0.5">
                            <button type="button" data-category="all"
                                class="category-btn px-4 py-1.5 bg-blue-600 text-white rounded-lg shadow text-xs font-semibold transition">
                                Semua
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Daftar Produk</label>
                        <div id="product-cards-grid"
                            class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-[380px] overflow-y-auto p-1 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="text-center col-span-full py-8 text-gray-400 text-sm">Memuat daftar produk...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Keranjang Belanja</h3>
                    <div id="cart-container">
                        @include('transactions.partials.cart_table', ['cart' => $cart, 'total' => $total])
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-4 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Ringkasan Pembayaran</h3>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Belanja</span>
                            <span id="total-amount" class="font-bold text-gray-800">Rp
                                {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Jumlah Bayar</span>
                            <input type="number" id="paid-amount" placeholder="Masukkan jumlah"
                                class="w-36 text-right border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Kembalian</span>
                            <span id="change-amount" class="font-bold text-green-600">Rp 0</span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Metode Pembayaran</label>
                        <select id="payment-method" class="w-full border-gray-300 rounded-lg shadow-sm">
                            <option value="cash">Tunai</option>
                            <option value="qris">QRIS</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>
                    <button id="process-payment"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow transition">
                        Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // ------------------------------------------------------------------
            // 1. Manajemen Data Produk, Kategori, & Card View Grid (Lokal)
            // ------------------------------------------------------------------
            const searchInput = document.getElementById('search-product');
            const resultsDiv = document.getElementById('autocomplete-results');
            let currentFocus = -1;
            let productsData = []; // Menyimpan data hasil pencarian aktif

            // AMBIL DATA DARI CONTROLLER LARAVEL
            let allProducts = @json($products ?? []);
            let selectedCategory = 'all';

            function loadInitialProducts() {
                if (allProducts.length === 0) {
                    document.getElementById('product-cards-grid').innerHTML =
                        '<div class="text-center col-span-full py-8 text-gray-400 text-sm">Tidak ada data produk tersedia.</div>';
                    return;
                }
                renderCategoryFilters(allProducts);
                renderProductCards(allProducts);
            }

            // Generate filter kategori unik secara otomatis
            function renderCategoryFilters(products) {
                const filterContainer = document.getElementById('category-filters');
                const categoriesMap = new Map();

                products.forEach(p => {
                    if (p.category_id && p.category_name) {
                        categoriesMap.set(p.category_id, p.category_name);
                    }
                });

                let html =
                    `<button type="button" data-category="all" class="category-btn px-4 py-1.5 bg-blue-600 text-white rounded-lg shadow text-xs font-semibold transition">Semua</button>`;
                categoriesMap.forEach((name, id) => {
                    html +=
                        `<button type="button" data-category="${id}" class="category-btn px-4 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition">${escapeHtml(name)}</button>`;
                });
                filterContainer.innerHTML = html;

                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.category-btn').forEach(b => {
                            b.classList.remove('bg-blue-600', 'text-white', 'shadow');
                            b.classList.add('bg-gray-200', 'text-gray-700');
                        });
                        this.classList.add('bg-blue-600', 'text-white', 'shadow');
                        this.classList.remove('bg-gray-200', 'text-gray-700');

                        selectedCategory = this.getAttribute('data-category');
                        filterAndRenderCards();
                    });
                });
            }

            // Render Produk ke Bentuk Card Grid
            function renderProductCards(products) {
                const grid = document.getElementById('product-cards-grid');
                if (!products.length) {
                    grid.innerHTML =
                        '<div class="text-center col-span-full py-8 text-gray-400 text-sm">Tidak ada produk yang cocok</div>';
                    return;
                }

                let html = '';
                products.forEach(prod => {
                    html += `
                    <div class="product-card bg-white hover:bg-blue-50/30 border border-gray-200 rounded-xl p-3 flex flex-col justify-between shadow-sm cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300 group" data-id="${prod.id}">
                        <div>
                            <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-medium inline-block mb-1.5">${escapeHtml(prod.category_name)}</span>
                            <div class="font-bold text-gray-800 text-xs sm:text-sm line-clamp-2 group-hover:text-blue-600 transition-colors">${escapeHtml(prod.name)}</div>
                            <div class="text-[11px] text-gray-400 mt-1 font-mono">SKU: ${escapeHtml(prod.sku || '-')}</div>
                        </div>
                        <div class="mt-3 flex items-center justify-between pt-2 border-t border-gray-50">
                            <span class="font-extrabold text-gray-900 text-xs sm:text-sm">Rp ${prod.price.toLocaleString('id-ID')}</span>
                            <div class="bg-blue-600 group-hover:bg-blue-700 text-white rounded-lg p-1.5 shadow-sm transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                `;
                });
                grid.innerHTML = html;

                document.querySelectorAll('.product-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        if (id) {
                            addToCart(id, 1);
                            clearSearch();
                        }
                    });
                });
            }

            // Gabungan Filter Kategori & Input Ketik untuk Card Grid
            function filterAndRenderCards() {
                const query = searchInput.value.toLowerCase().trim();
                const filtered = allProducts.filter(prod => {
                    const matchesCategory = (selectedCategory === 'all' || String(prod.category_id) ===
                        selectedCategory);
                    const matchesSearch = !query ||
                        (prod.name && prod.name.toLowerCase().includes(query)) ||
                        (prod.sku && prod.sku.toLowerCase().includes(query));
                    return matchesCategory && matchesSearch;
                });
                renderProductCards(filtered);
            }

            // ------------------------------------------------------------------
            // 2. Fungsi Autocomplete Dropdown Lokal (Arrow & Enter Aktif)
            // ------------------------------------------------------------------
            function localSearchAutocomplete(query) {
                if (query.length < 2) {
                    hideResults();
                    return;
                }

                productsData = allProducts.filter(prod => {
                    return (prod.name && prod.name.toLowerCase().includes(query)) ||
                        (prod.sku && prod.sku.toLowerCase().includes(query));
                });

                renderAutocompleteDropdown(productsData);
            }

            function renderAutocompleteDropdown(products) {
                if (!products.length) {
                    resultsDiv.innerHTML = '<div class="p-2 text-gray-500 text-center">Produk tidak ditemukan</div>';
                    resultsDiv.classList.remove('hidden');
                    return;
                }
                let html = '';
                products.forEach((prod, idx) => {
                    html += `
                    <div class="autocomplete-item px-4 py-2 hover:bg-gray-100 cursor-pointer ${idx === currentFocus ? 'bg-gray-100' : ''}"
                         data-id="${prod.id}">
                        <div class="font-medium">${escapeHtml(prod.name)}</div>
                        <div class="text-sm text-gray-500">SKU: ${escapeHtml(prod.sku)} | Harga: Rp ${prod.price.toLocaleString('id-ID')}</div>
                    </div>
                `;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.classList.remove('hidden');

                document.querySelectorAll('.autocomplete-item').forEach((el, idx) => {
                    el.addEventListener('click', () => {
                        const id = el.getAttribute('data-id');
                        if (id) {
                            addToCart(id, 1);
                            clearSearch();
                        }
                    });
                    el.addEventListener('mouseenter', () => setCurrentFocus(idx));
                });
            }

            function setCurrentFocus(idx) {
                currentFocus = idx;
                document.querySelectorAll('.autocomplete-item').forEach((el, i) => {
                    if (i === idx) el.classList.add('bg-gray-100');
                    else el.classList.remove('bg-gray-100');
                });
                const active = document.querySelector(`.autocomplete-item:nth-child(${idx + 1})`);
                if (active) active.scrollIntoView({
                    block: 'nearest'
                });
            }

            function hideResults() {
                resultsDiv.classList.add('hidden');
                currentFocus = -1;
            }

            function clearSearch() {
                searchInput.value = '';
                hideResults();
                filterAndRenderCards();
            }

            function escapeHtml(str) {
                if (!str) return '';
                return str.replace(/[&<>]/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;'
                } [m]));
            }

            // Event Listeners Input
            searchInput.addEventListener('input', function(e) {
                const val = e.target.value.toLowerCase().trim();
                filterAndRenderCards();
                localSearchAutocomplete(val);
            });

            // Navigasi Keyboard (Arrow Down, Up, Enter)
            searchInput.addEventListener('keydown', function(e) {
                const total = document.querySelectorAll('.autocomplete-item').length;
                if (total === 0) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentFocus < total - 1) setCurrentFocus(currentFocus + 1);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentFocus > 0) setCurrentFocus(currentFocus - 1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const selected = document.querySelector(
                        `.autocomplete-item:nth-child(${currentFocus + 1})`);
                    if (selected) {
                        const id = selected.getAttribute('data-id');
                        if (id) {
                            addToCart(id, 1);
                            clearSearch();
                        }
                    }
                } else if (e.key === 'Escape') {
                    hideResults();
                }
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) hideResults();
            });

            // ------------------------------------------------------------------
            // 3. Fungsi Cart (add, update, remove, refresh)
            // ------------------------------------------------------------------
            function addToCart(productId, quantity = 1) {
                fetch('{{ route('transactions.add-to-cart') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) refreshCart();
                        else alert('Gagal menambahkan produk');
                    })
                    .catch(err => console.error(err));
            }

            function updateCart(productId, quantity) {
                fetch('{{ route('transactions.update-cart') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) refreshCart();
                    });
            }

            function removeFromCart(productId) {
                const url = `/transactions/remove-from-cart/${productId}`;
                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) refreshCart();
                        else alert('Gagal hapus item');
                    })
                    .catch(err => console.error(err));
            }

            function refreshCart() {
                fetch('{{ route('transactions.cart-data') }}')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('cart-container').innerHTML = data.html;
                        document.getElementById('total-amount').innerText = 'Rp ' + data.total.toLocaleString(
                            'id-ID');
                        attachCartEvents();
                        calculateChange();
                    });
            }

            function attachCartEvents() {
                document.querySelectorAll('.cart-qty').forEach(input => {
                    input.removeEventListener('change', handleQuantityChange);
                    input.addEventListener('change', handleQuantityChange);
                });
                document.querySelectorAll('.remove-item').forEach(btn => {
                    btn.removeEventListener('click', handleRemoveClick);
                    btn.addEventListener('click', handleRemoveClick);
                });
            }

            function handleQuantityChange(e) {
                let id = e.target.getAttribute('data-id');
                let qty = parseInt(e.target.value);
                if (isNaN(qty) || qty < 1) qty = 1;
                updateCart(id, qty);
            }

            function handleRemoveClick(e) {
                let id = e.target.getAttribute('data-id');
                removeFromCart(id);
            }

            // ------------------------------------------------------------------
            // 4. Diskon: enable/disable input diskon
            // ------------------------------------------------------------------
            const discountTypeSelect = document.getElementById('discount_type');
            const discountValueInput = document.getElementById('discount_value');

            if (discountTypeSelect && discountValueInput) {
                discountTypeSelect.addEventListener('change', function() {
                    discountValueInput.disabled = !this.value;
                });
                // Trigger awal
                discountValueInput.disabled = !discountTypeSelect.value;
            }

            // ------------------------------------------------------------------
            // 5. Pembayaran dan Hitung Kembalian (dengan tambahan data order_type, diskon, catatan)
            // ------------------------------------------------------------------
            const paidInput = document.getElementById('paid-amount');
            const changeSpan = document.getElementById('change-amount');

            function calculateChange() {
                let totalText = document.getElementById('total-amount').innerText;
                let total = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;
                let paid = parseInt(paidInput.value) || 0;
                let change = paid - total;
                changeSpan.innerText = 'Rp ' + (change > 0 ? change.toLocaleString('id-ID') : '0');
            }

            paidInput.addEventListener('input', calculateChange);

            document.getElementById('process-payment').addEventListener('click', function() {
                let tableId = document.getElementById('table_id').value;
                let orderType = document.querySelector('input[name="order_type"]:checked')?.value || 'dine_in';
                let discountType = discountTypeSelect ? discountTypeSelect.value : '';
                let discountValue = discountValueInput ? (parseInt(discountValueInput.value) || 0) : 0;
                let notes = document.getElementById('global_notes') ? document.getElementById('global_notes')
                    .value : '';
                let paymentMethod = document.getElementById('payment-method').value;
                let paidAmount = parseInt(paidInput.value) || 0;
                let totalText = document.getElementById('total-amount').innerText;
                let total = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;

                if (paidAmount < total) {
                    alert('Pembayaran kurang!');
                    return;
                }

                fetch('{{ route('transactions.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            table_id: tableId,
                            order_type: orderType,
                            discount_type: discountType,
                            discount_value: discountValue,
                            notes: notes,
                            payment_method: paymentMethod,
                            paid_amount: paidAmount
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memproses transaksi');
                    });
            });

            // Inisialisasi awal
            loadInitialProducts();
            attachCartEvents();
            calculateChange(); // hitung awal
        })();
    </script>
</x-app-layout>
