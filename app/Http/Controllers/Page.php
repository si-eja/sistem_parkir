<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parkir;
use App\Models\Area;
use App\Models\User;
use Carbon\Carbon;

class Page extends Controller
{
    public function showLanding(Request $request)
    {
        $riwayat = null;
        $plat_cari = strtoupper(str_replace(' ', '',$request->query('plat_nomor', '')));

        if ($request->has('plat_nomor') && !empty($plat_cari)) {$riwayat = Parkir::with('area')
                ->where('plat_nomor', $plat_cari)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        $semua_area = Area::with(['parkir' => function ($query) {$query->where('status', 'AKTIF');
        }])->withCount(['parkir as parkir_aktif_count' => function ($query) {$query->where('status', 'AKTIF');
        }])->get();

        return view('landing', compact('riwayat', 'plat_cari', 'semua_area'));
    }

    public function monitoring(Request $request)
    {
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($q) {$q->where('status', 'AKTIF');
        }])->get();

        $area =$request->area_id 
            ? Area::find($request->area_id) 
            : $semua_area->first();

        if (!$area) {
            return redirect()->route('jukir.dashboard')->with('warning', 'Belum ada data area parkir yang tersedia.');
        }

        $kendaraan_aktif = Parkir::where('area_id',$area->id)
            ->where('status', 'AKTIF')
            ->orderBy('nomor_slot', 'asc')
            ->get();

        $riwayat_hari_ini = Parkir::where('area_id',$area->id)
            ->where('status', 'SELESAI')
            ->whereDate('updated_at', Carbon::now('Asia/Jakarta')->toDateString())
            ->latest('updated_at')
            ->take(10)
            ->get();

        $sisa_kuota = max(0, $area->kapasitas_total -$kendaraan_aktif->count());

        return view('monitoringjukir', compact(
            'semua_area',
            'area',
            'kendaraan_aktif',
            'riwayat_hari_ini',
            'sisa_kuota'
        ));
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

        if (Auth::attempt($request->only('email', 'password'))) {$request->session()->regenerate();
            return $this->redirectUserByRole(Auth::user());
        }

        return back()->with('error', 'Alamat email atau password salah.')->withInput();
    }

    public function prosesLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();$request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar dari sistem.');
    }

    private function redirectUserByRole($user)
    {
        if ($user->role === 'ADMIN') { return redirect()->route('admin.dashboard')->with('success', 'Selamat Datang Admin!'); } elseif ($user->role === 'JUKIR') {
            return redirect()->route('jukir.dashboard')->with('success', 'Selamat Bertugas Jukir!');
        }
        
        return redirect()->route('pengendara.dashboard')->with('success', 'Selamat Datang ' . $user->name . '!');
    }

    public function petugas()
    {
        $petugas = User::where('role', 'JUKIR')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($item,$key) {
                $item->kode_petugas = '#JKR-' . str_pad($key + 1, 3, '0', STR_PAD_LEFT);
                return $item;
            })
            ->sortByDesc('id')
            ->values();

        return view('petugas', compact('petugas'));
    }
}