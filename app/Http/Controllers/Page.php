<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Page extends Controller
{
    public function showLanding(Request $request)
    {
        $riwayat = null;
        $plat_cari = strtoupper(str_replace(' ', '', $request->query('plat_nomor')));

        if ($request->has('plat_nomor') && !empty($plat_cari)) {
            $riwayat = \App\Models\Parkir::with('area')
                ->where('plat_nomor', $plat_cari)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Hitung kuota parkir aktif per area
        $semua_area = \App\Models\Area::withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        return view('landing', compact('riwayat', 'plat_cari', 'semua_area'));
    }

    public function showLogin()
    {
        // Tetap tampilkan view login murni tanpa auto-redirect langsung
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return $user->role === 'ADMIN'
                ? redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin!')
                : redirect()->route('jukir.dashboard')->with('success', 'Selamat Bertugas Jukir!');
        }

        return back()->with('error', 'Alamat email atau password salah.')->withInput();
    }

    public function prosesLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar dari sistem.');
    }
}