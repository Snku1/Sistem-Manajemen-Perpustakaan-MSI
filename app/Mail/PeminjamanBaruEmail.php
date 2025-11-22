<?php

namespace App\Mail;

use App\Models\PeminjamanBuku;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PeminjamanBaruEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $tanggalHariIni;

    public function __construct(PeminjamanBuku $peminjaman)
    {
        $this->peminjaman = $peminjaman;
        $this->tanggalHariIni = Carbon::now()->format('d F Y');
    }

    public function build()
    {
        return $this->subject('📖 INFORMASI PEMINJAMAN BUKU - Perpustakaan')
                    ->view('emails.peminjaman-baru')
                    ->with([
                        'peminjaman' => $this->peminjaman,
                        'tanggalHariIni' => $this->tanggalHariIni,
                    ]);
    }
}