<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reminder Perpustakaan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .status-terlambat {
            background: #dc3545;
            color: white;
        }
        .status-pengingat {
            background: #ffc107;
            color: black;
        }
        .book-details {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Perpustakaan Digital</h1>
            <p>Sistem Manajemen Peminjaman Buku</p>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $peminjaman->user->name }}!</h2>
            
            @if($status == 'terlambat')
                <div class="status-badge status-terlambat">
                    ⚠️ PEMBERITAHUAN KETERLAMBATAN
                </div>
                <p>Kami informasikan bahwa peminjaman buku Anda telah <strong>melewati batas waktu pengembalian</strong>.</p>
            @else
                <div class="status-badge status-pengingat">
                    🔔 PENGINGAT PENGEMBALIAN BUKU
                </div>
                <p>Ini adalah pengingat untuk pengembalian buku yang Anda pinjam.</p>
            @endif

            <div class="book-details">
                <h3 style="margin-top: 0;">Detail Peminjaman</h3>
                <table width="100%">
                    <tr>
                        <td width="30%"><strong>Judul Buku:</strong></td>
                        <td>{{ $peminjaman->buku->judul }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Batas Kembali:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($status == 'terlambat')
                                <strong style="color: #dc3545;">Terlambat {{ $hari }} hari</strong>
                            @else
                                <strong style="color: #28a745;">Sisa {{ $hari }} hari</strong>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            @if($status == 'terlambat' && $pengaturan)
            <div class="info-box">
                <h4>📋 Informasi Denda</h4>
                <p>
                    <strong>Masa Tenggang:</strong> {{ $pengaturan->masa_tenggang }} hari<br>
                    <strong>Denda per Hari:</strong> Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}<br>
                    <strong>Estimasi Denda:</strong> 
                    @php
                        $hariKenaDenda = max(0, $hari - $pengaturan->masa_tenggang);
                        $totalDenda = $hariKenaDenda * $pengaturan->denda_per_hari;
                    @endphp
                    Rp {{ number_format($totalDenda, 0, ',', '.') }} ({{ $hariKenaDenda }} hari)
                </p>
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/peminjaman') }}" class="btn">
                    📖 Lihat Detail Peminjaman
                </a>
            </div>

            <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <strong>💡 Tips:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Kembalikan buku tepat waktu untuk menghindari denda</li>
                    <li>Pastikan buku dalam kondisi baik</li>
                    <li>Hubungi petugas jika ada kendala</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Perpustakaan Digital</strong></p>
            <p>Email ini dikirim secara otomatis pada {{ $tanggalHariIni }}</p>
            <p>© {{ date('Y') }} Perpustakaan Digital. All rights reserved.</p>
        </div>
    </div>
</body>
</html>