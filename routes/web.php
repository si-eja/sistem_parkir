<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Page;
use App\Http\Controllers\ParkirController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\SistemController;

/*
|--------------------------------------------------------------------------
| Web Routes - E-Parkir System (Universal Architecture)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. RUTE PUBLIK & AUTHENTICATION
// ==========================================

// Landing Page utama (Cek status real-time durasi & check-out mandiri)
Route::get('/', [Page::class, 'showLanding'])->name('landing');

// Autentikasi Pengguna & Petugas (Login, Register, Logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [Page::class, 'showLogin'])->name('login');
    Route::post('/login/proses', [Page::class, 'prosesLogin'])->name('login.proses');

    // Register Pengendara Mandiri Universal (5 Data: Email, Password, Name, Plat Nomor, Jenis Kendaraan)
    Route::get('/register', [Page::class, 'showRegister'])->name('register');
    Route::post('/register/proses', [SistemController::class, 'prosesRegister'])->name('register.proses');
});

// Logout (Hanya bisa dipanggil oleh pengguna yang sedang login)
Route::post('/logout', [Page::class, 'prosesLogout'])->name('logout')->middleware('auth');

// Check-Out Kendaraan (Dapat dipanggil publik dari landing page / modal pengendara)
Route::post('/parkir/checkout/{id}', [ParkirController::class, 'checkOut'])->name('parkir.checkout');


// ==========================================
// 2. RUTE TERPROTEKSI BERDASARKAN ROLE (MUST LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------
    // A. KELOMPOK AKSES: PENGENDARA MANDIRI
    // ------------------------------------------
    Route::middleware(['role:PENGENDARA'])->prefix('pengendara')->name('pengendara.')->group(function () {
        // Dashboard Pengendara (Menampilkan Kartu QR/Barcode Permanen & Form Check-In)
        Route::get('/dashboard', [ParkirController::class, 'indexPengendara'])->name('dashboard');
        
        // Process Check-In Mandiri (Auto-alokasi slot FT001-FT200 & rekomendasi)
        Route::post('/checkin', [ParkirController::class, 'checkIn'])->name('checkin');

        // ROUTE BARU: Update Plat Nomor & Profil Kendaraan
        Route::post('/pengendara/update-profile', [ParkirController::class, 'updateProfile'])->name('pengendara.update_profile');
    });

    // ------------------------------------------
    // B. KELOMPOK AKSES: JURU PARKIR (JUKIR)
    // ------------------------------------------
    Route::middleware(['role:JUKIR'])->prefix('jukir')->name('jukir.')->group(function () {
        // Dashboard Jukir (Monitoring area tugas & scanner QR)
        Route::get('/dashboard', [AreaController::class, 'dashboardJukir'])->name('dashboard');
        
        // Input Check-In oleh Jukir (Bisa manual plat nomor / hasil scan barcode pengendara)
        Route::post('/checkin', [ParkirController::class, 'checkInJukir'])->name('checkin');
    });

    // ------------------------------------------
    // C. KELOMPOK AKSES: ADMIN MANAJEMEN
    // ------------------------------------------
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Admin (Overview statistik & sisa kuota seluruh fakultas)
        Route::get('/dashboard', [AreaController::class, 'dashboardAdmin'])->name('dashboard');
        
        // Detail Grid Virtual Slotting (FT001 s/d FT200) per area fakultas
        Route::get('/area/{id}/slots', [AreaController::class, 'detailSlotArea'])->name('area.slots');
        
        // Update Kapasitas Total Area oleh Admin
        Route::post('/area/update-kuota', [AreaController::class, 'updateKuota'])->name('update_kuota');
        
        // Manajemen User/Petugas oleh Admin
        Route::get('/users', [SistemController::class, 'kelolaUser'])->name('users');
    });

});