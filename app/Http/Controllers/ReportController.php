<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{


    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:daily,monthly,yearly'
        ]);

        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today();
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::today();
        $period = $request->period ?? 'daily';

        // Query transaksi berdasarkan range tanggal
        $transactions = Transaction::with('items.product')
            ->whereBetween('transaction_date', [$start->startOfDay(), $end->endOfDay()])
            ->get();

        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Data untuk grafik (group by tanggal atau bulan)
        if ($period == 'daily') {
            $chartData = $transactions->groupBy(function ($item) {
                return $item->transaction_date->format('Y-m-d');
            })->map(function ($group) {
                return $group->sum('total_amount');
            })->sortKeys();
        } elseif ($period == 'monthly') {
            $chartData = $transactions->groupBy(function ($item) {
                return $item->transaction_date->format('Y-m');
            })->map(function ($group) {
                return $group->sum('total_amount');
            })->sortKeys();
        } else { // yearly
            $chartData = $transactions->groupBy(function ($item) {
                return $item->transaction_date->format('Y');
            })->map(function ($group) {
                return $group->sum('total_amount');
            })->sortKeys();
        }

        // Produk terlaris
        $bestSellers = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
            ->whereIn('transaction_items.transaction_id', $transactions->pluck('id'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        if ($request->has('export') && $request->export == 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', compact('transactions', 'totalSales', 'totalTransactions', 'averageTransaction', 'start', 'end', 'bestSellers'));
            return $pdf->download('laporan-penjualan-' . now()->format('YmdHis') . '.pdf');
        }

        return view('reports.index', compact('transactions', 'totalSales', 'totalTransactions', 'averageTransaction', 'chartData', 'start', 'end', 'period', 'bestSellers'));
    }

    public function voidReport(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::today();
        $voids = Transaction::where('status', 'void')
            ->whereBetween('voided_at', [$start->startOfDay(), $end->endOfDay()])
            ->with('cashier', 'voidedBy')
            ->get();
        return view('reports.void', compact('voids', 'start', 'end'));
    }

    public function productReport(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::today();
        $bestSellers = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('transaction', function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start, $end])->where('status', 'completed');
            })
            ->groupBy('product_id')->orderBy('total', 'desc')->limit(10)->with('product')->get();
        $leastSellers = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('transaction', function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start, $end])->where('status', 'completed');
            })
            ->groupBy('product_id')->orderBy('total', 'asc')->limit(10)->with('product')->get();
        return view('reports.product', compact('bestSellers', 'leastSellers', 'start', 'end'));
    }
}
