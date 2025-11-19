<?php

namespace App\Http\Controllers\KepalaPerpus;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::where('role', 'pustakawan')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->get();

        return view('kepala-perpus.user.index', compact('users'));
    }

    public function exportPDF(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::where('role', 'pustakawan')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->get();

        $pdf = Pdf::loadView('kepala-perpus.user.export-pdf', compact('users'));
        return $pdf->download('data-pustakawan-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = $request->get('search');
        
        return Excel::download(new UserExport($search), 'data-pustakawan-' . date('Y-m-d') . '.xlsx');
    }
}