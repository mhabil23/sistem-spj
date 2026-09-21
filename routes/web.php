<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

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

Route::get('/admin/spj', function () {
    return view('admin.spj');
})->name('admin.spj');


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
