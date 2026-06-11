<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Laporan Transaksi Void</h2>
    <p>Periode: {{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Invoice</th>
                <th>Nominal</th>
                <th>Kasir</th>
                <th>Dibatalkan Oleh</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($voids as $void)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($void->voided_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $void->invoice_number }}</td>
                    <td>{{ number_format($void->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $void->cashier->name ?? '-' }}</td>
                    <td>{{ $void->voider->name ?? 'Admin' }}</td>
                    <td>{{ $void->void_reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
