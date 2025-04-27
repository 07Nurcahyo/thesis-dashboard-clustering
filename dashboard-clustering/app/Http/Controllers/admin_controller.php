<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class admin_controller extends Controller
{
    public function index(){
        return view('admin/main');
    }

    public function login(){
        $user = Auth::user();
        // kondisi jika usernya ada
        if ($user) {
            return redirect()->intended('/');
        }
        return view('admin/login');
    }

    public function proses_login(Request $request){
        // form username password wajib diisi
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        // ambil data request username dan password saja
        $credential = $request->only('username', 'password');
        if (Auth::attempt($credential)) {
            $user = Auth::user();
            if ($user) {
                // dd($user);
                // $request->session()->regenerate();
                return redirect()->intended('/');
            }
            // return redirect()->intended('/');
        } else {
            return redirect('login_admin')
                ->withInput()
                ->withErrors(['error' => 'Pastikan kembali username dan password yang dimasukkan sudah benar']);
        }
    }

    public function logout(Request $request){
        // logout harus menghapus session
        $request->session()->flush();
        Auth::logout();
        return redirect('login_admin');
    }

}
