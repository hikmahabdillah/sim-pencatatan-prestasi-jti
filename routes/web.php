<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CobacrudController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PrestasiController;
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

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
    Route::middleware(['authorize:Admin'])->group(function () {
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
    });

    Route::prefix('prestasi')->group(function () {
        Route::get('/tambah-prestasi', [PrestasiController::class, 'create']);
        Route::post('/store', [PrestasiController::class, 'store']);
        Route::get('/{id}/edit-prestasi', [PrestasiController::class, 'getEditPrestasi']);
        Route::put('/{id}/update-prestasi', [PrestasiController::class, 'updatePrestasi']);
        Route::get('/{id}/confirm-delete-prestasi', [PrestasiController::class, 'confirmDeletePrestasi']);
        Route::delete('/{id}/delete-prestasi', [PrestasiController::class, 'deletePrestasi']);
    });

    // Routes untuk MahasiswaController
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index']);
        Route::post('/store', [MahasiswaController::class, 'store']);
        Route::post('/list', [MahasiswaController::class, 'list']);
        Route::get('/create', [MahasiswaController::class, 'create']);
        Route::get('/{id}/show', [MahasiswaController::class, 'show']);
        Route::get('/{id}/profile', [MahasiswaController::class, 'getProfile']);
        Route::put('/{id}/update-foto', [MahasiswaController::class, 'updateFoto']);
        Route::get('/{id}/edit-profile', [MahasiswaController::class, 'getUpdateProfile']);
        Route::put('/{id}/update-profile', [MahasiswaController::class, 'updateProfile']);
        Route::get('/{id}/edit', [MahasiswaController::class, 'edit']);
        Route::put('/{id}/update', [MahasiswaController::class, 'update']);
        Route::get('/{id}/confirm_delete', [MahasiswaController::class, 'confirm_delete']);
        Route::delete('/{id}/delete', [MahasiswaController::class, 'delete']);
        Route::get('/{id}/prestasi', [PrestasiController::class, 'getPrestasiMahasiswa']);
        Route::get('/{id}/detail-prestasi', [PrestasiController::class, 'getDetailPrestasiMahasiswa']);
    });

    // Routes untuk DosenPembimbinController
    Route::prefix('dospem')->group(function () {
        Route::get('/', [DosenPembimbingController::class, 'index']);
        Route::post('/store', [DosenPembimbingController::class, 'store']);
        Route::post('/list', [DosenPembimbingController::class, 'list']);
        Route::get('/create', [DosenPembimbingController::class, 'create']);
        Route::get('/{id}/show', [DosenPembimbingController::class, 'show']);
        Route::get('/{id}/profile', [DosenPembimbingController::class, 'getProfile']);
        Route::put('/{id}/update-foto', [DosenPembimbingController::class, 'updateFoto']);
        Route::get('/{id}/edit-profile', [DosenPembimbingController::class, 'getUpdateProfile']);
        Route::put('/{id}/update-profile', [DosenPembimbingController::class, 'updateProfile']);
        Route::get('/{id}/edit', [DosenPembimbingController::class, 'edit']);
        Route::put('/{id}/update', [DosenPembimbingController::class, 'update']);
        Route::get('/{id}/confirm_delete', [DosenPembimbingController::class, 'confirm_delete']);
        Route::delete('/{id}/delete', [DosenPembimbingController::class, 'delete']);
    });
     // Routes untuk AdminController
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/store', [AdminController::class, 'store']);
        Route::post('/list', [AdminController::class, 'list']);
        Route::get('/create', [AdminController::class, 'create']);
        Route::get('/{id}/show', [AdminController::class, 'show']);
        Route::get('/{id}/profile', [AdminController::class, 'getProfile']);
        Route::put('/{id}/update-foto', [AdminController::class, 'updateFoto']);
        Route::get('/{id}/edit-profile', [AdminController::class, 'getUpdateProfile']);
        Route::put('/{id}/update-profile', [AdminController::class, 'updateProfile']);
        Route::get('/{id}/edit', [AdminController::class, 'edit']);
        Route::put('/{id}/update', [AdminController::class, 'update']);
        Route::get('/{id}/confirm_delete', [AdminController::class, 'confirm_delete']);
        Route::delete('/{id}/delete', [AdminController::class, 'delete']);
    });
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
