<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SpjController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RiwayatController;

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
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| DATA SPJ
|--------------------------------------------------------------------------
*/

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

// Form edit SPJ
Route::get('/admin/spj/{spj}/edit', [SpjController::class, 'edit'])
    ->name('admin.spj.edit');

// Update SPJ
Route::put('/admin/spj/{id}', [SpjController::class, 'update'])
    ->name('admin.spj.update');

// Hapus SPJ
Route::delete('/admin/spj/{spj}', [SpjController::class, 'destroy'])
    ->name('admin.spj.destroy');


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

    // Form edit pengguna
    Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])
        ->name('pengguna.edit');

    // Update pengguna
    Route::put('/pengguna/{user}', [UserController::class, 'update'])
        ->name('pengguna.update');

    // Hapus pengguna
    Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])
        ->name('pengguna.destroy');
});

/*
|--------------------------------------------------------------------------
| LAPORAN SPJ
|--------------------------------------------------------------------------
*/

Route::get('/admin/laporan-spj', [SpjController::class, 'laporan'])
    ->name('admin.laporan.spj');

/*
|--------------------------------------------------------------------------
| RIWAYAT
|--------------------------------------------------------------------------
*/
Route::get('/admin/riwayat', [RiwayatController::class, 'index'])
    ->name('admin.riwayat');
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
