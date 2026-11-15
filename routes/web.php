<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SistemController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Parkir System Universitas Siliwangi
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE PUBLIK & AUTHENTICATION
// ==========================================
Route::get('/', [Page::class, 'showLanding'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [Page::class, 'showLogin'])->name('login');
    Route::post('/login/proses', [Page::class, 'prosesLogin'])->name('login.proses');

    Route::get('/register', [Page::class, 'showRegister'])->name('register');
    Route::post('/register/proses', [SistemController::class, 'prosesRegister'])->name('register.proses');
});

Route::post('/logout', [Page::class, 'prosesLogout'])->name('logout')->middleware('auth');


// ==========================================
// 2. RUTE TERPROTEKSI BERDASARKAN ROLE (MUST LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Check-Out Kendaraan (Hanya Jukir & Admin yang dapat memproses keluar)
    Route::post('/parkir/checkout/{id}', [ParkirController::class, 'checkOut'])
        ->middleware('role:JUKIR,ADMIN')
        ->name('parkir.checkout');

    // ------------------------------------------
    // A. KELOMPOK AKSES: PENGENDARA
    // ------------------------------------------
    Route::middleware(['role:PENGENDARA'])->prefix('pengendara')->name('pengendara.')->group(function () {
        // Dashboard Pengendara (QR Permanen, Riwayat & Info Slot Kampus)
        Route::get('/dashboard', [ParkirController::class, 'indexPengendara'])->name('dashboard');
        
        // Update Plat Nomor & Jenis Kendaraan
        Route::post('/update-profile', [ParkirController::class, 'updateProfile'])->name('update_profile');
    });

    // ------------------------------------------
    // B. KELOMPOK AKSES: JURU PARKIR (JUKIR)
    // ------------------------------------------
    Route::middleware(['role:JUKIR'])->prefix('jukir')->name('jukir.')->group(function () {
        // Page 1: POS Scan QR Universal & Input Manual
        Route::get('/dashboard', [AreaController::class, 'dashboardJukir'])->name('dashboard');
        
        // Page 2: Monitoring Denah Slot Lapangan
        Route::get('/monitoring', [Page::class, 'monitoring'])->name('monitoringjukir');
        
        // Proses Scan QR Universal (Auto Masuk/Keluar) & Input Manual Masuk
        Route::post('/checkin', [ParkirController::class, 'checkInJukir'])->name('checkin');
    });

    // ------------------------------------------
    // C. KELOMPOK AKSES: ADMIN MANAJEMEN
    // ------------------------------------------
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin Command Center
        Route::get('/dashboard', [AreaController::class, 'dashboardAdmin'])->name('dashboard');
        
        // Detail Grid Virtual Slotting per Area Fakultas
        Route::get('/area/{id}/slots', [AreaController::class, 'detailSlotArea'])->name('area.slots');
        
        // Update Kapasitas Total Area
        Route::post('/area/update-kuota', [AreaController::class, 'updateKuota'])->name('update_kuota');
        
        // Manajemen Data Petugas Parkir (Jukir)
        Route::get('/petugas', [Page::class, 'petugas'])->name('petugas');
        Route::post('/petugas/store', [SistemController::class, 'storePetugas'])->name('petugas.store');
        Route::post('/petugas/update/{id}', [SistemController::class, 'updatePetugas'])->name('petugas.update');
        Route::delete('/petugas/delete/{id}', [SistemController::class, 'deletePetugas'])->name('petugas.delete');
    });

});