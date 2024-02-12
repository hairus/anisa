<?php

use App\Http\Controllers\EximController;
use App\Http\Controllers\HomeController;
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

Route::get('/', function(){
    return redirect('/login');
});
Route::get('/login', function () {
    return view('App');
});

Route::get('/admin/{any}', [HomeController::class, 'index']);
Route::get('/op/{any}', [HomeController::class, 'index']);
// Route::get('/download', [EximController::class, 'index']);
Route::get('/batch/{id}', [EximController::class, 'edit']);
Route::get('/praktek', [HomeController::class, 'praktek'])->middleware(['auth', 'admin']);
Route::get('/lgt', [HomeController::class, 'lgt'])->middleware(['auth:web', 'admin']);
