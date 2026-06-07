<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Table;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $todaySales = Transaction::whereDate('transaction_date', today())->sum('total_amount');
            $totalTransactionsToday = Transaction::whereDate('transaction_date', today())->count();
            $totalProducts = Product::count();
            $totalUsers = User::count();
            $totalTables = Table::count();
            $bestSeller = TransactionItem::select('product_id', DB::raw('sum(quantity) as total'))
                ->groupBy('product_id')->orderBy('total', 'desc')->with('product')->first();
            return view('dashboard.admin', compact('todaySales', 'totalTransactionsToday', 'totalProducts', 'totalUsers', 'totalTables', 'bestSeller'));
        } else {
            $totalSalesToday = Transaction::whereDate('transaction_date', today())->sum('total_amount');
            $totalTransactionsToday = Transaction::whereDate('transaction_date', today())->count();
            return view('dashboard.kasir', compact('totalSalesToday', 'totalTransactionsToday'));
        }
    }
}
