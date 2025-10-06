<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CobacrudController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\TingkatPrestasiController;
use App\Http\Controllers\RekomendasiLombaController;
use App\Http\Controllers\LaporanPrestasiController;
use App\Notifications\RekomendasiLombaBaru;
use Illuminate\Notifications\DatabaseNotification;
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

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/tes', function () {
    return view('tes');
});

Route::get('/modal', function () {
    return view('modal');
});

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth', 'check.user.status'])->group(function () { // artinya semua route di dalam group ini harus login dulu

    // dashboard untuk semua user
    Route::get('/', [DashboardController::class, 'index']);

    // route yang hanya boleh diakses oleh admin
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

    // routes untuk prestasi controller
    Route::prefix('prestasi')->group(function () {
        Route::middleware(['authorize:Admin,Dosen Pembimbing'])->group(function () {
            Route::get('/', [PrestasiController::class, 'index']);
            Route::post('/list', [PrestasiController::class, 'list']);
        });
        Route::middleware(['authorize:Admin'])->group(function () {
            Route::get('/{id}/verifikasi-admin', [PrestasiController::class, 'getVerifikasiAdmin']); // id prestasi
            Route::put('/{id}/update-verifikasi-admin', [PrestasiController::class, 'updateVerifikasiAdmin']); // id prestasi
        });
        Route::get('/tambah-prestasi', [PrestasiController::class, 'create']);
        Route::post('/store', [PrestasiController::class, 'store']);
        Route::middleware(['check.prestasi'])->group(function () {
            Route::get('/{id}/detail-prestasi', [PrestasiController::class, 'getDetailPrestasiMahasiswa']); // id prestasi
            Route::middleware(['authorize:Dosen Pembimbing'])->group(function () {
                Route::get('/{id}/verifikasi-dospem', [PrestasiController::class, 'getVerifikasiDospem']); // id prestasi
                Route::put('/{id}/update-verifikasi-dospem', [PrestasiController::class, 'updateVerifikasiDospem']); // id prestasi
            });
            Route::middleware(['authorize:Mahasiswa'])->group(function () {
                Route::get('/{id}/edit-prestasi', [PrestasiController::class, 'getEditPrestasi']);
                Route::put('/{id}/update-prestasi', [PrestasiController::class, 'updatePrestasi']);
                Route::get('/{id}/confirm-delete-prestasi', [PrestasiController::class, 'confirmDeletePrestasi']);
                Route::delete('/{id}/delete-prestasi', [PrestasiController::class, 'deletePrestasi']);
            });
        });
    });

    Route::get('/mahasiswa/{id}/prestasi', [PrestasiController::class, 'getPrestasiMahasiswa'])
        ->name('mahasiswa.prestasi');


    // Routes untuk MahasiswaController
    Route::prefix('mahasiswa')->group(function () {
        Route::middleware(['authorize:Admin'])->group(function () {
            Route::get('/', [MahasiswaController::class, 'index']);
            Route::post('/store', [MahasiswaController::class, 'store']);
            Route::post('/list', [MahasiswaController::class, 'list']);
            Route::get('/create', [MahasiswaController::class, 'create']);
            Route::get('/{id}/show', [MahasiswaController::class, 'show']);
            Route::get('/{id}/edit', [MahasiswaController::class, 'edit']);
            Route::put('/{id}/update', [MahasiswaController::class, 'update']);
            Route::get('/{id}/confirm_delete', [MahasiswaController::class, 'confirm_delete']);
            Route::delete('/{id}/delete', [MahasiswaController::class, 'delete']);
            Route::get('/import', [MahasiswaController::class, 'import']); // ajax form uplod excel
            Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax']); //ajax import excel
        });
        Route::middleware(['check.access:Mahasiswa'])->group(function () {
            Route::get('/{id}/edit-password', [MahasiswaController::class, 'getUpdatePassword']);
            Route::put('/{id}/update-password', [MahasiswaController::class, 'updatePassword']);
            Route::get('/{id}/profile', [MahasiswaController::class, 'getProfile']);
            Route::put('/{id}/update-foto', [MahasiswaController::class, 'updateFoto']);
            Route::get('/{id}/edit-profile', [MahasiswaController::class, 'getUpdateProfile']);
            Route::put('/{id}/update-profile', [MahasiswaController::class, 'updateProfile']);
            Route::get('/{id}/prestasi', [PrestasiController::class, 'getPrestasiMahasiswa']); // id mahasiswa
        });
    });

    // Routes untuk DosenPembimbinController
    Route::prefix('dospem')->group(function () {
        Route::middleware(['authorize:Admin'])->group(function () {
            Route::get('/', [DosenPembimbingController::class, 'index']);
            Route::post('/store', [DosenPembimbingController::class, 'store']);
            Route::post('/list', [DosenPembimbingController::class, 'list']);
            Route::get('/create', [DosenPembimbingController::class, 'create']);
            Route::get('/{id}/show', [DosenPembimbingController::class, 'show']);
            Route::get('/{id}/edit', [DosenPembimbingController::class, 'edit']);
            Route::put('/{id}/update', [DosenPembimbingController::class, 'update']);
            Route::get('/{id}/confirm_delete', [DosenPembimbingController::class, 'confirm_delete']);
            Route::delete('/{id}/delete', [DosenPembimbingController::class, 'delete']);
            Route::get('/import', [DosenPembimbingController::class, 'import']);
            Route::post('/import_ajax', [DosenPembimbingController::class, 'import_ajax']);
        });
        Route::middleware(['check.access:Dosen Pembimbing'])->group(function () {
            Route::get('/{id}/edit-password', [DosenPembimbingController::class, 'getUpdatePassword']); // id dospem
            Route::put('/{id}/update-password', [DosenPembimbingController::class, 'updatePassword']);
            Route::get('/{id}/profile', [DosenPembimbingController::class, 'getProfile']);
            Route::put('/{id}/update-foto', [DosenPembimbingController::class, 'updateFoto']);
            Route::get('/{id}/edit-profile', [DosenPembimbingController::class, 'getUpdateProfile']);
            Route::put('/{id}/update-profile', [DosenPembimbingController::class, 'updateProfile']);
        });
    });

    // Routes untuk AdminController
    Route::prefix('admin')->group(function () {
        Route::middleware(['authorize:Admin'])->group(function () {
            Route::get('/', [AdminController::class, 'index']);
            Route::post('/store', [AdminController::class, 'store']);
            Route::post('/list', [AdminController::class, 'list']);
            Route::get('/create', [AdminController::class, 'create']);
            Route::get('/{id}/show', [AdminController::class, 'show']);
            Route::get('/{id}/edit-password', [AdminController::class, 'getUpdatePassword']); // id admin
            Route::put('/{id}/update-password', [AdminController::class, 'updatePassword']);
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

    // Routes untuk LombaController
    Route::prefix('lomba')->group(function () {
        Route::get('/', [LombaController::class, 'indexMahasiswa']);
        Route::get('/indexDosen', [LombaController::class, 'indexDosen']);
        Route::get('/input-lomba', [LombaController::class, 'inputLomba']);
        Route::get('/create', [LombaController::class, 'create']);
        Route::post('/store', [LombaController::class, 'store']);
        Route::get('/{id}/showDosen', [LombaController::class, 'showDosen']);
        Route::post('/listLomba', [LombaController::class, 'listLomba']);
        Route::post('/listInput', [LombaController::class, 'listInput']);
        Route::post('/listRekom', [LombaController::class, 'listRekom']);
        Route::post('/getRekombyDosen', [LombaController::class, 'getRekombyDosen']);


        Route::middleware(['authorize:Admin'])->group(function () {
            Route::get('/manajemen-lomba', [LombaController::class, 'indexAdmin'])->name('lomba.manajemen');
            Route::post('/manajemen-lomba/{id}/setujui', [LombaController::class, 'setujui']);
            Route::post('/manajemen-lomba/{id}/tolak', [LombaController::class, 'tolak']);
            Route::post('/listAdmin', [LombaController::class, 'listAdmin']);
        });

        Route::get('/{id}/edit', [LombaController::class, 'edit']);
        Route::put('/{id}/update', [LombaController::class, 'update']);
        Route::get('/{id}/confirm_delete', [LombaController::class, 'confirm_delete']);
        Route::delete('/{id}/delete', [LombaController::class, 'delete']);
        Route::get('/{id}/showInput', [LombaController::class, 'showInput']);
        Route::get('/{id}/showMahasiswa', [LombaController::class, 'showMahasiswa'])->name('lomba.showMahasiswa');

        Route::get('/{id}/show', [LombaController::class, 'show']);
    });

    Route::prefix('laporan-prestasi')->group(function () {

        // A.a - By Student (Admin only)
        Route::middleware(['authorize:Admin'])->group(function () {
            // Main page - bisa diakses admin
            Route::get('/', [LaporanPrestasiController::class, 'index'])
                ->name('laporan-prestasi.index');
            Route::get('/mahasiswa', [LaporanPrestasiController::class, 'mahasiswa'])
                ->name('laporan-prestasi.mahasiswa');
            Route::get('/mahasiswa/data', [LaporanPrestasiController::class, 'listMahasiswa'])
                ->name('laporan-prestasi.mahasiswa.list');
            Route::get('/mahasiswa/{id}', [LaporanPrestasiController::class, 'showMahasiswa'])
                ->name('laporan-prestasi.mahasiswa.show');
            Route::get('/mahasiswa/export/{id}', [LaporanPrestasiController::class, 'exportMahasiswa'])
                ->name('laporan-prestasi.export-mahasiswa');
            Route::get('/periode', [LaporanPrestasiController::class, 'listPeriode'])
                ->name('laporan-prestasi.periode');
            Route::get('/periode/data', [LaporanPrestasiController::class, 'listByPeriode'])
                ->name('laporan-prestasi.list-by-periode');
            Route::get('/periode/export/{id_periode}', [LaporanPrestasiController::class, 'exportPeriode'])
                ->name('laporan-prestasi.export-periode');
        });
        Route::get('/mahasiswa/prestasi-saya', [LaporanPrestasiController::class, 'showByUser'])
            ->name('mahasiswa.prestasi');
    });

    Route::get('/rekomendasi/{idMahasiswa}/detail', [RekomendasiLombaController::class, 'hitungRekomendasiDenganStep'])->name('rekomendasi.detail');
    Route::get('/rekomendasi/lomba/{id}', [RekomendasiLombaController::class, 'index']);
    Route::get('/admin/lomba/{id}/rekomendasi-mahasiswa', [RekomendasiLombaController::class, 'topMahasiswaLomba']);
    Route::post('/rekomendasi/simpan-dospem', [RekomendasiLombaController::class, 'simpanDospem'])->name('rekomendasi.simpanDospem');
    Route::post('/rekomendasi/by-dosen', [RekomendasiLombaController::class, 'rekombyDosen'])->name('rekomendasi.byDosen');

    Route::middleware('auth')->get('/notifikasi/baca/{id}', function ($id) {
        $notification = auth()->user()->notifications()->find($id);

        if (!$notification) {
            return redirect('/dashboard')->with('error', 'Notifikasi tidak ditemukan.');
        }

        $notification->markAsRead();

        // Pastikan URL ada dalam data notifikasi
        $redirectUrl = $notification->data['url'] ?? '/dashboard';

        return redirect($redirectUrl);
    })->name('notifikasi.baca')->where('id', '[0-9a-f-]+');
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
