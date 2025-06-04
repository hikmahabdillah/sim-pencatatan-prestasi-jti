<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('username', 'password');

            // Coba login dengan username dan password yang dimasukkan.
            if (Auth::attempt($credentials)) {
                // Periksa status aktif pengguna setelah berhasil otentikasi
                if (Auth::user()->status_aktif) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Login Berhasil',
                        'redirect' => url('/')
                    ]);
                } else {
                    $keterangan = Auth::user()->keterangan_nonaktif;
                    // Logout pengguna jika status tidak aktif
                    Auth::logout();
                    return response()->json([
                        'status' => false,
                        'message' => 'Akun Anda tidak aktif, karena ' . $keterangan
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }

    public function logout(Request $request)
    {
        // keluar dari sesi login
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
