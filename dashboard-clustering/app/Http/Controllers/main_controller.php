<?php

namespace App\Http\Controllers;

use App\Models\provinsi;
use Illuminate\Http\Request;
use App\Models\data_pekerja;
use Yajra\DataTables\Facades\DataTables;

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
        $provinsi = provinsi::all();
         // dd($provinsi);
        return view('lihat_data', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'provinsi' => $provinsi]);
    }
    public function list_data_pekerja(Request $request){
        $data_pekerjas = data_pekerja::select('id', 'id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi');
        $data_provinsis = provinsi::all();
        // filter
        if ($request->id_provinsi) {
            $p = strval($request->id_provinsi);
            // $provinsis->where('id_provinsi', 'like', '%' . $p . '%');
            $data_pekerjas->where('id_provinsi',$p);
        }
        return DataTables::of($data_pekerjas)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($data_pekerja) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/lihat_data/' . $data_pekerja->id).'" class="btn btn-info btn-sm">Detail <i class="fas fa-info-circle"></i></a> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
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
