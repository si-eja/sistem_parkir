<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Parkir;
use Illuminate\Support\Facades\Auth;

class AreaController extends Controller
{
    public function dashboardJukir(Request $request)
    {
        // Ambil semua area parkir untuk dropdown & monitoring
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        // Jika Jukir memilih area dari dropdown, gunakan area tersebut. 
        // Jika tidak, gunakan area pertama sebagai default.
        $selectedAreaId = $request->query('area_id', $semua_area->first()->id ?? 1);
        $area = Area::findOrFail($selectedAreaId);

        // Ambil daftar kendaraan aktif khusus di area yang sedang dipilih Jukir
        $kendaraan_aktif = Parkir::where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->orderBy('created_at', 'desc')
            ->get();

        $sisa_kuota = max(0, $area->kapasitas_total - $kendaraan_aktif->count());

        return view('jukir', compact('area', 'kendaraan_aktif', 'sisa_kuota', 'semua_area'));
    }

    public function dashboardAdmin()
    {
        // Tambahkan withCount untuk menghitung parkir yang statusnya AKTIF
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        $riwayat_parkir = Parkir::where('status', 'SELESAI')
            ->orderBy('waktu_keluar', 'desc')
            ->get();

        return view('admin', compact('semua_area', 'riwayat_parkir'));
    }

    public function updateKuota(Request $request)
    {
        $request->validate([
            'area_id'        => 'required|exists:areas,id',
            'kapasitas_baru' => 'required|integer|min:1'
        ]);

        $area = Area::findOrFail($request->area_id);
        $area->update([
            'kapasitas_total' => $request->kapasitas_baru
        ]);

        return back()->with('success', 'Batas kuota ' . $area->nama_area . ' berhasil dikonfigurasi ulang.');
    }
}