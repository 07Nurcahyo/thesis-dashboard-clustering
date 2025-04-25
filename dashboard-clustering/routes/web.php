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

Route::get('/login_admin', [admin_controller::class, 'login'])->name('login');
Route::post('proses_login', [admin_controller::class, 'proses_login'])->name('proses_login');
Route::get('logout', [admin_controller::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function() {
    Route::group(['middleware' => ['cek_login']], function() {
        Route::get('admin', [admin_controller::class, 'index']);
    });
});