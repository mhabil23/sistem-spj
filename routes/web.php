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

Route::middleware(['auth', 'role:umum'])->prefix('umum')->name('umum.')->group(function () {
    Route::get('/dashboard', function () {
        return view('umum.dashboard');
    })->name('dashboard');
});
