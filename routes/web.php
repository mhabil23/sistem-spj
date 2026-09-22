<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SpjController;

Route::get('/', function () {
    return view('home');
});

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Daftar SPJ
    Route::get('/spj', [SpjController::class, 'index'])->name('spj.index');
    Route::get('/spj/create', [SpjController::class, 'create'])->name('spj.create');
    Route::post('/spj', [SpjController::class, 'store'])->name('spj.store');

    // Pengguna
    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [UserController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');

    // Riwayat
    Route::get('/riwayat', function () {
        return view('admin.riwayat');
    })->name('riwayat');
});


// Riwayat handled above


use App\Http\Controllers\Teknis\DashboardController as TeknisDashboardController;
use App\Http\Controllers\Teknis\SpjController as TeknisSpjController;
use App\Http\Controllers\Teknis\RiwayatController as TeknisRiwayatController;
use App\Http\Controllers\Teknis\DokumenController as TeknisDokumenController;
use App\Http\Controllers\Teknis\ProfilController as TeknisProfilController;

/*
|--------------------------------------------------------------------------
| TEKNIS
|--------------------------------------------------------------------------
*/

Route::redirect('/teknis', '/teknis/dashboard');
Route::middleware(['auth', 'role:teknis'])->prefix('teknis')->name('teknis.')->group(function () {
    Route::get('/dashboard', [TeknisDashboardController::class, 'index'])->name('dashboard');
    Route::resource('spj', TeknisSpjController::class);
    Route::get('/riwayat', [TeknisRiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/dokumen', [TeknisDokumenController::class, 'index'])->name('dokumen.index');
    Route::post('/dokumen/{spj}/upload', [TeknisDokumenController::class, 'upload'])->name('dokumen.upload');
    Route::get('/profil', [TeknisProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [TeknisProfilController::class, 'update'])->name('profil.update');
});


/*
|--------------------------------------------------------------------------
| UMUM
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Umum\DashboardController as UmumDashboardController;
use App\Http\Controllers\Umum\SpjController as UmumSpjController;
use App\Http\Controllers\Umum\ProfilController as UmumProfilController;
use App\Http\Controllers\Umum\RiwayatController as UmumRiwayatController;

Route::redirect('/umum', '/umum/dashboard');
Route::middleware(['auth', 'role:umum'])->prefix('umum')->name('umum.')->group(function () {
    Route::get('/dashboard', [UmumDashboardController::class, 'index'])->name('dashboard');
    Route::get('/spj', [UmumSpjController::class, 'index'])->name('spj.index');
    Route::get('/spj/{id}', [UmumSpjController::class, 'show'])->name('spj.show');
    Route::get('/spj/{id}/print', [UmumSpjController::class, 'print'])->name('spj.print');
    Route::post('/spj/{id}/verify', [UmumSpjController::class, 'verify'])->name('spj.verify');
    
    Route::get('/riwayat', [UmumRiwayatController::class, 'index'])->name('riwayat.index');
    Route::delete('/riwayat/{id}', [UmumRiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    Route::get('/profil', [UmumProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [UmumProfilController::class, 'update'])->name('profil.update');
});


/*
|--------------------------------------------------------------------------
| PPK
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Ppk\DashboardController as PpkDashboardController;
use App\Http\Controllers\Ppk\SpjController as PpkSpjController;
use App\Http\Controllers\Ppk\RiwayatController as PpkRiwayatController;
use App\Http\Controllers\Ppk\ProfilController as PpkProfilController;

use App\Http\Controllers\Ppspm\DashboardController as PpspmDashboardController;
use App\Http\Controllers\Ppspm\SpjController as PpspmSpjController;
use App\Http\Controllers\Ppspm\RiwayatController as PpspmRiwayatController;
use App\Http\Controllers\Ppspm\ProfilController as PpspmProfilController;

// Controllers untuk Bendahara
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboardController;
use App\Http\Controllers\Bendahara\SpjController as BendaharaSpjController;
use App\Http\Controllers\Bendahara\RiwayatController as BendaharaRiwayatController;
use App\Http\Controllers\Bendahara\ProfilController as BendaharaProfilController;

Route::redirect('/ppk', '/ppk/dashboard');
Route::middleware(['auth', 'role:ppk'])->prefix('ppk')->name('ppk.')->group(function () {
    Route::get('/dashboard', [PpkDashboardController::class, 'index'])->name('dashboard');
    Route::get('/spj', [PpkSpjController::class, 'index'])->name('spj.index');
    Route::post('/spj/bulk-verify', [PpkSpjController::class, 'bulkVerify'])->name('spj.bulk-verify');
    Route::get('/spj/{id}', [PpkSpjController::class, 'show'])->name('spj.show');
    Route::post('/spj/{id}/verify', [PpkSpjController::class, 'verify'])->name('spj.verify');
    
    Route::get('/riwayat', [PpkRiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/export/csv', [PpkRiwayatController::class, 'exportCsv'])->name('riwayat.export.csv');
    Route::get('/riwayat/export/pdf', [PpkRiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');
    Route::delete('/riwayat/{id}', [PpkRiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    Route::get('/profil', [PpkProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [PpkProfilController::class, 'update'])->name('profil.update');
});

// ==========================================
// MODUL PPSPM (Pejabat Penandatangan SPM)
// ==========================================
Route::middleware(['auth', 'role:ppspm'])->prefix('ppspm')->name('ppspm.')->group(function () {
    Route::get('/dashboard', [PpspmDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/spj', [PpspmSpjController::class, 'index'])->name('spj.index');
    Route::get('/spj/{id}', [PpspmSpjController::class, 'show'])->name('spj.show');
    Route::post('/spj/{id}/verify', [PpspmSpjController::class, 'verify'])->name('spj.verify');
    Route::post('/spj/bulk-verify', [PpspmSpjController::class, 'bulkVerify'])->name('spj.bulk-verify');
    
    Route::get('/riwayat', [PpspmRiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/export/csv', [PpspmRiwayatController::class, 'exportCsv'])->name('riwayat.export.csv');
    Route::get('/riwayat/export/pdf', [PpspmRiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');
    Route::delete('/riwayat/{id}', [PpspmRiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    Route::get('/profil', [PpspmProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [PpspmProfilController::class, 'update'])->name('profil.update');
});

// ==========================================
// MODUL BENDAHARA (Pencairan Dana)
// ==========================================
Route::middleware(['auth', 'role:bendahara'])->prefix('bendahara')->name('bendahara.')->group(function () {
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/spj', [BendaharaSpjController::class, 'index'])->name('spj.index');
    Route::get('/spj/{id}', [BendaharaSpjController::class, 'show'])->name('spj.show');
    Route::post('/spj/{id}/verify', [BendaharaSpjController::class, 'verify'])->name('spj.verify');
    Route::post('/spj/bulk-verify', [BendaharaSpjController::class, 'bulkVerify'])->name('spj.bulk-verify');
    
    Route::get('/riwayat', [BendaharaRiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('/riwayat/export/csv', [BendaharaRiwayatController::class, 'exportCsv'])->name('riwayat.export.csv');
    Route::get('/riwayat/export/pdf', [BendaharaRiwayatController::class, 'exportPdf'])->name('riwayat.export.pdf');
    Route::delete('/riwayat/{id}', [BendaharaRiwayatController::class, 'destroy'])->name('riwayat.destroy');
    
    Route::get('/profil', [BendaharaProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil', [BendaharaProfilController::class, 'update'])->name('profil.update');
});
