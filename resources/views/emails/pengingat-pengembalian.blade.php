<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengingat Pengembalian Buku</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; padding: 30px 20px; text-align: center; }
        .content { padding: 30px; }
        .book-details { background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 0 5px 5px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
        .btn { display: inline-block; padding: 12px 30px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px; margin: 10px 0; font-weight: bold; }
        .urgent { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Perpustakaan Digital</h1>
            <p>Pengingat Pengembalian Buku</p>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $peminjaman->user->name }}!</h2>
            
            @if($hariTersisa == 0)
                <div style="background: #dc3545; color: white; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
                    <h3 style="margin: 0;">⏰ BATAS PENGEMBALIAN HARI INI!</h3>
                    <p style="margin: 10px 0 0 0;">Segera kembalikan buku sebelum tutup perpustakaan</p>
                </div>
            @else
                <p>Ini adalah pengingat untuk <strong>pengembalian buku</strong> yang Anda pinjam.</p>
            @endif

            <div class="book-details">
                <h3 style="margin-top: 0; color: #856404;">📖 Buku yang Perlu Dikembalikan</h3>
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
                        <td><strong>Status Waktu:</strong></td>
                        <td>
                            @if($hariTersisa == 0)
                                <strong style="color: #dc3545;">⏰ HARI INI BATASNYA!</strong>
                            @else
                                <strong style="color: #fd7e14;">Sisa {{ $hariTersisa }} hari</strong>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            @if($pengaturan)
            <div style="background: #d1ecf1; padding: 15px; border-radius: 5px; border-left: 4px solid #17a2b8;">
                <strong>📋 Informasi Masa Tenggang & Denda:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Masa tenggang: <strong>{{ $pengaturan->masa_tenggang }} hari</strong> setelah batas kembali</li>
                    <li>Denda setelah masa tenggang: <strong>Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}/hari</strong></li>
                    <li>Hindari denda dengan mengembalikan tepat waktu</li>
                </ul>
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/peminjaman') }}" class="btn {{ $hariTersisa == 0 ? 'urgent' : '' }}">
                    @if($hariTersisa == 0)
                        🚀 KEMBALIKAN SEKARANG
                    @else
                        📋 LIHAT DETAIL PEMINJAMAN
                    @endif
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