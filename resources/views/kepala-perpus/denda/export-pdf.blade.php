<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Denda Perpustakaan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 20px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary h3 {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DENDA PERPUSTAKAAN</h1>
        <p>Periode: {{ date('d F Y') }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Denda</h3>
        <p><strong>Total Semua Denda:</strong> Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
        @if($pustakawanTerbanyak)
        <p><strong>Pustakawan dengan Denda Terbanyak:</strong> {{ $pustakawanTerbanyak->name }} (Rp {{ number_format($pustakawanTerbanyak->denda_sum_total_denda, 0, ',', '.') }})</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Pustakawan</th>
                <th class="text-center">Jumlah Denda</th>
                <th class="text-right">Total Denda</th>
                <th class="text-right">Rata-rata</th>
                <th class="text-right">Denda Tertinggi</th>
                <th class="text-right">Denda Terendah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailDenda as $d)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $d->name }}</td>
                <td class="text-center">
                    <span class="badge badge-danger">
                        {{ $d->denda_count }}
                    </span>
                </td>
                <td class="text-right">Rp {{ number_format($d->denda_sum_total_denda, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d->denda_avg_total_denda ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d->denda_max_total_denda ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($d->denda_min_total_denda ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>Total Pustakawan: {{ $detailDenda->count() }}</p>
    </div>
</body>
</html>