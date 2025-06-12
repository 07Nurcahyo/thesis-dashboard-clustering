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
use Phpml\Clustering\KMeans;
use Phpml\Math\Distance\Euclidean;
use Illuminate\Support\Collection;

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
            $data_pekerjas->where('data_pekerja.id_provinsi',$p);
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
    public function edit_cluster_awal($id)
    {
        $data_cluster_awal = iterasi_cluster_awal::find($id);
        if ($data_cluster_awal) {
            return response()->json([
                'success' => true,
                'data_cluster_awal' => $data_cluster_awal
            ]);
        }
        return response()->json(['success' => false], 404);
    }
    public function update_cluster_awal(Request $request, $id){
        $data_cluster_awal = iterasi_cluster_awal::find($id);
        $data_cluster_awal->id_data_pekerja = $request->id_data_pekerja;
        $data_cluster_awal->cluster = $request->cluster;
        $data_cluster_awal->garis_kemiskinan = $request->garis_kemiskinan;
        $data_cluster_awal->upah_minimum = $request->upah_minimum;
        $data_cluster_awal->pengeluaran = $request->pengeluaran;
        $data_cluster_awal->rr_upah = $request->rr_upah;
        if ($data_cluster_awal->save()) {
            return redirect('/admin/clustering')->with('success', 'Data berhasil diubah');
        } else {
            return redirect('/admin/clustering')->with('error', 'Data gagal diubah');
        }
    }
    public function destroy_cluster_awal($id){
        $check = iterasi_cluster_awal::find($id);
        if (!$check) {
            return redirect('/admin/clustering')->with('error', 'Data cluster tidak ditemukan');
        }
        try {
            iterasi_cluster_awal::destroy($id);
            return redirect('/admin/clustering');
        } catch (\Illuminate\Database\QueryException $te) {
            return redirect('/admin/clustering')->with('error', 'Data cluster gagal di hapus karena masih terdapat table lain terkait dengan data ini');
        }
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
    public function gantiManual(Request $request)
    {
        $dataBaru = $request->input('data');

        // Validasi jumlah data
        if (count($dataBaru) !== 3) {
            return back()->with('error', 'Harus memasukkan tepat 3 data.');
        }

        // Hapus 3 data awal (opsional: bisa yang terbaru atau random)
        iterasi_cluster_awal::orderBy('created_at', 'asc')->limit(3)->delete();

        // Simpan data baru
        $i = 1;
        foreach ($dataBaru as $item) {
            iterasi_cluster_awal::create([
                'id_data_pekerja' => 1,
                'cluster' => $i++, // atau ambil dari logic sebelumnya
                'garis_kemiskinan' => $item['garis_kemiskinan'],
                'upah_minimum' => $item['upah_minimum'],
                'pengeluaran' => $item['pengeluaran'],
                'rr_upah' => $item['rr_upah'],
            ]);
        }

        return back()->with('success', '3 data berhasil diganti secara manual.');
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

    private function hapus()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        data_pekerja_cluster::truncate();
        iterasi_cluster_baru::truncate();
        // iterasi_sse::truncate();
        iterasi_jarak::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }


    // public function jalankan()
    // {
    //     $data = DB::table('data_pekerja')->get()->map(function ($item) {
    //         return [
    //             'id' => $item->id,
    //             'id_provinsi' => $item->id_provinsi,
    //             'tahun' => $item->tahun,
    //             'fitur' => [
    //                 (double) $item->garis_kemiskinan,
    //                 (double) $item->upah_minimum,
    //                 (double) $item->pengeluaran,
    //                 (double) $item->rr_upah
    //             ]
    //         ];
    //     });

    //     // Ambil centroid awal dari cluster_awal
    //     $clusterAwal = DB::table('iterasi_cluster_awal')->orderBy('cluster')->get();
    //     $centroids = [];
    //     foreach ($clusterAwal as $item) {
    //         $centroids[$item->cluster - 1] = [
    //             (double) $item->garis_kemiskinan,
    //             (double) $item->upah_minimum,
    //             (double) $item->pengeluaran,
    //             (double) $item->rr_upah
    //         ];
    //     }

    //     $maxIterations = 100;
    //     $threshold = 0.001;

    //     for ($iterasi = 1; $iterasi <= $maxIterations; $iterasi++) {
    //         $clusters = [[], [], []];
    //         $totalSSE = 0;

    //         foreach ($data as $point) {
    //             $distances = [];
    //             foreach ($centroids as $centroid) {
    //                 $distances[] = $this->euclideanDistance($point['fitur'], $centroid);
    //             }

    //             $minIndex = array_keys($distances, min($distances))[0];
    //             $clusters[$minIndex][] = $point;
    //             $squaredError = pow($distances[$minIndex], 2);
    //             $totalSSE += $squaredError;

    //             // Simpan iterasi jarak
    //             $idIterasiJarak = DB::table('iterasi_jarak')->insertGetId([
    //                 'id_provinsi' => $point['id_provinsi'],
    //                 'tahun' => $point['tahun'],
    //                 'jarak_c1' => $distances[0],
    //                 'jarak_c2' => $distances[1],
    //                 'jarak_c3' => $distances[2],
    //                 'c_terkecil' => $distances[$minIndex],
    //                 'cluster' => $minIndex + 1,
    //                 'jarak_minimum' => $squaredError,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //         }
    //         // Simpan SSE
    //         DB::table('iterasi_sse')->insert([
    //             'id_iterasi_jarak' => $idIterasiJarak,
    //             'sse' => $squaredError,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //         // Hitung centroid baru
    //         $newCentroids = [];
    //         foreach ($clusters as $index => $clusterPoints) {
    //             if (count($clusterPoints) === 0) {
    //                 $newCentroids[$index] = $centroids[$index];
    //                 continue;
    //             }

    //             $sum = array_fill(0, 4, 0);
    //             foreach ($clusterPoints as $point) {
    //                 foreach ($point['fitur'] as $i => $val) {
    //                     $sum[$i] += $val;
    //                 }
    //             }

    //             $newCentroid = array_map(fn($x) => $x / count($clusterPoints), $sum);
    //             $newCentroids[$index] = $newCentroid;

    //             // Simpan cluster baru
    //             $lastSSEId = DB::table('iterasi_sse')->latest('id_iterasi_sse')->value('id_iterasi_sse');
    //             DB::table('iterasi_cluster_baru')->insert([
    //                 'id_iterasi_sse' => $lastSSEId,
    //                 'cluster' => $index + 1,
    //                 'garis_kemiskinan' => $newCentroid[0],
    //                 'upah_minimum' => $newCentroid[1],
    //                 'pengeluaran' => $newCentroid[2],
    //                 'rr_upah' => $newCentroid[3],
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }

    //         // Cek konvergensi
    //         $delta = 0;
    //         for ($i = 0; $i < 3; $i++) {
    //             $delta += $this->euclideanDistance($centroids[$i], $newCentroids[$i]);
    //         }

    //         if ($delta < $threshold) break;
    //         else $this->hapus();
    //         $centroids = $newCentroids;
    //     }

    //     // Simpan hasil akhir keanggotaan
    //     foreach ($clusters as $clusterIndex => $clusterPoints) {
    //         foreach ($clusterPoints as $point) {
    //             DB::table('data_pekerja_cluster')->insert([
    //                 'cluster' => $clusterIndex + 1,
    //                 'id_provinsi' => $point['id_provinsi'],
    //                 'tahun' => $point['tahun'],
    //                 'garis_kemiskinan' => $point['fitur'][0],
    //                 'upah_minimum' => $point['fitur'][1],
    //                 'pengeluaran' => $point['fitur'][2],
    //                 'rr_upah' => $point['fitur'][3],
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //     }
    //     return redirect()->back()->with('success', 'Clustering berhasil dilakukan dan disimpan.');
    // }

    public function jalankan()
    {
        // Simpan semua ID iterasi_sse sebelum clustering baru
        $oldSSEIds = DB::table('iterasi_sse')->pluck('id_iterasi_sse')->toArray();

        $data = DB::table('data_pekerja')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'id_provinsi' => $item->id_provinsi,
                'tahun' => $item->tahun,
                'fitur' => [
                    (double) $item->garis_kemiskinan,
                    (double) $item->upah_minimum,
                    (double) $item->pengeluaran,
                    (double) $item->rr_upah
                ]
            ];
        });

        // Ambil centroid awal dari cluster_awal
        $clusterAwal = DB::table('iterasi_cluster_awal')->orderBy('cluster')->get();
        $centroids = [];
        foreach ($clusterAwal as $item) {
            $centroids[$item->cluster - 1] = [
                (double) $item->garis_kemiskinan,
                (double) $item->upah_minimum,
                (double) $item->pengeluaran,
                (double) $item->rr_upah
            ];
        }

        $maxIterations = 100;
        $threshold = 0.001;

        for ($iterasi = 1; $iterasi <= $maxIterations; $iterasi++) {
            $clusters = [[], [], []];
            $totalSSE = 0;

            foreach ($data as $point) {
                $distances = [];
                foreach ($centroids as $centroid) {
                    $distances[] = $this->euclideanDistance($point['fitur'], $centroid);
                }

                $minIndex = array_keys($distances, min($distances))[0];
                $clusters[$minIndex][] = $point;
                $squaredError = pow($distances[$minIndex], 2);
                $totalSSE += $squaredError;

                // Simpan iterasi jarak
                $idIterasiJarak = DB::table('iterasi_jarak')->insertGetId([
                    'id_provinsi' => $point['id_provinsi'],
                    'tahun' => $point['tahun'],
                    'jarak_c1' => $distances[0],
                    'jarak_c2' => $distances[1],
                    'jarak_c3' => $distances[2],
                    'c_terkecil' => $distances[$minIndex],
                    'cluster' => $minIndex + 1,
                    'jarak_minimum' => $squaredError,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Simpan SSE (berdasarkan total SSE dari semua data)
            DB::table('iterasi_sse')->insert([
                'id_iterasi_jarak' => $idIterasiJarak,
                'sse' => $totalSSE,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Hitung centroid baru
            $newCentroids = [];
            foreach ($clusters as $index => $clusterPoints) {
                if (count($clusterPoints) === 0) {
                    $newCentroids[$index] = $centroids[$index];
                    continue;
                }

                $sum = array_fill(0, 4, 0);
                foreach ($clusterPoints as $point) {
                    foreach ($point['fitur'] as $i => $val) {
                        $sum[$i] += $val;
                    }
                }

                $newCentroid = array_map(fn($x) => $x / count($clusterPoints), $sum);
                $newCentroids[$index] = $newCentroid;

                // Simpan cluster baru
                $lastSSEId = DB::table('iterasi_sse')->latest('id_iterasi_sse')->value('id_iterasi_sse');
                DB::table('iterasi_cluster_baru')->insert([
                    'id_iterasi_sse' => $lastSSEId,
                    'cluster' => $index + 1,
                    'garis_kemiskinan' => $newCentroid[0],
                    'upah_minimum' => $newCentroid[1],
                    'pengeluaran' => $newCentroid[2],
                    'rr_upah' => $newCentroid[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Cek konvergensi
            $delta = 0;
            for ($i = 0; $i < 3; $i++) {
                $delta += $this->euclideanDistance($centroids[$i], $newCentroids[$i]);
            }

            if ($delta < $threshold) break;
            else $this->hapus();

            $centroids = $newCentroids;
        }

        // Hapus iterasi_sse lama (setelah selesai clustering)
        if (!empty($oldSSEIds)) {
            DB::table('iterasi_sse')->whereIn('id_iterasi_sse', $oldSSEIds)->delete();
        }

        // Simpan hasil akhir keanggotaan
        foreach ($clusters as $clusterIndex => $clusterPoints) {
            foreach ($clusterPoints as $point) {
                DB::table('data_pekerja_cluster')->insert([
                    'cluster' => $clusterIndex + 1,
                    'id_provinsi' => $point['id_provinsi'],
                    'tahun' => $point['tahun'],
                    'garis_kemiskinan' => $point['fitur'][0],
                    'upah_minimum' => $point['fitur'][1],
                    'pengeluaran' => $point['fitur'][2],
                    'rr_upah' => $point['fitur'][3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Clustering berhasil dilakukan dan disimpan.');
    }


    private function euclideanDistance(array $a, array $b)
    {
        return sqrt(array_sum(array_map(fn($x, $y) => pow($x - $y, 2), $a, $b)));
    }

    public function getDataSSE()
    {
        $sseData = DB::table('iterasi_sse')
            ->select('id_iterasi_sse', 'sse')
            ->orderBy('id_iterasi_sse')
            ->get();

        // $labels = $sseData->pluck('id_iterasi_sse');
        $labels = range(1, $sseData->count());
        $values = $sseData->pluck('sse');

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    // public function getElbowData()
    // {
    //     // Ambil data pekerja
    //     $data = DB::table('data_pekerja')->get()->map(function ($item) {
    //         return [
    //             'fitur' => [
    //                 (double) $item->garis_kemiskinan,
    //                 (double) $item->upah_minimum,
    //                 (double) $item->pengeluaran,
    //                 (double) $item->rr_upah
    //             ]
    //         ];
    //     })->toArray();

    //     $results = [];

    //     // Coba dari K = 2 sampai K = 10
    //     for ($k = 2; $k <= 10; $k++) {
    //         // Ambil centroid awal dari DB (hanya sebanyak K)
    //         $clusterAwal = DB::table('iterasi_cluster_awal')
    //             ->whereBetween('cluster', [1, $k])
    //             ->orderBy('cluster')
    //             ->get();

    //         $centroids = [];
    //         foreach ($clusterAwal as $item) {
    //             $centroids[] = [
    //                 (double) $item->garis_kemiskinan,
    //                 (double) $item->upah_minimum,
    //                 (double) $item->pengeluaran,
    //                 (double) $item->rr_upah
    //             ];
    //         }

    //         $clusters = array_fill(0, $k, []);
    //         $sse = 0;

    //         // Klasifikasi setiap data ke centroid terdekat
    //         foreach ($data as $point) {
    //             $fitur = $point['fitur'];
    //             $distances = array_map(function ($centroid) use ($fitur) {
    //                 return sqrt(array_sum(array_map(function ($a, $b) {
    //                     return pow($a - $b, 2);
    //                 }, $fitur, $centroid)));
    //             }, $centroids);

    //             $minIndex = array_keys($distances, min($distances))[0];
    //             $clusters[$minIndex][] = $fitur;

    //             $sse += pow($distances[$minIndex], 2);
    //         }

    //         $results[] = [
    //             'k' => $k,
    //             'sse' => $sse
    //         ];
    //     }

    //     return response()->json($results);
    // }

    public function getElbowData()
    {
        // Ambil semua SSE dari tabel iterasi_sse, urut berdasarkan id_iterasi_sse
        $allSSE = DB::table('iterasi_sse')
            ->orderBy('id_iterasi_sse')
            ->pluck('sse')
            ->toArray();

        $results = [];

        // Karena k dari 2 sampai 10, dan array SSE mungkin mulai dari index 0,
        // kita ambil SSE dari index k - 2
        for ($k = 2; $k <= 8; $k++) {
            $index = $k - 2;

            $sse = isset($allSSE[$index]) ? (double) $allSSE[$index] : 0;

            $results[] = [
                'k' => $k,
                'sse' => $sse
            ];
        }

        return response()->json($results);
    }

    // public function silhouetteScore()
    // {
    //     // Ambil data hasil clustering (data_pekerja_cluster)
    //     $dataClustered = DB::table('data_pekerja_cluster')->get()->map(function ($item) {
    //         return [
    //             'id_provinsi' => $item->id_provinsi,
    //             'tahun' => $item->tahun,
    //             'fitur' => [
    //                 (double) $item->garis_kemiskinan,
    //                 (double) $item->upah_minimum,
    //                 (double) $item->pengeluaran,
    //                 (double) $item->rr_upah
    //             ],
    //             'cluster' => $item->cluster
    //         ];
    //     });

    //     if ($dataClustered->isEmpty()) {
    //         return response()->json(['silhouette_score' => null, 'message' => 'Data clustering belum ada']);
    //     }

    //     // Group data by cluster
    //     $clusters = $dataClustered->groupBy('cluster');

    //     // Fungsi hitung jarak Euclidean
    //     $euclideanDistance = function($a, $b) {
    //         $sum = 0;
    //         foreach ($a as $i => $val) {
    //             $sum += pow($val - $b[$i], 2);
    //         }
    //         return sqrt($sum);
    //     };

    //     $silhouetteScores = [];

    //     foreach ($dataClustered as $point) {
    //         $ownCluster = $point['cluster'];
    //         $ownFeatures = $point['fitur'];

    //         // a(i): rata-rata jarak ke semua titik lain dalam cluster yang sama
    //         $sameClusterPoints = $clusters[$ownCluster]->filter(fn($p) => $p !== $point);
    //         if ($sameClusterPoints->count() > 0) {
    //             $a = $sameClusterPoints->reduce(function($carry, $p) use ($euclideanDistance, $ownFeatures) {
    //                 return $carry + $euclideanDistance($ownFeatures, $p['fitur']);
    //             }, 0) / $sameClusterPoints->count();
    //         } else {
    //             $a = 0;
    //         }

    //         // b(i): rata-rata jarak ke cluster terdekat berikutnya (cluster lain dengan rata-rata jarak terkecil)
    //         $b = null;
    //         foreach ($clusters as $clusterId => $pointsInCluster) {
    //             if ($clusterId == $ownCluster) continue;

    //             $avgDist = $pointsInCluster->reduce(function($carry, $p) use ($euclideanDistance, $ownFeatures) {
    //                 return $carry + $euclideanDistance($ownFeatures, $p['fitur']);
    //             }, 0) / $pointsInCluster->count();

    //             if ($b === null || $avgDist < $b) {
    //                 $b = $avgDist;
    //             }
    //         }

    //         // Hitung silhouette score untuk point i
    //         $s = 0;
    //         if ($a < $b) {
    //             $s = 1 - ($a / $b);
    //         } elseif ($a > $b) {
    //             $s = ($b / $a) - 1;
    //         }
    //         // jika a == b, s = 0

    //         $silhouetteScores[] = $s;
    //     }

    //     // Rata-rata silhouette score seluruh data
    //     $avgSilhouette = count($silhouetteScores) ? array_sum($silhouetteScores) / count($silhouetteScores) : 0;

    //     // Kembalikan sebagai JSON (bisa dipakai AJAX)
    //     return response()->json(['silhouette_score' => round($avgSilhouette, 4)]);
    // }

    public function silhouetteScore()
    {
        $dataClustered = DB::table('data_pekerja_cluster')->get()->map(function ($item) {
            return [
                'id_provinsi' => $item->id_provinsi,
                'tahun' => $item->tahun,
                'fitur' => [
                    (double) $item->garis_kemiskinan,
                    (double) $item->upah_minimum,
                    (double) $item->pengeluaran,
                    (double) $item->rr_upah
                ],
                'cluster' => $item->cluster
            ];
        });

        if ($dataClustered->isEmpty()) {
            return response()->json(['silhouette_score' => null, 'message' => 'Data clustering belum ada']);
        }

        $clusters = $dataClustered->groupBy('cluster');

        $euclideanDistance = function($a, $b) {
            $sum = 0;
            foreach ($a as $i => $val) {
                $sum += pow($val - $b[$i], 2);
            }
            return sqrt($sum);
        };

        $silhouetteScores = [];
        $silhouettePerCluster = [];

        foreach ($dataClustered as $point) {
            $ownCluster = $point['cluster'];
            $ownFeatures = $point['fitur'];

            $sameClusterPoints = $clusters[$ownCluster]->filter(fn($p) => $p !== $point);
            if ($sameClusterPoints->count() > 0) {
                $a = $sameClusterPoints->reduce(function($carry, $p) use ($euclideanDistance, $ownFeatures) {
                    return $carry + $euclideanDistance($ownFeatures, $p['fitur']);
                }, 0) / $sameClusterPoints->count();
            } else {
                $a = 0;
            }

            $b = null;
            foreach ($clusters as $clusterId => $pointsInCluster) {
                if ($clusterId == $ownCluster) continue;
                $avgDist = $pointsInCluster->reduce(function($carry, $p) use ($euclideanDistance, $ownFeatures) {
                    return $carry + $euclideanDistance($ownFeatures, $p['fitur']);
                }, 0) / $pointsInCluster->count();

                if ($b === null || $avgDist < $b) {
                    $b = $avgDist;
                }
            }

            $s = 0;
            if ($a < $b) {
                $s = 1 - ($a / $b);
            } elseif ($a > $b) {
                $s = ($b / $a) - 1;
            }

            $silhouetteScores[] = $s;

            if (!isset($silhouettePerCluster[$ownCluster])) {
                $silhouettePerCluster[$ownCluster] = [];
            }
            $silhouettePerCluster[$ownCluster][] = $s;
        }

        $avgSilhouette = count($silhouetteScores) ? array_sum($silhouetteScores) / count($silhouetteScores) : 0;

        // Hitung rata-rata silhouette per cluster
        $avgPerCluster = [];
        foreach ($silhouettePerCluster as $clusterId => $scores) {
            $avgPerCluster[$clusterId] = count($scores) ? array_sum($scores) / count($scores) : 0;
        }

        return response()->json([
            'silhouette_score' => round($avgSilhouette, 4),
            'per_cluster' => $avgPerCluster
        ]);
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
