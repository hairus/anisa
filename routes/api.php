<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EximController;
use App\Http\Controllers\KabKotaController;
use App\Http\Controllers\OpController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\SekolahFinalController;
use App\Http\Controllers\SmasController;
use App\Http\Controllers\SmpsController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiswaController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::resource('roles', RoleController::class)->middleware(['auth:sanctum', 'admin']);
Route::resource('upload', UploadController::class)->middleware(['auth:sanctum', 'operator']);
Route::resource('exim', EximController::class)->middleware(['auth:sanctum', 'operator']);
Route::resource('eximSmp', EximController::class)->middleware(['auth:sanctum', 'operator']);
Route::resource('roleUsers', RoleUserController::class)->middleware(['auth:sanctum', 'admin']);
Route::resource('smps', SmpsController::class)->middleware(['auth:sanctum', 'admin']);
Route::resource('smas', SmasController::class)->middleware(['auth:sanctum', 'admin']);
Route::resource('kabs', KabKotaController::class)->middleware(['auth:sanctum', 'admin']);
Route::get('kabs/belum/{id}', [KabKotaController::class, 'belum'])->middleware(['auth:sanctum', 'admin']);
Route::get('kabs/getData/{id}', [KabKotaController::class, 'getData'])->middleware(['auth:sanctum', 'admin']);
Route::get('kabs/sudah/{id}', [KabKotaController::class, 'sudah'])->middleware(['auth:sanctum', 'admin']);
Route::get('kabs/final/{id}', [KabKotaController::class, 'final'])->middleware(['auth:sanctum', 'admin']);
Route::get('kabs/nofinal/{id}', [KabKotaController::class, 'nofinal'])->middleware(['auth:sanctum', 'admin']);
Route::resource('users', UserController::class)->middleware(['auth:sanctum', 'admin']);
Route::resource('final', SekolahFinalController::class)->middleware(['auth:sanctum', 'admin']);
Route::get('allSiswa', [UserController::class, 'allSiswa'])->middleware(['auth:sanctum', 'admin']);

Route::group(['prefix' => 'op', 'middleware' => ['auth:sanctum', 'operator']], function(){
    Route::resource('cp', UserController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('final', SekolahFinalController::class);
    Route::resource('residu', \App\Http\Controllers\ResiduController::class);
    Route::get('getAntrian', [OpController::class, 'getAntrian']);
    Route::get('getSiswa',[OpController::class, 'getSiswa']);
    Route::get('downloadExcel',[OpController::class, 'downloadExcel']);
    Route::get('getSiswas',[OpController::class, 'getsiswas']);
    Route::post('saveSiswasDapodik',[OpController::class, 'saveSiswasDapodik']);
    Route::delete('delSiswaDapodik/{id}',[OpController::class, 'delSiswaDapodik']);
    Route::post('finalSiswa',[OpController::class, 'finalSiswa']);
    Route::put('updateSiswasDapodik/{id}',[OpController::class, 'updateSiswasDapodik']);
    Route::get('cekNilai',[OpController::class, 'cekNilai']);
});
