<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Cek_login
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //cek sudah login apa belum, jika belum kembali ke hal login
        if (!Auth::check()) {
            redirect(route('login_admin'));
        }
        //simpan data user pada variabel user
        $admin = Auth::user();
        //jika user memiliki level sesuai pada kolom maka lanjutkan request
        if ($admin) {
            return $next($request);
        }
        //simpan data user pada variabel user
        return redirect('login_admin')->with('error', 'Maaf anda tidak memiliki akses');
    }
}
