<?php

namespace App\Http\Controllers;

use App\Models\cluster;
use App\Models\data_pekerja;
use App\Models\data_pekerja_cluster;
use App\Models\iterasi_jarak_default;
use App\Models\iterasi_sse_default;
use App\Models\provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

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
        return view('admin/main', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
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
        // return DataTables::of($data_pekerjas)
        //     ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        //     ->addColumn('aksi', function ($data_pekerja) { // menambahkan kolom aksi
        //         // $btn = '<a href="'.url('/admin/' . $data_pekerja->id . '/edit').'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> ';
        //         $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="'.$data_pekerja->id.'"><i class="fas fa-edit"></i></button> ';
        //         $btn .= '<form class="d-inline-block" method="POST" action="'.url('/admin/'.$data_pekerja->id).'" id="delete_'.$data_pekerja->id.'">'. csrf_field() . method_field('DELETE') .'<button type="" class="btn btn-danger btn-sm" onclick="return deleteConfirm(\''.$data_pekerja->id.'\');"><i class="fas fa-trash-alt"></i></button></form>';
        //         return $btn;
        //     })
        //     ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        //     ->make(true);
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
