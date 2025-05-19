<?php

use App\Http\Controllers\CobacrudController;
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

// contoh route untuk penerapannya
Route::prefix('cobacrud')->group(function () {
    Route::get('/', [CobacrudController::class, 'index']);
    Route::post('/store', [CobacrudController::class, 'store']);
    Route::post('/list', [CobacrudController::class, 'list']);
    Route::get('/create', [CobacrudController::class, 'create']);
    Route::get('/{id}/show', [CobacrudController::class, 'show']);
    Route::get('/{id}/edit', [CobacrudController::class, 'edit']);
    Route::put('/{id}/update', [CobacrudController::class, 'update']);
    Route::get('/{id}/confirm_delete', [CobacrudController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [CobacrudController::class, 'delete']);
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
