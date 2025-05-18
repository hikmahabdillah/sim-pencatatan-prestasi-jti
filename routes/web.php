<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tes', function () {
    return view('tes');
});

Route::get('/modal', function () {
    return view('modal');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::prefix('kategori')->group(function () {
Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
Route::post('/', [KategoriController::class, 'store_ajax'])->name('kategori.store_ajax');
Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax'])->name('kategori.edit_ajax');
Route::put('/{id}', [KategoriController::class, 'update_ajax'])->name('kategori.update_ajax');
Route::delete('/{id}/delete_ajax', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});