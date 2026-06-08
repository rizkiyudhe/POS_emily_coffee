<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <p>Periode: {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</p>
    <p>Total Penjualan: Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
    <p>Jumlah Transaksi: {{ $totalTransactions }}</p>
    <p>Rata-rata per Transaksi: Rp {{ number_format($averageTransaction, 0, ',', '.') }}</p>
    
    <h3>Detail Transaksi</h3>
    <table>
        <thead>
            <tr><th>Invoice</th><th>Tanggal</th><th>Kasir</th><th>Total</th></tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
            <tr>
                <td>{{ $trx->invoice_number }}</td>
                <td>{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->cashier->name }}</td>
                <td class="text-right">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>