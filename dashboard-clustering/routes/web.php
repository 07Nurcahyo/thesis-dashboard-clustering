<?php

use App\Http\Controllers\admin_controller;
use App\Http\Controllers\main_controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/tes', [main_controller::class, 'tes'])->name('tes');
Route::get('/', [main_controller::class, 'index']);
Route::get('/lihat_data', [main_controller::class, 'lihat_data'])->name('lihat_data');
Route::get('/lihat_grafik', [main_controller::class, 'lihat_grafik'])->name('lihat_grafik');
Route::get('/lihat_peta', [main_controller::class, 'lihat_peta'])->name('lihat_peta');
Route::get('/get-pie-chart', [main_controller::class, 'getPieChart']);
Route::get('/data-sse', [admin_controller::class, 'getDataSSE'])->name('data.sse');
Route::get('/elbow-data', [admin_controller::class, 'getElbowData'])->name('data.elbow');
Route::get('/silhouette-score', [admin_controller::class, 'silhouetteScore'])->name('silhouette.score');

Route::group(['prefix' => 'lihat_data'], function() {
    Route::get('list', [main_controller::class, 'list_data_pekerja'])->name('list_data_pekerja');
    Route::get('list_data_iterasi_default', [main_controller::class, 'list_data_iterasi_default'])->name('list_data_iterasi_default');
    Route::get('list_data_sse', [main_controller::class, 'list_data_sse'])->name('list_data_sse');
    Route::get('list_data_cluster_akhir', [main_controller::class, 'list_data_cluster_akhir'])->name('list_data_cluster_akhir');
});

Route::get('/login_admin', [admin_controller::class, 'login'])->name('login');
Route::post('proses_login', [admin_controller::class, 'proses_login'])->name('proses_login');
Route::get('logout', [admin_controller::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function() {
    Route::group(['middleware' => ['cek_login']], function() {
        
        Route::group(['prefix' => 'admin'], function() {
            Route::get('/', [admin_controller::class, 'index']);
            Route::get('/dashboard', [admin_controller::class, 'index']);
            Route::get('/get-pie-chart', [main_controller::class, 'getPieChart']);
            Route::get('/kelola_data', [admin_controller::class, 'kelola_data']);
                Route::get('list_data_pekerja', [admin_controller::class, 'list_data_pekerja'])->name('list_data_pekerja');
                Route::get('/create', [admin_controller::class, 'create_data_pekerja'])->name('create_data_pekerja');
                Route::post('/preview_csv', [admin_controller::class, 'preview_csv']);
                Route::post('/import_csv', [admin_controller::class, 'import_csv']);
                Route::post('/store_data_pekerja', [admin_controller::class, 'store_data_pekerja'])->name('store_data_pekerja');
                Route::get('/{id}/edit_data_pekerja', [admin_controller::class, 'edit_data_pekerja'])->name('edit_data_pekerja');
                Route::get('/{id}/edit-json', [admin_controller::class, 'edit_json']);
                Route::put('/{id}', [admin_controller::class, 'update_data_pekerja'])->name('update_data_pekerja');
                Route::delete('/{id}', [admin_controller::class, 'destroy_data_pekerja'])->name('destroy_data_pekerja');
            Route::get('/clustering', [admin_controller::class, 'clustering']);
                Route::get('list_data_cluster_awal', [admin_controller::class, 'list_data_cluster_awal'])->name('list_data_cluster_awal');
                Route::post('cluster-awal/acak', [admin_controller::class, 'simpanDataAcak'])->name('cluster-awal.acak');
                Route::get('edit-cluster-awal/{id}', [admin_controller::class, 'edit_cluster_awal']);
                Route::put('update-cluster-awal/{id}', [admin_controller::class, 'update_cluster_awal']);
                Route::delete('/{id}', [admin_controller::class, 'destroy_cluster_awal'])->name('destroy_cluster_awal');
                // Route::get('/{id}/edit_cluster_awal', [admin_controller::class, 'edit_cluster_awal'])->name('edit_cluster_awal');
                // Route::put('/{id}', [admin_controller::class, 'update_cluster_awal'])->name('update_cluster_awal');
                Route::post('ganti-manual-cluster', [admin_controller::class, 'gantiManual'])->name('ganti.manual.cluster');
                Route::get('list_data_iterasi', [admin_controller::class, 'list_data_iterasi'])->name('list_data_iterasi');
                Route::get('list_iterasi_sse', [admin_controller::class, 'list_iterasi_sse'])->name('list_iterasi_sse');
                Route::get('list_iterasi_cluster_baru', [admin_controller::class, 'list_iterasi_cluster_baru'])->name('list_iterasi_cluster_baru');
                Route::get('list_data_hasil_akhir', [admin_controller::class, 'list_data_hasil_akhir'])->name('list_data_hasil_akhir');
                Route::post('jalankan-kmeans', [admin_controller::class, 'jalankan'])->name('jalankan.kmeans');
                // Route::get('/data-sse', [admin_controller::class, 'getDataSSE'])->name('data.sse');
                // Route::get('/elbow-data', [admin_controller::class, 'getElbowData'])->name('data.elbow');
                // Route::get('/silhouette-score', [admin_controller::class, 'silhouetteScore'])->name('silhouette.score');

        });

    });
});