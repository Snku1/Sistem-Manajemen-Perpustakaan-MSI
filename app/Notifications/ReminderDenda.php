<?php

namespace App\Notifications;

use App\Models\PeminjamanBuku;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReminderDenda extends Notification
{
    use Queueable;

    public $peminjaman;
    public $hariTersisa;

    public function __construct(PeminjamanBuku $peminjaman, $hariTersisa)
    {
        $this->peminjaman = $peminjaman;
        $this->hariTersisa = $hariTersisa;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reminder Pengembalian Buku')
            ->line('Hai ' . $notifiable->name . '!')
            ->line('Ini adalah reminder untuk pengembalian buku:')
            ->line('Judul: ' . $this->peminjaman->buku->judul)
            ->line('Tanggal Kembali: ' . $this->peminjaman->tanggal_kembali)
            ->line('Sisa Waktu: ' . $this->hariTersisa . ' hari')
            ->action('Lihat Detail', url('/peminjaman'))
            ->line('Terima kasih!');
    }

    public function toArray($notifiable)
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'buku' => $this->peminjaman->buku->judul,
            'tanggal_kembali' => $this->peminjaman->tanggal_kembali,
            'hari_tersisa' => $this->hariTersisa,
            'message' => 'Reminder pengembalian buku: ' . $this->peminjaman->buku->judul,
            'type' => 'reminder'
        ];
    }
}