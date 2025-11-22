<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konfirmasi Peminjaman Buku</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px 20px; text-align: center; }
        .content { padding: 30px; }
        .book-details { background: #f8f9fa; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 0 5px 5px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
        .btn { display: inline-block; padding: 12px 30px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Perpustakaan Digital</h1>
            <p>Konfirmasi Peminjaman Buku Berhasil</p>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $peminjaman->user->name }}!</h2>
            <p>Peminjaman buku Anda telah <strong>berhasil diproses</strong>. Berikut detail peminjaman:</p>

            <div class="book-details">
                <h3 style="margin-top: 0; color: #28a745;">📖 Detail Peminjaman</h3>
                <table width="100%">
                    <tr>
                        <td width="35%"><strong>Judul Buku:</strong></td>
                        <td><strong>{{ $peminjaman->buku->judul }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pinjam:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Batas Kembali:</strong></td>
                        <td><strong style="color: #dc3545;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Durasi:</strong></td>
                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->diffInDays($peminjaman->tanggal_kembali) }} hari</td>
                    </tr>
                </table>
            </div>

            <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; border-left: 4px solid #17a2b8;">
                <strong>💡 Informasi Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Jaga buku dengan baik selama masa peminjaman</li>
                    <li>Kembalikan buku tepat waktu untuk menghindari denda</li>
                    <li>Hubungi petugas perpustakaan jika ada kendala</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/peminjaman') }}" class="btn">
                    📋 Lihat Detail Peminjaman
                </a>
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