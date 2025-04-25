<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class main_controller extends Controller
{
    // public function index(){
    //     return view('main');
    // }

    public function tes(){
        return view('tes');
    }
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Home',
            'list' => ['Home']
        ];
        $page = (object) [
            'title' => 'Halaman Utama'
        ];
        $activeMenu = 'home'; //set menu yang sedang aktif
        return view('main', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function lihat_data(){
        $breadcrumb = (object) [
            'title' => 'Lihat Data',
            'list' => ['Home', 'Lihat Data']
        ];
        $page = (object) [
            'title' => 'Lihat Data'
        ];
        $activeMenu = 'lihat_data'; //set menu yang sedang aktif
        return view('lihat_data', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function lihat_grafik(){
        $breadcrumb = (object) [
            'title' => 'Lihat Grafik',
            'list' => ['Home', 'Lihat Grafik']
        ];
        $page = (object) [
            'title' => 'Lihat Grafik'
        ];
        $activeMenu = 'lihat_grafik'; //set menu yang sedang aktif
        return view('lihat_grafik', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function lihat_peta(){
        $breadcrumb = (object) [
            'title' => 'Lihat Peta',
            'list' => ['Home', 'Lihat Peta']
        ];
        $page = (object) [
            'title' => 'Lihat Peta'
        ];
        $activeMenu = 'lihat_peta'; //set menu yang sedang aktif
        return view('lihat_peta', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
}
