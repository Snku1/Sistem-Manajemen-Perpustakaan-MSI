<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Pengembalian Buku</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
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
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA PENGEMBALIAN BUKU</h1>
        <p>Perpustakaan - {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th class="text-center">Tanggal Pinjam</th>
                <th class="text-center">Tanggal Kembali</th>
                <th class="text-center">Status</th>
                <th class="text-center">Denda</th>
                <th class="text-center">Keterlambatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalian as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->peminjaman->user->name ?? '-' }}</td>
                <td>{{ $item->peminjaman->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}</td>
                <td class="text-center">
                    @if($item->peminjaman->denda)
                    <span class="badge badge-danger">
                        Terlambat
                    </span>
                    @else
                    <span class="badge badge-success">
                        Tepat Waktu
                    </span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->peminjaman->denda)
                    <span class="badge badge-warning">
                        Rp {{ number_format($item->peminjaman->denda->total_denda, 0, ',', '.') }}
                    </span>
                    @else
                    <span class="badge badge-secondary">
                        Tidak Ada
                    </span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->peminjaman->denda)
                    <span class="badge badge-info">
                        {{ $item->peminjaman->denda->jumlah_hari }} hari
                    </span>
                    @else
                    <span class="badge badge-success">
                        0 hari
                    </span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        @php
            $totalDenda = 0;
            $totalTerlambat = 0;
            foreach($pengembalian as $item) {
                if($item->peminjaman->denda) {
                    $totalDenda += $item->peminjaman->denda->total_denda;
                    $totalTerlambat++;
                }
            }
        @endphp
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>Total Pengembalian: {{ $pengembalian->count() }}</p>
        <p>Total Keterlambatan: {{ $totalTerlambat }}</p>
        <p>Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
    </div>
</body>
</html>