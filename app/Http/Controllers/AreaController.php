<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Parkir;

class AreaController extends Controller
{
    public function dashboardJukir(Request $request)
    {
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        $selectedAreaId = $request->query('area_id', $semua_area->first()->id ?? 1);
        $area = Area::findOrFail($selectedAreaId);

        $kendaraan_aktif = Parkir::with('user')
            ->where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->orderBy('created_at', 'desc')
            ->get();

        $sisa_kuota = max(0, $area->kapasitas_total - $kendaraan_aktif->count());

        return view('jukir', compact('area', 'kendaraan_aktif', 'sisa_kuota', 'semua_area'));
    }

    public function dashboardAdmin()
    {
        $semua_area = Area::withCount(['parkir as parkir_aktif_count' => function ($query) {
            $query->where('status', 'AKTIF');
        }])->get();

        $riwayat_parkir = Parkir::with(['area', 'user'])
            ->where('status', 'SELESAI')
            ->orderBy('waktu_keluar', 'desc')
            ->take(50)
            ->get();

        return view('admin', compact('semua_area', 'riwayat_parkir'));
    }

    // Monitoring Grid Virtual Slot (FT001 s/d FT200) tanpa memberatkan DB
    public function detailSlotArea($id)
    {
        $area = Area::findOrFail($id);

        // Ambil transaksi aktif yang di-key berdasarkan nomor_slot
        $parkirAktif = Parkir::with('user')
            ->where('area_id', $area->id)
            ->where('status', 'AKTIF')
            ->get()
            ->keyBy('nomor_slot');

        $listSlot = [];
        for ($i = 1; $i <= $area->kapasitas_total; $i++) {
            $kodeSlot = $area->kode_area . str_pad($i, 3, '0', STR_PAD_LEFT);
            $terisi = isset($parkirAktif[$i]);

            $listSlot[] = [
                'nomor'     => $i,
                'kode_slot' => $kodeSlot,
                'status'    => $terisi ? 'TERISI' : 'KOSONG',
                'data'      => $terisi ? $parkirAktif[$i] : null,
            ];
        }

        return view('admin_slot_detail', compact('area', 'listSlot'));
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

        return back()->with('success', 'Batas kuota ' . $area->nama_area . ' berhasil dikonfigurasi ulang menjadi ' . $request->kapasitas_baru . ' Slot.');
    }
}