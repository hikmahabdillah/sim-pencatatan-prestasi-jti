<?php

use App\Http\Controllers\CobacrudController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TingkatPrestasiController;
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

// Routes untuk PeriodeController
Route::prefix('periode')->group(function () {
    Route::get('/', [PeriodeController::class, 'index']);
    Route::post('/store', [PeriodeController::class, 'store']);
    Route::post('/list', [PeriodeController::class, 'list']);
    Route::get('/create', [PeriodeController::class, 'create']);
    Route::get('/{id}/show', [PeriodeController::class, 'show']);
    Route::get('/{id}/edit', [PeriodeController::class, 'edit']);
    Route::put('/{id}/update', [PeriodeController::class, 'update']);
    Route::get('/{id}/confirm_delete', [PeriodeController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [PeriodeController::class, 'delete']);
});

// Routes untuk ProdiController
Route::prefix('prodi')->group(function () {
    Route::get('/', [ProdiController::class, 'index']);
    Route::post('/store', [ProdiController::class, 'store']);
    Route::post('/list', [ProdiController::class, 'list']);
    Route::get('/create', [ProdiController::class, 'create']);
    Route::get('/{id}/show', [ProdiController::class, 'show']);
    Route::get('/{id}/edit', [ProdiController::class, 'edit']);
    Route::put('/{id}/update', [ProdiController::class, 'update']);
    Route::get('/{id}/confirm_delete', [ProdiController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [ProdiController::class, 'delete']);
});

// Routes untuk RoleController
Route::prefix('role')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/store', [RoleController::class, 'store']);
    Route::post('/list', [RoleController::class, 'list']);
    Route::get('/create', [RoleController::class, 'create']);
    Route::get('/{id}/show', [RoleController::class, 'show']);
    Route::get('/{id}/edit', [RoleController::class, 'edit']);
    Route::put('/{id}/update', [RoleController::class, 'update']);
    Route::get('/{id}/confirm_delete', [RoleController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [RoleController::class, 'delete']);
});

// Routes untuk TingkatPrestasiController
Route::prefix('tingkat_prestasi')->group(function () {
    Route::get('/', [TingkatPrestasiController::class, 'index']);
    Route::post('/store', [TingkatPrestasiController::class, 'store']);
    Route::post('/list', [TingkatPrestasiController::class, 'list']);
    Route::get('/create', [TingkatPrestasiController::class, 'create']);
    Route::get('/{id}/show', [TingkatPrestasiController::class, 'show']);
    Route::get('/{id}/edit', [TingkatPrestasiController::class, 'edit']);
    Route::put('/{id}/update', [TingkatPrestasiController::class, 'update']);
    Route::get('/{id}/confirm_delete', [TingkatPrestasiController::class, 'confirm_delete']);
    Route::delete('/{id}/delete', [TingkatPrestasiController::class, 'delete']);
});
