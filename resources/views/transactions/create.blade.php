<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('Transaksi Baru') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-2.5 px-5 rounded-xl transition duration-200 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Batal
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="table_id" class="block text-slate-700 text-sm font-semibold mb-1.5">Pilih
                                    Meja</label>
                                <select id="table_id"
                                    class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                                    <option value="">Take Away (Tanpa Meja)</option>
                                    @foreach ($tables as $table)
                                        <option value="{{ $table->id }}">
                                            Meja {{ $table->table_number }} (Kapasitas {{ $table->capacity }} orang)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-slate-700 text-sm font-semibold mb-1.5">Diskon
                                    Transaksi</label>
                                <div class="flex gap-3">
                                    <select id="discount_type"
                                        class="w-1/2 border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                                        <option value="">Tanpa Diskon</option>
                                        <option value="nominal">Rp (Nominal)</option>
                                        <option value="percentage">% (Persen)</option>
                                    </select>
                                    <input type="number" id="discount_value" placeholder="Nilai..."
                                        class="w-1/2 border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm text-center disabled:bg-slate-50 disabled:text-slate-400"
                                        disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 space-y-5">
                        <div class="relative">
                            <label for="search-product" class="block text-slate-700 text-sm font-semibold mb-1.5">Cari
                                Produk</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <input type="text" id="search-product"
                                    placeholder="Ketik nama atau SKU menu... (Gunakan ↑↓ dan Enter)" autocomplete="off"
                                    class="pl-11 w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                            </div>
                            <div id="autocomplete-results"
                                class="absolute z-50 w-full bg-white border border-slate-200 rounded-xl shadow-xl mt-2 max-h-60 overflow-y-auto hidden divide-y divide-slate-100">
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Kategori
                                Menu</label>
                            <div id="category-filters" class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-0.5">
                                <button type="button" data-category="all"
                                    class="category-btn px-4 py-1.5 bg-blue-600 text-white rounded-xl shadow-sm text-xs font-semibold transition duration-150">
                                    Semua
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Daftar
                                Menu Tersedia</label>
                            <div id="product-cards-grid"
                                class="grid grid-cols-2 sm:grid-cols-3 gap-3.5 max-h-[380px] overflow-y-auto p-2 bg-slate-50/50 rounded-2xl border border-slate-100">
                                <div class="text-center col-span-full py-12 text-slate-400 text-sm">Memuat daftar
                                    produk...</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span>🛒 Keranjang Belanja</span>
                        </h3>
                        <div id="cart-container" class="overflow-hidden rounded-xl border border-slate-100">
                            @include('transactions.partials.cart_table', [
                                'cart' => $cart,
                                'total' => $total,
                            ])
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 md:p-6 sticky top-6 space-y-5">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2.5">Ringkasan
                            Pembayaran</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm text-slate-500">
                                <span>Total Belanja</span>
                                <span id="total-amount" class="text-xl font-extrabold text-slate-800">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center pt-2">
                                <label for="paid-amount" class="text-sm font-semibold text-slate-700">Jumlah
                                    Bayar</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-medium text-sm">Rp</span>
                                    <input type="number" id="paid-amount" placeholder="0"
                                        class="w-40 pl-9 text-right border-slate-300 text-slate-800 font-bold rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 py-2 text-sm">
                                </div>
                            </div>

                            <div class="flex justify-between items-center border-t border-slate-100 pt-3">
                                <span class="text-sm font-semibold text-slate-500">Kembalian</span>
                                <span id="change-amount" class="text-lg font-black text-emerald-600">Rp 0</span>
                            </div>
                        </div>

                        <div>
                            <label for="payment-method" class="block text-slate-700 text-sm font-semibold mb-1.5">Metode
                                Pembayaran</label>
                            <select id="payment-method"
                                class="w-full border-slate-300 text-slate-700 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition py-2.5 text-sm">
                                <option value="cash">💰 Tunai</option>
                                <option value="qris">📱 QRIS</option>
                                <option value="debit">💳 Debit Card</option>
                            </select>
                        </div>

                        <button id="process-payment"
                            class="w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-sm transition duration-200 hover:-translate-y-0.5">
                            Proses Transaksi Selesai
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function() {
            const searchInput = document.getElementById('search-product');
            const resultsDiv = document.getElementById('autocomplete-results');
            let currentFocus = -1;
            let productsData = [];

            let allProducts = @json($products ?? []);
            let selectedCategory = 'all';

            function loadInitialProducts() {
                if (allProducts.length === 0) {
                    document.getElementById('product-cards-grid').innerHTML =
                        '<div class="text-center col-span-full py-8 text-slate-400 text-sm">Tidak ada data produk tersedia.</div>';
                    return;
                }
                renderCategoryFilters(allProducts);
                renderProductCards(allProducts);
            }

            function renderCategoryFilters(products) {
                const filterContainer = document.getElementById('category-filters');
                const categoriesMap = new Map();

                products.forEach(p => {
                    if (p.category_id && p.category_name) {
                        categoriesMap.set(p.category_id, p.category_name);
                    }
                });

                let html =
                    `<button type="button" data-category="all" class="category-btn px-4 py-1.5 bg-blue-600 text-white rounded-xl shadow-sm text-xs font-semibold transition duration-150">Semua</button>`;
                categoriesMap.forEach((name, id) => {
                    html +=
                        `<button type="button" data-category="${id}" class="category-btn px-4 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition duration-150">${escapeHtml(name)}</button>`;
                });
                filterContainer.innerHTML = html;

                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.category-btn').forEach(b => {
                            b.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
                            b.classList.add('bg-slate-100', 'text-slate-600');
                        });
                        this.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
                        this.classList.remove('bg-slate-100', 'text-slate-600');

                        selectedCategory = this.getAttribute('data-category');
                        filterAndRenderCards();
                    });
                });
            }

            function renderProductCards(products) {
                const grid = document.getElementById('product-cards-grid');
                if (!products.length) {
                    grid.innerHTML =
                        '<div class="text-center col-span-full py-8 text-slate-400 text-sm">Tidak ada produk yang cocok</div>';
                    return;
                }

                let html = '';
                products.forEach(prod => {
                    html += `
                <div class="product-card bg-white border border-slate-200 rounded-2xl p-3.5 flex flex-col justify-between shadow-sm cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300 group" data-id="${prod.id}">
                    <div>
                        <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-bold inline-block mb-2">${escapeHtml(prod.category_name)}</span>
                        <div class="font-bold text-slate-800 text-xs sm:text-sm line-clamp-2 group-hover:text-blue-600 transition-colors">${escapeHtml(prod.name)}</div>
                        <div class="text-[11px] text-slate-400 mt-1 font-mono tracking-tight">SKU: ${escapeHtml(prod.sku || '-')}</div>
                    </div>
                    <div class="mt-4 flex items-center justify-between pt-2.5 border-t border-slate-50">
                        <span class="font-extrabold text-slate-900 text-xs sm:text-sm">Rp ${prod.price.toLocaleString('id-ID')}</span>
                        <div class="bg-blue-600 group-hover:bg-blue-700 text-white rounded-xl p-1.5 shadow-sm transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
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
                    resultsDiv.innerHTML =
                        '<div class="p-4 text-slate-500 text-center text-sm">Produk tidak ditemukan</div>';
                    resultsDiv.classList.remove('hidden');
                    return;
                }
                let html = '';
                products.forEach((prod, idx) => {
                    html += `
                <div class="autocomplete-item flex flex-col px-4 py-3 cursor-pointer transition-colors border-l-4 border-transparent hover:bg-slate-50"
                     data-id="${prod.id}" data-index="${idx}">
                    <div class="font-semibold text-slate-800 text-sm">${escapeHtml(prod.name)}</div>
                    <div class="text-xs text-slate-500 mt-0.5">SKU: <span class="font-mono bg-slate-100 px-1 rounded">${escapeHtml(prod.sku)}</span> | Harga: Rp ${prod.price.toLocaleString('id-ID')}</div>
                </div>
            `;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.classList.remove('hidden');

                document.querySelectorAll('.autocomplete-item').forEach((el) => {
                    el.addEventListener('click', () => {
                        const id = el.getAttribute('data-id');
                        if (id) {
                            addToCart(id, 1);
                            clearSearch();
                        }
                    });
                    el.addEventListener('mouseenter', function() {
                        const idx = parseInt(this.getAttribute('data-index'));
                        setCurrentFocus(idx);
                    });
                });
            }

            function setCurrentFocus(idx) {
                const itemsList = document.querySelectorAll('.autocomplete-item');
                if (!itemsList.length) return;

                itemsList.forEach((el) => {
                    el.classList.remove('bg-blue-50', 'border-blue-500');
                    el.classList.add('border-transparent');
                });

                currentFocus = idx;
                if (currentFocus >= itemsList.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (itemsList.length - 1);

                const active = itemsList[currentFocus];
                if (active) {
                    active.classList.remove('border-transparent');
                    active.classList.add('bg-blue-50', 'border-blue-500');
                    active.scrollIntoView({
                        block: 'nearest'
                    });
                }
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

            searchInput.addEventListener('input', function(e) {
                const val = e.target.value.toLowerCase().trim();
                filterAndRenderCards();
                localSearchAutocomplete(val);
            });

            searchInput.addEventListener('keydown', function(e) {
                const itemsList = document.querySelectorAll('.autocomplete-item');
                if (e.key === 'ArrowDown') {
                    if (resultsDiv.classList.contains('hidden') || itemsList.length === 0) return;
                    e.preventDefault();
                    setCurrentFocus(currentFocus + 1);
                } else if (e.key === 'ArrowUp') {
                    if (resultsDiv.classList.contains('hidden') || itemsList.length === 0) return;
                    e.preventDefault();
                    setCurrentFocus(currentFocus - 1);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (currentFocus > -1 && itemsList[currentFocus]) {
                        const id = itemsList[currentFocus].getAttribute('data-id');
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

            // AJAX: Tambah ke Keranjang Belanja
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

            // AJAX: Perbarui Keranjang (Ditambahkan variabel Catatan/Notes)
            function updateCart(productId, quantity, notesValue = '') {
                fetch('{{ route('transactions.update-cart') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity,
                            notes: notesValue // 👈 Mengirim catatan menu ke backend session
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) refreshCart();
                    });
            }

            // AJAX: Hapus Item Keranjang
            function removeFromCart(productId) {
                fetch(`/transactions/remove-from-cart/${productId}`, {
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

            // Event Listener Keranjang Belanja Baru (Mendukung input Catatan/Notes)
            function attachCartEvents() {
                document.querySelectorAll('.cart-qty').forEach(input => {
                    input.removeEventListener('change', handleQuantityChange);
                    input.addEventListener('change', handleQuantityChange);
                });

                // 👈 Menambahkan Event Listener untuk Kolom Catatan Menu
                document.querySelectorAll('.product-note').forEach(input => {
                    input.removeEventListener('change', handleNoteChange);
                    input.addEventListener('change', handleNoteChange);
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

                // Mengambil isi catatan aktif agar tidak hilang saat QTY diubah
                let noteInput = e.target.closest('tr').querySelector('.product-note');
                let notes = noteInput ? noteInput.value : '';

                updateCart(id, qty, notes);
            }

            // Handler saat kasir mengubah teks di kolom catatan
            function handleNoteChange(e) {
                let id = e.target.getAttribute('data-id');
                let notes = e.target.value;

                // Mengambil jumlah QTY aktif di baris yang sama
                let qtyInput = e.target.closest('tr').querySelector('.cart-qty');
                let qty = qtyInput ? parseInt(qtyInput.value) : 1;

                updateCart(id, qty, notes);
            }

            function handleRemoveClick(e) {
                let id = e.target.getAttribute('data-id');
                removeFromCart(id);
            }

            const discountTypeSelect = document.getElementById('discount_type');
            const discountValueInput = document.getElementById('discount_value');

            if (discountTypeSelect && discountValueInput) {
                discountTypeSelect.addEventListener('change', function() {
                    discountValueInput.disabled = !this.value;
                });
                discountValueInput.disabled = !discountTypeSelect.value;
            }

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

            // Handler submit pembayaran final ke server
            document.getElementById('process-payment').addEventListener('click', function() {
                let tableId = document.getElementById('table_id').value;
                let discountType = discountTypeSelect ? discountTypeSelect.value : '';
                let discountValue = discountValueInput ? (parseInt(discountValueInput.value) || 0) : 0;
                let paymentMethod = document.getElementById('payment-method').value;
                let paidAmount = parseInt(paidInput.value) || 0;
                let totalText = document.getElementById('total-amount').innerText;
                let total = parseInt(totalText.replace(/[^0-9]/g, '')) || 0;

                // Membaca input catatan transaksi global (jika ada elemen dengan id "transaction-notes")
                let globalNotesEl = document.getElementById('transaction-notes');
                let globalNotes = globalNotesEl ? globalNotesEl.value : '';

                let orderType = tableId ? 'dine_in' : 'takeaway';

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
                            notes: globalNotes, // Mengirim catatan global transaksi
                            payment_method: paymentMethod,
                            paid_amount: paidAmount
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) window.location.href = data.redirect;
                        else alert(data.message || 'Terjadi kesalahan');
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memproses transaksi');
                    });
            });

            loadInitialProducts();
            attachCartEvents();
            calculateChange();
        })();
    </script>
</x-app-layout>
