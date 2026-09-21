<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/pengguna', function () {
    return view('admin.pengguna');
})->name('admin.users.index');

Route::get('/teknis/dashboard', function () {
    return view('teknis.dashboard');
})->name('teknis.dashboard');

Route::get('/umum/dashboard', function () {
    return view('umum.dashboard');
})->name('umum.dashboard');
