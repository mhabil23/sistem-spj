<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SpjController;

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login');
})->name('login');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');


/*
|--------------------------------------------------------------------------
| DATA SPJ
|--------------------------------------------------------------------------
*/

// Daftar SPJ
Route::get('/admin/spj', [SpjController::class, 'index'])
    ->name('admin.spj.index');

// Form tambah SPJ
Route::get('/admin/spj/create', [SpjController::class, 'create'])
    ->name('admin.spj.create');

// Simpan SPJ
Route::post('/admin/spj', [SpjController::class, 'store'])
    ->name('admin.spj.store');


/*
|--------------------------------------------------------------------------
| PENGGUNA
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Daftar pengguna
    Route::get('/pengguna', [UserController::class, 'index'])
        ->name('pengguna.index');

    // Form tambah pengguna
    Route::get('/pengguna/create', [UserController::class, 'create'])
        ->name('pengguna.create');

    // Simpan pengguna
    Route::post('/pengguna', [UserController::class, 'store'])
        ->name('pengguna.store');
});


/*
|--------------------------------------------------------------------------
| RIWAYAT
|--------------------------------------------------------------------------
*/

Route::get('/admin/riwayat', function () {
    return view('admin.riwayat');
})->name('admin.riwayat');


/*
|--------------------------------------------------------------------------
| TEKNIS
|--------------------------------------------------------------------------
*/

Route::get('/teknis/dashboard', function () {
    return view('teknis.dashboard');
})->name('teknis.dashboard');


/*
|--------------------------------------------------------------------------
| UMUM
|--------------------------------------------------------------------------
*/

Route::get('/umum/dashboard', function () {
    return view('umum.dashboard');
})->name('umum.dashboard');
