<?php

namespace App\Http\Middleware;

use App\Models\LombaModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLombaAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $lombaId = $request->route('id');

        $lomba = LombaModel::findOrFail($lombaId);

        if ($user->role->nama_role === 'Admin') {
            return $next($request);
        }

        if ($lomba->added_by != $user->id_pengguna) {
            abort(403, 'Anda hanya bisa mengakses lomba yang Anda tambahkan');
        }

        return $next($request);
    }
}
