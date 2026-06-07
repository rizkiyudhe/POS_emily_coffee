<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Table;
use App\Models\Product;
use App\Helpers\TransactionHelper;
use App\Services\PrintService;
use App\Models\ActivityLog;

class TransactionController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        $transactions = Transaction::with('table', 'cashier')->orderBy('id', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $tables = Table::where('status', 'available')->get();
        $cart = $this->cart->getCart();
        $total = $this->cart->getTotal();
        return view('transactions.create', compact('tables', 'cart', 'total'));
    }

    public function addToCart(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $this->cart->addItem($request->product_id, $request->quantity);
        return response()->json(['success' => true, 'cart' => $this->cart->getCart(), 'total' => $this->cart->getTotal()]);
    }

    public function updateCart(Request $request)
    {
        $this->cart->updateQuantity($request->product_id, $request->quantity);
        return response()->json(['success' => true, 'total' => $this->cart->getTotal()]);
    }

    public function removeFromCart($productId)
    {
        $this->cart->removeItem($productId);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'payment_method' => 'required|in:cash,qris,debit',
            'paid_amount' => 'required|integer|min:0'
        ]);
        $cart = $this->cart->getCart();
        if (empty($cart)) return back()->with('error', 'Cart kosong!');
        $total = $this->cart->getTotal();
        $paid = $request->paid_amount;
        if ($paid < $total) return back()->with('error', 'Pembayaran kurang!');

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
        $printService->printReceipt($transaction);
        $printService->printKOT($transaction);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create transaction',
            'description' => "Transaksi {$transaction->invoice_number}",
            'ip_address' => $request->ip()
        ]);
        $this->cart->clearCart();
        return redirect()->route('transactions.receipt', $transaction)->with('success', 'Transaksi berhasil!');
    }

    public function receipt(Transaction $transaction)
    {
        return view('transactions.receipt', compact('transaction'));
    }

    public function reprintReceipt(Transaction $transaction, Request $request)
    {
        $print = new PrintService();
        $print->printReceipt($transaction);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'reprint receipt', 'description' => "Cetak ulang struk {$transaction->invoice_number}", 'ip_address' => $request->ip()]);
        return back()->with('success', 'Struk dicetak ulang.');
    }

    public function reprintKOT(Transaction $transaction, Request $request)
    {
        $print = new PrintService();
        $print->printKOT($transaction);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'reprint kot', 'description' => "Cetak ulang KOT {$transaction->invoice_number}", 'ip_address' => $request->ip()]);
        return back()->with('success', 'KOT dicetak ulang.');
    }
}
