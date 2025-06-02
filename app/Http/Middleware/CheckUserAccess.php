<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAccess
{
    public function handle(Request $request, Closure $next, $role = '')
    {
        $user = $request->user();
        $id = $request->route('id');

        if ($user->role->nama_role !== $role) {
            abort(403, 'Anda tidak punya akses ke halaman ini');
        }

        // Jika ID di route tidak sama dengan ID user
        if ($user->role_id === 3) {
            if ($user->mahasiswa->id_mahasiswa != $id) {
                abort(403, 'Gaboleh yaa ini punya mahasiswa lain');
            }
        } elseif ($user->role_id === 2) {
            if ($user->dosen->id_dospem != $id) {
                abort(403, 'Data ini milik dosen pembimbing lain');
            }
        }

        return $next($request);
    }
}
