<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

// Pastikan kamu sudah membuat class Export ini (Panduan di bawah)
use App\Exports\SalesExport;
use App\Exports\ProductExport;
use App\Exports\VoidExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // 1. LAPORAN PENJUALAN (SALES)
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

        // ✅ FIX: Tambahkan filter status != 'void' agar transaksi batal tidak masuk omzet
        $transactions = Transaction::with('items.product')
            ->where('status', '!=', 'void')
            ->whereBetween('transaction_date', [$start->startOfDay(), $end->endOfDay()])
            ->get();

        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Data untuk grafik
        if ($period == 'daily') {
            $chartData = $transactions->groupBy(fn($item) => $item->transaction_date->format('Y-m-d'))
                ->map(fn($group) => $group->sum('total_amount'))->sortKeys();
        } elseif ($period == 'monthly') {
            $chartData = $transactions->groupBy(fn($item) => $item->transaction_date->format('Y-m'))
                ->map(fn($group) => $group->sum('total_amount'))->sortKeys();
        } else {
            $chartData = $transactions->groupBy(fn($item) => $item->transaction_date->format('Y'))
                ->map(fn($group) => $group->sum('total_amount'))->sortKeys();
        }

        // Produk terlaris (Berdasarkan transaksi yang tidak void)
        $bestSellers = DB::table('transaction_items')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(transaction_items.quantity) as total_qty'))
            ->whereIn('transaction_items.transaction_id', $transactions->pluck('id'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        $data = compact('transactions', 'totalSales', 'totalTransactions', 'averageTransaction', 'chartData', 'start', 'end', 'period', 'bestSellers');

        // FITUR EXPORT
        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.sales', $data);
            return $pdf->download('Laporan_Penjualan_' . $start->format('d-M-Y') . '.pdf');
        }
        if ($request->export == 'excel') {
            return Excel::download(new SalesExport($data), 'Laporan_Penjualan_' . $start->format('d-M-Y') . '.xlsx');
        }

        return view('reports.index', $data);
    }

    // 2. LAPORAN VOID
    public function voidReport(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::today();

        // ✅ FIX: Ganti 'voidedBy' menjadi 'voider' sesuai model
        $voids = Transaction::where('status', 'void')
            ->whereBetween('voided_at', [$start->startOfDay(), $end->endOfDay()])
            ->with('cashier', 'voider')
            ->orderBy('voided_at', 'desc')
            ->get();

        $data = compact('voids', 'start', 'end');

        // FITUR EXPORT
        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.void', $data);
            return $pdf->download('Laporan_Void_' . $start->format('d-M-Y') . '.pdf');
        }
        if ($request->export == 'excel') {
            return Excel::download(new VoidExport($data), 'Laporan_Void_' . $start->format('d-M-Y') . '.xlsx');
        }

        return view('reports.void', $data);
    }

    // 3. LAPORAN PRODUK
    public function productReport(Request $request)
    {
        $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::today()->subDays(30);
        $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::today();

        // ✅ FIX: Tambahkan startOfDay() dan endOfDay()
        $bestSellers = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('transaction', function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start->startOfDay(), $end->endOfDay()])
                    ->where('status', '!=', 'void');
            })
            ->groupBy('product_id')->orderBy('total', 'desc')->limit(10)->with('product')->get();

        $leastSellers = TransactionItem::select('product_id', DB::raw('SUM(quantity) as total'))
            ->whereHas('transaction', function ($q) use ($start, $end) {
                $q->whereBetween('transaction_date', [$start->startOfDay(), $end->endOfDay()])
                    ->where('status', '!=', 'void');
            })
            ->groupBy('product_id')->orderBy('total', 'asc')->limit(10)->with('product')->get();

        $data = compact('bestSellers', 'leastSellers', 'start', 'end');

        // FITUR EXPORT
        if ($request->export == 'pdf') {
            $pdf = Pdf::loadView('reports.pdf.product', $data);
            return $pdf->download('Laporan_Produk_' . $start->format('d-M-Y') . '.pdf');
        }
        if ($request->export == 'excel') {
            return Excel::download(new ProductExport($data), 'Laporan_Produk_' . $start->format('d-M-Y') . '.xlsx');
        }

        return view('reports.product', $data);
    }
}
