<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peminjaman Buku</title>
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
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .badge-success {
            background-color: #28a745;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA PEMINJAMAN BUKU</h1>
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
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $item->user->name ?? '-' }}</td>
                <td>{{ $item->buku->judul ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td class="text-center">
                    {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') : '-' }}
                </td>
                <td class="text-center">
                    @if($item->status === 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @else
                        <span class="badge badge-success">Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        @php
            $dipinjam = $peminjaman->where('status', 'dipinjam')->count();
            $dikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
        @endphp
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>Total Peminjaman: {{ $peminjaman->count() }}</p>
        <p>Status: {{ $dipinjam }} Dipinjam, {{ $dikembalikan }} Dikembalikan</p>
    </div>
</body>
</html>