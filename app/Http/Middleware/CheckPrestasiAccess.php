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
        $idMahasiswa = $user->mahasiswa->id_mahasiswa ?? ''; // id mahasiswa yang saat ini sedang login
        $idDospem = $user->dosen->id_dospem ?? ''; // id dospem yang saat ini sedang login
        $prestasi = PrestasiMahasiswaModel::findOrFail($idPrestasi);

        switch ($user->role->nama_role) {
            case 'Mahasiswa':
                // jika prestasi denngan id mahasiswa yang coba diakses tidak sama dengan id mahasiswa yang login saat ini
                if ($prestasi->id_mahasiswa != $idMahasiswa) {
                    abort(403, 'Anda hanya bisa mengakses prestasi milik sendiri');
                }
                break;

            case 'Dosen Pembimbing':
                // jika prestasi denngan id dospem yang coba diakses tidak sama dengan id dospem yang login saat ini
                if ($prestasi->id_dospem != $idDospem) {
                    abort(403, 'Anda hanya bisa mengakses prestasi yang Anda bimbing');
                }
                break;

            case 'Admin':

                break;

            default:
                abort(403, 'Role tidak dikenali');
        }

        return $next($request);
    }
}
