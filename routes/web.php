<?php

use App\Http\Controllers\ParkirController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Page;
use Illuminate\Support\Facades\Route;

// 1. RUTE PUBLIK
Route::get('/', [Page::class, 'showLanding'])->name('landing');

// Sisi Pengendara Mandiri
Route::get('/pengendara', [ParkirController::class, 'indexPengendara'])->name('parkir.index');
Route::post('/checkin', [ParkirController::class, 'checkIn'])->name('parkir.checkin');

// Autentikasi Petugas (DIPERBAIKI: NAMA ROUTE MENJADI 'login')
Route::get('/login', [Page::class, 'showLogin'])->name('login');
Route::post('/login/proses', [Page::class, 'prosesLogin'])->name('login.proses');
Route::post('/logout', [Page::class, 'prosesLogout'])->name('logout');
Route::post('/parkir/checkout/{id}', [ParkirController::class, 'checkout'])->name('parkir.checkout');

// 2. RUTE TERPROTEKSI (MUST LOGIN)
Route::middleware(['auth'])->group(function () {

    // KELOMPOK AKSES: JURU PARKIR (JUKIR)
    Route::middleware(['role:JUKIR'])->group(function () {
        Route::get('/jukir/dashboard', [AreaController::class, 'dashboardJukir'])->name('jukir.dashboard');
        
        // Jukir bisa input check-in atas nama jukir & melakukan check-out
        Route::post('/jukir/checkin', [ParkirController::class, 'checkIn'])->name('jukir.checkin');
        Route::post('/checkout/{id}', [ParkirController::class, 'checkOut'])->name('parkir.checkout');
    });

    // KELOMPOK AKSES: ADMIN MANAJEMEN
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::get('/admin/dashboard', [AreaController::class, 'dashboardAdmin'])->name('admin.dashboard');
        Route::post('/admin/area/update-kuota', [AreaController::class, 'updateKuota'])->name('admin.update_kuota');
    });

});