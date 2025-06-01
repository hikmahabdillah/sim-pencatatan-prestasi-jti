<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // get nama role
        $user_role = $request->user()->getRole();
        // jika nama role sesuai dengan yang ada di array
        if (in_array($user_role, $roles)) {
            return $next($request);
        }

        // $user = $request->user(); // ambil data user yg login
        // // fungsi user() diambil dari UserModel.php
        // if ($user->hasRole($role)) { // cek apakah user punya role yg diinginkan | $role = 'admin'
        //     return $next($request);
        // }

        // jika tidak punya role, maka tampilkan error 403
        abort(403, 'Forbidden. Jangan coba-coba ya!');
    }
}
