<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanBuku;
use App\Models\Denda;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReminderDenda;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<=', now()->addDays(2)) // 2 hari sebelum deadline
            ->orderBy('tanggal_kembali')
            ->get();

        return view('admin.reminder.index', compact('reminders'));
    }

    public function kirimReminder($peminjamanId)
    {
        $peminjaman = PeminjamanBuku::with('user')->findOrFail($peminjamanId);

        // Hitung hari tersisa
        $hariTersisa = Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(now());

        if ($hariTersisa >= 0) {
            // Kirim notifikasi
            $peminjaman->user->notify(new ReminderDenda($peminjaman, $hariTersisa));

            return redirect()->back()->with('success', 'Reminder berhasil dikirim!');
        }

        return redirect()->back()->with('error', 'Buku sudah terlambat!');
    }

    public function otomatisReminder()
    {
        $peminjaman = PeminjamanBuku::with('user')
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', now()->addDay()) // 1 hari sebelum
            ->get();

        foreach ($peminjaman as $pinjam) {
            $pinjam->user->notify(new ReminderDenda($pinjam, 1));
        }

        return response()->json(['message' => 'Reminder otomatis terkirim']);
    }

    public function getTerlambat()
    {
        $terlambat = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', today())
            ->get();

        return view('admin.reminder.terlambat', compact('terlambat'));
    }

    // Tambahkan method ini di ReminderController
    public function pustakawanIndex()
    {
        $reminders = PeminjamanBuku::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '>=', today())
            ->where('tanggal_kembali', '<=', now()->addDays(3))
            ->orderBy('tanggal_kembali')
            ->get();

        return view('pustakawan.reminder.index', compact('reminders'));
    }
}
