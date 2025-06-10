<?php

namespace App\Http\Middleware;

use App\Models\PrestasiMahasiswaModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPrestasiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $idPrestasi = $request->route('id');

        // Ambil data prestasi
        $prestasi = PrestasiMahasiswaModel::with('anggota')->find($idPrestasi);

        if (!$prestasi) {
            abort(404, 'Data prestasi tidak ditemukan');
        }

        $role = $user->role->nama_role ?? null;

        if (!$role) {
            abort(403, 'Role tidak dikenali');
        }

        switch ($role) {
            case 'Mahasiswa':
                $idMahasiswa = $user->mahasiswa->id_mahasiswa ?? null;

                // Cek apakah mahasiswa ini termasuk anggota prestasi
                $isAnggota = $prestasi->anggota->contains('id_mahasiswa', $idMahasiswa);

                if (!$idMahasiswa || !$isAnggota) {
                    abort(403, 'Anda hanya bisa mengakses prestasi yang Anda ikuti');
                }
                break;

            case 'Dosen Pembimbing':
                $idDospem = $user->dosen->id_dospem ?? null;

                if (!$idDospem || $prestasi->id_dospem != $idDospem) {
                    abort(403, 'Anda hanya bisa mengakses prestasi yang Anda bimbing');
                }
                break;

            case 'Admin':
                // Admin memiliki akses penuh
                break;

            default:
                abort(403, 'Role tidak dikenali');
        }

        return $next($request);
    }
}
