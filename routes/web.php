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
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Pustakawan\DashboardPustakawanController;
use App\Http\Controllers\Pustakawan\PeminjamanPustakawanController;
use App\Http\Controllers\Pustakawan\PengembalianPustakawanController;
use App\Http\Controllers\Pustakawan\BukuPustakawanController;

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
    Route::get('/denda/hitung', [DendaController::class, 'hitungDenda'])->name('denda.hitung');

    Route::resource('users', UserController::class);

    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
    Route::get('/backup/download/{id}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{id}', [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::get('/backup/restore/{id}', [BackupController::class, 'restore'])->name('backup.restore');
});


// ROUTE PUSTAKAWAN
Route::middleware(['auth', 'role:pustakawan'])->prefix('pustakawan')->name('pustakawan.')->group(function () {
    Route::get('/dashboard', [DashboardPustakawanController::class, 'index'])->name('dashboard');
    Route::get('/peminjaman', [PeminjamanPustakawanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanPustakawanController::class, 'show'])->name('peminjaman.show');
    Route::get('/pengembalian', [PengembalianPustakawanController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{id}', [PengembalianPustakawanController::class, 'show'])->name('pengembalian.show');
    Route::get('/buku', [BukuPustakawanController::class, 'index'])->name('buku.index');
});
