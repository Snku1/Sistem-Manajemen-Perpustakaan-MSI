<?php

namespace App\Mail;

use App\Models\PeminjamanBuku;
use App\Models\PengaturanDenda;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PengingatPengembalianEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $hariTersisa;
    public $pengaturan;
    public $tanggalHariIni;

    public function __construct(PeminjamanBuku $peminjaman, $hariTersisa)
    {
        $this->peminjaman = $peminjaman;
        $this->hariTersisa = $hariTersisa;
        $this->pengaturan = PengaturanDenda::getAktif();
        $this->tanggalHariIni = Carbon::now()->format('d F Y');
    }

    public function build()
    {
        $subject = $this->hariTersisa == 0 
            ? '⏰ BATAS PENGEMBALIAN HARI INI - Perpustakaan'
            : '🔔 PENGINGAT PENGEMBALIAN BUKU - Perpustakaan';

        return $this->subject($subject)
                    ->view('emails.pengingat-pengembalian')
                    ->with([
                        'peminjaman' => $this->peminjaman,
                        'hariTersisa' => $this->hariTersisa,
                        'pengaturan' => $this->pengaturan,
                        'tanggalHariIni' => $this->tanggalHariIni,
                    ]);
    }
}