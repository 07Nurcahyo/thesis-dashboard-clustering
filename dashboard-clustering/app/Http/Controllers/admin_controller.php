<?php

namespace App\Http\Controllers;

use App\Models\cluster;
use App\Models\data_pekerja;
use App\Models\data_pekerja_cluster;
use App\Models\iterasi_cluster_awal;
use App\Models\iterasi_cluster_baru;
use App\Models\iterasi_jarak;
use App\Models\iterasi_jarak_default;
use App\Models\iterasi_sse;
use App\Models\iterasi_sse_default;
use App\Models\provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class admin_controller extends Controller
{
    public function index(){
        // return view('admin/main');
        $breadcrumb = (object) [
            'title' => 'Dashboard',
            'list' => ['Home']
        ];
        $page = (object) [
            'title' => ''
        ];
        $activeMenu = 'dashboard'; //set menu yang sedang aktif
        $provinsi = Provinsi::all();
        $data_pekerja = data_pekerja::all();
        $tahunList = data_pekerja::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $provinsiList = provinsi::whereIn('id_provinsi', data_pekerja::select('id_provinsi')->distinct())
                    ->orderBy('nama_provinsi')
                    ->pluck('nama_provinsi', 'id_provinsi');
        return view('admin/main', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'provinsi' => $provinsi, 'data_pekerja' => $data_pekerja, 'tahunList' => $tahunList, 'provinsiList' => $provinsiList]);
    }

    public function login(){
        $user = Auth::user();
        // kondisi jika usernya ada
        if ($user) {
            return redirect()->intended('admin');
        }
        return view('admin/login');
    }

    // public function proses_login(Request $request){
    //     // form username password wajib diisi
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);
    //     // ambil data request username dan password saja
    //     $credential = $request->only('username', 'password');
    //     if (Auth::attempt($credential)) {
    //         $user = Auth::user();
    //         if ($user) {
    //             // dd($user);
    //             // $request->session()->regenerate();
    //             return redirect()->intended('/admin');
    //         }
    //         // return redirect()->intended('/');
    //     } else {
    //         return redirect('login_admin')
    //             ->withInput()
    //             ->withErrors(['error' => 'Pastikan kembali username dan password yang dimasukkan sudah benar']);
    //     }
    // }
    public function proses_login(Request $request)
    {
        // Validasi form username dan password
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        // Ambil data username dan password
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->ajax()) {
                return response()->json(['status' => 'success']);
            } else {
                return redirect()->intended('/admin');
            }
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Username atau Password salah!'
                ]);
            } else {
                return redirect('login_admin')
                    ->withInput()
                    ->withErrors(['error' => 'Pastikan kembali username dan password yang dimasukkan sudah benar']);
            }
        }
    }

    public function logout(Request $request){
        // logout harus menghapus session
        $request->session()->flush();
        Auth::logout();
        return redirect('login_admin');
    }

    public function kelola_data(){
        $breadcrumb = (object) [
            'title' => 'Kelola Data',
            'list' => ['Home', 'Kelola Data']
        ];
        $page = (object) [
            'title' => ''
        ];
        $activeMenu = 'kelola_data'; //set menu yang sedang aktif
        $data_pekerja = data_pekerja::all();
        $provinsi = provinsi::all();
        $cluster = cluster::all();
        return view('admin/kelola_data', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'provinsi' => $provinsi, 'cluster' => $cluster, 'data_pekerja' => $data_pekerja]);
    }
    public function list_data_pekerja(Request $request){
        // $data_pekerjas = data_pekerja::select('id', 'id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi');
        $data_pekerjas = data_pekerja::select('data_pekerja.id', 'data_pekerja.id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi')->join('provinsi', 'data_pekerja.id_provinsi', '=', 'provinsi.id_provinsi')->orderBy('nama_provinsi', 'asc')->orderBy('tahun', 'asc');
        // filter
        if ($request->id_provinsi) {
            $p = strval($request->id_provinsi);
            $data_pekerjas->where('id_provinsi',$p);
        } 
        return DataTables::of($data_pekerjas)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($data_pekerja) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/admin/' . $data_pekerja->id . '/edit').'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="'.$data_pekerja->id.'"><i class="fas fa-edit"></i></button> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/admin/'.$data_pekerja->id).'" id="delete_'.$data_pekerja->id.'">'. csrf_field() . method_field('DELETE') .'<button type="" class="btn btn-danger btn-sm" onclick="return deleteConfirm(\''.$data_pekerja->id.'\');"><i class="fas fa-trash-alt"></i></button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function create_data_pekerja(){
        $provinsi = Provinsi::all(); // Sesuaikan modelnya
        return view('admin.kelola_data', compact('provinsi'));
    }
    public function store_data_pekerja(Request $request){
         $request->validate([
            'data.*.id_provinsi' => 'required|exists:provinsi,id_provinsi',
            'data.*.tahun' => 'required|numeric',
            'data.*.garis_kemiskinan' => 'required|numeric',
            'data.*.upah_minimum' => 'required|numeric',
            'data.*.pengeluaran' => 'required|numeric',
            'data.*.rr_upah' => 'required|numeric',
        ]);

        foreach ($request->data as $item) {
            data_pekerja::create($item);
        }

        return response()->json(['success' => true]);
    }
    public function preview_csv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,xlsx,xls,txt',
        ]);

        $collection = Excel::toCollection(null, $request->file('csv_file'));
        $sheet = $collection->first();
        $rows = $sheet->take(20)->values(); // ambil max 20 baris untuk preview
        $headers = $rows->first()->keys();

        return response()->json([
            'columns' => $headers,
            'rows' => $rows->toArray(),
        ]);
    }
    public function import_csv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,xlsx,xls,txt',
        ]);

        $collection = Excel::toCollection(null, $request->file('csv_file'));
        $sheet = $collection->first();

        $i=0;
        foreach ($sheet as $row) {
            if (empty($row[0])) {
                continue; // Skip empty rows
            }
            if (!in_array($i, $request->get('row_ids', []))) {
                $i++;
                continue;
            }
            $i++;
            data_pekerja::create([
                // 'id_provinsi' => $row['id_provinsi'],
                'id_provinsi' => provinsi::where('nama_provinsi', $row['provinsi'] ?? $row[0])->first()->id_provinsi,
                'tahun' => $row['tahun'] ?? $row[1],
                'garis_kemiskinan' => $row['garis_kemiskinan'] ?? $row[2],
                'upah_minimum' => $row['upah_minimum'] ?? $row[3],
                'pengeluaran' => $row['pengeluaran'] ?? $row[4],
                'rr_upah' => $row['rr_upah'] ?? $row[5],
            ]);
        }

        return response()->json(['success' => true]);
    }
    public function edit_data_pekerja($id){
        $breadcrumb = (object) [
            'title' => 'Edit Data Pekerja',
            'list' => ['Home', 'Kelola Data', 'Edit Data Pekerja']
        ];
        $page = (object) [
            'title' => ''
        ];
        $activeMenu = 'kelola_data'; //set menu yang sedang aktif
        $data_pekerja = data_pekerja::find($id);
        $provinsi = provinsi::all();
        return view('admin/edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'provinsi' => $provinsi, 'data_pekerja' => $data_pekerja]);
    }
    public function edit_json($id)
    {
        $data = data_pekerja::findOrFail($id);
        return response()->json($data);
    }
    public function update_data_pekerja(Request $request, $id){
        $data_pekerja = data_pekerja::find($id);
        $data_pekerja->id_provinsi = $request->id_provinsi;
        $data_pekerja->tahun = $request->tahun;
        $data_pekerja->garis_kemiskinan = $request->garis_kemiskinan;
        $data_pekerja->upah_minimum = $request->upah_minimum;
        $data_pekerja->pengeluaran = $request->pengeluaran;
        $data_pekerja->rr_upah = $request->rr_upah;
        if ($data_pekerja->save()) {
            return redirect('/admin/kelola_data')->with('success', 'Data berhasil diubah');
        } else {
            return redirect('/admin/kelola_data')->with('error', 'Data gagal diubah');
        }
    }
    public function destroy_data_pekerja($id){
        // $data_pekerja = data_pekerja::find($id);
        // if ($data_pekerja->delete()) {
        //     return redirect('/admin/kelola_data')->with('success', 'Data berhasil dihapus');
        // } else {
        //     return redirect('/admin/kelola_data')->with('error', 'Data gagal dihapus');
        // }
        $check = data_pekerja::find($id);
        if (!$check) {
            return redirect('/admin/kelola_data')->with('error', 'Data buku tidak ditemukan');
        }
        try {
            data_pekerja::destroy($id);
            return redirect('/admin/kelola_data');
        } catch (\Illuminate\Database\QueryException $te) {
            return redirect('/admin/kelola_data')->with('error', 'Data buku gagal di hapus karena masih terdapat table lain terkait dengan data ini');
        }
    }

    // Clustering
    public function clustering(){
        $breadcrumb = (object) [
            'title' => 'Clustering',
            'list' => ['Home', 'K-Means Clustering']
        ];
        $page = (object) [
            'title' => ''
        ];
        $activeMenu = 'clustering'; //set menu yang sedang aktif
        $data_pekerja = data_pekerja::all();
        $provinsi = provinsi::all();
        $iterasi_cluster_awal = iterasi_cluster_awal::all();
        $cluster = cluster::all();
        $iterasi_jarak = iterasi_jarak::all();
        return view('admin/clustering', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'data_pekerja' => $data_pekerja, 'provinsi' => $provinsi, 'iterasi_cluster_awal' => $iterasi_cluster_awal, 'cluster' => $cluster ,'iterasi_jarak' => $iterasi_jarak]);
    }
    public function list_data_cluster_awal(Request $request){
        $data_cluster_awals = iterasi_cluster_awal::select('id_iterasi_cluster_awal', 'cluster', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('cluster')->get();
        return DataTables::of($data_cluster_awals)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($data_cluster_awal) { // menambahkan kolom aksi
                // $btn = '<a href="'.url('/admin/' . $data_pekerja->id . '/edit').'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
                $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="'.$data_cluster_awal->id_iterasi_cluster_awal.'"><i class="fas fa-edit"></i></button> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'.url('/admin/'.$data_cluster_awal->id_iterasi_cluster_awal).'" id="delete_'.$data_cluster_awal->id_iterasi_cluster_awal.'">'. csrf_field() . method_field('DELETE') .'<button type="" class="btn btn-danger btn-sm" onclick="return deleteConfirm(\''.$data_cluster_awal->id_iterasi_cluster_awal.'\');"><i class="fas fa-trash-alt"></i></button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function simpanDataAcak(Request $request)
    {
        // 1. Kosongkan tabel dulu
        iterasi_cluster_awal::truncate();

        // 2. Masukkan 3 baris data dengan cluster 1, 2, dan 3
        for ($i = 1; $i <= 3; $i++) {
            iterasi_cluster_awal::create([
                'id_data_pekerja' => 1, // bisa diganti nanti kalau dinamis
                'cluster' => $i, // Mengisi cluster dengan angka 1, 2, atau 3
                'garis_kemiskinan' => rand(1000000, 9999999),
                'upah_minimum' => rand(1000000, 9999999),
                'pengeluaran' => rand(1000000, 9999999),
                'rr_upah' => rand(10000, 99999),
            ]);
        }

        return response()->json(['status' => 'success', 'message' => '3 data dengan cluster 1, 2, 3 berhasil disimpan setelah reset.']);
    }

    public function list_data_iterasi(Request $request){
        $data_iterasi = iterasi_jarak::select('id_iterasi_jarak', 'id_provinsi','cluster', 'tahun', 'jarak_c1', 'jarak_c2', 'jarak_c3', 'c_terkecil', 'cluster', 'jarak_minimum')->with('provinsi','cluster')->get();
        return DataTables::of($data_iterasi)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    public function list_iterasi_sse(Request $request){
        $data_sse = iterasi_sse::select('id_iterasi_sse', 'sse')->get();
        return DataTables::of($data_sse)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }
    public function list_iterasi_cluster_baru(Request $request){
        $data_iterasi_cluster_baru = iterasi_cluster_baru::select('id_iterasi_cluster_baru', 'cluster', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('cluster')->get();
        return DataTables::of($data_iterasi_cluster_baru)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }
    public function list_data_hasil_akhir(Request $request){
        // $data_cluster_akhir = data_pekerja_cluster::select('id', 'cluster', 'id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('cluster','provinsi')->get();
        $data_cluster_akhir = data_pekerja_cluster::select('data_pekerja_cluster.id', 'data_pekerja_cluster.cluster', 'data_pekerja_cluster.id_provinsi', 'tahun', 'garis_kemiskinan', 'upah_minimum', 'pengeluaran', 'rr_upah')->with('provinsi', 'cluster')->join('provinsi', 'data_pekerja_cluster.id_provinsi', '=', 'provinsi.id_provinsi')->orderBy('nama_provinsi', 'asc')->orderBy('tahun', 'asc');
        if ($request->id_provinsi) {
            $p = strval($request->id_provinsi);
            // $data_cluster_akhir->where('id_provinsi',$p);
            $data_cluster_akhir->where('data_pekerja_cluster.id_provinsi', $p);
        }
        return DataTables::of($data_cluster_akhir)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->make(true);
    }




    // api
    public function deleteDataPekerja(String $id){
        // dd($id);
        logger()->info('deleteDataPekerja: '.$id);
        $check = data_pekerja::find($id);
        if (!$check) {
            // return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
            return '';
        }
        try {
            data_pekerja::destroy($id);
            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\QueryException $te) {
            return response()->json(['success' => false, 'error' => 'Data buku gagal di hapus karena masih terdapat table lain terkait dengan data ini']);
        }
        return view('admin/kelola_data', ['id' => $id]);
    }

}
