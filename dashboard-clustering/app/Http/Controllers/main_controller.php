<?php

namespace App\Http\Controllers;

use App\Models\cluster;
use App\Models\provinsi;
use Illuminate\Http\Request;
use App\Models\data_pekerja;
use App\Models\data_pekerja_cluster;
use App\Models\iterasi_cluster_baru_default;
use App\Models\iterasi_jarak_default;
use App\Models\iterasi_sse_default;
use App\Models\sse_default;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class main_controller extends Controller
{
    // public function index(){
    //     return view('main');
    // }

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
        $cluster = cluster::all();
        $iterasi_jarak_default = iterasi_jarak_default::all();
        $iterasi_sse_default = iterasi_sse_default::all();
         // dd($provinsi);
        return view('lihat_data', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'provinsi' => $provinsi, 'cluster' => $cluster, 'iterasi_jarak_default' => $iterasi_jarak_default, 'iterasi_sse_default' => $iterasi_sse_default]);
    }
    public function list_data_pekerja(Request $request){
        // $data_pekerjas = data_pekerja::select('id', 'id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi');
        $data_pekerjas_cluster = data_pekerja_cluster::select('id', 'cluster', 'id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi', 'cluster');
        // filter
        // if ($request->id_provinsi) {
        //     $p = strval($request->id_provinsi);
        //     $data_pekerjas->where('id_provinsi',$p);
        // }
        if ($request->id_provinsi) {
            $p = strval($request->id_provinsi);
            $data_pekerjas_cluster->where('id_provinsi',$p);
        } 
        return DataTables::of($data_pekerjas_cluster)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }
    public function list_data_iterasi_default(Request $request){
        $data_iterasi = iterasi_jarak_default::select('id_iterasi_jarak_default', 'id_provinsi', 'tahun', 'jarak_c1', 'jarak_c2', 'jarak_c3', 'c_terkecil', 'cluster', 'jarak_minimum')->with('provinsi', 'cluster');
        // filter
        if ($request->id_provinsi) {
            $p = strval($request->id_provinsi);
            $data_iterasi->where('id_provinsi',$p);
        } 
        return DataTables::of($data_iterasi)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }
    public function list_data_sse(Request $request){
        $data_sse = iterasi_sse_default::select('id_iterasi_sse_default', 'id_iterasi_jarak_default', 'sse')->with('iterasi_jarak_default');
        return DataTables::of($data_sse)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }
    public function list_data_cluster_akhir(Request $request){
        $data_cluster_akhir = iterasi_cluster_baru_default::select('id_iterasi_cluster_baru_default', 'id_iterasi_sse_default', 'cluster', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('iterasi_sse_default', 'cluster');
        return DataTables::of($data_cluster_akhir)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }

    // lihat grafik
    public function lihat_grafik(){
        $breadcrumb = (object) [
            'title' => 'Lihat Grafik',
            'list' => ['Home', 'Lihat Grafik']
        ];
        $page = (object) [
            'title' => 'Lihat Grafik'
        ];
        $activeMenu = 'lihat_grafik'; //set menu yang sedang aktif
        $data_pekerja = data_pekerja::all();
        $tahunList = data_pekerja::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $provinsiList = provinsi::whereIn('id_provinsi', data_pekerja::select('id_provinsi')->distinct())
                    ->orderBy('nama_provinsi')
                    ->pluck('nama_provinsi', 'id_provinsi');
        // dd($provinsiList);
        $provinsi = Provinsi::all();
        return view('lihat_grafik', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'data_pekerja' => $data_pekerja, 'provinsiList' => $provinsiList ], compact('tahunList', 'provinsi'));
    }
    public function getDataGK(Request $request){
        $query = data_pekerja::select('provinsi.nama_provinsi', 'data_pekerja.garis_kemiskinan')
            ->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi');

        if ($request->tahun) {
            $query->where('data_pekerja.tahun', $request->tahun);
        }

        $data = $query->get();
        return response()->json($data);
    }
    public function getDataUMP(Request $request){
        $query = data_pekerja::select('provinsi.nama_provinsi', 'data_pekerja.upah_minimum')
        ->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi');

        if ($request->tahun) {
            $query->where('data_pekerja.tahun', $request->tahun);
        }

        return response()->json($query->get());
    }
    public function getDataPengeluaran(Request $request){
        $query = data_pekerja::select('provinsi.nama_provinsi', 'data_pekerja.pengeluaran')
        ->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi');

        if ($request->tahun) {
            $query->where('data_pekerja.tahun', $request->tahun);
        }

        return response()->json($query->get());
    }
    public function getDataRRU(Request $request){
        $query = data_pekerja::select('provinsi.nama_provinsi', 'data_pekerja.rr_upah')
        ->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi');

        if ($request->tahun) {
            $query->where('data_pekerja.tahun', $request->tahun);
        }

        return response()->json($query->get());
    }
    // linechart
    public function getLineGK(Request $request)
    {
        $query = data_pekerja::select('tahun', 'garis_kemiskinan')->where('id_provinsi', $request->get('id_provinsi', 1))->orderBy('tahun');
        return response()->json($query->get());
    }
    public function getLineUMP(Request $request)
    {
        $query = data_pekerja::select('tahun', 'upah_minimum')->where('id_provinsi', $request->get('id_provinsi', 1))->orderBy('tahun');
        return response()->json($query->get());
    }
    public function getLinePengeluaran(Request $request)
    {
        $query = data_pekerja::select('tahun', 'pengeluaran')->where('id_provinsi', $request->get('id_provinsi', 1))->orderBy('tahun');
        return response()->json($query->get());
    }
    public function getLineRRU(Request $request)
    {
        $query = data_pekerja::select('tahun', 'rr_upah')->where('id_provinsi', $request->get('id_provinsi', 1))->orderBy('tahun');
        return response()->json($query->get());
    }

    // lihat peta
    public function lihat_peta(){
        $breadcrumb = (object) [
            'title' => 'Lihat Peta',
            'list' => ['Home', 'Lihat Peta']
        ];
        $page = (object) [
            'title' => 'Lihat Peta'
        ];
        $activeMenu = 'lihat_peta'; //set menu yang sedang aktif
        $tahunList = data_pekerja::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        return view('lihat_peta', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'tahunList' => $tahunList]);
    }
    public function getDataPeta(Request $request){
        // $query = data_pekerja::select('provinsi.nama_provinsi', 'data_pekerja.garis_kemiskinan', 'data_pekerja.upah_minimum', 'data_pekerja.pengeluaran', 'data_pekerja.rr_upah')
        //     ->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi');
    
        // if ($request->tahun) {
        //     $query->where('data_pekerja.tahun', $request->tahun);
        // }
        $query = data_pekerja_cluster::select('provinsi.path','data_pekerja_cluster.cluster','cluster.nama_cluster', 'provinsi.nama_provinsi', 'data_pekerja_cluster.tahun', 
                                               'data_pekerja_cluster.garis_kemiskinan', 'data_pekerja_cluster.upah_minimum', 
                                               'data_pekerja_cluster.pengeluaran', 'data_pekerja_cluster.rr_upah')
            ->join('cluster', 'data_pekerja_cluster.cluster', '=', 'cluster.cluster') // Join tabel cluster
            ->join('provinsi', 'data_pekerja_cluster.id_provinsi', '=', 'provinsi.id_provinsi'); // Join tabel provinsi
    
        if ($request->tahun) {
            $query->where('data_pekerja_cluster.tahun', $request->tahun);
        }
    
        return response()->json($query->get());
    }
    
}
