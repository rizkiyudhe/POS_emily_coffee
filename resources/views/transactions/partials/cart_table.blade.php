<div id="cart-content">
    @if (count($cart) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($cart as $id => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $item['name'] }}</td>
                            <td class="px-4 py-2 text-center">
                                <input type="number" value="{{ $item['quantity'] }}" min="1"
                                    class="cart-qty w-20 text-center border-gray-300 rounded-md shadow-sm"
                                    data-id="{{ $id }}">
                            </td>
                            <td class="px-4 py-2">
                                <input type="text" class="product-note w-full text-xs border-gray-300 rounded"
                                    data-id="{{ $id }}" value="{{ $item['notes'] ?? '' }}"
                                    placeholder="Catatan (ex: no ice)">
                            </td>
                            <td class="px-4 py-2 text-right text-sm text-gray-900">Rp
                                {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">
                                <button class="remove-item text-red-600 hover:text-red-800"
                                    data-id="{{ $id }}">✖</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right font-bold text-gray-800">
            Total: Rp {{ number_format($total, 0, ',', '.') }}
        </div>
    @else
        <div class="text-center text-gray-500 py-4">Keranjang kosong. Silakan tambah produk.</div>
    @endif
</div>
