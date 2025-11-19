<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Hanya tampilkan user dengan role admin dan pustakawan
        $users = User::whereIn('role', ['admin', 'pustakawan'])
                    ->orderBy('name')
                    ->paginate(10);
                    
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,pustakawan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        // Prevent editing kepala_perpus
        if ($user->role === 'kepala_perpus') {
            abort(403, 'Tidak dapat mengedit user dengan role Kepala Perpustakaan');
        }
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Prevent updating kepala_perpus
        if ($user->role === 'kepala_perpus') {
            abort(403, 'Tidak dapat mengupdate user dengan role Kepala Perpustakaan');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:admin,pustakawan',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting kepala_perpus
        if ($user->role === 'kepala_perpus') {
            abort(403, 'Tidak dapat menghapus user dengan role Kepala Perpustakaan');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
