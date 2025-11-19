<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriBukuController;
use App\Http\Controllers\RakBukuController;
use App\Http\Controllers\PeminjamanBukuController;
use App\Http\Controllers\PengembalianBukuController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\Pustakawan\DashboardPustakawanController;
use App\Http\Controllers\Pustakawan\PeminjamanPustakawanController;
use App\Http\Controllers\Pustakawan\PengembalianPustakawanController;
use App\Http\Controllers\Pustakawan\BukuPustakawanController;
use App\Http\Controllers\Pustakawan\JadwalPustakawanController;
use App\Http\Controllers\Pustakawan\ReminderPustakawanController;



Route::get('/', fn() => redirect()->route('login'));

// === LOGIN & LOGOUT ===
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ROUTE ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('buku', BukuController::class);
    Route::post('/buku/import', [BukuController::class, 'importExcel'])->name('buku.import');
    Route::resource('kategori', KategoriBukuController::class);
    Route::resource('rak', RakBukuController::class);
    Route::resource('peminjaman', PeminjamanBukuController::class);

    Route::get('pengembalian', [PengembalianBukuController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{peminjaman}/create', [PengembalianBukuController::class, 'create'])->name('pengembalian.create');
    Route::post('pengembalian/{peminjaman}', [PengembalianBukuController::class, 'store'])->name('pengembalian.store');

    Route::get('/denda', [DendaController::class, 'index'])->name('denda.index');
    Route::get('/denda/pengaturan', [DendaController::class, 'pengaturan'])->name('denda.pengaturan');
    Route::post('/denda/pengaturan', [DendaController::class, 'updatePengaturan'])->name('denda.update-pengaturan');

    Route::resource('users', UserController::class);

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/hari-ini', [JadwalController::class, 'getHariIni'])->name('jadwal.hari-ini');
    Route::get('/jadwal/akan-datang', [JadwalController::class, 'getAkanDatang'])->name('jadwal.akan-datang');
    Route::get('/jadwal/terlambat', [JadwalController::class, 'getTerlambat'])->name('jadwal.terlambat');

    // FITUR BARU: Reminder & Notifikasi
    Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');
    Route::get('/reminder/terlambat', [ReminderController::class, 'getTerlambat'])->name('reminder.terlambat');
    Route::post('/reminder/{peminjamanId}/kirim', [ReminderController::class, 'kirimReminder'])->name('reminder.kirim');
    Route::post('/reminder/otomatis', [ReminderController::class, 'otomatisReminder'])->name('reminder.otomatis');
});


// ROUTE PUSTAKAWAN
Route::middleware(['auth', 'role:pustakawan'])->prefix('pustakawan')->name('pustakawan.')->group(function () {
    Route::get('/dashboard', [DashboardPustakawanController::class, 'index'])->name('dashboard');
    Route::get('/peminjaman', [PeminjamanPustakawanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanPustakawanController::class, 'show'])->name('peminjaman.show');
    Route::get('/pengembalian', [PengembalianPustakawanController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{id}', [PengembalianPustakawanController::class, 'show'])->name('pengembalian.show');
    Route::get('/buku', [BukuPustakawanController::class, 'index'])->name('buku.index');

    // Jadwal untuk pustakawan (view only)
    Route::get('/jadwal', [JadwalController::class, 'pustakawanindex'])->name('jadwal.index');
    Route::get('/jadwal/hari-ini', [JadwalController::class, 'pustakawanHariIni'])->name('jadwal.hari-ini');
    Route::get('/jadwal/akan-datang', [JadwalController::class, 'pustakawanAkanDatang'])->name('jadwal.akan-datang');

    // Reminder untuk pustakawan (hanya lihat, tidak bisa kirim)
    Route::get('/reminder', [ReminderController::class, 'pustakawanIndex'])->name('reminder.index');
});


// ROUTE KEPALA PERPUSTAKAAN
Route::middleware(['auth', 'role:kepala_perpus'])->prefix('kepala-perpus')->name('kepala-perpus.')->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\KepalaPerpus\DashboardKepalaPerpusController::class, 'index'])->name('dashboard');

    // Statistik
    Route::get('/statistik', [App\Http\Controllers\KepalaPerpus\StatistikController::class, 'index'])->name('statistik.index');
    // Laporan Stok Buku
    Route::get('/buku', [App\Http\Controllers\KepalaPerpus\BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/export-pdf', [App\Http\Controllers\KepalaPerpus\BukuController::class, 'exportPDF'])->name('buku.export.pdf');
    Route::get('/buku/export-excel', [App\Http\Controllers\KepalaPerpus\BukuController::class, 'exportExcel'])->name('buku.export.excel');
    // Laporan Denda
    Route::get('/denda', [App\Http\Controllers\KepalaPerpus\DendaController::class, 'index'])->name('denda.index');
    Route::get('/laporan/denda/export-pdf', [App\Http\Controllers\KepalaPerpus\DendaController::class, 'exportPDF'])->name('laporan.denda.export.pdf');
    Route::get('/laporan/denda/export-excel', [App\Http\Controllers\KepalaPerpus\DendaController::class, 'exportExcel'])->name('laporan.denda.export.excel');

    // Data Pengguna → hanya menampilkan daftar pustakawan
    Route::get('/user', [App\Http\Controllers\KepalaPerpus\UserController::class, 'index'])->name('user.index');
    Route::get('/user/export-pdf', [App\Http\Controllers\KepalaPerpus\UserController::class, 'exportPDF'])->name('user.export.pdf');
    Route::get('/user/export-excel', [App\Http\Controllers\KepalaPerpus\UserController::class, 'exportExcel'])->name('user.export.excel');

    // Transaksi
    // Peminjaman - hanya tampilan data (index + detail)
    Route::get('/peminjaman', [App\Http\Controllers\KepalaPerpus\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [App\Http\Controllers\KepalaPerpus\PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/export-pdf', [App\Http\Controllers\KepalaPerpus\PeminjamanController::class, 'exportPDF'])->name('peminjaman.export.pdf');
    Route::get('/peminjaman/export-excel', [App\Http\Controllers\KepalaPerpus\PeminjamanController::class, 'exportExcel'])->name('peminjaman.export.excel');

    // Pengembalian - hanya tampilan data (index + detail)
    Route::get('/pengembalian', [App\Http\Controllers\KepalaPerpus\PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/export-pdf', [App\Http\Controllers\KepalaPerpus\PengembalianController::class, 'exportPDF'])->name('pengembalian.export.pdf');
    Route::get('/pengembalian/export-excel', [App\Http\Controllers\KepalaPerpus\PengembalianController::class, 'exportExcel'])->name('pengembalian.export.excel');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('kepala-perpus.jadwal.index');
    Route::get('/jadwal/hari-ini', [JadwalController::class, 'getHariIni'])->name('kepala-perpus.jadwal.hari-ini');
    Route::get('/jadwal/akan-datang', [JadwalController::class, 'getAkanDatang'])->name('kepala-perpus.jadwal.akan-datang');

    Route::get('/reminder', [ReminderController::class, 'index'])->name('kepala-perpus.reminder.index');
    Route::get('/reminder/terlambat', [ReminderController::class, 'getTerlambat'])->name('kepala-perpus.reminder.terlambat');
});
