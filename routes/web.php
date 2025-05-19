<?php

use App\Http\Controllers\CobacrudController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Models\KategoriModel;

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
    Route::get('/', [KategoriController::class, 'index']);
    Route::post('/store', [KategoriController::class, 'store']);
    Route::post('/list', [KategoriController::class, 'list']);
    Route::get('/create', [KategoriController::class, 'create']);
    Route::get('/{id}/show', [KategoriController::class, 'show']);
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);
    Route::put('/{id}/update', [KategoriController::class, 'update']);
    Route::get('/{id}/confirm_delete', [KategoriController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [KategoriController::class, 'delete']);
});
