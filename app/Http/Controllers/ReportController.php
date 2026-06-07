<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{


    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        $start = $request->start_date;
        $end = $request->end_date;
        $reportData = Transaction::whereBetween('transaction_date', [$start, $end])
            ->selectRaw('DATE(transaction_date) as date, count(*) as count, sum(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        return view('reports.index', compact('reportData', 'start', 'end'));
    }
}
