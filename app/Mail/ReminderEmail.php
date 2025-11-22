<?php

namespace App\Mail;

use App\Models\PeminjamanBuku;
use App\Models\PengaturanDenda;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $peminjaman;
    public $hari;
    public $status;
    public $pengaturan;
    public $tanggalHariIni;

    public function __construct(PeminjamanBuku $peminjaman, $hari, $status = 'pengingat')
    {
        $this->peminjaman = $peminjaman;
        $this->hari = $hari;
        $this->status = $status;
        $this->pengaturan = PengaturanDenda::getAktif();
        $this->tanggalHariIni = Carbon::now()->format('d F Y');
    }

    public function build()
    {
        $subject = $this->getSubject();
        
        return $this->subject($subject)
                    ->view('emails.reminder')
                    ->with([
                        'peminjaman' => $this->peminjaman,
                        'hari' => $this->hari,
                        'status' => $this->status,
                        'pengaturan' => $this->pengaturan,
                        'tanggalHariIni' => $this->tanggalHariIni,
                    ]);
    }

    private function getSubject()
    {
        switch ($this->status) {
            case 'terlambat':
                return "⏰ PERINGATAN KETERLAMBATAN - Perpustakaan";
            case 'pengingat':
                return "📚 PENGINGAT PENGEMBALIAN BUKU - Perpustakaan";
            default:
                return "📖 INFORMASI PEMINJAMAN BUKU - Perpustakaan";
        }
    }
}