<?php

namespace App\Mail;

use App\Models\PeminjamanBuku;
use App\Models\PengaturanDenda;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class PeringatanKeterlambatanEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $hariTerlambat;
    public $pengaturan;
    public $tanggalHariIni;
    public $totalDenda;

    public function __construct(PeminjamanBuku $peminjaman, $hariTerlambat)
    {
        $this->peminjaman = $peminjaman;
        $this->hariTerlambat = $hariTerlambat;
        $this->pengaturan = PengaturanDenda::getAktif();
        $this->tanggalHariIni = Carbon::now()->format('d F Y');
        $this->totalDenda = $this->hitungDenda();
    }

    public function build()
    {
        return $this->subject('⏰ PERINGATAN KETERLAMBATAN - Perpustakaan')
                    ->view('emails.peringatan-keterlambatan')
                    ->with([
                        'peminjaman' => $this->peminjaman,
                        'hariTerlambat' => $this->hariTerlambat,
                        'pengaturan' => $this->pengaturan,
                        'tanggalHariIni' => $this->tanggalHariIni,
                        'totalDenda' => $this->totalDenda,
                    ]);
    }

    private function hitungDenda()
    {
        if (!$this->pengaturan) return 0;

        $hariKenaDenda = max(0, $this->hariTerlambat - $this->pengaturan->masa_tenggang);
        return $hariKenaDenda * $this->pengaturan->denda_per_hari;
    }
}