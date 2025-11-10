<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Buku;
use App\Models\PeminjamanBuku;
use App\Models\PengembalianBuku;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data statistik
        $totalUsers = User::count();
        $totalBooks = Buku::count();
        $totalLoans = PeminjamanBuku::count();
        $totalReturns = PengembalianBuku::count();

        return view('admin.dashboard', compact('totalUsers', 'totalBooks', 'totalLoans', 'totalReturns'));
    }
}
