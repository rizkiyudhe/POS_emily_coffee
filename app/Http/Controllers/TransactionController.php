<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Table;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Services\CartService;
use App\Helpers\TransactionHelper;
use App\Services\PrintService;
use Illuminate\Http\Request;

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

        // TAMBAHKAN INI: Ambil semua produk untuk dilempar ke Blade
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

        // Masukkan variabel 'products' ke dalam compact
        return view('transactions.create', compact('tables', 'cart', 'total', 'products'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);
        $this->cart->addItem($request->product_id, $request->quantity);
        return response()->json(['success' => true]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0'
        ]);
        $this->cart->updateQuantity($request->product_id, $request->quantity);
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
            'table_id' => 'nullable|exists:tables,id',
            'payment_method' => 'required|in:cash,qris,debit',
            'paid_amount' => 'required|integer|min:0'
        ]);
        $cart = $this->cart->getCart();
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart kosong!'], 400);
        }
        $total = $this->cart->getTotal();
        $paid = $request->paid_amount;
        if ($paid < $total) {
            return response()->json(['success' => false, 'message' => 'Pembayaran kurang!'], 400);
        }

        $transaction = Transaction::create([
            'invoice_number' => TransactionHelper::generateInvoiceNumber(),
            'queue_number' => TransactionHelper::generateQueueNumber(),
            'table_id' => $request->table_id,
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'change_amount' => $paid - $total,
            'payment_method' => $request->payment_method,
            'status' => 'completed',
            'transaction_date' => now()
        ]);

        foreach ($cart as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal']
            ]);
            Product::find($item['product_id'])->decrement('stock', $item['quantity']);
        }

        if ($request->table_id) {
            Table::find($request->table_id)->update(['status' => 'occupied']);
        }

        $printService = new PrintService();
        $printService->printReceiptCustomer($transaction);
        $printService->printChecker($transaction);
        $printService->printKitchen($transaction);
        $printService->close();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create transaction',
            'description' => "Transaksi {$transaction->invoice_number}",
            'ip_address' => $request->ip()
        ]);

        $this->cart->clearCart();

        return response()->json([
            'success' => true,
            'redirect' => route('transactions.receipt', $transaction)
        ]);
    }

    public function receipt(Transaction $transaction)
    {
        return view('transactions.receipt', compact('transaction'));
    }

    public function reprintCustomer(Transaction $transaction)
    {
        $print = new \App\Services\PrintService();
        $print->printReceiptCustomer($transaction);
        $print->close();
        return back()->with('success', 'Struk konsumen dicetak ulang.');
    }

    public function reprintChecker(Transaction $transaction)
    {
        $print = new \App\Services\PrintService();
        $print->printChecker($transaction);
        $print->close();
        return back()->with('success', 'Struk checker dicetak ulang.');
    }

    public function reprintKitchen(Transaction $transaction)
    {
        $print = new \App\Services\PrintService();
        $print->printKitchen($transaction);
        $print->close();
        return back()->with('success', 'Struk dapur dicetak ulang.');
    }
}
