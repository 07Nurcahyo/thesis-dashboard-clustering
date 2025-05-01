<?php

use App\Http\Controllers\main_controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getDataGK', [main_controller::class, 'getDataGK']);
Route::get('getDataUMP', [main_controller::class, 'getDataUMP']);
Route::get('getDataPengeluaran', [main_controller::class, 'getDataPengeluaran']);
Route::get('getDataRRU', [main_controller::class, 'getDataRRU']);

Route::get('getLineGK', [main_controller::class, 'getLineGK']);
Route::get('getLineUMP', [main_controller::class, 'getLineUMP']);
Route::get('getLinePengeluaran', [main_controller::class, 'getLinePengeluaran']);
Route::get('getLineRRU', [main_controller::class, 'getLineRRU']);