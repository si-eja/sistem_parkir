<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parkir;
use App\Models\Area;

class Page extends Controller
{
    public function showLanding(Request $request)
    {
        $riwayat = null;
        $plat_cari = strtoupper(str_replace(' ', '', $request->query('plat_nomor')));

        if ($request->has('plat_nomor') && !empty($plat_cari)) {
            $riwayat = Parkir::with('area')
                ->where('plat_nomor', $plat_cari)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Ambil area beserta parkir aktif untuk visualisasi grid slot di landing page
        $semua_area = Area::with(['parkir' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        return view('landing', compact('riwayat', 'plat_cari', 'semua_area'));
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user());
        }
        return view('register');
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

            return $this->redirectUserByRole($user);
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

    // Helper Redirect Berdasarkan Role Pengguna
    private function redirectUserByRole($user)
    {
        if ($user->role === 'ADMIN') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin!');
        } elseif ($user->role === 'JUKIR') {
            return redirect()->route('jukir.dashboard')->with('success', 'Selamat Bertugas Jukir!');
        }
        
        return redirect()->route('landing')->with('success', 'Selamat Datang ' . $user->name . '!');
    }
}