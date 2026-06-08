<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\Table;
use App\Models\TransactionItem;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            // --- Data dasar ---
            $todaySales = Transaction::whereDate('transaction_date', today())->sum('total_amount');
            $totalTransactionsToday = Transaction::whereDate('transaction_date', today())->count();
            $totalProducts = Product::count();
            $totalUsers = User::count();
            $totalTables = Table::count();

            // --- Data tambahan untuk enhanced dashboard (YANG DITANYAKAN) ---
            $salesLast7Days = Transaction::where('status', 'completed')
                ->where('transaction_date', '>=', now()->subDays(7))
                ->selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $lowStockProducts = Product::where('stock', '<=', 10)->take(5)->get();

            $topProducts = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->with('product')
                ->get();

            $recentActivities = ActivityLog::with('user')->latest()->limit(5)->get();

            $totalVoidToday = Transaction::where('status', 'void')
                ->whereDate('voided_at', today())->count();

            // Kirim semua data ke view
            return view('dashboard.admin', compact(
                'todaySales',
                'totalTransactionsToday',
                'totalProducts',
                'totalUsers',
                'totalTables',
                'salesLast7Days',
                'lowStockProducts',
                'topProducts',
                'recentActivities',
                'totalVoidToday'
            ));
        } else {
            // Kasir dashboard
            $totalSalesToday = Transaction::whereDate('transaction_date', today())->sum('total_amount');
            $totalTransactionsToday = Transaction::whereDate('transaction_date', today())->count();
            // Ambil nomor antrian terakhir (opsional)
            $lastQueueNumber = Transaction::latest()->value('queue_number');

            return view('dashboard.kasir', compact('totalSalesToday', 'totalTransactionsToday', 'lastQueueNumber'));
        }
    }
}
