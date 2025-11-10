<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupControllerr extends Controller
{
    /**
     * Menampilkan daftar backup.
     */
    public function index()
    {
        // $backups = Backup::latest()->paginate(10);
        return view('admin.backups.index'); //, compact('backups'));
    }

    /**
     * Membuat file backup baru dan menyimpannya.
     */
    public function store(Request $request)
    {
        try {
            // Persiapan nama file dan path
            $fileName = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
            $directory = 'backups';
            $filePath = "{$directory}/{$fileName}";
            $storagePath = storage_path("app/{$filePath}");

            // Pastikan direktori 'backups' ada di dalam storage/app/
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            // Dapatkan kredensial database dari file .env
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');

            // Buat perintah mysqldump yang aman
            // Menggunakan array untuk menghindari masalah dengan karakter spesial
            $command = [
                'mysqldump',
                "--host={$dbHost}",
                "--port={$dbPort}",
                "-u{$dbUser}",
                "-p{$dbPass}",
                $dbName,
            ];
            
            // Buat proses baru
            $process = new Process($command);
            $process->setTimeout(3600); // Tambahkan batas waktu 1 jam untuk proses backup
            
            // Jalankan proses dan arahkan output ke file
            $process->mustRun(); // akan melempar exception jika gagal
            $output = $process->getOutput();
            Storage::put($filePath, $output);


            // Simpan informasi backup ke database setelah file berhasil dibuat
            Backup::create([
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => filesize($storagePath), // File sekarang pasti ada
                'backup_date' => Carbon::now(),
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('backup.index')->with('success', 'Backup berhasil dibuat!');

        } catch (ProcessFailedException $exception) {
            // Jika proses mysqldump gagal, catat error dan beri tahu user
            Log::error('Backup Gagal: ' . $exception->getMessage());
            return redirect()->route('backup.index')->with('error', 'Gagal membuat backup. Pastikan `mysqldump` terkonfigurasi dengan benar. Cek log untuk detail.');
        } catch (\Exception $e) {
            // Menangkap error lainnya
            Log::error('Terjadi error saat backup: ' . $e->getMessage());
            return redirect()->route('backup.index')->with('error', 'Terjadi kesalahan yang tidak terduga saat membuat backup.');
        }
    }

    /**
     * Mengunduh file backup.
     */
    public function download($id)
    {
        $backup = Backup::findOrFail($id);
        return Storage::download($backup->file_path, $backup->file_name);
    }

    /**
     * Menghapus file backup dan record dari database.
     */
    public function destroy($id)
    {
        $backup = Backup::findOrFail($id);

        // Hapus file dari storage
        if (Storage::exists($backup->file_path)) {
            Storage::delete($backup->file_path);
        }

        $backup->delete();

        return redirect()->route('backup.index')->with('success', 'Backup berhasil dihapus!');
    }

    /**
     * Merestore database dari file backup yang dipilih.
     */
    public function restore($id)
    {
        $backup = Backup::findOrFail($id);
        $storagePath = storage_path("app/{$backup->file_path}");

        // Pastikan file backup ada
        if (!Storage::exists($backup->file_path)) {
            return redirect()->route('backup.index')->with('error', 'File backup tidak ditemukan!');
        }

        try {
            // Dapatkan kredensial database
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');

            // Buat perintah restore yang aman
            $command = [
                'mysql',
                "--host={$dbHost}",
                "--port={$dbPort}",
                "-u{$dbUser}",
                "-p{$dbPass}",
                $dbName,
            ];
            
            // Buat proses baru dan set input dari file backup
            $process = new Process($command);
            $process->setTimeout(3600); // Tambahkan batas waktu 1 jam untuk proses restore
            $process->setInput(Storage::get($backup->file_path));

            // Jalankan perintah
            $process->mustRun();

            return redirect()->route('backup.index')->with('success', 'Database berhasil direstore dari backup!');

        } catch (ProcessFailedException $exception) {
            Log::error('Restore Gagal: ' . $exception->getMessage());
            return redirect()->route('backup.index')->with('error', 'Gagal merestore database. Cek log untuk detail.');
        }
    }
}

