<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            font-size: 12px;
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
            color: black;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK BUKU</h1>
        <p>Perpustakaan - {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Stok Total</th>
                <th>Dipinjam</th>
                <th>Dikembalikan</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->judul }}</td>
                <td>
                    <span class="badge badge-{{ $item->stok > 0 ? 'success' : 'danger' }}">
                        {{ $item->stok }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-warning">
                        {{ $item->dipinjam ?? 0 }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-info">
                        {{ $item->dikembalikan ?? 0 }}
                    </span>
                </td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
        <p>Total Data: {{ $buku->count() }} buku</p>
    </div>
</body>
</html>