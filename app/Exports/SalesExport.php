<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        // Ini akan memanggil view yang sama dengan PDF untuk dijadikan tabel Excel
        return view('reports.pdf.sales', $this->data);
    }
}
