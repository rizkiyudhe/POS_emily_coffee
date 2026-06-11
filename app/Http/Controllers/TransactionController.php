<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Table;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Services\CartService;
use App\Helpers\TransactionHelper;
use Illuminate\Http\Request;
use App\Helpers\TransactionCalculator;
use App\Models\StoreSetting;

class TransactionController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index(Request $request)
    {
        $query = Transaction::with('table', 'cashier');

        // Filter tanggal
        if ($request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Pencarian invoice atau queue
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhere('queue_number', 'like', "%{$request->search}%");
            });
        }

        $transactions = $query->orderBy('id', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        $cart = $this->cart->getCart();
        $total = $this->cart->getTotal();

        // Ambil semua produk untuk dilempar ke Blade
        $products = Product::with('category')->get()->map(function ($prod) {
            return [
                'id' => $prod->id,
                'name' => $prod->name,
                'sku' => $prod->sku,
                'price' => $prod->price,
                'category_id' => $prod->category_id ?? '',
                'category_name' => $prod->category->name ?? 'Umum',
            ];
        });

        return view('transactions.create', compact('tables', 'cart', 'total', 'products'));
    }

    // TIMPA FUNGSI INI
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $this->cart->addItem($request->product_id, $request->quantity);

        return response()->json(['success' => true]);
    }

    // TIMPA FUNGSI INI
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string'
        ]);

        // 1. Update jumlah pesanan
        $this->cart->updateQuantity($request->product_id, $request->quantity);

        // 2. Update catatan menggunakan fungsi bawaan CartService kamu
        if ($request->has('notes')) {
            $this->cart->updateNote($request->product_id, $request->notes ?? '');
        }

        return response()->json(['success' => true]);
    }

    public function removeFromCart($productId)
    {
        $this->cart->removeItem($productId);
        return response()->json(['success' => true]);
    }

    public function cartData()
    {
        $cart = $this->cart->getCart();
        $total = $this->cart->getTotal();
        $html = view('transactions.partials.cart_table', compact('cart', 'total'))->render();
        return response()->json(['html' => $html, 'total' => $total]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'         => 'nullable|exists:tables,id',
            'order_type'       => 'required|in:dine_in,takeaway',
            'payment_method'   => 'required|in:cash,qris,debit',
            'paid_amount'      => 'required|integer|min:0',
            'discount_type'    => 'nullable|in:nominal,percentage',
            'discount_value'   => 'nullable|integer|min:0',
            'notes'            => 'nullable|string',
        ]);

        $cart = $this->cart->getCart();
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart kosong!'], 400);
        }

        //return response()->json(['success' => false, 'message' => json_encode($cart)]);

        $subtotal = $this->cart->getTotal();
        $discountType = $request->discount_type;
        $discountValue = $request->discount_value ?? 0;

        // Ambil konfigurasi pajak dan service dari database
        $taxPercentage = (float) StoreSetting::get('tax_percentage', 11);
        $servicePercentage = (float) StoreSetting::get('service_percentage', 5);
        $enableTax = StoreSetting::get('enable_tax', true);
        $enableService = StoreSetting::get('enable_service', true);

        $tax = $enableTax ? $taxPercentage : 0;
        $service = $enableService ? $servicePercentage : 0;

        $calc = TransactionCalculator::calculate($subtotal, $discountType, $discountValue, $tax, $service);
        $grandTotal = $calc['grand_total'];

        $paid = $request->paid_amount;
        if ($paid < $grandTotal) {
            return response()->json(['success' => false, 'message' => 'Pembayaran kurang!'], 400);
            return response()->json(['success' => false, 'message' => 'Pembayaran kurang!'], 400);
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $transaction = Transaction::create([
            'invoice_number'     => TransactionHelper::generateInvoiceNumber(),
            'queue_number'       => TransactionHelper::generateQueueNumber(),
            'table_id'           => $request->table_id,
            'order_type'         => $request->order_type,
            'user_id'            => $user->id,
            'total_amount'       => $grandTotal,
            'paid_amount'        => $paid,
            'change_amount'      => $paid - $grandTotal,
            'payment_method'     => $request->payment_method,
            'status'             => 'completed',
            'transaction_date'   => now(),
            'notes'              => $request->notes, // Catatan global transaksi
            'discount_type'      => $discountType,
            'discount_value'     => $discountValue,
            'discount_amount'    => $calc['discount_amount'],
            'tax_percentage'     => $tax,
            'tax_amount'         => $calc['tax_amount'],
            'service_percentage' => $service,
            'service_amount'     => $calc['service_amount'],
        ]);

        // Ambil data session murni untuk menyelamatkan custom notes tiap menu
        $sessionCart = session()->get('cart', []);

        foreach ($cart as $id => $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id'     => $id,
                'quantity'       => $item['quantity'],
                'price'          => $item['price'],
                'subtotal'       => $item['subtotal'],
                'notes'          => isset($item['notes']) && $item['notes'] !== '' ? $item['notes'] : null,
            ]);
            Product::find($id)->decrement('stock', $item['quantity']);
        }

        if ($request->table_id && $request->order_type == 'dine_in') {
            Table::find($request->table_id)->update(['status' => 'occupied']);
        }

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'create transaction',
            'description' => "Transaksi {$transaction->invoice_number}",
            'ip_address'  => $request->ip(),
        ]);

        $this->cart->clearCart();

        return response()->json(['success' => true, 'redirect' => route('transactions.receipt', $transaction)]);
    }

    public function void(Transaction $transaction, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->hasPermissionTo('void transactions')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['reason' => 'required|string|min:3']);

        if ($transaction->status === 'void') {
            return back()->with('error', 'Transaksi sudah void.');
        }

        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        $transaction->update([
            'status'      => 'void',
            'void_reason' => $request->reason,
            'voided_by'   => $user->id,
            'voided_at'   => now(),
        ]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'void transaction',
            'description' => "Void transaksi {$transaction->invoice_number} dengan alasan: {$request->reason}",
            'ip_address'  => $request->ip(),
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil divoid.');
    }

    public function receipt(Transaction $transaction)
    {
        return view('transactions.receipt', compact('transaction'));
    }

    public function reprintCustomer(Transaction $transaction)
    {
        return redirect()->route('transactions.receipt', [$transaction, 'type' => 'customer'])
            ->with('success', 'Silakan cetak ulang struk konsumen melalui browser.');
    }

    public function reprintChecker(Transaction $transaction)
    {
        return redirect()->route('transactions.receipt', [$transaction, 'type' => 'checker'])
            ->with('success', 'Silakan cetak ulang struk checker melalui browser.');
    }

    public function reprintKitchen(Transaction $transaction)
    {
        return redirect()->route('transactions.receipt', [$transaction, 'type' => 'kitchen'])
            ->with('success', 'Silakan cetak ulang struk dapur melalui browser.');
    }
}
