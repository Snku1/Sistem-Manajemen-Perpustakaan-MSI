<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peringatan Keterlambatan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
        }

        .book-details {
            background: #f8d7dae1;
            border-left: 4px solid #dc3545;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }

        .denda-info {
            background: #343a40;
            color: white;
            padding: 20px;
            border-radius: 5px;
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
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Perpustakaan Digital</h1>
            <p>Peringatan Keterlambatan Pengembalian</p>
        </div>

        <div class="content">
            <h2>Halo, {{ $peminjaman->user->name }}!</h2>

            <div style="background: #dc3545; color: white; padding: 15px; border-radius: 5px; text-align: center; margin: 20px 0;">
                <h3 style="margin: 0;">⚠️ PEMINJAMAN ANDA TELAH TERLAMBAT!</h3>
                <p style="margin: 10px 0 0 0;">Segera lakukan pengembalian untuk menghentikan akumulasi denda</p>
            </div>

            <div class="book-details">
                <h3 style="margin-top: 0; color: #721c24;">📖 Detail Keterlambatan</h3>
                <table width="100%">
                    <tr>
                        <td width="40%"><strong>Judul Buku:</strong></td>
                        <td><strong>{{ $peminjaman->buku->judul }}</strong></td>
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
                        <td><strong>Keterlambatan:</strong></td>
                        <td><strong style="color: #dc3545;">{{ $hariTerlambat }} hari</strong></td>
                    </tr>
                    {{-- Di bagian status --}}
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($hariTerlambat <= $pengaturan->masa_tenggang)
                                <strong style="color: #ffa407ff;">📅 Dalam Masa Tenggang</strong>
                                <br>
                                <small>Sisa masa tenggang: {{ $pengaturan->masa_tenggang - $hariTerlambat }} hari</small>
                                @else
                                <strong style="color: #dc3545;">💸 Sudah Kena Denda</strong>
                                <br>
                                <small>Terlambat {{ $hariTerlambat - $pengaturan->masa_tenggang }} hari setelah masa tenggang</small>
                                @endif
                        </td>
                    </tr>
                </table>
            </div>

            @if($pengaturan)
            <div class="denda-info">
                <h4 style="margin-top: 0; color: #ffc107;">💰 INFORMASI DENDA</h4>
                <table width="100%" style="color: white;">
                    <tr>
                        <td width="40%"><strong>Masa Tenggang:</strong></td>
                        <td>{{ $pengaturan->masa_tenggang }} hari</td>
                    </tr>
                    <tr>
                        <td><strong>Denda per Hari:</strong></td>
                        <td>Rp {{ number_format($pengaturan->denda_per_hari, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Hari Kena Denda:</strong></td>
                        <td>
                            @php
                            $hariKenaDenda = max(0, $hariTerlambat - $pengaturan->masa_tenggang);
                            @endphp
                            {{ $hariKenaDenda }} hari
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL DENDA:</strong></td>
                        <td><strong style="color: #ffc107; font-size: 18px;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                @if($hariKenaDenda > 0)
                <div style="background: #dc3545; padding: 10px; border-radius: 3px; margin-top: 10px; text-align: center;">
                    <strong>Denda akan terus bertambah setiap hari hingga buku dikembalikan!</strong>
                </div>
                @endif
            </div>
            @endif

            <div style="background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
                <strong>🚀 Tindakan yang Perlu Dilakukan:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Segera kembalikan buku ke perpustakaan</li>
                    <li>Bayar denda yang terutang (jika ada)</li>
                    <li>Hubungi petugas untuk konfirmasi</li>
                    <li>Hindari pemblokiran akses peminjaman</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/peminjaman') }}" class="btn">
                    📞 HUBUNGI PETUGAS
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