<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Tentukan redirect berdasarkan role
            $redirectUrl = match ($user->role) {
                'admin' => route('dashboard'), // route dashboard admin
                'pustakawan' => route('pustakawan.dashboard'),
                'kepala_perpus' => route('kepala-perpus.dashboard'),
                default => url('/'), // fallback jika ada role lain
            };

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect()->intended($redirectUrl);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
